<style>th{text-align:left;}
thead tr{background:#ddd;color:#222;} h3{margin:0 0 10px 0;}</style>


<div style="width:100%">
<h1>Settings</h1>

<h3>Label List</h3>

      <table>
        <thead>
          
          <tr>
            <th width="50px">Id</th>
            <th width="170px">Name</th>
            <th width="50px"></th>
          </tr>
        </thead>
        <tbody>
          <?php $alternate = 0; if(isset($_REQUEST['delete'])){	global $link;	$result = mysql_query("DELETE FROM label WHERE id=".$_REQUEST['id'], $link);	$result = mysql_query("DELETE FROM label_bug WHERE label_id=".$_REQUEST['id'], $link);}$labels = GlobalData::GetAllLabels();foreach($labels as $label): ?>
<tr <?php if ($alternate%2==1){
            echo "style='background-color:#f4f4f4;'"; 
}
	     $alternate += 1; ?>>
         
            <td>
              <a href="?module=label&action=new&id=<?php echo $label->id ?>"><?php echo $label->id; ?>
              </a>
            </td>
            <td>
              <?php echo $label->name; ?>
            </td>
            <td>
              <a href="index.php?module=settings&action=list&delete=1&id=
                <?php echo $label->id ?>" onclick="javascript: return confirm('Are you sure you wish to delete <?php echo $label->name; ?> label record?')">delete
              </a>
            </td>
          </tr>
          <?php endforeach; ?>

          <tr style="background:#eaeaea;">
            <td colspan="2" style="text-align:right;"></td><td>
              <a href="#" class="EditLabel">New</a>
            </td>
          </tr>
        </tbody>
      </table>
<br/>
<h3>Status List</h3>
      <table>
        <thead>
          
          <tr>
             <th width="50px">Id</th>
            <th width="170px">Name</th>
            <th width="50px"></th>
          </tr>
        </thead>
        <tbody>
          <?php if(isset($_REQUEST['delete']))
            {	global $link;	$result = mysql_query("DELETE FROM status WHERE id=".$_REQUEST['id'], $link);	}
          $statuses = GlobalData::GetAllStatuses();foreach($statuses as $status): ?>
          <tr <?php if ($alternate%2==1){
            echo "style='background-color:#f4f4f4;'"; 
}
	     $alternate += 1; ?>>
            <td>
              <a href="?module=status&action=new&id=<?php echo $status->id ?>"><?php echo $status->id; ?>
              </a>
            </td>
            <td>
              <?php echo $status->label; ?>
            </td>
            <td>
              <a href="index.php?module=settings&action=list&delete=1&id=
                <?php echo $status->id ?>" onclick="javascript: return confirm('Are you sure you wish to delete <?php echo $status->name; ?> status record?')">delete
              </a>
            </td>
          </tr>
          <?php endforeach; ?>

          <tr style="background:#eaeaea;">
            <td colspan="2" style="text-align:right;"></td><td>
              <a href="#" id="EditStatus">New</a>
            </td>
            
          </tr>
        </tbody>
        
      </table>
<br/>

<? if ($isadmin == 1) {?>

<h3 style="margin-top:0;">Accounts List</h3>
<table width="100%">

  <thead>

 	
    <tr>

      <th>Id</th>

      <th>Created at</th>



      <th>Name</th>

      <th>Email</th>

      <th>Comment</th>

      <th width="50"></th>

    </tr>

  </thead>

  <tbody>

  

<?php 



if(isset($_REQUEST['delete']))

{

	global $link;

	$result = mysql_query("DELETE FROM account WHERE id=".$_REQUEST['id'], $link);

	$result = mysql_query("DELETE FROM account_bug WHERE account_id=".$_REQUEST['id'], $link);

	$result = mysql_query("DELETE FROM comment WHERE account_id=".$_REQUEST['id'], $link);

}



$accounts = GlobalData::GetAllAccounts();

foreach($accounts as $account): ?>

	

    <tr <?php if ($alternate%2==1){
            echo "style='background-color:#f4f4f4;'"; 
}
	     $alternate += 1; ?>>



      <td><a href="?module=account&action=new&id=<?php echo $account->id ?>"><?php echo $account->id; ?></a></td>

      <td><?php echo $account->created_at; ?></td>

      <td><?php echo $account->name; ?></td>

      <td><?php echo $account->email; ?></td>

      <td><?php echo $account->comment; ?></td>

      <td><a href="index.php?module=settings&action=list&delete=1&id=<?php echo $account->id ?>" onclick="javascript: return confirm('Are you sure you wish to delete <?php echo $account->name; ?> account record?')">delete</a></td>

    </tr>	



<?php endforeach; ?>

<tr style="background:#eaeaea;">
            <td colspan="5" style="text-align:right;"></td><td>
             <a href="#" id="EditAccount">New</a>
            </td>
            
          </tr>

      </tbody>

</table>
    
  <?}?>


  </div>
  







<div id="dialog-edit-label"  title="New Label">
<form action="?module=label&action=new&is_new=<?php echo isset($_REQUEST['id']) ? '0' : '1'; ?>" method="post" >
  <table>
    <tfoot>
      <tr>
        <td colspan="2">

          &nbsp;<a href="?module=settings&action=list">Back to list</a>
                    <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <tr>
  <th><label for="label_name">Name</label></th>

  <td><input type="text" name="label[name]" id="label_name" />  
  </td>
</tr>
    </tbody>
  </table>
</form>

</div>




<div id="dialog-edit-status"  title="New Status">
<form action="?module=status&action=new&is_new=<?php echo isset($_REQUEST['id']) ? '0' : '1'; ?>" method="post" >
<?php if(isset($_REQUEST['id'])): ?>
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">

          &nbsp;<a href="?module=settings&action=list">Back to list</a>
                    <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <tr>
  <th><label for="status_name">Name</label></th>

  <td><input type="text" name="status[label]" id="status_name" />  
  </td>
</tr>
    </tbody>
  </table>
</form>


</div>



<div id="dialog-edit-account"  title="New Account">
<form name="newad" action="?module=account&action=new" enctype="multipart/form-data" method="post" >


<?php if(isset($_REQUEST['id'])): ?>
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
  <td><input type="text" name="account[name]" id="account_name" />  
  </td>
</tr>

<tr>
  <th><label for="account_email">Email</label></th>
  <td><input type="text" name="account[email]" id="account_email" /></td>
</tr>

<tr>
  <th><label for="account_password">Password</label></th>
  <td><input type="password" name="account[password]" id="account_password"/></td>
</tr>

<tr>
  <th><label for="account_email_notify">Email Notification</label></th>
  <td><input type="checkbox" name="account[email_notify]" />Email Notify</td> 
</tr>

<tr>
  <th><label for="account_isadmin">User Type</label></th>
  <td><input type="checkbox" name="account[isadmin]"  />Admin</td>
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


</div>






