<?php

$bug = new Bug();


//edit mode
if(isset($_REQUEST['id']))
{
	$bug->loadById($_REQUEST['id']);
}


if(isset($_REQUEST['is_new']))
{
        if(!isset($_REQUEST['id']))
	{



            $bug->title = $_REQUEST['bug']['title'];
            $bug->description = $_REQUEST['bug']['description'];
        }
	$bug->status_id = $_REQUEST['bug']['status_id'];
        if ($_REQUEST['bug']['duedate'] == "")
        {$bug->duedate = null;}
        else
        {$bug->duedate = $_REQUEST['bug']['duedate'];}


	$al = isset($_REQUEST['bug']['account_bug_list']) ? $_REQUEST['bug']['account_bug_list'] : array();

	$ll = isset($_REQUEST['bug']['label_bug_list']) ? $_REQUEST['bug']['label_bug_list'] : array();

	//$bug->save($al, $ll);

}
        ?>

<?

if(is_array($_REQUEST['comment']))

{

	mysql_query("INSERT INTO comment (created_at, message, account_id, bug_id) VALUES(NOW(), '".$_REQUEST['comment']['message']."', ".$current_member.", ".$_REQUEST['id'].")", $link);



	    foreach ($bug->getAccounts() as $account) {

        if ($account->email_notify == 1) {
            $buglink = "<a href='http://www.musopen.org/bugs/index.php?module=bug&action=new&id={$bug->id }'>{$bug->title}</a>";
            $body = <<<END_SDS_ALERT

            Dear {$account->name},<br><br><br>


            New comment were posted to the following bug: {$buglink} <br><br>


            Message:<br><br>

            {$_REQUEST['comment']['message']}


            <br><br>
            Regards

END_SDS_ALERT;



            $subject = "New comment were posted to the following bug: " . $bug->title;

            try {
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                @mail($account->email, $subject, $body, $headers);
            } catch (Exception $e) {
                
            }
        }
    }
}
?>




<?php if(true): ?>
<div style="width:800px;">
<div style="float:left; width:60px; height:47px; margin:7px 15px 0 0; background:url(/bugs/design/images/box.gif); color:#336699; font-weight:bold;
padding:13px 0 0 0px; text-align:center;">
<?php if(true): ?>
#<?php echo $bug->id ?>
<?php else: ?>
New Bug!
<?php endif; ?>

<?php
$statuses = GlobalData::GetAllStatuses();
foreach ($statuses as $status): ?>
<?php
                    if ($bug->id) {
                        if ($bug->getStatus()->id == $status->id)
				echo $status->label;
                    }
?>
<?php endforeach; ?>
</div>

<div style="float:left;">
<h1 style="margin:15px 0 0 0;"><div id="bug_title_edit" style=""  ><?php echo stripcslashes(ereg_replace("(\r\n|\n|\r)", "<br />", $bug->title)); ?> </div>
             </h1>


<b>Type:</b> 

<?$existing_labels_ids = array();
                foreach($bug->getLabels() as $existing_label)
        		$existing_labels_ids[] = $existing_label->id;
                    ?>
               <?php
                $labels = GlobalData::GetAllLabels();
                foreach ($labels as $label): ?>
                    <? if(in_array($label->id, $existing_labels_ids)) echo $label->name; ?>
<?php endforeach; ?>


&nbsp;&nbsp;&nbsp;&nbsp;
<b>Assignee:</b> 
<?$existing_accounts_ids = array();
                foreach($bug->getAccounts() as $existing_account)
        		$existing_accounts_ids[] = $existing_account->id;
                    ?>

               <?php
                $accounts = GlobalData::GetAllAccounts();
                foreach ($accounts as $account): ?>
                   <? if(in_array($account->id, $existing_accounts_ids)) echo $account->name; ?>
                <?php endforeach; ?>

&nbsp;&nbsp;&nbsp;&nbsp;
<b>Due Date:</b> <?php  if($bug->duedate != "00/0/0000") { echo $bug->duedate;} else {echo "N/A";}?>
</div>
<div style="float:right; text-align:right; margin:15px 0 0 0;">
<a style="width:110px; margin-left:10px; font-size:small; height:24px;" href="#" id="EditBug" style="font-size:15px;">Edit</a>
<a style="width:110px; margin-left:10px; font-size:small; height:24px;" href="#" id="deletebug">Delete</a>
</div>
<div style="clear:both;"><br/></div>

