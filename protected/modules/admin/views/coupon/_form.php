<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'coupon-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>255, 'style'=>'width:180px')); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'period'); ?>
        <?php
            echo CHtml::activeDropDownList($model,
                'period',
                Coupon::getPeriods(),
                array(
                    'class' => 'chzn-select',
                    'style' => 'width:180px'
                )
            );
        ?>
   		<?php echo $form->error($model,'period'); ?>
   	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'enabled'); ?>
        <?php
            echo CHtml::activeDropDownList($model,
                'enabled',
                SiteSettings::itemAlias('onOff'),
                array(
                    'class' => 'chzn-select',
                    'style' => 'width:180px'
                )
            );
        ?>
   		<?php echo $form->error($model,'invites_limit'); ?>
   	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',
            array('class'=>'bkButtonBlueSmall normal', 'style'=>'float:right; width:auto')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->