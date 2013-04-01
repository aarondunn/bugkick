<?php
$currDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$paramsDir = $currDir . 'params' . DIRECTORY_SEPARATOR;

// uncomment the following to define a path alias

// Yii::setPathOfAlias('local','path/to/local-folder');



// This is the main Web application configuration. Any writable

// CWebApplication properties can be configured here.

return array(

	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	'name'=>'BugKick',

	'theme'=>'bugkick_theme',

    'controllerMap' => require($currDir . 'controller-map.php'),


	// preloading 'log' component

	'preload'=>array('log'),



	// autoloading model and component classes

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.payments.*',
        'application.components.plan_config.*',
        'application.components.view_more.*',
        'application.helpers.*',
        'application.components.storage.*',
        'application.modules.forum.components.*',
        'application.modules.forum.extensions.*',
    ),

    'aliases' => array(
        'xupload' => 'ext.xupload',
    ),

	'modules'=>array(
		'admin',
		'api',
        'github',
		// comment the following to disable the Gii tool
		'gii'=>array(

			'class'=>'system.gii.GiiModule',
			'password'=>'123',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
        'forum'=>array(
            'userModelClassName'=>'User',
            'userRolePropertyName'=>'forum_role', // User model property that defines user role on forum
        ),
	),


	// application components

	'components'=>array(

		'request'=>array(
            'class' => 'application.components.EHttpRequest',
			'enableCsrfValidation'=>true,
			'noCsrfValidationRoutes'=>array(
				'payment/stripe-web-hook',
				'api',
			)
		),

        'session' => array(
            'class' => 'CDbHttpSession',
            'connectionID' => 'db',
            'autoCreateSessionTable' => false,
            'sessionTableName'=>'bk_yii_session',
        ),

        'user'=>array(
            //'class'=>'WebUser',
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'autoRenewCookie'=>true,
            'class' => 'BKWebUser',
        ),

        'authManager' => array(
            'class' => 'BKPhpAuthManager',
            'defaultRoles' => array('guest'),
        ),

        'image'=>array(

          'class'=>'application.extensions.image.CImageComponent',

            // GD or ImageMagick

            'driver'=>'GD',

            // ImageMagick setup path

            //'params'=>array('directory'=>'/opt/local/bin'),

        ),

		'logger' => array(

			'class' => 'Logger',

		),

		'notificator' => array(

			'class' => 'Notificator',

		),

		 'mailer' => array(

			  'class' => 'application.extensions.mailer.EMailer',

			  //'pathViews' => 'application.views.email',

			  //'pathLayouts' => 'application.views.email.layouts'

		       ),

		// uncomment the following to enable URLs in path-format



		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>require($currDir . 'routes.php'),
			/*
			'rules'=>array(

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',

				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',

				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

			),
			*/
		),


		/*'db'=>array(

			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',

		),

		// uncomment the following to use a MySQL database

		*/

		'db'=>require($currDir . 'db.php'),
		/**
		 * 	The database settings has been placed in db.php file.
		 *	So we can set this file as ignored in the .hgignore to
		 *	avoid the overwriting of developers local settings.
		 *
		 *	-f0t0n
		 *
		 'db'=>array(

			'connectionString' => 'mysql:host=bugs2.db.3312190.hostedresource.com;dbname=bugs2',

			'emulatePrepare' => true,

			'username' => 'bugs2',

			'password' => 'fwf3#sefsS',

			'charset' => 'utf8',

		),
		 */



		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>require($currDir.'logroutes.php'),
		),

		'clientScript'=>array(
            'class'=>'application.components.ClientScript',
			'scriptMap'=>array(
				'jquery.js'=>false,
				'jquery.min.js'=>false,
				'jquery-ui.min.js'=>false,
				'jquery-ui.js'=>false,
			),
		),

		'syntaxhighlighter'=>array(
			'class'=>'ext.JMSyntaxHighlighter.JMSyntaxHighlighter',
			/*	Available themes:
							Default (the default if none provided)
							Django
							Eclipse
							Emacs
							FadeToGrey
							MDUltra
							Midnight
							RDark
			 */
			'theme'=>'Eclipse',
		),

        'widgetFactory' => array(
            'widgets' => array(
                'CLinkPager' => array(
                    'nextPageLabel' => '<span class="pagination-right"></span>',
                    'prevPageLabel' => '<span class="pagination-left"></span>',
                ),
                'CJuiDialog' => array(
                    'themeUrl' => '/css/',
                    'theme' => 'ui',
                    'options'=>array(
                        'resizable'=>false,
                    )
                ),
            ),
        ),

		'cache'=>array(
			'class'=>'system.caching.CApcCache',
		),

		 // Redis cache:
		'rcache'=>array(
			'class'=>'ext.redis.CRedisCache',
			//Cluster of servers:
			'servers'=>array(
				array(
					'host'=>'127.0.0.1',
					'port'=>6379
				),
//				other servers of the cluster:

//				array(
//					'host'=>'server2',
//					'port'=>6379,
//				),
			),
		),

		'CURL' =>array(
			'class'=>'application.extensions.curl.Curl',
			'options'=>array(
				'timeout'=>30,
				'setOptions'=>require($currDir . 'curl-options.php'),
			),
		),

        'minScript'=>array(
            'class'=>'ext.minScript.components.ExtMinScript',
            'allowDirs'=>array(
                'js',
                'css',
                'themes/bugkick_theme/css',
                'themes/bugkick_theme/js',
            ),
         ),

        /* File Storage */
        's3Storage'=>require($paramsDir . 's3-storage.php'),
        'localStorage' => array(
            'class' => 'application.components.storage.LocalStorage',
        ),
        /* END of File Storage */

    ),


	// application-level parameters that can be accessed

	// using Yii::app()->params['paramName']

	'params'=>array(

		// this is used in contact page

        'siteUrl'=>'https://bugkick.com',

		'adminEmail'=>'notifications@bugkick.com',

        'passwordSalt'=>require($paramsDir . 'salt.php'),
        
        'bcryptWorkFactor'=>10,

        'profileImageUrl'=>'images/profile_img/',

        'profileImageThumbUrl'=>'images/profile_img/thumb/',

        'companyLogoUrl'=>'images/company_logo/',

        'companyLogoThumbUrl'=>'images/company_logo/thumb/',

        // leave empty to use php mail(), or 'ses' - to use Amazon SES
        //'emailService' => 'ses','sqs'
        'emailService' => 'ses',

        'amazon'=>require($paramsDir . 'amazon.php'),

		//	Payments via stripe.com
		'stripe'=>require($currDir . 'stripe.php'),

		//	Payment plans
		'plans'=>require($currDir . 'plans.php'),

		'facebook'=>require($currDir . 'facebook.php'),
        'github'=>require($paramsDir . 'github.php'),
        'ssl'=>require($paramsDir . 'ssl.php'),

		//number of labels to be shown on bug index page

		'label_number_shown' => 3,

		'node'=>require($currDir . 'node.php'),

        //These status presets will be created for newly registered companies
        'statuses'=>array(
            array(
                'label'=>'Testing',
                'status_color'=>'#D97925',
                'is_visible_by_default'=>'1',
            ),
            array(
                'label'=>'New',
                'status_color'=>'',
                'is_visible_by_default'=>'1',
            ),
            array(
                'label'=>'Resolved',
                'status_color'=>'#181F24',
                'is_visible_by_default'=>'1',
            ),
            array(
                'label'=>'In Progress',
                'status_color'=>'#025159',
                'is_visible_by_default'=>'1',
            ),
            array(
                'label'=>'On Hold',
                'status_color'=>'#6E7273',
                'is_visible_by_default'=>'0',
            ),
        ),

        //These label presets will be created for newly registered companies
        'labels'=>array(
            'Feature'=> '#359ce4',
            'Bug'=> '#d5ce45',
            'Enhancement'=> '#74dc42',
            'Proposal'=> '#b435cf',
            'Design'=> '#49ddab',
        ),

        //default preset of label colors
        'labelDefaultColors'=>array(
            '#2D9BE7',
            '#2ECBE7',
            '#3FDEAA',
            '#70DE33',
            '#B1DE2F',
            '#D5D035',
            '#E0B730',
            '#E0822C',
            '#C83528',
            '#DF2C51',
            '#DD2B9D',
            '#B42AD0',
        ),

         //if set to false - we send both email and node notification,
        //else node message will be shown if user is online, email will be send if not.
        'skipEmailIfNodeReceived'=>false,
        
        'bugkickApiSettings'=>require($currDir . 'bugkick-api-settings.php'),

        //Files Storage: one of these options - 's3', 'local'
        'storageType'=>require($paramsDir . 'storage-type.php'),

        //projects number available for free companies
        'projects_number_for_free'=>3,

        //MixPanel events tracking
        'mixpanel'=>require($paramsDir . 'mixpanel.php'),

        //Box.net settings
        'box'=>require($paramsDir . 'box.php'),
    ),
);