<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'bug-update-form',
        //'action'=> Yii::app()->createUrl('bug/update', array('id'=>$model->number)),
        'action' => Yii::app()->createUrl('bug/update', array('id' => $model->id)),
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <!--
    <div class="form-container-border">
        <label>Title:</label>&nbsp;<span id="updateBug_bugTitle"><?php echo $model->title; ?></span>
    </div>
-->
    <div class="form-container">
        <div class="row">
            <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50, 'tabindex' => 1, 'placeholder' => 'Description...', 'onkeyup' => 'showBugTitle($(this), $("#updateBug_bugTitle"));')); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>

    <div class="form-container-border">
        <div class="row">
            <div class="assignees-dropdown">
                <?php echo $form->labelEx($model, 'assignees'); ?>
                <?php
                $defaultProjectSettings = Project::getProjectSettings();
                // $defaultUserSettings = User::getUserSettings();

                if (empty($model->assignees)) {
                    if (!empty($defaultProjectSettings->defaultAssignee))
                        $model->assignees[] = $defaultProjectSettings->defaultAssignee;
                    /*                    elseif(!empty($defaultUserSettings->defaultAssignee))
                                            $model->assignees[]=$defaultUserSettings->defaultAssignee;*/
                }
                echo CHtml::activeDropDownList(
                    $model,
                    'assignees',
                    CHtml::listData(Project::getUsers(), 'user_id', 'name'),
                    array(
                        'multiple' => 'multiple',
                        'key' => 'label_id',
                        //'prompt'=>'&nbsp;',
                        'class' => 'chzn-select',
                        'style' => 'width: 180px',
                        'tabindex' => 2,
                    )
                );
                ?>
            </div>
            <div class="form-date-row">
                <?php echo $form->labelEx($model, 'Due'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'duedate',
                    'id' => 'duedate-update',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd'
                    ),
                    'htmlOptions' => array(
                        'class' => 'calendar-field',
                        'style' => 'margin-left:7px',
                        'tabindex' => -1
                    ),
                ));
                ?>
            </div>
            <?php echo $form->error($model, 'duedate'); ?>
            <?php echo $form->error($model, 'assignees'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'labels'); ?>
            <?php
            if (empty($model->labels)) {
                if (!empty($defaultProjectSettings->defaultLabel))
                    $model->labels[] = $defaultProjectSettings->defaultLabel;
                /*                elseif(!empty($defaultUserSettings->defaultLabel))
                                    $model->labels[]=$defaultUserSettings->defaultLabel;*/
            }
            echo CHtml::activeDropDownList(
                $model,
                'labels',
                CHtml::listData(Project::getLabels(), 'label_id', 'name'),
                array(
                    'multiple' => 'multiple',
                    'key' => 'label_id',
                    //'prompt'=>'&nbsp;',
                    'class' => 'chzn-select label-select',
                    'style' => 'width: 315px',
                    'tabindex' => 3
                )
            );
            ?>
            <img class="createBug_newLabelBtn" alt="Create New Label" title="Create New Label"
                 src="<?php echo Yii::app()->theme->baseUrl; ?>/images/btn_plus.png"/>
            <?php echo $form->error($model, 'labels'); ?>
        </div>
        <div class="row update-ticket-status">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php
            if (empty($model->status_id)) {
                if (!empty($defaultProjectSettings->defaultStatus))
                    $model->status_id = $defaultProjectSettings->defaultStatus;
                /*                elseif(!empty($defaultUserSettings->defaultStatus))
                                    $model->status_id=$defaultUserSettings->defaultStatus;*/
            }
            echo CHtml::activeDropDownList(
                $model,
                'status_id',
                CHtml::listData(Company::getStatuses(), 'status_id', 'label'),
                array(
                    //'prompt'=>'&nbsp;',
                    'class' => 'chzn-select',
                    'tabindex' => 4,
                    'style' => 'width: 160px'
                )
            );
            ?>
            <?php echo $form->error($model, 'status_id'); ?>
        </div>
        <!--
    <div class="row buttons">
<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
<?php //echo CHtml::submitButton(Yii::t('main','Save')); ?>
    </div>
-->
    </div>
    <?php $this->endWidget(); ?>
    <div class="form-container-border newLabelContainer" style="display:none;">
        <span class="ajaxUrl" style="display:none;"><?php echo CHtml::normalizeUrl(array('label/createOnFly'));?></span>
        <div class="form-placeholder"></div>
    </div>
</div><!-- form -->

