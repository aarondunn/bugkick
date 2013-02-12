<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.11.12
 * Time: 22:40
 */
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-settings-form',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    ),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'invites_module'); ?>
        <?php
            echo CHtml::activeDropDownList($model,
                'invites_module',
                SiteSettings::itemAlias('onOff'),
                array(
                    'class'=>'chzn-select',
                    'style'=>'width:180px'
                )
            );
        ?>
		<?php echo $form->error($model,'invites_module'); ?>
	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'invites_limit'); ?>
        <?php
            echo CHtml::activeDropDownList($model,
                'invites_limit',
                SiteSettings::itemAlias('onOff'),
                array(
                    'class' => 'chzn-select',
                    'style' => 'width:180px'
                )
            );
        ?>
   		<?php echo $form->error($model,'invites_limit'); ?>
   	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'invites_count'); ?>
        <?php echo $form->textField($model,'invites_count',
            array('size'=>4,'maxlength'=>10, 'style'=>'width:170px')); ?>
   		<?php echo $form->error($model,'invites_count'); ?>
   	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Save',
            array('class'=>'bkButtonBlueSmall normal', 'style'=>'float:right; width:auto')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->