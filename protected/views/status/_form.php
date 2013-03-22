<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'status-form',
    'action'=> Yii::app()->createUrl('status/update', array('id'=>$model->status_id)),
	'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
    ),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

<?php /*?>
	<div class="row">
        <div style="clear: both;">
		<?php echo $form->labelEx($model,'is_visible_by_default'); ?>
		<?php echo $form->checkBox($model,'is_visible_by_default',array('class'=>'iPhone-checkbox')); ?>
		<?php echo $form->error($model,'is_visible_by_default'); ?>
        </div>
	</div>
<?php */ ?>

<?php /* <!--	<div class="row">
		<?php echo $form->labelEx($model,'company_id'); ?>
		<?php echo $form->textField($model,'company_id'); ?>
		<?php echo $form->error($model,'company_id'); ?>
	</div>
--> */?>
    
    <div class="row" style="clear:both;">
        <?php echo $form->labelEx($model,'status_color'); ?>
        <?php
            $this->widget('ext.colorpicker.SActiveColorPicker', array(
            'model' => $model,
            'attribute' => 'status_color',
            'hidden'=>true, // defaults to false - can be set to hide the textarea with the hex
            'options' => array(), // jQuery plugin options
            'htmlOptions' => array(), // html attributes
        ));
         ?>
        <?php echo $form->error($model,'status_color'); ?>
    </div>
    <div class="clear"></div>
	<div class="row buttons">
        <?php
            echo CHtml::ajaxLink('Delete', array('/status/delete', 'id'=>$model->status_id), array('success'=>'js: function(){window.location.reload(true)}'),
                array('class'=>'bkButtonGraySmall small','confirm'=>'Delete status?',));
        ?>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array (
                    'class'=>'bkButtonBlueSmall normal',
                    'style'=>'float:right; box-shadow:none;')
                );
        ?>
        <div class="clear"></div>
	</div>
    <div class="clear"></div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
    $(function() {
        $('#status-form .iPhone-checkbox').iphoneStyle({
            resizeContainer: false,
            resizeHandle: false,
            checkedLabel: 'YES',
            uncheckedLabel: 'NO'
        });
    });
</script>