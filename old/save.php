<?php

require_once 'config.php';


$link = mysql_connect($db_host, $db_user, $db_pass);

if (!$link) {

    die('Could not connect: ' . mysql_error());

}



mysql_select_db($db_database);


require_once 'classes/BaseModel.php';
require_once 'classes/Bug.php';

$bug = new Bug();
$bug->Update($_POST['bugid'],$_POST['value'],$_POST['method']);

print $_POST['value'];

mysql_close($link);

?>
