<?php
require_once(__DIR__.'/stripe/lib/Stripe.php');
/**
 * StripePaymentFactory
 *
 * @author f0t0n
 */
class StripePaymentFactory implements PaymentFactory {

	public function createCharge(array $paymentData) {
		return new StripeCharge($paymentData);
	}
	
	public function createSubscription(array $paymentData) {
		return new StripeSubscription($paymentData);
	}
}