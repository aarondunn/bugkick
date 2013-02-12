<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'bug-duplicate-form',
        'action'=> Yii::app()->createUrl('bug/duplicate', array('id'=>$model->id)),
        'enableAjaxValidation' => true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'duplicate_number', array('class'=>'duplicate-number-label')); ?>
        <?php echo $form->textField($model, 'duplicate_number', array('size' => 4, 'maxlength' => 100, 'class'=>'duplicate-number-input')); ?>
        <?php echo $form->error($model, 'duplicate_number'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->