<hr style="margin:0px 0 15px 0;"/>

<div id="bug_description_edit" style=""><?php echo stripcslashes(ereg_replace("(\r\n|\n|\r)", "<br />", $bug->description)); ?> </div>
             
<br/>

<hr style="margin:15px 0 15px 0; border-color:#99bbff;"/>

</div>


<?php endif; ?>








<style>
hr{height: 0; border-style: dotted; border-width:1px 0 0 0; border-color:#E8E8E8;}
</style>








<?php if(isset($_REQUEST['id'])): ?>





<?php $comments = $bug->getCommentsArray(); ?>



<?php if(count($comments) > 0): ?>

<h2 style="margin:0;">Replies and Issue History:</h2>
<br/>
<?php endif; ?>

<?php foreach($comments as $comment): ?>

<div style="width:800px;margin:10px 0 10px 0">

<div style="float:left; width:54px; padding:2px; margin-right:10px;background:#aaa;">
<div style="float:left; width:50px; padding:2px; margin-right:10px;background:#fff;">

<?php if ($comment['profile_img']): ?>
<img width="50" src="profile_images/<? echo $comment['profile_img'];?>"  border="2" bordercolor="#1887c5"/>
<?php endif; ?>
<?php if (!($comment['profile_img'])): ?>
<img width="50" src="profile_images/default.jpg"/>
<?php endif; ?>
</div></div>

<div style="float:left; width:725px">
<font size="2" color="#aaaaaa">
<b style="color:#1887c5;"><?php echo $comment['name'] ?></b> replied on 
<span style="color:#1887c5;"><?php echo $comment['created_at'] ?></span></font>
<p style="margin-top:5px;"><?php echo $comment['message'] ?></p>
</div>

<div style="clear:both;"></div></div>
<hr style="border-color:#99bbff;font-size:3px;"/>
<?php endforeach; ?>

<br/>

<center>
<h2>Submit Comment:</h2>

<div class="submit_comment_div">



<form action="index.php?module=bug&action=new&id=<?php echo $_REQUEST['id'] ?>" method="post">



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

		

		

    </tbody>

  </table>

</form>
    


</div>

</center>

<?php endif; ?>

<script type="text/javascript">
	$(function() {
		$( "#bug_duedate" ).datepicker();
                $("#test").click(function(){
                    alert($(this).attr('id'));
                });

                $("#bug_description_edit").editable('save.php', {
                    type   : 'textarea',
                    indicator : 'Saving...',
                    tooltip   : 'Click to edit...',
                    submitdata : {bugid: $("#bugid").val(),method: "bug_description_edit"},
                    submit : "Save",
                    cancel : "Cancel",
                    data: function(value, settings) {
                      /* Convert <br> to newline. */
                      var retval = value.replace(/<br[\s\/]?>/gi, '\n');
                      return retval;
                    },
                    callback : function(value, settings) {
                    $("#bug_description_edit").html( jQuery.trim(value) );
                    }
                });

                $("#bug_title_edit").editable('save.php', {
                    type   : 'textarea',
                    indicator : 'Saving...',
                    tooltip   : 'Click to edit...',
                    submitdata : {bugid: $("#bugid").val(),method: "bug_title_edit"},
                    submit : "Save",
                    cancel : "Cancel",
                    data: function(value, settings) {
                      /* Convert <br> to newline. */
                      var retval = value.replace(/<br[\s\/]?>/gi, '\n');
                      return retval;
                    },
                    callback : function(value, settings) {
                    $("#bug_title_edit").html( jQuery.trim(value) );
                    }
                });

                $("#deletebug").click(function () {
                    var answer = confirm("Are you sure to Delete this bug?");
                    if (answer)
                        {
                    if (Request.QueryString("id").Count > 0) {
                        bugid	 = Request.QueryString("id").Item(1);
                    }
                    else
			bugid = 0;
                    
                     $.ajax({
                    type: "POST",
                    url: "ajax_service.php",
                    data: "id=" + bugid + "&action=DeleteBug"
                    ,
                    success: function(html){
                        $("#msg").html(html);
                        setTimeout(window.location = "index.php",2000);
                        //$(this).dialog('close');
                        


                    }
                });
                        }
                });
	});
</script>


