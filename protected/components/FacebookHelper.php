<?php
//Yii::import('application.vendors.facebook.src.facebook', true);
/**
 * FacebookHelper
 * 
 * @author f0t0n
 */
class FacebookHelper {
	
	/**
	 *
	 * @return BKFacebook 
	 */
	public static function getFacebook() {
		return new BKFacebook(
			array(
				'appId'  => Yii::app()->params['facebook']['appId'],
				'secret' => Yii::app()->params['facebook']['secret'],
				'cookie' => true,
			)
		);
	}

	public static function handleFacebookApiException($exception) {
		if(!$exception instanceof FacebookApiException)
			return false;
		// Handle an exception
		return true;
	}
	
	public static function parseSignedRequest($signedRequest) {
		$secret = Yii::app()->params['facebook']['secret'];
		list($encodedSig, $payload) = explode('.', $signedRequest, 2); 
		// decode the data
		$sig = self::base64UrlDecode($encodedSig);
		$data = json_decode(self::base64UrlDecode($payload), true);
		if(strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			//error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}
		// check sig
		$expectedSig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expectedSig) {
			//error_log('Bad Signed JSON signature!');
			return null;
		}
		return $data;
	}
	
	public static function base64UrlDecode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
}