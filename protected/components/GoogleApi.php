<?php
/**
 * GoogleApi
 *
 * @author f0t0n
 */
class GoogleApi extends CGoogleApi {

	/**
	 * Renders the jsapi script file.
	 * @param string $apiKey the API key. Null if you do not have a key.
	 * @return string the script tag that loads Google jsapi.
	 */
	public static function init($apiKey=null, $useSSL=false)
	{
		if($useSSL) {
			self::$bootstrapUrl=preg_replace('@^http:@', 'https:', self::$bootstrapUrl);
		}
		return parent::init();
	}
}