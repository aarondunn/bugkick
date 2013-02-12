<?php
/* @var $this PostController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Bkposts',
);

$this->menu=array(
	array('label'=>'Create BKPost', 'url'=>array('create')),
	array('label'=>'Manage BKPost', 'url'=>array('admin')),
);
?>

<h1>Bkposts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
