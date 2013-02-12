<span id="updateGrid" style="display:none;"><?php echo (int)!$form->hasErrors() ?></span>
<span id="groupsEditFormTitle" style="display:none;"><?php echo $formTitle; ?></span>
<div class="form" id="groupFormContainer">
	<?php
	$activeForm=$this->beginWidget('CActiveForm',
		array(
			'id'=>'group-form',
			'action'=>$formAction,
			'enableClientValidation'=>true,
			'enableAjaxValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
				'validateOnChange'=>false,
			),
		)
	);
	?>
	<div class="row">
	<?php
	//	name of group:
	echo $activeForm->labelEx($form, 'name');
	?> 
	<?php echo
		$activeForm->textField($form, 'name', array('style'=>'width:200px;')),
		$activeForm->error($form, 'name');
	?>
	</div>
	<div class="row">
	<?php
	//	 project:
	echo $activeForm->labelEx($form, 'project_id');
	?> 
	<?php
	echo
		$activeForm->dropDownList(
			$form,
			'project_id',
			CHtml::listData($projects, 'project_id', 'name'),
			array(
				'class'=>'chzn-select', 
				'style'=>'width:200px;', 
				'key'=>'project_id'
			)
		),
		$activeForm->error($form, 'project_id');
	?>
	</div>
	<div class="row">
	<?php
	//	 project:
	echo $activeForm->labelEx($form, 'user_ids');
	?> 
	<?php echo
		$activeForm->dropDownList(
			$form,
			'user_ids',
			CHtml::listData($users, 'user_id', 'name'),
			array(
				'class'=>'chzn-select', 
				'style'=>'width:200px;',
				'multiple'=>'multiple',
				'key'=>'user_id',
				'data-placeholder'=>Yii::t('main','Select members'),
			)
		),
		$activeForm->error($form, 'user_ids');
	?>
	</div>
	<?php $this->endWidget(); ?>
</div>