<?php

$status = new Status();
if(isset($_REQUEST['id']))
{
	$status->loadById($_REQUEST['id']);
}

if(isset($_REQUEST['is_new']))
{
	$status->label = $_REQUEST['status']['label'];
	
	$status->save();
	?>
	
<script type="text/javascript">
 location.href = 'index.php?module=settings&action=list';
</script>
	
	<?php
}

?>

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

  <td><input type="text" name="status[label]" id="status_name" value="<?php echo $status->label; ?>" />  
  </td>
</tr>
    </tbody>
  </table>
</form>
