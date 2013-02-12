<div class="form">

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-update-form',
            'action'=> Yii::app()->createUrl('user/update', array('id'=>$model->user_id)),
            'enableAjaxValidation' =>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>false,
            ),
        ));
?>

<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?php echo $form->labelEx($model, 'projects'); ?>
    <?php
        echo CHtml::activeDropDownList(
            $model,
            'projects',
            CHtml::listData(Company::getProjects(), 'project_id', 'name'),
            array(
                'multiple'=>'multiple',
                'key'=>'project_id',
                'prompt'=>'&nbsp;',
                'class'=>'chzn-select',
            )
        );
    ?>
    <?php echo $form->error($model, 'projects'); ?>
</div>
<div class="row buttons">
    <?php echo CHtml::submitButton('Save', array (
               'class'=>'bkButtonBlueSmall normal',
               'style'=>'box-shadow:none;'
          ));
    ?>
</div>
<div class="clear"></div>
<?php $this->endWidget(); ?>

</div>

