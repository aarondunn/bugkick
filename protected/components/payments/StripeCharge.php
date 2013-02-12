<?php
/**
 * StripeCharge
 *
 * @author f0t0n
 */
class StripeCharge extends Charge {
	
	public function __construct(array $paymentData) {
		parent::__construct($paymentData);
		Stripe::setApiKey($this->paymentData['apiKey']);
	}
	
	public function chargeClient(User $user) {
		$charge = Stripe_Charge::create(array(
			'amount'=>$this->paymentData['amount'], // amount in cents
			'currency'=>$this->paymentData['currency'],
			'card'=>$this->paymentData['stripeToken'],
			'description'=>$this->paymentData['description'],
		));
		VarDumper::dd($charge);
	}
}
