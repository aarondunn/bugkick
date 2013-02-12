<?php
$this->breadcrumbs=array(
	'Statuses'=>array('index'),
	$model->status_id=>array('view','id'=>$model->status_id),
	'Update',
);

//$this->menu=array(
//	array('label'=>'List Status', 'url'=>array('index')),
//	array('label'=>'Create Status', 'url'=>array('create')),
//	array('label'=>'View Status', 'url'=>array('view', 'id'=>$model->status_id)),
//	array('label'=>'Manage Status', 'url'=>array('admin')),
//);
?>
<div class="settings">
<h2 class="listing-title"><?php echo Yii::t('main', 'Update Status'); ?></h2>
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
