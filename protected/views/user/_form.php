<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lname'); ?>
		<?php echo $form->textField($model,'lname',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'lname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_notify'); ?>
		<?php echo $form->textField($model,'email_notify'); ?>
		<?php echo $form->error($model,'email_notify'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isadmin'); ?>
		<?php echo $form->textField($model,'isadmin'); ?>
		<?php echo $form->error($model,'isadmin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'profile_img'); ?>
		<?php echo $form->textField($model,'profile_img',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'profile_img'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_preference'); ?>
		<?php echo $form->textField($model,'email_preference'); ?>
		<?php echo $form->error($model,'email_preference'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'randomPassword'); ?>
		<?php echo $form->textField($model,'randomPassword',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'randomPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'userStatus'); ?>
		<?php echo $form->textField($model,'userStatus'); ?>
		<?php echo $form->error($model,'userStatus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'defaultAssignee'); ?>
		<?php echo $form->textField($model,'defaultAssignee'); ?>
		<?php echo $form->error($model,'defaultAssignee'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->