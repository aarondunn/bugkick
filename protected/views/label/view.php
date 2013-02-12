<?php
$this->breadcrumbs=array(
	'Labels'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Label', 'url'=>array('index')),
	array('label'=>'Create Label', 'url'=>array('create')),
	array('label'=>'Update Label', 'url'=>array('update', 'id'=>$model->label_id)),
	array('label'=>'Delete Label', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->label_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Label', 'url'=>array('admin')),
);
?>

<h1>View Label #<?php echo $model->label_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'label_id',
		'name',
		'company_id',
	),
)); ?>
