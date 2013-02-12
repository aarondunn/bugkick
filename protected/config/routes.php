<?php
return array(
    'signup'=>'registration',
	'settings/email-preferences'=>'settings/emailPreferences',
    'projects'=>'project',
    'projects/<action:\w+>'=>'project/<action>',
    'projects/<action:\w+>/<id:\d+>'=>'project/<action>',
    'updates'=>'notification',
    'help'=>'site/help',
	//	Bug
	array(
		'class'=>'application.components.ProjectUrlRuleEx',
		'connectionID'=>'db',
	),
	'ticket/<id:\d+>'=>'bug/view',
	//	Default routes
	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
	'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
	'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
);