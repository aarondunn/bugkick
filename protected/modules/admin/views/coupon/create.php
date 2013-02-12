<?php
$this->breadcrumbs=array(
	'Coupons'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Back', 'url'=>array('/admin/coupon')),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Create Coupon'); ?></h2>
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>