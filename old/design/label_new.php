<?php

$label = new Label();
if(isset($_REQUEST['id']))
{
	$label->loadById($_REQUEST['id']);
}

if(isset($_REQUEST['is_new']))
{
	$label->name = $_REQUEST['label']['name'];
	
	$label->save();
	?>
	
<script type="text/javascript">

 location.href = 'index.php?module=settings&action=list';

</script>
	
	<?php
}

?>

<form action="?module=label&action=new&is_new=<?php echo isset($_REQUEST['id']) ? '0' : '1'; ?>" method="post" >
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
  <th><label for="label_name">Name</label></th>

  <td><input type="text" name="label[name]" id="label_name" value="<?php echo $label->name; ?>" />  
  </td>
</tr>
    </tbody>
  </table>
</form>
