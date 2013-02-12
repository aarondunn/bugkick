<?php
class StripeWebHookException extends CException {
}
/**
 * StripeWebhookAction
 * 
 *	Next handlers on next events are implemented for Stripe calls at the moment:
 * 
 *	recurring_payment_succeeded
 *	recurring_payment_failed
 *
 * @author f0t0n
 */
class StripeWebHookAction extends Action {

	protected $json;
	protected $eventDictionary;
	
	public function __construct($controller, $id) {
		parent::__construct($controller, $id);
		$this->json=null;
		$this->eventDictionary=array(
			'recurring_payment_succeeded'=>'recurringPaymentSucceeded',
			'recurring_payment_failed'=>'recurringPaymentFailed',
			'subscription_final_payment_attempt_failed'=>'finalAttepmtFailed',
		);
	}

	public function run() {
		header('Content-Type: text/plain');
		$this->json=CJSON::decode($this->request->getPost('json'));
		if(is_array($this->json) && !empty($this->json['event'])
			&& isset($this->eventDictionary[$this->json['event']])) {
			$hookMethod=$this->eventDictionary[$this->json['event']];
			echo 'Stripe raised an event: "', $this->json['event'] , '";';
			try {
				$this->$hookMethod();
			} catch(StripeWebHookException $ex) {
				echo $ex->getMessage();
				Yii::app()->end();
			}
		}
		Yii::app()->end();
	}
	
	/**
	 * Handles Stripe's notification about successfull subscription payment.
	 * 
	 */
	protected function recurringPaymentSucceeded() {
		if(!$this->json['payment']['success']
			|| !$this->json['invoice']['paid']) {
			return;
		}
		$stripeCustomer=$this->getStripeCustomer();
		$stripeCustomer->last_payment_time=$this->json['invoice']['date'];
		$stripeCustomer->is_canceled=0;
		$stripeCustomer->notified_at=null;
		$stripeCustomer->save();
	}
	
	protected function recurringPaymentFailed() {
	}
	
	/**
	 * Handles Stripe's notification when final attempt to charge customer <br />
	 * on his subscription is failed.
	 * 
	 */
	protected function finalAttepmtFailed() {
		$stripeCustomer=$this->getStripeCustomer();
		$stripeCustomer->is_canceled=1;
		if($this->notifySubscriptionCanceled($stripeCustomer)) {
			$stripeCustomer->notified_at=time();
		}
		$stripeCustomer->save();
	}
	
	protected function getStripeCustomer() {
		$stripeCustomer=StripeCustomer::model()->findByPk($this->json['customer']);
		if(empty($stripeCustomer)) {
			throw new StripeWebHookException('Wrong request');
		}
		return $stripeCustomer;
	}
	
	/**
	 * @todo Send an e-mail to user and return true on success, false otherwise.
	 * 
	 * @return boolean is user notified about his subscription cancelation.
	 */
	protected function notifySubscriptionCanceled() {
		return false;
	}
}

//	The example of request from Stripe for recurring_payment_succeeded:
//		{
//		  "payment": {
//			"card": {
//			  "type": "Visa",
//			  "last4": "2424",
//			  "id": "Ncqojy1Sgdw"
//			},
//			"time": 1324950721,
//			"success": true
//		  },
//		  "customer": "cus_fTcnTQVUUzpKEQ",
//		  "event": "recurring_payment_succeeded",
//		  "invoice": {
//			"paid": true,
//			"period_start": 1325096899,
//			"attempted": true,
//			"closed": true,
//			"customer": "cus_fTcnTQVUUzpKEQ",
//			"lines": {
//			  "subscriptions": [
//				{
//				  "period": {
//					"end": 1327775299,
//					"start": 1325096899
//				  },
//				  "plan": {
//					"object": "plan",
//					"amount": 500,
//					"name": "BugKick Pro Plan",
//					"id": "bugkick_plan_pro",
//					"livemode": false,
//					"currency": "usd",
//					"interval": "month"
//				  },
//				  "amount": 500
//				}
//			  ]
//			},
//			"period_end": 1325096899,
//			"charge": "ch_wLlQxPMGuIbYKl",
//			"object": "invoice",
//			"date": 1325096899,
//			"total": 500,
//			"subtotal": 500,
//			"id": "in_9a8zlFvZyuV6vc",
//			"livemode": false
//		  },
//		  "livemode": false
//		}

//	The example of request from Stripe for recurring_payment_failed:
//		{
//		  "attempt": 1,
//		  "payment": {
//			"card": {
//			  "type": "Visa",
//			  "last4": "2424",
//			  "id": "Ncqojy1Sgdw"
//			},
//			"time": 1324950721,
//			"success": false
//		  },
//		  "customer": "cus_fTcnTQVUUzpKEQ",
//		  "event": "recurring_payment_failed",
//		  "invoice": {
//			"paid": false,
//			"period_start": 1325096899,
//			"attempted": true,
//			"closed": true,
//			"customer": "cus_fTcnTQVUUzpKEQ",
//			"lines": {
//			  "subscriptions": [
//				{
//				  "period": {
//					"end": 1327775299,
//					"start": 1325096899
//				  },
//				  "plan": {
//					"object": "plan",
//					"amount": 500,
//					"name": "BugKick Pro Plan",
//					"id": "bugkick_plan_pro",
//					"livemode": false,
//					"currency": "usd",
//					"interval": "month"
//				  },
//				  "amount": 500
//				}
//			  ]
//			},
//			"period_end": 1325096899,
//			"charge": "ch_wLlQxPMGuIbYKl",
//			"object": "invoice",
//			"date": 1325096899,
//			"total": 500,
//			"subtotal": 500,
//			"id": "in_9a8zlFvZyuV6vc",
//			"livemode": false
//		  },
//		  "livemode": false
//		}

//	The example of request from Stripe for subscription_final_payment_attempt_failed:
//		{
//		  "customer": "cus_MOohbisUxZs0rU",
//		  "subscription": {
//			"ended_at": 1327774553,
//			"status": "canceled",
//			"customer": "cus_MOohbisUxZs0rU",
//			"start": 1325096153,
//			"object": "subscription",
//			"plan": {
//			  "object": "plan",
//			  "amount": 500,
//			  "name": "BugKick Pro Plan",
//			  "id": "bugkick_plan_pro",
//			  "livemode": false,
//			  "currency": "usd",
//			  "interval": "month"
//			},
//			"current_period_start": 1325096153,
//			"current_period_end": 1327774553
//		  },
//		  "event": "subscription_final_payment_attempt_failed",
//		  "livemode": false
//		}