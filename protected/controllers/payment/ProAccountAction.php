<?php
/**
 * ProAccountAction
 * The action that provides functionality to pay for Pro account.
 *
 * @author f0t0n
 */
class ProAccountAction extends Action {

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
	
	public function __construct($controller, $id) {
		parent::__construct($controller, $id);
		$this->user=User::current();
		$this->company=Company::model()->findByPk(Company::current());
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
				'user_id'=>$this->user->user_id,
				'company_id'=>$this->company->company_id,
			)
		);
		$this->checkStripeCustomer();

        $subscription = $this->request->getParam('subscription');
        if (!empty($subscription)){
            switch ($subscription) {
/*                case 'premium':
                    $this->controller->session['planType'] = 'premium';
                    break;
                case 'ultimate':
                    $this->controller->session['planType'] = 'ultimate';
                    break;  */
                case 'pro':
                    $this->controller->session['planType'] = 'pro';
                    break;
                default:
                    Yii::app()->user->setFlash('error', "Please choose a subscription.");
                    $this->controller->redirect(array('payment/chooseSubscription'));
            }
        }
        else{
            Yii::app()->user->setFlash('error', "Please choose a subscription.");
            $this->controller->redirect(array('payment/chooseSubscription'));
        }

		$this->initViewData();
		$this->handlePost();
        if(empty($this->viewData['subscriptionSuccess'])){
            $view='pro-account';
        }
        else{
            $view='successful-subscription';
            MixPanel::instance()->registerEvent(MixPanel::SIGN_UP, array('type'=>'pay')); // MixPanel events tracking
        }
		$this->controller->render($view, $this->viewData);
	}
	
	protected function checkStripeCustomer() {
		if(!empty($this->stripeCustomer)) {
			$this->controller->redirect(array('bug/'));
		}
	}
	
	protected function initViewData() {
		$this->viewData['model']=new PaymentForm();
		$currYear=date('Y');
		$lastYear=$currYear + 51;
		$this->viewData['months']=$this->createListData(1, 13, 'm');
		$this->viewData['years']=$this->createListData($currYear, $lastYear, 'y');
	}
	
	protected function createListData($min, $max, $key) {
		$arr=array();
		for($i = $min; $i < $max; $i++)
			$arr[]=array($key=>$i);
		return $arr;
	}
	
	protected function handlePost() {
		$paymentFormAttributes=$this->request->getPost('PaymentForm');
		$paymentFactory=null;
		$paymentData=null;
		if(!empty($paymentFormAttributes)) {
			$this->viewData['model']->setAttributes($paymentFormAttributes);
			//	Stripe payment:
			if($this->viewData['model']->validate()) {
				$paymentFactory=new StripePaymentFactory();
				$paymentData=array_merge(
					$paymentFormAttributes,
					$this->getStripePaymentData($paymentFormAttributes['interval'])	// the keys of this array will override same keys from $paymentFormAttributes
				);
			}
		}
		try {
			$this->processCall($paymentFactory, $paymentData);
		} catch(Exception $ex) {
			$this->viewData['paymentError']=$ex->getMessage();
		}
	}
	
	protected function getStripePaymentData($interval)
    {
        $type = $this->controller->session['planType'];

        //creating plan config
        $planName = $type . '_' . $interval;
        $storageType = Yii::app()->params['stripe']['planConfigStorageType'];
        $planConfig = StripePlanConfigFactory::createPlanConfig($planName, $storageType);
        $planID = $planConfig->getPlanID();

        if( empty($type) || empty($planID) ){
            Yii::app()->user->setFlash('error', "Please choose a subscription.");
            $this->controller->redirect(array('payment/chooseSubscription'));
        }
		return array(
			'apiKey'=>Yii::app()->params['stripe']['secretKey'],
			'planID'=>$planID,
            'planName'=>$planName,
		);
	}
	
	protected function processCall($paymentFactory, $paymentData) {
		if($paymentFactory instanceof PaymentFactory && !empty($paymentData)) {
			//	Next case is only for Stripe payment for now
			if(!empty($_POST['subscription'])) {
				if($this->subscribe($paymentFactory, $paymentData)) {
					$this->viewData['subscriptionSuccess']=true;
				}
			}
		}
	}
	
	protected function charge(
		PaymentFactory $paymentFactory, array $paymentData) {
		$charge=$paymentFactory->createCharge($paymentData);
		$charge->chargeClient($this->user);
	}
	
	protected function subscribe(
		PaymentFactory $paymentFactory, array $paymentData) {
		$subscription=$paymentFactory->createSubscription($paymentData);
		if(!$subscription->subscribeClient($this->user)) {
			return false;
		}
		$stripeCustomer=StripeCustomer::model()->findByAttributes(
			array('user_id'=>$this->user->user_id)
		);
		if(empty($stripeCustomer)) {
			return false;
		}
		$stripeCustomer->company_id=$this->company->company_id;
		if($stripeCustomer->save()) {
			$this->company->account_type=Company::TYPE_PAY;
            $this->company->account_plan= $paymentData['planName'];
			return $this->company->save();
		} else {
			return false;
		}
	}

}