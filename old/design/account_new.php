<?php



$account = new Account();

if(isset($_REQUEST['id']))

{

	$account->loadById($_REQUEST['id']);

}



if(isset($_POST['Submit']))

{

	$account->name = $_REQUEST['account']['name'];

	$account->email = $_REQUEST['account']['email'];

	$account->comment = $_REQUEST['account']['comment'];

        $account->password = $_REQUEST['account']['password'];

       // $account->profile_img = $_REQUEST['account']['profile_img'];
        
        if ($_REQUEST['account']['email_notify'] == "on")
            $account->email_notify = 1;
        else
            $account->email_notify = 0;

        if ($_REQUEST['account']['isadmin'] == "on")
            $account->isadmin = 1;
        else
            $account->isadmin = 0;


       
        
	


        define ("MAX_SIZE","5000");

//This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }


 $image=$_FILES['image']['name'];
 	//if it is not empty
 	if ($image)
 	{
 	//get the original name of the file from the clients machine
 		$filename = stripslashes($_FILES['image']['name']);
 	//get the extension of the file in a lower case format
  		$extension = getExtension($filename);
 		$extension = strtolower($extension);
 	//if it is not a known extension, we will suppose it is an error and will not  upload the file,
	//otherwise we will do more tests
 if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
 		{
		//print error message
 			echo '<h1>Unknown extension!</h1>';
 			$errors=1;
 		}
 		else
 		{
//get the size of the image in bytes
 //$_FILES['image']['tmp_name'] is the temporary filename of the file
 //in which the uploaded file was stored on the server
 $size=filesize($_FILES['image']['tmp_name']);

//compare the size with the maxim size we defined and print error if bigger
if ($size > MAX_SIZE*1024)
{
	echo '<h1>You have exceeded the size limit!</h1>';
	$errors=1;
}

//we will give an unique name, for example the time in unix time format
$image_name=time().'.'.$extension;
//the new name will be containing the full path where will be stored (images folder)
$newname="profile_images/".$image_name;
//we verify if the image has been uploaded, and print error instead

$copied = copy($_FILES['image']['tmp_name'], $newname);
if (!$copied)
{
	echo '<h1>Copy unsuccessfull!</h1>';
	$errors=1;
}}}

$account->profile_img = $image_name;
$account->save();
 if(isset($_POST['Submit']) && !$errors)
 {
 	echo "<h1>Account Saved!</h1>"; ?>
	<script type="text/javascript">
 location.href = 'index.php?module=settings&action=list';
</script><?php 
 }
	?>

	


	

	<?php

}



?>



<form name="newad" action="" enctype="multipart/form-data" method="post" >


<?php if(isset($_REQUEST['id'])): ?>

<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />

<?if($isadmin != 1)
{header("Location: index.php");}
?>
<?php endif; ?>

  <table>

    <tfoot>

      <tr>

        <td colspan="2">



          &nbsp;<a href="?module=settings&action=list">Back to list</a>

                    <input type="submit" name="Submit" value="Save" />

        </td>

      </tr>

    </tfoot>

    <tbody>

      <tr>

  <th><label for="account_name">Name</label></th>



  <td><input type="text" name="account[name]" id="account_name" value="<?php echo $account->name; ?>" />  

  </td>

</tr>

<tr>

  <th><label for="account_email">Email</label></th>

  <td><input type="text" name="account[email]" id="account_email"  value="<?php echo $account->email; ?>" /></td>

</tr>

<tr>

  <th><label for="account_password">Password</label></th>

  <td><input type="password" name="account[password]" id="account_password" value="<?php echo $account->password;?>" /></td>

</tr>

<tr>

  <th><label for="account_email_notify">Email Notification</label></th>

  <td><input type="checkbox" name="account[email_notify]"  <?php if($account->email_notify) echo "checked"; else echo ""; ?> />Email Notify</td>

</tr>

<tr>

  <th><label for="account_isadmin">User Type</label></th>

  <td><input type="checkbox" name="account[isadmin]"  <?php if($account->isadmin) echo "checked"; else echo ""; ?> />Admin</td>

</tr>

<tr>
    <th>

    </th>
    <td>
        <input type="file" name="image">
    </td>
</tr>


<tr>

  <th><label for="account_comment">Comment</label></th>

  <td><input type="text" name="account[comment]" id="account_comment" value="<?php echo $account->comment; ?>" /><input type="hidden" name="account[id]" id="account_id" /></td>



</tr>

    </tbody>

  </table>

</form>

