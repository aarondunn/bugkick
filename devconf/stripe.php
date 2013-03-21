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
		// test:
		'secretKey'=>'<secretKey>',
		'publishableKey'=>'<publishableKey>',
	));
/******************************************************************************
 *                                                                            *
 * Set the LIVE KEYS below for real transactions                              *
 *                                                                            *
 ******************************************************************************/
else
	return array_merge($stripeConfig, array(
		// live:
		'secretKey'=>'<secretKey>',
		'publishableKey'=>'<publishableKey>',
	));