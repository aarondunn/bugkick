<?php
$this->breadcrumbs=array(
	'Bugs'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Bug', 'url'=>array('index')),
	array('label'=>'Create Bug', 'url'=>array('create')),
	array('label'=>'View Bug', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Bug', 'url'=>array('admin')),
);
?>

<h1>Update Bug <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>