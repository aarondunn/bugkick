<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>$formID,
        'action'=>$this->createUrl(
            '/admin/user/update', array('id'=>$model->user_id)),
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <h4><?php echo $model->name, ' ', $model->lname; ?></h4>
    </div>
    <div class="clear"></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'email'),
            $form->textField($model, 'email'),
            $form->error($model, 'email');
        ?>
    </div>
    <div class="clear"></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'password'),
            $form->passwordField($model, 'password'),
            $form->error($model, 'password');
        ?>
    </div>
    <div class="clear"></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'is_global_admin'),
            $form->checkBox($model, 'is_global_admin'),
            $form->error($model, 'is_global_admin');
        ?>
    </div>
    <div class="clear"></div>
    <?php $this->endWidget(); ?>
</div>