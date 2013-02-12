<?php
$this->breadcrumbs=array(
	'Articles'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Articles', 'url'=>array('/admin/article')),
	array('label'=>'Create Article', 'url'=>array('create')),
	array('label'=>'View Article', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Update Article'); ?></h2>
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>