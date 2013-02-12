<?php

require_once 'config.php';


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


$action = $_REQUEST['action'];


switch ($action){
case 'show_events':
            global $link;

        $result =  mysql_query("SELECT DATE_FORMAT(duedate,'%m,%e,%Y') as duedate from bug", $link);

        echo '[';
        do{
            if($row->duedate != ''){//makes sure no blanks are returned
                 //this assumes that "eventdate" is UNIX timestamp
               $cdate = $row->duedate;//date("m,d,Y",$row->duedate);
                $dates2 .= '['.$cdate.'],';



            }
        }while($row = mysql_fetch_object($result));
        $dates2 =rtrim($dates2, ",");
        echo $dates2;
        echo ']';

break;
case 'getBugsByDate':
    global $link;
    $duedate = $_POST['duedate'];
    
        $result =  mysql_query("SELECT * from bug where DATE_FORMAT(duedate,'%m/%d/%Y') = '".$duedate."'" , $link);
        
        while($row = mysql_fetch_row($result))
        {
           $bugslist .= "<a href='?module=bug&action=new&id=". $row[0] ."'>". $row[2]."</a><hr><br>";
        }

$num_rows = mysql_num_rows($result);
//echo "$num_rows Rows\n";
echo "$bugslist";

    
break;
case 'DeleteBug':
    global $link;
   $l_id = $_POST['id'];
   mysql_query("DELETE FROM label_bug WHERE bug_id=".$l_id, $link);
   mysql_query("DELETE FROM account_bug WHERE bug_id=".$l_id, $link);
    $result = mysql_query("DELETE FROM bug WHERE id=".$l_id, $link);
    echo "Bug is successfully deleted";
break;

case 'ExportEmail':

    $body ="";
    $body = $_POST['content'];
    $subject = "Export email";
    $msg = "";
     try {
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                @mail("jitesh81@hotmail.com", $subject, $body, $headers);
              $msg = "Email successully send! Please wait for a while...";
            } catch (Exception $e) {
                $msg = $e;
            }

    echo $msg;
    break;

?>

<ul id="allbugs">
<? while(list($id) = mysql_fetch_row($result)):



	$Bug = new Bug();

	$Bug->loadById($id);

	$bugs[] = $Bug;


   ?>
    <li><? echo $Bug->title ?></li>
    <?
endwhile;
?>
</ul>
<?

while(list($id) = mysql_fetch_row($result)):



	$Bug = new Bug();

	$Bug->loadById($id);

	$bugs[] = $Bug;

?>
    

<?
if ($Bug->duedate != "00/0/0000" && !is_null($Bug->duedate))
        $duedate_exist = 1;
else $duedate_exist = 0; ?>

    <div <? if ($duedate_exist == 1 && $Bug->duedate >= date('m/j/Y')) echo "class='bug_main_div'"; else echo "class='bug_main_div'";?>>

				    <?php echo $Bug->id ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



			      	<a href="?module=bug&action=new&id=<?php echo $Bug->id ?>"><?php echo $Bug->title ?>
                                <? if ($Bug->duedate != "00/0/0000" && !is_null($Bug->duedate)) echo "|| Duedate: ".$Bug->duedate;?>
                                </a>



			      	<?php foreach($Bug->getLabels() as $label): ?>

			      	<div class="label_div"><?php echo $label->name; ?></div>

			      	<?php endforeach; ?>



			      	<?php foreach($Bug->getAccounts() as $account): ?>

			      	<div class="people_div"><?php echo $account->name; ?></div>

			      	<?php endforeach; ?>



			      	<div class="status_div"><?php echo $Bug->getStatus()->label; ?></div>



    </div>

<?
endwhile;
?>

 
 <? break;?>

<?
}

mysql_close($link);

?>
