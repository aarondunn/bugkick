<?php
/**
 * Charge
 *
 * @author f0t0n
 */
abstract class Charge {
	
	protected $paymentData;
	
	public function __construct(array $paymentData) {
		$this->paymentData=$paymentData;
	}
	
	abstract public function chargeClient(User $user);
}