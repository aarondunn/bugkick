<?php
$this->breadcrumbs=array(
	'Companys'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Company', 'url'=>array('index')),
	array('label'=>'Manage Company', 'url'=>array('admin')),
);
?>

<h1>Create Company</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>