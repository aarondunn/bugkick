<?php
/* @var $this TopicController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Bktopics',
);

$this->menu=array(
	array('label'=>'Create Topic', 'url'=>array('create'),
		'visible'=>Yii::app()->user->checkAccess('user')),
	array('label'=>'Manage Topic', 'url'=>array('admin'),
	    'visible'=>Yii::app()->user->checkAccess('moderator')),
);
?>

<h1>Topics</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
