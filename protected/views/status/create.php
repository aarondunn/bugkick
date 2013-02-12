<?php
$this->breadcrumbs=array(
	'Statuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Status', 'url'=>array('index')),
	array('label'=>'Manage Status', 'url'=>array('admin')),
);
?>

<h1>Create Status</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>