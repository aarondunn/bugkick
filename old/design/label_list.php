						<h1>Labels List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  
<?php 

if(isset($_REQUEST['delete']))
{
	global $link;
	$result = mysql_query("DELETE FROM label WHERE id=".$_REQUEST['id'], $link);
	$result = mysql_query("DELETE FROM label_bug WHERE label_id=".$_REQUEST['id'], $link);
}

$labels = GlobalData::GetAllLabels();
foreach($labels as $label): ?>
	
        <tr>

      <td><a href="?module=label&action=new&id=<?php echo $label->id ?>"><?php echo $label->id; ?></a></td>
      <td><?php echo $label->name; ?></td>
      <td><a href="index.php?module=label&action=list&delete=1&id=<?php echo $label->id ?>" onclick="javascript: return confirm('Are you sure you wish to delete <?php echo $label->name; ?> label record?')">delete</a></td>
    </tr>	

<?php endforeach; ?>
      </tbody>
</table>

  <a href="?module=label&action=new">New</a>
