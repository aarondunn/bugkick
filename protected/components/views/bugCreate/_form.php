<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'bug-form',
        'action' => CHtml::normalizeUrl(array('bug/create')),
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'validateOnType' => false,
            'afterValidate' => 'js: function(form, data, hasError) {
                 if (hasError != true){
                    createTicket();
                 }
                }',
        ),
        'htmlOptions' => array('style' => 'display:none')
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-container new-ticket-title" style="display:none;">
        <label>Title:</label>&nbsp;<span id="createBug_bugTitle"></span>
    </div>
    <div class="form-container">
        <div class="row">
            <?php echo $form->textArea($model, 'description',
            array(
                'rows' => 6,
                'cols' => 50,
                'tabindex' => 1,
                'placeholder' => 'Description...',
                'onkeyup' => 'showBugTitle($(this), $("#createBug_bugTitle"));'
            )
        );
            ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>
    <div class="form-container-border">
        <div class="row">
            <div class="assignees-dropdown">
                <?php echo $form->labelEx($model, 'assignees'); ?>
                <?php
                $defaultProjectSettings = Project::getProjectSettings();
//                    $defaultUserSettings = User::getUserSettings();

                if (!empty($defaultProjectSettings->defaultAssignee))
                    $model->assignees[] = $defaultProjectSettings->defaultAssignee;
//                else
//                    $model->assignees[] = Yii::app()->user->id;
                /*                    elseif(!empty($defaultUserSettings->defaultAssignee))
                                        $model->assignees[]=$defaultUserSettings->defaultAssignee;*/

                echo CHtml::activeDropDownList(
                    $model,
                    'assignees',
                    CHtml::listData(Project::getUsers(), 'user_id', 'name'),
                    array(
                        'multiple' => 'multiple',
                        'key' => 'user_id',
                        //'prompt' => '&nbsp;',
                        'class' => 'chzn-select',
                        'style' => 'width: 180px;',
                        'tabindex' => '2'
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
                    'id' => 'duedate',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        //'showAnim'=>'fold',
                        'dateFormat' => 'yy-mm-dd'
                    ),
                    'htmlOptions' => array(
                        'class' => 'calendar-field',
                        'tabindex' => '-1',
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
            if (!empty($defaultProjectSettings->defaultLabel))
                $model->labels[] = $defaultProjectSettings->defaultLabel;
//            else{
//                $bugLabel = Project::getBugLabel();
//                if(!empty($bugLabel))
//                    $model->labels[] = $bugLabel->label_id;
//            }
            /*                elseif(!empty($defaultUserSettings->defaultLabel))
                                $model->labels[]=$defaultUserSettings->defaultLabel;*/

            echo CHtml::activeDropDownList(
                $model,
                'labels',
                CHtml::listData(Project::getLabels(), 'label_id', 'name'),
                array(
                    'multiple' => 'multiple',
                    'key' => 'label_id',
                    //'prompt'=>'&nbsp;',
                    'class' => 'chzn-select label-select',
                    'style' => 'width: 307px',
                    'tabindex' => '3'
                )
            );
            ?>
            <img class="createBug_newLabelBtn" alt="Create New Label" title="Create New Label"
                 src="<?php echo Yii::app()->theme->baseUrl; ?>/images/btn_plus.png"/>
            <?php echo $form->error($model, 'labels'); ?>
        </div>
        <?php
        /*
        <!--    <div class="row">
                <?php echo $form->labelEx($model, 'status_id'); ?>
                <?php
                $model->status_id = User::getDefaultStatus(Yii::app()->user->id);
                echo CHtml::activeDropDownList(
                        $model, 'status_id', CHtml::listData(Company::getStatuses(), 'status_id', 'label')
                );
                ?>
                <?php echo $form->error($model, 'status_id'); ?>
            </div>
           -->
        */
        ?>
    </div>
    <?php $this->endWidget(); ?>
    <div class="form-container-border newLabelContainer" style="display:none;">
        <span class="ajaxUrl" style="display:none;"><?php echo CHtml::normalizeUrl(array('label/createOnFly'));?></span>

        <div class="form-placeholder"></div>
    </div>
</div><!-- form -->