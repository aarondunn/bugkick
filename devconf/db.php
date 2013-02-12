<?php
$isProfilingEnabled=defined('YII_PROFILE') && YII_PROFILE > 0;
return array(
	'connectionString' => 'mysql:host=localhost;dbname=bugkick',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
    'tablePrefix' => 'bugkick_',
	'enableProfiling'=>$isProfilingEnabled,
	'enableParamLogging'=>$isProfilingEnabled,
);