<?php
$this->breadcrumbs=array(
	'Labels'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Label', 'url'=>array('index')),
	array('label'=>'Manage Label', 'url'=>array('admin')),
);
?>

<h1>Create Label</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>