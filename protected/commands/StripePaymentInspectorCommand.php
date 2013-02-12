<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 04.01.12
 * Time: 0:25
 *
 * Command helps to find missed payments and notify users
 *
 */
class StripePaymentInspectorCommand extends Command
{
   	public function actionIndex()
    {
        //Downgrade companies with canceled payments
        $downgradedNum = $this->downgradeCompanies();

        //Downgrade companies that were registered using coupons
        $this->downgradeCouponCompanies();

        if ($downgradedNum >0)
            print $downgradedNum . ' companies were downgraded...';

        $customers = $this->checkCustomers();

        if (empty($customers['outdated']) && empty($customers['canceled'])){
            print 'There are no problems with payments.';
            return;
        }

        //Notify customers with canceled payments
        if (!empty($customers['canceled'])){
            $this->handleCanceledSubscriptions($customers['canceled']);
        }

        //Check customers with outdated payment information
        if (!empty($customers['outdated'])){
            $this->handleOutdatedSubscriptions($customers['outdated']);
        }
    }

    /**
	* Find customers with outdated payment information
    * and customers with canceled payments, but not notified
	*
    * @return array - array of StripeCustomer objects
 	*/
    private function checkCustomers()
    {
        $customers = array();
        //Find customers with outdated payment information
        $criteria = new CDbCriteria();
        $lastTerm = time() - Yii::app()->params['stripe']['interval1'];
        $criteria->condition = '(t.next_payment_time != 0 AND t.next_payment_time < :lastTerm) AND (is_canceled = 0)';
        $criteria->params=array(':lastTerm'=>$lastTerm);
        $customers['outdated'] = StripeCustomer::model()->with('user')->findAll($criteria);

        //Find customers with canceled payments, but not notified
        $criteria = new CDbCriteria();
        $criteria->condition = 't.is_canceled = 1 AND t.notified_at IS NULL';
        $customers['canceled'] = StripeCustomer::model()->with('user')->findAll($criteria);

        return $customers;
    }


    /**
	 * Find and downgrade companies with canceled payments
	 *
	 * @return int number of downgraded companies
	 */
    private function downgradeCompanies()
    {
		$now=time();
        //Users that haven't pay in allowed time interval
        $criteria = new CDbCriteria();
        $lastTerm = $now - ( Yii::app()->params['stripe']['interval1'] + Yii::app()->params['stripe']['interval2'] + Yii::app()->params['stripe']['interval3'] );
        $criteria->condition = 't.next_payment_time != 0 AND t.next_payment_time < :lastTerm';
        $criteria->params = array(':lastTerm'=>$lastTerm);
        $companiesToDelete = StripeCustomer::model()->findAll($criteria);

        //Users that ended their subscriptions
        $criteria = new CDbCriteria();
        $lastTerm = $now;
        $criteria->condition = 't.expires_at IS NOT NULL AND t.expires_at <= :lastTerm';
        $criteria->params = array(':lastTerm'=>$lastTerm);
        $companiesToDelete2 = StripeCustomer::model()->findAll($criteria);

        return $this->performDowngrade($companiesToDelete) + $this->performDowngrade($companiesToDelete2);
    }

    /**
     * Performs companies downgrade.
     * @param array $companies - array of StripeCustomer objects.
     * @return int - number of downgraded companies.
     */
    private function performDowngrade(array $companies)
    {
        if (!empty($companies)){
            foreach($companies as $value){
                $companyIDs[] = $value->company_id;
                $customerIDs[] = $value->customer_id;
            }

            //delete customers from stripe
            $this->deleteStripeCustomers($customerIDs);

            //delete customers from our Db
            $criteria = new CDbCriteria();
            $criteria->addInCondition('company_id', $companyIDs);
            StripeCustomer::model()->deleteAll($criteria);
            return Company::model()->updateAll(array(
                    'account_type'=>Company::TYPE_FREE,
                    'show_ads'=>1,
                    'company_top_logo'=>''
            ),$criteria);
        }
        return 0;
    }

    /**
     * Deletes stripe customers.
     * @param array $customers - array of stripe customer_id's
     */
    private function deleteStripeCustomers(array $customers)
    {

        Yii::import('application.components.payments.stripe.lib.Stripe',true);
        Stripe::setApiKey(Yii::app()->params['stripe']['secretKey']);

        if (!empty($customers)){
            foreach($customers as $customerID){
                try {
                    $customer = Stripe_Customer::retrieve($customerID);
                    if (!empty($customer))
                        $customer->delete();

                } catch(Stripe_Error $ex) {
                    $msg="[Stripe error] :: StripePaymentInspector: Can't delete stripe customer #{$customerID}: {$ex->getMessage()}";
                    $this->logError($msg);
                    return false;
                }
            }
        }
    }

