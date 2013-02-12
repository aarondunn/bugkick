<?php
$this->breadcrumbs=array(
	'Settings',
);?>
<h1><?php echo Yii::t('main', 'Account Listing'); ?></h1>

<?php if(!empty($userProvider)): ?>
    <?php $this->renderPartial('_users',array(
        'model' => $userProvider,
    )); ?>
<?php else: ?>
<?php echo Yii::t('main', 'No Users'); ?><br>
<?php endif ?>

<?php echo CHtml::link('New user', '#', array('id'=>'newUser')) ?>

<?php
Yii::app()->clientScript->registerScript('raiser', '
    $("#newUser").click(function(){       
        $("#userDialog").dialog("open");
    });

', CClientScript::POS_READY);
?>

<?php 
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'userDialog',
        'options'=>array(
            'title'=>'New User',
            'autoOpen'=>false,
            'modal'=>true,
            'hide'=>'drop',
	     	'show'=>'drop',
        ),
    ));
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
    'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    'action'=>CHtml::normalizeUrl(array('user/create'))
)); ?>

    <div class="row">
		<?php echo $form->labelEx($userModel,'name'); ?>
		<?php echo $form->textField($userModel,'name',array('size'=>40,'maxlength'=>100)); ?>
		<?php echo $form->error($userModel,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($userModel,'lname'); ?>
		<?php echo $form->textField($userModel,'lname',array('size'=>40,'maxlength'=>255)); ?>
		<?php echo $form->error($userModel,'lname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($userModel,'email'); ?>
		<?php echo $form->textField($userModel,'email',array('size'=>40,'maxlength'=>100)); ?>
		<?php echo $form->error($userModel,'email'); ?>
	</div>

   <div class="row">
		<?php echo $form->labelEx($userModel,'isadmin'); ?>
		<?php echo CHtml::activeCheckBox($userModel, 'isadmin') ?>
		<?php echo $form->error($userModel,'isadmin'); ?>
	</div>

<br/>
<div class="row buttons">
    <?php echo CHtml::submitButton('Save'); ?>
</div>

<?php $this->endWidget(); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
