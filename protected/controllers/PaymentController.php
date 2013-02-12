<?php

/**
 * PaymentController
 *
 * @author f0t0n
 */
class PaymentController extends Controller {

	public function actions() {
		$actionsPath='application.controllers.payment.';
		return array(
			'pro-account'=>$actionsPath.'ProAccountAction',
			'cancel-subscription'=>$actionsPath.'CancelSubscriptionAction',
			'stripe-web-hook'=>$actionsPath.'StripeWebHookAction',
		);
	}
	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array('allow',	// allow authenticated user to perform next actions
				'actions'=>array(
					'pro-account',
					'cancel-subscription',
                    'chooseSubscription'
				),
				'users'=>array('@'),
			),
			array('allow',	// allow all users to perform next actions
				'actions'=>array(
					'donate',
					'stripe-web-hook',
				),
				'users'=>array('*'),
			),
			array('deny',	// deny from all users
				'users'=>array('*'),
				'message' => 'You have no permissions to access this project.'
			),
		);
	}
	
	/**
	 * The action that allow users to donate money for project.
	 */
	public function actionDonate() {
		
	}

    public function actionChooseSubscription()
    {
        $this->render('chooseSubscription');
    }
}