    private function getInterval($strInterval)
    {
   		$intervals=array(
   			'month'=>2678400,	//	31 day
   			'year'=>31622400,	// 366 days
   		);
   		if(isset($intervals[$strInterval])) {
   			return $intervals[$strInterval];
   		}
   		throw new Stripe_Error('Wrong subscription interval.');
   	}

    private function logError($msg)
    {
        Yii::log($msg, CLogger::LEVEL_ERROR, 'stripe.payments.log');
    }

    /**
     * Notifies user about canceled payments. Send message about downgrade in 3 days
     * @param array $customers - array of StripeCustomer objects
     */
    private function handleCanceledSubscriptions($customers)
    {
        Notificator::outdatedPayment($customers);

        foreach($customers as $value){
            $customersIDs[] = $value->customer_id;
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition('customer_id', $customersIDs);
        $notifiedNum = StripeCustomer::model()->updateAll(array('notified_at'=>time()), $criteria);
        print $notifiedNum . ' users were notified about canceled payments...';
    }

    /**
     * Checks users status on Stripe.com
     * and updates outdated payment info
     * @param array $customers - array of StripeCustomer objects
     * @return boolean - false when information from stripe.com is not available
     * */
    private function handleOutdatedSubscriptions($customers)
    {
        Yii::import('application.components.payments.stripe.lib.Stripe',true);
        Stripe::setApiKey(Yii::app()->params['stripe']['secretKey']);

        foreach($customers as $stripeCustomer){
            
            try {
                $this->handleOutdatedSubscription($stripeCustomer);
            } catch(Stripe_Error $ex) {
                $msg="[Stripe error] :: StripePaymentInspector: Can't update user #{$stripeCustomer->user_id}: {$ex->getMessage()}";
                $this->logError($msg);
                return false;
            }

        }
    }

    private function handleOutdatedSubscription(StripeCustomer $stripeCustomer)
    {
        $customer = Stripe_Customer::retrieve($stripeCustomer->customer_id);

        if($customer->subscription->status==='active' && $customer->subscription->current_period_end > time()) {
            //customer is active
            $this->updateUsersData($stripeCustomer, $customer);
        }
        else{
            //customer is not active.
            $this->setUserCanceled($stripeCustomer);
        }
    }

    /*
     * Called when users status is active.
     * Updates payment information
     * */
    private function updateUsersData(StripeCustomer $stripeCustomer, Stripe_Customer $customer)
    {
        $stripeCustomer->last_payment_time = $customer->subscription->current_period_start;
        $stripeCustomer->plan_id = $customer->subscription->plan->id;
        $stripeCustomer->payment_interval = $this->getInterval($customer->subscription->plan->interval);
        if(!$stripeCustomer->save()) {
            throw new Stripe_Error('Stripe customer was not saved properly (updateUsersData method).');
        }
        print "User #{$stripeCustomer->user_id} was updated... ";
    }

    /*
     * Called when users status is not active.
     * Notifies user and updates payment information.
     * */
    private function setUserCanceled(StripeCustomer $stripeCustomer)
    {
        Notificator::outdatedPayment(array(0=>$stripeCustomer));
        $stripeCustomer->notified_at = time();
        $stripeCustomer->is_canceled = 1;
        if(!$stripeCustomer->save()) {
            throw new Stripe_Error('Stripe customer was not saved properly (setUserCanceled method).');
        }
        print "User #{$stripeCustomer->user_id} was notified... ";
    }

    /**
     * Downgrades companies that were registered using coupons.
     */
    private function downgradeCouponCompanies()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'account_type=:account_type AND coupon_id!=0 AND coupon_expires_at<:time';
        $criteria->params = array(
            ':account_type'=>Company::TYPE_PAY,
            ':time'=>time(),
        );
        $companies = Company::model()->findAll($criteria);
        if(!empty($companies) && is_array($companies)){
            foreach($companies as $company)
                $this->downgradeCouponCompany($company);
        }
    }

    /**
     * Downgrades company if company was not registered
     * @param Company $company
     * @return bool
     */
    private function downgradeCouponCompany(Company $company)
    {
        $stripeCustomer = $company->stripeCustomer;
        if(!empty($stripeCustomer)){
            //case when user already has upgraded the company to pro until coupon ended.
            //in this case we keep Pro status and plan info
            $company->coupon_id = 0;
            $company->coupon_expires_at = 0;
            $company->show_ads = 1;
        }
        else{
            $company->account_type = Company::TYPE_FREE;
            $company->account_plan = '';
            $company->coupon_id = 0;
            $company->coupon_expires_at = 0;
            $company->show_ads = 1;
        }
        return $company->save();
    }
}