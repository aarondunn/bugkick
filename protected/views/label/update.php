<?php
$this->breadcrumbs=array(
	'Labels'=>array('index'),
	$model->name=>array('view','id'=>$model->label_id),
	'Update',
);

//$this->menu=array(
//	array('label'=>'List Label', 'url'=>array('index')),
//	array('label'=>'Create Label', 'url'=>array('create')),
//	array('label'=>'View Label', 'url'=>array('view', 'id'=>$model->label_id)),
//	array('label'=>'Manage Label', 'url'=>array('admin')),
//);
?>
<div class="settings">
<h2 class="listing-title"><?php echo Yii::t('main', 'Update Label'); ?></h2>
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
