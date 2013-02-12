<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'filter-form',
        'action' => Yii::app()->createUrl('user/saveFilter'),
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
        'htmlOptions' => array('style' => 'display:none')
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
<?php echo $form->textField($model, 'name'); ?>
    <?php echo $form->error($model, 'name'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->