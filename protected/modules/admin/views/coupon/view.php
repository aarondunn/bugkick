<?php
$this->breadcrumbs=array(
	'Coupons'=>array('index'),
	$model->code,
);

$this->menu=array(
	array('label'=>'List Coupons', 'url'=>array('/admin/coupon')),
	array('label'=>'Create Coupon', 'url'=>array('create')),
	array('label'=>'Update Coupon', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Coupon', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?','csrf' => true)),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'View Coupon'); ?></h2>
    <div class="admin_content">
        <b><?php echo CHtml::encode($model->getAttributeLabel('code')); ?>:</b>
        <?php echo CHtml::encode($model->code); ?>
        <p></p>

        <b><?php echo CHtml::encode($model->getAttributeLabel('period')); ?>:</b>
        <?php echo Coupon::getPeriodLabel($model->period); ?>
        <p></p>

        <b><?php echo CHtml::encode($model->getAttributeLabel('enabled')); ?>:</b>
        <?php echo SiteSettings::itemAlias("onOff",$model->enabled); ?>
       	<p></p>
    </div>
</div>
