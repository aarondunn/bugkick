<?php
$this->breadcrumbs=array(
	'Coupons'=>array('index'),
	$model->code=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Coupons', 'url'=>array('/admin/coupon')),
	array('label'=>'Create Coupon', 'url'=>array('create')),
	array('label'=>'View Coupon', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Update Coupon'); ?></h2>
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>