<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'label-form',
    'action'=> Yii::app()->createUrl('label/update', array('id'=>$model->label_id)),
	'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
    ),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model, 'Label Projects'); ?>
        <?php
            echo CHtml::activeDropDownList(
            $model,
            'projects',
            CHtml::listData(Company::getProjects(), 'project_id', 'name'),
                array(
                   'id'=>'projects-multiple-select',
                   'multiple'=>'multiple',
                   'key'=>'project_id',
                   'prompt'=>'&nbsp;',
                   'class'=>'chzn-select',
                )
            );
        ?>
        <?php echo $form->error($model, 'projects'); ?>
    </div>

<!--	<div class="row">
		<?php // echo $form->labelEx($model,'company_id'); ?>
		<?php // echo $form->textField($model,'company_id'); ?>
		<?php // echo $form->error($model,'company_id'); ?>
	</div> -->

    <div class="row">
        <?php echo $form->labelEx($model,'label_color'); ?>
        <?php
            $this->widget('ext.colorpicker.SActiveColorPicker', array(
            'model' => $model,
            'attribute' => 'label_color',
            'hidden'=>true, // defaults to false - can be set to hide the textarea with the hex
            'options' => array(), // jQuery plugin options
            'htmlOptions' => array(), // html attributes
        ));
         ?>
        <?php echo $form->error($model,'label_color'); ?>
    </div>
	<div class="row buttons">
        <?php
            echo CHtml::ajaxLink('Delete', array('/label/delete', 'id'=>$model->label_id), array('success'=>'js: function(){window.location.reload(true)}'),
                array('class'=>'bkButtonGraySmall small','confirm'=>'Delete label?'));
        ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array (
                    'class'=>'bkButtonBlueSmall normal')
                );
        ?>
        <div class="clear"></div>
	</div>
    <div class="clear"></div>
<?php $this->endWidget(); ?>

</div><!-- form -->