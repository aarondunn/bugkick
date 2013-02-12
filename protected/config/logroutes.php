<?php
$logRoutes[]=array(
	'class'=>'CFileLogRoute',
	'maxFileSize'=>1024,
	'maxLogFiles'=>16,
);
if(defined('YII_PROFILE') && YII_PROFILE > 0) {
	$logRoutes[]=array(
		'class'=>'ext.db_profiler.DbProfileLogRoute',
		'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
		'slowQueryMin' => 0.01, // Minimum time for the query to be slow
	);
	/*$logRoutes[]=array(
		'class'=>'CWebLogRoute',
	);*/
}
return $logRoutes;