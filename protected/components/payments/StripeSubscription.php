<?php
/**
 * StripeSubscription
 *
 * @author f0t0n
 */
class StripeSubscription extends Subscription {

	public function __construct(array $paymentData) {
		parent::__construct($paymentData);
		Stripe::setApiKey($this->paymentData['apiKey']);
	}
	
	public function subscribeClient(User $user) {
		$stripeCustomer=empty($user->stripeCustomer)
			? $this->createStripeCustomer($user)
			: $user->stripeCustomer;
		if(empty($stripeCustomer)) {
			return false;
		}
		try {
			$cus=Stripe_Customer::retrieve($stripeCustomer->customer_id);
			$subscription=$cus->updateSubscription(
				array(
                    'plan'=>$this->paymentData['planID'],
                    'coupon'=>$this->paymentData['coupon'],
                )
			);
			if($subscription->status==='active') {
				$stripeCustomer->last_payment_time=$subscription->current_period_start;
				$stripeCustomer->plan_id=$subscription->plan->id;
				$stripeCustomer->payment_interval=$this->getInterval($subscription->plan->interval);
				if(!$stripeCustomer->save()) {
					throw new Stripe_Error('Stripe customer was not saved properly.');
				}
			}
		} catch(Stripe_Error $ex) {
			$msg="[Stripe error] :: Can't subscribe user #{$user->user_id}: {$ex->getMessage()}";
			$this->logError($msg);
			return false;
		}
		return true;
	}
	
	protected function getInterval($strInterval) {
		$intervals=array(
			'month'=>2678400,	//	31 day
			'year'=>31622400,	// 366 days
		);
		if(isset($intervals[$strInterval])) {
			return $intervals[$strInterval];
		}
		throw new Stripe_Error('Wrong subscription interval.');
	}
	
	/**
	 *
	 * @param User $user 
	 * @return StripeCustomer
	 */
	protected function createStripeCustomer(User $user) {
		$_stripeCustomer=$this->apiCreateCustomer($user);
		if(empty($_stripeCustomer) || empty($_stripeCustomer->id)) {
			return null;
		}
		return $this->createStripeCustomerModel($user, $_stripeCustomer);
	}
	
	/**
	 *
	 * @param User $user
	 * @param Stripe_Customer $_stripeCustomer
	 * @return StripeCustomer 
	 */
	protected function createStripeCustomerModel(
		User $user, Stripe_Customer $_stripeCustomer) {
		$stripeCustomer=new StripeCustomer();
		$stripeCustomer->customer_id=$_stripeCustomer->id;
		$stripeCustomer->user_id=$user->user_id;
		$stripeCustomer->company_id=0;
		if($stripeCustomer->save()) {
			return $stripeCustomer;
		}
		$modelErrors=print_r($stripeCustomer->getErrors(), true);
		$msg="[Stripe error] :: Can't save Stripe customer for user #{$user->user_id}. Errors: $modelErrors";
		$this->logError($msg);
		return null;
	}
	
	protected function apiCreateCustomer(User $user) {
		try {
			$_stripeCustomer=Stripe_Customer::create(
				array(
					'description'=>'BugKick user #' . $user->user_id,
					'card'=>$this->paymentData['stripeToken'],
					'coupon'=>$this->paymentData['coupon'],
				)
			);
			return $_stripeCustomer;
		} catch(Stripe_Error $ex) {
			$msg="[Stripe error] :: Can't create Stripe customer for user #{$user->user_id}: {$ex->getMessage()}";
			$this->logError($msg);
			return null;
		}
	}
	
	public function unsubscribeClient(User $user) {
		$stripeCustomer=$user->stripeCustomer;
		if(empty($stripeCustomer)) {
			return false;
		}
		try {
			$cus=Stripe_Customer::retrieve($stripeCustomer->customer_id);
			$subscr=$cus->cancelSubscription();
			$stripeCustomer->expires_at=$subscr->current_period_end;
			$stripeCustomer->save();
		} catch(Stripe_Error $ex) {
			$msg="[Stripe error] :: Can't unsubscribe user #{$user->user_id}: {$ex->getMessage()}";
			$this->logError($msg);
			return false;
		}
		return true;
	}
	
	protected function logError($msg) {
		Yii::log($msg, CLogger::LEVEL_ERROR, 'stripe.payments.log');
	}
}