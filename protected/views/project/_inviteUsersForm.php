<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.12.12
 * Time: 0:00
 */
?>
<div class="form">
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'invite-form',
    'action'=> Yii::app()->createUrl('project/managePeople'),
    'enableAjaxValidation' =>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
    ),
));
?>
<?php echo $form->errorSummary($model); ?>

<p class="note">Invite a new user by email:</p>

<div class="row">
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'name'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'lname'); ?>
    <?php echo $form->textField($model, 'lname', array('size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'lname'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'email'); ?>
    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'email'); ?>
</div>
<p class="note">Or, add someone from another project to this one:</p>
<div class="row">
    <?php //echo $form->labelEx($model, 'user'); ?>
    <?php
        echo CHtml::activeDropDownList(
            $model,
            'user',
            Project::getInvitePeopleListData(),
            array(
                'prompt'=>'Choose Existing User',
                'key'=>'user_id',
                'class'=>'chzn-select',
            )
        );
    ?>
    <?php echo $form->error($model, 'user'); ?>
</div>

<?php
    /*

    if(User::current()->isCompanyAdmin(Company::current())):
?>
    <div class="row">
        <?php $model->isadmin = (boolean)$model->isadmin; ?>
        <?php echo $form->labelEx($model,'isadmin');?>
        <?php echo CHtml::checkBox(
                get_class($model).'[isadmin]',
                (bool)$model->isadmin,
                array(
            'onclick'=>'this.value=this.checked?1:0;',
            'value'=>(int)$model->isadmin,
        )) ?>
        <?php echo $form->error($model,'isadmin'); ?>
    </div>
<?php
    endif;

    */
?>

<?php $this->endWidget(); ?>
</div>

<?php
    Yii::app()->getClientScript()->registerScript(
        'clearOnChange',
        '$(\'#InviteForm_email\').on("click", function(){
            $(\'#InviteForm_user\').val("");
            $(\'#InviteForm_user\').trigger("liszt:updated");
        });
        $(\'#InviteForm_user_chzn\').live("click", function(){
            $(\'#InviteForm_email\').val("");
        })',
        ClientScript::POS_READY
    );
?>