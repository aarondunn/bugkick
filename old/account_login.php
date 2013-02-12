<?require("startup.php");

$index =1 ;





$link = mysql_connect($db_host, $db_user, $db_pass);

if (!$link) {

    die('Could not connect: ' . mysql_error());

}

mysql_select_db($db_database);

if($_POST['Submit']){

	$login = mysql_escape_string($_POST['login']);
	$password = mysql_escape_string($_POST['password']);

        $result =  mysql_query("select * from account where email = '".$login."'" , $link);

        $member = mysql_fetch_row($result);
			
		
			

			if(trim($password) == trim($member[5])){
				{
                            session_start();
					$_SESSION['valid_user'] = $member['0'];
                                        //$msg = "login success".$_SESSION['valid_userme'];
					header("Location: index.php");
                                        
                                        
				}
			}else
			{	$msg = 'Invalid Email or Password!';}
                        

}


?>

 <head>

	<title>Bug tracking system</title>



    <link rel="shortcut icon" href="/favicon.ico" />

    <link rel="stylesheet" type="text/css" media="screen" href="design/css/reset-fonts-grids.css" />

    <link rel="stylesheet" type="text/css" media="screen" href="design/css/main.css" />

    <link rel="stylesheet" type="text/css" media="screen" href="design/css/base.css" />

 </head>

<div id="hd">

		<div id="hd_left">

		<font size="3"><a href="<?php echo "?module=bug&action=list" ?>">bugs</a></font>

                </div>
</div>
<form action="account_login.php" method="post">
  
  <table width="326" border="0">
  <tr>
    <td width="131">Email : </td>
    <td width="185"><input type="text" name="login" id="login" value="" /></td>
  </tr>
  <tr>
    <td>Password : </td>
    <td style="text-align: left;"><input type="password" name="password" id="password" /></td>
  </tr>
  <tr>

<br />



  </tr>
  <tr>
      <td colspan="2" style="text-align:right;">
           <input style="width:90px;" type="submit" name="Submit" value="Login" />
      </td>
  </tr>
</table>

</form>
<p class="style1"><?=$msg?></p>
<br />
