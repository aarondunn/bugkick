<?php
/* @var $this PostController */
/* @var $model BKPost */

$this->breadcrumbs=array(
	'Bkposts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List BKPost', 'url'=>array('index')),
	array('label'=>'Create BKPost', 'url'=>array('create')),
	array('label'=>'Update BKPost', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete BKPost', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BKPost', 'url'=>array('admin')),

    array('label'=>Yii::t('main','Posts'),'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Create Post', 'url'=>array('post/create', 'topicID'=>$model->id)),
);
?>

<h1>View BKPost #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'body',
		'topic_id',
		'user_id',
	),
)); ?>
