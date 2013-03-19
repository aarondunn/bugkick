<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'task-form',
        'action'=> Yii::app()->createUrl('task/create', array('ticketID'=>$ticketID)),
        'enableAjaxValidation' => true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textField($model, 'description', array('maxlength' => 500)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->