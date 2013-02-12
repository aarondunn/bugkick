<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.11.12
 * Time: 21:46
 */
?>
<div class="form">
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'people-update-form',
    'action'=> Yii::app()->createUrl('project/managePeople'),
    'enableAjaxValidation' =>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
    ),
));
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?php echo $form->labelEx($model, 'users'); ?>
    <?php
        echo CHtml::activeDropDownList(
            $model,
            'users',
            CHtml::listData(Company::getUsers(), 'user_id', 'name'),
            array(
                'multiple'=>'multiple',
                'key'=>'user_id',
                'class'=>'chzn-select',
            )
        );
    ?>
    <?php echo $form->error($model, 'users'); ?>
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

