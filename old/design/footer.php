<script>
function filterlist(type,id){
var statusId = 0;        
var labelId = 0;        
var accountId = 0; 
if (type == "status") statusId = id;        
else if (Request.QueryString("statusid").Count > 0) { statusId = Request.QueryString("statusid").Item(1);        }        
if(type == "label")  labelId = id;        
else if (Request.QueryString("labelid").Count > 0) { labelId = Request.QueryString("labelid").Item(1);        }        
if(type == "account")            accountId = id;        
else if (Request.QueryString("accountid").Count > 0) {            accountId = Request.QueryString("accountid").Item(1);        }              
window.location = "?statusid="+ statusId+"&labelid="+ labelId + "&accountid="+accountId;    }
</script></div>
<?if (stripos($_SERVER["REQUEST_URI"],"calendar") <= 0 && stripos($_SERVER["REQUEST_URI"],"archive")  <= 0 ){        ?>
					
<style>.right_menu a{font-size:14px;} ul{margin:0 0 0px 0;} .right_menu p{margin:0 0 5px -15px;} 
.right_menu tr{border-bottom: 1px solid #dedede;} .right_menu tr a{text-decoration:none;}</style>
<div class="yui-u right_menu" style="width:150px;margin-right:20px;">

							
	<ul id="status_ul" style="margin:0;">
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="background:#f4f4f4; color:#222;">
<td><a href="javascript:filterlist('status',0);" style="color:#222;"><b>Status (All):</b></a></td></tr>													
<?php 							$statuses = GlobalData::GetAllStatuses();							
foreach($statuses as $status): ?>								
<tr><td><li style="list-style-type:none;"><a href='javascript:filterlist("status",<?php echo $status->id; ?>)'><?php echo $status->label; ?></a></li></td></tr>	
					<?php endforeach; ?>	</table></ul>	
				
<ul id="label_ul"  style="margin:0;">
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="background:#f4f4f4; color:#222;">
<td><a href='javascript:filterlist("label",0)' style="color:#222;"><b>Labels (All):</b></a>	</td></tr>													
<?php 							$labels = GlobalData::GetAllLabels();							
foreach($labels as $label): ?>								
<tr><td><li style="list-style-type:none;"><a href='javascript:filterlist("label",<?php echo $label->id; ?>)'><?php echo $label->name; ?></a></li></td></tr>		
<?php endforeach; ?>						</table></ul>


					<ul id="people_ul"  style="margin:0;">
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="background:#f4f4f4; color:#222;">
<td><a href='javascript:filterlist("account",0)' style="color:#222;"><b>People (All):</b></a></td></tr>							
<?php 							$accounts = GlobalData::GetAllAccounts();							
foreach($accounts as $account): ?>								
<tr><td><li style="list-style-type:none;"><a href='javascript:filterlist("account",<?php echo $account->id; ?>)'><?php echo $account->name; ?></a></li></td></tr>	
					<?php endforeach; ?>						</table></ul>		

									
</div><?}?>				</div>			</div>		</div>		

<div class="yui-b">			<!-- PUT SECONDARY COLUMN CODE HERE -->		</div>	</div>	<div id="ft" style="font-size:16px;">            <? if($current_member) { ?>              
 <a href="#" id="ExportEmail">Export to Email</a> | <a href="?module=archive&action=list">Archives</a> <br/> User: <? echo $account_name ?>  | <a href="logout.php">Logout</a><?}?>		<!-- PUT FOOTER CODE HERE -->	</div></div> 











<div id="dialog-new-bug"  title="Create New Bug">	

<?php
$bug = new Bug();
        ?>
<form action="?module=bug&action=new&is_new=1" method="post" id="create_bug_form">
<fieldset>

    <ol class="forms" style="width:650px;">

        <li><label for="bug_title">Title:</label>
                        <input type="text" name="bug[title]" style="width:100%;" value="<?php echo $bug->title; ?>" />
                   </li>

	 <li><label for="description">Description:</label>
                           <textarea rows="2" cols="30" name="bug[description]" style="width:100%; height:60px;"><?php echo $bug->description; ?></textarea>
                    </li>



<table><tr><td width="25%">
        <li>
            <label for="bug_status_id">Status:</label>
            <select name="bug[status_id]" style="width:100%">
                <?php
                $statuses = GlobalData::GetAllStatuses();

                foreach ($statuses as $status): ?>

                    <option value="<?php echo $status->id; ?>"><?php echo $status->label; ?></option>

                <?php endforeach; ?>

                </select>

            </li></td>

            
<td width="25%"><li><label for="Add accounts">Assigned to:</label>

		<select name="bug[account_bug_list][]" style="width:100%">

<option value="0">All</option>

<?php
                 $labels = GlobalData::GetAllLabels();

                foreach ($accounts as $account): ?>

	        <option value="<?php echo $account->id; ?>"><?php echo $account->name; ?>
</option>

                <?php endforeach; ?>

                </select>
               
            </li></td>


           
            <td width="25%"><li><label for="Add Label">Select Labels:</label>

 		
		<select name="bug[label_bug_list][]" style="width:100%">
<option value="0">None</option>
                <?php
                 $labels = GlobalData::GetAllLabels();

                foreach ($labels as $label): ?>

	        <option value="<?php echo $label->id;?>"><?php echo $label->name; ?></option>

                <?php endforeach; ?>

                </select>

            </li></td>


          <td width="25%"><li><label for="txtduedate">Due date:</label>

<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			showOtherMonths: true,
			selectOtherMonths: true
		});
	});
	</script>

                <input type="text" name="bug[duedate]" style="width:100%" id="datepicker"/>
            </li></td>  
