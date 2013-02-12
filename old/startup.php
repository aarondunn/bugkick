<?session_start(); 

@header("Cache-control: private");
@ini_set("session.gc_maxlifetime","14400");
@ini_set("max_execution_time", "14400");
//error_reporting(0);

require("config.php");


/*
if(!isset($_SESSION['valid_user']) && !$index)
	header("Location: ./login.php");
		
if(isset($_SESSION['valid_user']))
{
	$current_member = $_SESSION['valid_user'];
	
	$member = db_get_row("select * from Members where MemberId = '$current_member'");
	
	$member_type = $member['MemberType'];
}
else
	$current_member = 0;


	
*/
?>