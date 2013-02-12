<?php
/**
 * PaymentForm
 * The class currently developed to use in processing the payments via Stripe.com
 *
 * @author f0t0n
 */
class PaymentForm extends FormModel {
	
	public $interval;
	public $cardNumber;
	public $cvc;
	public $cardExpiryMonth;
	public $cardExpiryYear;
	public $stripeToken;
	public $coupon;
	protected static $intervals=array(
		'month'=>'Monthly',
		'year'=>'Yearly',
	);
	
	public function init() {
		parent::init();
		$this->interval='year';	//	pay each month by default
	}
	
	public function getIntervals() {
		return self::$intervals;
	}
	
	public function rules() {
		return array(
			array('interval, cardNumber, cvc, cardExpiryMonth, cardExpiryYear, stripeToken', 'required'),
			array('interval', 'paymentInterval'),
			array('cardNumber, cvc, cardExpiryMonth, cardExpiryYear', 'numerical'),
		);
	}
	
	public function paymentInterval($attribute, $params) {
		if(!isset(self::$intervals[$this->$attribute])) {
			$this->addError($attribute, Yii::t('main', 'Wrong choice'));
		}
	}
	
	public function attributeLabels() {
		return array_map(
			function($n) {
				return Yii::t('main', $n);
			},
			array(
				'interval'=>'Plan type',
				'cardNumber'=>'Card Number',
				'cvc'=>'CVC',
				'cardExpiryMonth'=>'Card Expiry Month',
				'cardExpiryYear'=>'Card Expiry Year',
				'stripeToken'=>'Stripe Token',
				'coupon'=>'Coupon',
			)
		);
	}
}