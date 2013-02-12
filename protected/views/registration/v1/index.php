<?php
$this->breadcrumbs=array(
	'Registration',
);?>
<div class="settings">
    <h2><?php echo Yii::t('main', 'User Registration'); ?></h2>

    <?php if(Yii::app()->user->hasFlash('registration')): ?>

    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('registration'); ?>
    </div>

    <?php else: ?>
        <?php echo $this->renderPartial('v1/_form', array(
            'user'=>$user,
            'company'=>$company,
            'subscription'=>$subscription
        )); ?>
    <?php endif; ?>

</div>