<?
	session_start();
	unset($_SESSION['valid_user']);
	unset($current_member);
	header("Location: ./index.php");
?>