<?php
/**
 * Subscription
 *
 * @author f0t0n
 */
abstract class Subscription {
	
	protected $paymentData;
	
	public function __construct(array $paymentData) {
		$this->paymentData=$paymentData;
	}
	
	abstract public function subscribeClient(User $user);
	abstract public function unsubscribeClient(User $user);
}