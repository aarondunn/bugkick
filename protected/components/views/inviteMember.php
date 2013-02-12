<?php echo CHtml::link('Invite new member', '#',
                        array(
                            'id'=>'inviteMember',
                            'class'=>'bkButtonBlueSmall normal',
                            'style'=>'float:right',
                        )
       );
?>
    <div class="clear"></div>
<?php
Yii::app()->clientScript->registerScript('inviteMember', '
    $("#inviteMember").click(function(){
        $("#inviteMemberDialog").dialog("open");
        $("#invite-form").css("display", "block");
        $(".admin-tip[title]").colorTip({color:"yellow", timeout:100, delay:750});
        return false;
    });
', CClientScript::POS_END);
?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'inviteMemberDialog',
    'options' => array(
        'title' => 'Invite Member',
        'autoOpen' => false,
        'modal' => true,
        'buttons' => array(
        //'Cancel'=>'js:function(){ $(this).dialog("close");}',
        //'Save'=>'js:savePassword',
        ),
    ),
));
?>

<div class="form" class="invite-widget-form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'invite-form',
                'enableAjaxValidation' => true,
                'action' => CHtml::normalizeUrl(array('user/invite')),
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'validateOnChange' => false
                ),
                'htmlOptions'=>array(
                    'style'=>'display:none',
                )
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
<?php /*?>
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

    <div class="row"><?php echo $form->labelEx($model, 'project'); ?>
        <?php echo CHtml::activeDropDownList(
                    $model,
                    'project',
                    CHtml::listData(Company::getProjects(), 'project_id', 'name'),
                    array(
                       'key'=>'project_id',
                       'class'=>'chzn-select',
                       'style'=>'width: 220px',
                   )
                ) ?>
            <?php echo $form->error($model, 'project'); ?>
    </div>
<?php */?>
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 100)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

<?php /*
    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 100)); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>
*/?>

<?php
    if(User::current()->isCompanyAdmin(Company::current())):
?>
    <div class="row">
        <?php $model->isadmin = (boolean)$model->isadmin; ?>
        <div style="clear: both;">
		<?php echo $form->labelEx($model,'isadmin'); ?>
        <span class="admin-tip" title="A project manager can add or remove other people in a project">?</span>
        <?php echo $form->checkBox($model,'isadmin',array('class'=>'iPhone-checkbox')); ?>
<?php //echo CHtml::checkBox(
//            get_class($model).'[isadmin]',
//            (bool)$model->isadmin,
//            array(
//                'onclick'=>'this.value=this.checked?1:0;',
//                'value'=>(int)$model->isadmin,
//                'class'=>'iPhone-checkbox'
//            ))
//        ?>
		<?php echo $form->error($model,'isadmin'); ?>
        </div>
	</div>
<?php
    endif;
?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Invite', array (
            'class'=>'bkButtonBlueSmall normal',
            'style'=>'float:right; box-shadow:none;')
        ); ?>
    </div>
    <div class="clear"></div>
    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php Yii::app()->clientScript->registerScript('checkboxes',
    '$("#invite-form .iPhone-checkbox").iphoneStyle({
        resizeContainer: false,
        resizeHandle: false,
        checkedLabel: "YES",
        uncheckedLabel: "NO"
    });',
    ClientScript::POS_END
)?>
