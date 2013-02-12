<?php
$currDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$paramsDir = $currDir . 'params' . DIRECTORY_SEPARATOR;
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>$currDir.'..',
	'name'=>'BugKick',
	// application components
	'components'=>array(
		'logger' => array(
			'class' => 'Logger',
		),
		'db'=>require($currDir.'db.php'),
        'CURL' =>array(
            'class'=>'application.extensions.curl.Curl',
            'options'=>array(
                'timeout'=>30,
                'setOptions'=>require($currDir . 'curl-options.php'),
            ),
        ),

        'cache'=>array(
			'class'=>'system.caching.CDummyCache',
		),

        /* File Storage */
        's3Storage' =>require($paramsDir . 's3-storage.php'),
        'localStorage' => array(
            'class' => 'application.components.storage.LocalStorage',
        ),
        /* END of File Storage */

	),
	'import'=>array(
		'application.components.cli.*',
		'application.models.*',
        'application.components.*',
        'application.components.storage.*',
	),


	'params'=>array(
		// this is used in contact page
        'siteUrl'=>'https://bugkick.com',
		'adminEmail'=>'notifications@bugkick.com',
        'passwordSalt'=>require($paramsDir . 'salt.php'),
        'profileImageUrl'=>'images/profile_img/',
        'profileImageThumbUrl'=>'images/profile_img/thumb/',
        'companyLogoUrl'=>'images/company_logo/',
        'companyLogoThumbUrl'=>'images/company_logo/thumb/',
        // leave empty to use php mail(), or 'ses' - to use Amazon SES
        'emailService' => 'ses',
        'amazon'=>require($paramsDir . 'amazon.php'),
		'facebook'=>require($currDir . 'facebook.php'),
        'node'=>require($currDir . 'node.php'),

        //	Payments via stripe.com
        'stripe'=>require($currDir . 'stripe.php'),

        //Files Storage: one of these options - 's3', 'local'
        'storageType'=>'s3',
	),

    
);