</tr></table>
<input width="110" style="width:110px; margin-left:10px; font-size:small;" type="submit" id="btnAddEditBug" name="Submit" value="Save" />
    
</fieldset>
</form>
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

              
	});
</script>

</div>




















<div id="dialog-edit-bug"  title="Edit Bug">	

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

	$bug->save($al, $ll);
	$is_new = 0;

}
        ?>
<form action="?module=bug&action=new&is_new=1" method="post" id="create_bug_form">
<?php if(isset($_REQUEST['id'])): ?>
<input type="hidden" id="bugid" name="id" value="<?php echo $_REQUEST['id']; ?>" />
<?php endif; ?>
<fieldset>

    <ol class="forms" style="width:650px;">

     <!--   <li><label for="bug_title"><b>Title:</b></label>
            <?if(isset($_REQUEST['id'])) {?>
             <div id="bug_title_edit" style=""  ><?php echo stripcslashes(ereg_replace("(\r\n|\n|\r)", "<br />", $bug->title)); ?> </div>
             <?
            }
            else
            {
             ?>
            <!--<input type="text" name="bug[title]" style="width:100%;" value="<?php echo $bug->title; ?>" />-->
            <?}?>
        </li>

	 <li><label for="description"><b>Description:</b></label>
                <?if(isset($_REQUEST['id'])) {?>

             <div id="bug_description_edit" style=""><?php echo stripcslashes(ereg_replace("(\r\n|\n|\r)", "<br />", $bug->description)); ?> </div>
             <?} else {?>
            <textarea rows="2" cols="30" name="bug[description]" style="width:100%; height:60px;"><?php echo $bug->description; ?></textarea>
            <?}?>
         </li>

-->

<table><tr><td width="25%">
        <li>
            <label for="bug_status_id">Status:</label>
            <select name="bug[status_id]" style="width:100%">
                <?php
                $statuses = GlobalData::GetAllStatuses();

                foreach ($statuses as $status): ?>
<?php
                    $is_selected = "";
                    if ($bug->id) {
                        if ($bug->getStatus()->id == $status->id)
                            $is_selected = " SELECTED";
                    }
?>

                    <option value="<?php echo $status->id; ?>" <?php echo $is_selected; ?>><?php echo $status->label; ?></option>

                <?php endforeach; ?>

                </select>

            </li></td>

            
<td width="25%"><li><label for="Add accounts">Assigned to:</label>

		<select name="bug[account_bug_list][]" style="width:100%">

<option value="0">All</option>

<?$existing_accounts_ids = array();
                foreach($bug->getAccounts() as $existing_account)
        		$existing_accounts_ids[] = $existing_account->id;
                    ?>
                

<?php
                 $labels = GlobalData::GetAllLabels();

                foreach ($accounts as $account): ?>

	        <option value="<?php echo $account->id; ?>" <?php if(in_array($account->id, $existing_accounts_ids)) echo "SELECTED";?>><?php echo $account->name; ?>
</option>

                <?php endforeach; ?>

                </select>
               

<!-- <?$existing_accounts_ids = array();
                foreach($bug->getAccounts() as $existing_account)
        		$existing_accounts_ids[] = $existing_account->id;
                    ?>

               <?php
                $accounts = GlobalData::GetAllAccounts();
                foreach ($accounts as $account): ?>
                    <input type="checkbox" style="width:15px;" <? if(in_array($account->id, $existing_accounts_ids)) 
echo "checked"; else echo ""; ?> name="bug[account_bug_list][]" value='<? echo $account->id ?>' /> <?php echo $account->name; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php endforeach; ?>
-->
            </li></td>
           
            <td width="25%"><li><label for="Add Label">Select Labels:</label>

 		<?$existing_labels_ids = array();
                foreach($bug->getLabels() as $existing_label)
        		$existing_labels_ids[] = $existing_label->id;
                    ?>


		<select name="bug[label_bug_list][]" style="width:100%">
<option value="0">None</option>
                <?php
                 $labels = GlobalData::GetAllLabels();

                foreach ($labels as $label): ?>

	        <option value="<?php echo $label->id;?>" <?php if(in_array($label->id, $existing_labels_ids)) echo "SELECTED";?>><?php echo $label->name; ?></option>

                <?php endforeach; ?>

                </select>


<!--
                <?$existing_labels_ids = array();
                foreach($bug->getLabels() as $existing_label)
        		$existing_labels_ids[] = $existing_label->id;
                    ?>

               <?php
                $labels = GlobalData::GetAllLabels();

                foreach ($labels as $label): ?>
                    <input type="checkbox" style="width:15px;" 
<? if(in_array($label->id, $existing_labels_ids)) echo "checked"; else echo ""; ?> name="bug[label_bug_list][]" 
value='<? echo $label->id ?>' /> <?php echo $label->name; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php endforeach; ?>
-->


            </li></td>


          <td width="25%"><li><label for="txtduedate">Due date:</label>

	<script>
	$(function() {
		$( "#datepicker2" ).datepicker({
			showOtherMonths: true,
			selectOtherMonths: true
		});
	});
	</script>


                <input type="text" name="bug[duedate]" style="width:100%" id="datepicker2" 
                value="<?php  if($bug->duedate != "00/0/0000") { echo $bug->duedate;} ?>" />

            </li></td>  
</tr></table>
    </ol>   
<input width="110" style="width:110px; margin-left:10px; font-size:small;" type="submit" id="btnAddEditBug" name="Submit" value="Save" />
<div id="msg"></div>
    
</fieldset>
</form>
</div>



 </body></html>