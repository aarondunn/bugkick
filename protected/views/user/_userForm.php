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

<?php
    if(User::current()->isCompanyAdmin(Company::current())):
?>
    <div class="row">
        <?php $model->is_company_admin = (boolean)$model->is_company_admin; ?>
        <?php echo $form->labelEx($model,'is_company_admin');?>
        <?php echo CHtml::checkBox(
                get_class($model).'[is_company_admin]',
                (bool)$model->is_company_admin,
                array(
            'onclick'=>'this.value=this.checked?1:0;',
            'value'=>(int)$model->is_company_admin,
        )) ?>
        <?php echo $form->error($model,'is_company_admin'); ?>
    </div>
<?php
    endif;
?>




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

