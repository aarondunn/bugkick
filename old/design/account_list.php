						<h1>Accounts List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Created at</th>

      <th>Name</th>
      <th>Email</th>
      <th>Comment</th>
      <th></th>
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
	
        <tr>

      <td><a href="?module=account&action=new&id=<?php echo $account->id ?>"><?php echo $account->id; ?></a></td>
      <td><?php echo $account->created_at; ?></td>
      <td><?php echo $account->name; ?></td>
      <td><?php echo $account->email; ?></td>
      <td><?php echo $account->comment; ?></td>
      <td><a href="index.php?module=account&action=list&delete=1&id=<?php echo $account->id ?>" onclick="javascript: return confirm('Are you sure you wish to delete <?php echo $account->name; ?> account record?')">delete</a></td>
    </tr>	

<?php endforeach; ?>
      </tbody>
</table>

  <a href="?module=account&action=new">New</a>
