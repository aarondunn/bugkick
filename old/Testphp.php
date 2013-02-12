<?
require_once 'config.php';


$link = mysql_connect($db_host, $db_user, $db_pass);

if (!$link) {

    die('Could not connect: ' . mysql_error());

}



mysql_select_db($db_database);


require_once 'classes/BaseModel.php';
require_once 'classes/Bug.php';

global $link;
$l_labelid = $_GET['labelid'];
$l_accountid = $_GET['accountid'];

$qr = "";

 $duedate_where_qr = " and duedate IS NOT NULL
AND duedate !=  '0000-00-00' ";

if ($l_accountid != 0 && $l_labelid == 0) // filter by account
    $qr = "select bug.* from bug,account_bug where bug.isarchive != 1 and bug.id = account_bug.bug_id and account_bug.account_id = " . $l_accountid . $duedate_where_qr;
else if ($l_accountid == 0 && $l_labelid != 0) // filter by label
    $qr = "select bug.* from bug,label_bug where bug.isarchive != 1 and bug.id = label_bug.bug_id and label_bug.label_id = " . $l_labelid . $duedate_where_qr;
else if ($l_accountid != 0 && $l_labelid != 0) // filter by label and account
    $qr = "select bug.* from bug,account_bug,label_bug where bug.isarchive != 1 and (bug.id = account_bug.bug_id and bug.id = label_bug.bug_id) and (account_bug.account_id = " . $l_accountid . " and label_bug.label_id = " . $l_labelid . $duedate_where_qr . ")";
else
    $qr = "select * from bug where isarchive != 1".$duedate_where_qr;

//$result =  mysql_query("SELECT * FROM bug WHERE isarchive != 1 and duedate IS NOT NULL AND duedate !=  '0000-00-00'", $link);
$result =  mysql_query($qr, $link);
$arr = "[";
while($record = mysql_fetch_assoc($result)) {

    $arr .= "{".'"title":"'.$record['title'].'","start":'.'"'.$record['duedate'].'","url":'.'"?module=bug&action=new&id='.$record['id'].'"},';
}

$arr = substr_replace($arr,"",-1);
$arr .= "]";


echo $arr;

//$arr2 = array(title=>'bug 1',start=>'2010-11-11');
//echo "[". json_encode($arr2)."]";
?>
