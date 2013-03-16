<?php
class StripeWebHookException extends CException {
}
/**
 * StripeWebHookAction
 * 
 *	Next handlers on next events are implemented for Stripe calls at the moment:
 * 
 *	invoice.payment_succeeded
 *	invoice.payment_failed
 *
 * @author f0t0n
 * @author Alexey Kavshirko
 */
class StripeWebHookAction extends Action {

	protected $json;
	protected $eventDictionary;
	
	public function __construct($controller, $id) {
		parent::__construct($controller, $id);
		$this->json=null;
		$this->eventDictionary=array(
			'invoice.payment_succeeded'=>'recurringPaymentSucceeded',
			'invoice.payment_failed'=>'recurringPaymentFailed',
			//'subscription_final_payment_attempt_failed'=>'finalAttemptFailed',
		);
	}

	public function run() {
		header('Content-Type: text/plain');
		$this->json=CJSON::decode(stripslashes(@file_get_contents('php://input')));
		if(is_array($this->json) && !empty($this->json['type'])
			&& isset($this->eventDictionary[$this->json['type']])) {
			$hookMethod=$this->eventDictionary[$this->json['type']];
			echo 'Stripe raised an event: "', $this->json['type'] , '";';
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
	 * Handles Stripe's notification about successful subscription payment.
	 * 
	 */
	protected function recurringPaymentSucceeded() {
		if(!$this->json['data']['object']['paid']) {
			return;
		}
		$stripeCustomer=$this->getStripeCustomer();
		$stripeCustomer->last_payment_time=$this->json['data']['object']['date'];
		$stripeCustomer->is_canceled=0;
		$stripeCustomer->notified_at=null;
		$stripeCustomer->save();
	}

    /**
     * Checks if Stripe's notification when attempt to charge was failed <br />
     * and if it was final, we need to cancel user's subscription
     *
     */
    protected function recurringPaymentFailed() {
        //check if it's the last attempt to charge(in that case 'next_payment_attempt' is null)
        if(!empty($this->json['data']['object']['next_payment_attempt'])) {
            return;
        }
        $stripeCustomer=$this->getStripeCustomer();
        $stripeCustomer->is_canceled=1;
        if($this->notifySubscriptionCanceled($stripeCustomer)) {
            $stripeCustomer->notified_at=time();
        }
        $stripeCustomer->save();
	}
	
	/**
	 * Handles Stripe's notification when final attempt to charge customer <br />
	 * on his subscription is failed.
	 * 
	 */
	protected function finalAttemptFailed() {
		$stripeCustomer=$this->getStripeCustomer();
		$stripeCustomer->is_canceled=1;
		if($this->notifySubscriptionCanceled($stripeCustomer)) {
			$stripeCustomer->notified_at=time();
		}
		$stripeCustomer->save();
	}
	
	protected function getStripeCustomer() {
		$stripeCustomer=StripeCustomer::model()->findByPk($this->json['data']['object']['customer']);
		if(empty($stripeCustomer)) {
			throw new StripeWebHookException('Wrong request');
		}
		return $stripeCustomer;
	}
	
	/**
	 * @todo Send an e-mail to user and return true on success, false otherwise.
	 * 
	 * @return boolean is user notified about his subscription cancellation.
	 */
	protected function notifySubscriptionCanceled() {
		return false;
	}
}

//	The example of request from Stripe for invoice.payment_succeeded:
//{
//  "object": "event",
//  "type": "invoice.payment_succeeded",
//  "created": 1326853478,
//  "livemode": false,
//  "data": {
//    "object": {
//      "object": "invoice",
//      "next_payment_attempt": null,
//      "livemode": false,
//      "attempted": true,
//      "lines": {
//        "subscriptions": [
//          {
//            "amount": 5995,
//            "period": {
//              "end": 1350900376,
//              "start": 1348308376
//            },
//            "plan": {
//              "amount": 2995,
//              "object": "plan",
//              "livemode": false,
//              "trial_period_days": null,
//              "name": "Support Services",
//              "interval": "month",
//              "id": "2",
//              "currency": "usd"
//            }
//          }
//        ]
//      },
//      "subtotal": 5995,
//      "amount_due": 5995,
//      "period_start": 1345629916,
//      "starting_balance": 0,
//      "paid": true,
//      "attempt_count": 1,
//      "ending_balance": 0,
//      "discount": null,
//      "closed": true,
//      "date": 1345629991,
//      "id": "in_00000000000000",
//      "period_end": 1345629976,
//      "total": 5995,
//      "customer": "cus_00000000000000",
//      "currency": "usd",
//      "charge": "_00000000000000"
//    }
//  },
//  "id": "evt_00000000000000"
//}

//	The example of request from Stripe for invoice.payment_failed:
//{
//  "object": "event",
//  "type": "invoice.payment_failed",
//  "created": 1326853478,
//  "livemode": false,
//  "data": {
//    "object": {
//      "object": "invoice",
//      "next_payment_attempt": null,
//      "livemode": false,
//      "attempted": true,
//      "lines": {
//        "subscriptions": [
//          {
//            "amount": 5995,
//            "period": {
//              "end": 1350900376,
//              "start": 1348308376
//            },
//            "plan": {
//              "amount": 2995,
//              "object": "plan",
//              "livemode": false,
//              "trial_period_days": null,
//              "name": "Support Services",
//              "interval": "month",
//              "id": "2",
//              "currency": "usd"
//            }
//          }
//        ]
//      },
//      "subtotal": 5995,
//      "amount_due": 5995,
//      "period_start": 1345629916,
//      "starting_balance": 0,
//      "paid": false,
//      "attempt_count": 1,
//      "ending_balance": 0,
//      "discount": null,
//      "closed": false,
//      "date": 1345629991,
//      "id": "in_00000000000000",
//      "period_end": 1345629976,
//      "total": 5995,
//      "customer": "cus_00000000000000",
//      "currency": "usd",
//      "charge": "ch_00000000000000"
//    }
//  },
//  "id": "evt_00000000000000"
//}
