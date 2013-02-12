<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lname'); ?>
		<?php echo $form->textField($model,'lname',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email_notify'); ?>
		<?php echo $form->textField($model,'email_notify'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isadmin'); ?>
		<?php echo $form->textField($model,'isadmin'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'profile_img'); ?>
		<?php echo $form->textField($model,'profile_img',array('size'=>60,'maxlength'=>1000)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email_preference'); ?>
		<?php echo $form->textField($model,'email_preference'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'randomPassword'); ?>
		<?php echo $form->textField($model,'randomPassword',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'userStatus'); ?>
		<?php echo $form->textField($model,'userStatus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'defaultAssignee'); ?>
		<?php echo $form->textField($model,'defaultAssignee'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->