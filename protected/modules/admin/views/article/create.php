<?php
$this->breadcrumbs=array(
	'Articles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Back', 'url'=>array('/admin/article')),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Create Article'); ?></h2>
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>