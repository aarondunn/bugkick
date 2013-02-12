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
<!--	<div class="row">
	<?php
/*	echo $activeForm->labelEx($form, 'color');
	$this->widget(
		'ext.colorpicker.SActiveColorPicker',
		array(
			'model'=>$form,
			'attribute'=>'color',
			'hidden'=>true, // defaults to false - can be set to hide the textarea with the hex
			'options'=>array(), // jQuery plugin options
			'htmlOptions'=>array('class'=>'miniColorsWidget'), // html attributes
		)
	);
	echo $activeForm->error($form, 'color');
	*/?>
	</div>-->
    <div class="row">
    <?php
    //	 projects:
    echo $activeForm->labelEx($form, 'project_ids');
    ?>
    <?php
        $projects = Company::getProjects();
        echo $activeForm->dropDownList(
            $form,
            'project_ids',
            CHtml::listData($projects, 'project_id', 'name'),
            array(
                'class'=>'chzn-select',
                'style'=>'width:210px;',
                'multiple'=>'multiple',
                'key'=>'project_id',
                'data-placeholder'=>'',
            )
        ),
        $activeForm->error($form, 'project_ids');
    ?>
    </div>
	<?php $this->endWidget(); ?>
</div>