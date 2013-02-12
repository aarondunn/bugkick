<?php
defined('STRIPE_PAYMENTS_DEBUG') or define('STRIPE_PAYMENTS_DEBUG', 0);
$stripeConfig=array(
	/*
	 * Stripe intervals between payment attempts
	 */
	'interval1'=>86400, //1day
	'interval2'=>86400, //1day
	'interval3'=>86400, //1day

    /*
     * Type of plans configuration storage
     * */
    'planConfigStorageType'=>'file', //or 'database'
);
/******************************************************************************
 *                                                                            *
 * This is a TEST KEYS that ARE NOT VALID for transactions IN PRODUCTION mode *
 *                                                                            *
 ******************************************************************************/
if(STRIPE_PAYMENTS_DEBUG)
	return array_merge($stripeConfig, array(
		// f0t0n:
		//'secretKey'=>'V6OOc1jPn0iauNQPLt7TLGyK2riXyCnw',
		//'publishableKey'=>'pk_KvxJ12R1KPyfLtyXOVyR053vQoIBM',
		
		// Aaron:
		'secretKey'=>'k0iMJ6xla2zyG7wztzRYzuX66Dd6ihMp',
		'publishableKey'=>'pk_JOW98WC0ZOqolwsIRSmVEH9NEQT8s',
	));
/******************************************************************************
 *                                                                            *
 * Set the LIVE KEYS below for real transactions                              *
 *                                                                            *
 ******************************************************************************/
else
	return array_merge($stripeConfig, array(
		// Aaron:
		'secretKey'=>'lzRKu2uiUld8eeVUGJNZFQh8JKn7ddVo',
		'publishableKey'=>'pk_i0sgZdOhWR1vjb93zZRBn7cwfUtq3',
	));