<?php
/**
 * Hash
 *
 * @author f0t0n
 */
class Hash {

	protected static $algorithms = array(
		'sha1'=>2,		//	MHASH_SHA1
		'sha256'=>17,	//	MHASH_SHA256
		'sha512'=>20,	//	MHASH_SHA512
	);

	public static function sha1($string) {
		return self::_hash('sha1', $string);
	}
	
	public static function sha256($string) {
		return self::_hash('sha256', $string);
	}
	
	public static function sha512($string) {
		return self::_hash('sha512', $string);
	}
	
	protected static function _hash($algorithm, $string) {
		if(function_exists('hash'))
			return hash($algorithm, $string);
		return bin2hex(mhash(self::$algorithms[$algorithm], $string));
	}
}