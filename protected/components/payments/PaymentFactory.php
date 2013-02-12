<?php
/**
 *
 * @author f0t0n
 */
interface PaymentFactory {
	/**
	 * @return Charge
	 */
	public function createCharge(array $paymentData);
	/**
	 * @return Subscription
	 */
	public function createSubscription(array $paymentData);
}