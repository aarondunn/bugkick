<?php
/**
 * CancelSubscriptionAction
 *
 * @author f0t0n
 */
class CancelSubscriptionAction extends Action {
	
	/**
	 *
	 * @var User
	 */
	protected $user;
	/**
	 *
	 * @var Company
	 */
	protected $company;
	
	/**
	 *
	 * @var StripeCustomer
	 */
	protected $stripeCustomer;
	
	/**
	 *
	 * @var PaymentFactory
	 */
	protected $paymentFactory;
	
	/**
	 *
	 * @var array
	 */
	protected $paymentData;
	
	public function __construct($controller, $id) {
		parent::__construct($controller, $id);
		$this->user=User::current();
		$this->company=Company::model()->findByPk(Company::current());
		$this->paymentFactory=null;
		$this->paymentData=array();
	}
	
	public function run() {
		if(empty($this->user) || empty($this->company)) {
			Yii::app()->user->setFlash(
				'error',
				Yii::t('main', 'You have to be logged-in within some company.')
			);
			$this->controller->redirect(array('/site/login'));
		}
		$this->stripeCustomer=StripeCustomer::model()->findByAttributes(
			array(
				'company_id'=>$this->company->company_id,
				'user_id'=>$this->user->user_id,
			)
		);
		if(!empty($this->stripeCustomer)
			&& empty($this->stripeCustomer->expires_at)) {
			$this->paymentFactory=new StripePaymentFactory();
			$this->paymentData['apiKey']=Yii::app()->params['stripe']['secretKey'];
		}
		//	else if... another cases for other payment systems can be here
		else {
			$this->controller->redirect(array('bug/'));
		}
		$this->viewData['isSubscriptionCanceled']=false;
		if(!empty($_POST)) {
			$this->cancelSubscription();
		}
		$this->controller->render('cancel-subscription', $this->viewData);
	}
	
	protected function cancelSubscription() {
		$subscription=$this->paymentFactory->createSubscription($this->paymentData);
		if($subscription->unsubscribeClient($this->user)) {
			$this->stripeCustomer->refresh();
			$this->viewData['expires_at']=$this->stripeCustomer->expires_at;
			$this->viewData['isSubscriptionCanceled']=true;
		}
	}
}