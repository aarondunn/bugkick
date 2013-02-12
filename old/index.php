<?php
require("startup.php");
$link = mysql_connect($db_host, $db_user, $db_pass);
if (!$link) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_database);
require_once 'classes/BaseModel.php';
require_once 'classes/Bug.php';
require_once 'classes/Account.php';
require_once 'classes/Label.php';
require_once 'classes/Status.php';
require_once 'classes/GlobalData.php';
if(!isset($_SESSION['valid_user']) && !$index)
	header("Location: account_login.php");
if(isset($_SESSION['valid_user'])) {
	$current_member = $_SESSION['valid_user'];
	$result =  mysql_query("select * from account where id = ".$current_member, $link);
	$member = mysql_fetch_row($result);
	$account_name = $member[2];
	$isadmin = $member[7];
}
else
	$current_member = 0;
require_once 'design/header.php';
if(isset($_REQUEST['module']) && isset($_REQUEST['action']))
	require_once 'design/'.$_REQUEST['module'].'_'.$_REQUEST['action'].'.php';
else
	require_once 'design/bug_list.php';
require_once 'design/footer.php';
mysql_close($link);
