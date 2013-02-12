<?php
$this->breadcrumbs=array(
	'Statuses',
);

$this->menu=array(
	array('label'=>'Create Status', 'url'=>array('create')),
	array('label'=>'Manage Status', 'url'=>array('admin')),
);
?>

<h1>Statuses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
