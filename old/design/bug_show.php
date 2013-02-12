<?php



$bug = new Bug();

$bug->loadById($_REQUEST['id']);



if(is_array($_REQUEST['comment']))

{

	mysql_query("INSERT INTO comment (created_at, message, account_id, bug_id) VALUES(NOW(), '".$_REQUEST['comment']['message']."', ".$_REQUEST['comment']['account_id'].", ".$_REQUEST['id'].")", $link);

	

	    foreach($bug->getAccounts() as $account)

		    {

	$body=<<<END_SDS_ALERT

Dear {$account->name},



New comment were posted to the following bug: {$bug->title}

Message:

{$_REQUEST['comment']['message']}



Regards

END_SDS_ALERT;



	$subject = "New comment were posted to the following bug: ".$bug->title;

	try {

	    @mail($account->email, $subject, $body);

	} catch (Exception $e) {

	    

	}

	

		    }

}





?>



<table>

  <tbody>

    <tr>

      <th>Id:</th>

      <td><?php echo $bug->id; ?></td>

    </tr>

    <tr>

      <th>Created at:</th>

      <td><?php echo $bug->created_at; ?></td>

    </tr>

    <tr>

      <th>Title:</th>

      <td><?php echo $bug->title; ?></td>



    </tr>

    <tr>

      <th>Description:</th>

      <td><?php echo $bug->description; ?></td>

    </tr>

  </tbody>

</table>



<hr />



<a href="?module=bug&action=new&id=<?php echo $bug->id ?>">Edit</a>

&nbsp;

<a href="?module=bug&action=list">List</a>

<hr />



<h2>Comments History:</h2>



<?php $comments = $bug->getCommentsArray(); ?>



<?php foreach($comments as $comment): ?>

	<div class="comment_div">

	<font size="1" color="#bbbbbb"><?php echo $comment['created_at'] ?>: <?php echo $comment['name'] ?></font>

	<p><?php echo $comment['message'] ?></p>

	</div>

<?php endforeach; ?>



<br>

<br>

<h2>Submit Comment:</h2>

<div class="submit_comment_div">



<form action="index.php?module=bug&action=show&id=<?php echo $_REQUEST['id'] ?>" method="post">



  <table>

    <tfoot>

      <tr>

        <td colspan="2">

          <input type="submit" value="Submit" />

        </td>

      </tr>



    </tfoot>

    <tbody>

      <tr>

  <th><label for="comment_message">Message</label></th>

  <td><textarea rows="4" cols="30" name="comment[message]" id="comment_message"></textarea></td>

</tr>

<tr>

  <th><label for="comment_account_id">My name is</label></th>



  <td><select name="comment[account_id]" id="comment_account_id">

		<?php 

		$accounts = GlobalData::GetAllAccounts();

		foreach($accounts as $account): ?>

			<option value="<?php echo $account->id; ?>"><?php echo $account->name; ?></option>

		<?php endforeach; ?>

</select></td>

</tr>

    </tbody>

  </table>

</form>



</div>