<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2><?php echo Yii::t('main', 'Error'); ?> <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>