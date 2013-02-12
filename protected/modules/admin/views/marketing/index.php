<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.11.12
 * Time: 0:41
 */

$this->menu=array(
	array('label'=>'Edit Settings', 'url'=>array('/admin/marketing/update')),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Marketing Settings'); ?></h2>
    <div class="admin_content">
        <b><?php echo CHtml::encode($model->getAttributeLabel('invites_module')); ?>:</b>
       	<?php echo SiteSettings::itemAlias('onOff',$model->invites_module); ?>
       	<p></p>

        <b><?php echo CHtml::encode($model->getAttributeLabel('invites_limit')); ?>:</b>
        <?php echo SiteSettings::itemAlias('onOff',$model->invites_limit); ?>
        <p></p>

        <b><?php echo CHtml::encode($model->getAttributeLabel('invites_count')); ?>:</b>
        <?php echo CHtml::encode($model->invites_count); ?>
    </div>
</div>