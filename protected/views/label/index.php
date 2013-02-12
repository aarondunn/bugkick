<?php
$this->breadcrumbs=array(
	'Labels',
);

$this->menu=array(
	array('label'=>'Create Label', 'url'=>array('create')),
	array('label'=>'Manage Label', 'url'=>array('admin')),
);
?>

<h1>Labels</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
