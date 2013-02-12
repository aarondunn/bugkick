<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 30.08.12
 * Time: 13:08
 */
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'project-settings-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'action' => $this->createUrl('settings/projectSettings')
    ));
?>
    <ul>
        <li class="default_assignee">
            <span class="label"><?php echo $form->labelEx($defaultProjectSettings, 'defaultAssignee'); ?>:</span>
            <span class="selectbox">
                <?php echo CHtml::activeDropDownList(
                    $defaultProjectSettings,
                    'defaultAssignee',
                    CHtml::listData(Company::getUsers(), 'user_id', 'name'),
                    array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
                ) ?>
                <?php echo $form->error($defaultProjectSettings, 'defaultAssignee'); ?>
            </span>
        </li>
        <li class="default_status">
            <span class="label"><?php echo $form->labelEx($defaultProjectSettings, 'defaultStatus'); ?>:</span>
            <span class="selectbox">
                <?php echo CHtml::activeDropDownList(
                    $defaultProjectSettings,
                    'defaultStatus',
                    CHtml::listData(Company::getStatuses(), 'status_id', 'label'),
                    array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
                ) ?>
                <?php echo $form->error($defaultProjectSettings, 'defaultStatus'); ?>
            </span>
        </li>
        <li class="default_label">
            <span class="label"><?php echo $form->labelEx($defaultProjectSettings, 'defaultLabel'); ?>:</span>
            <span class="selectbox">
                <?php echo CHtml::activeDropDownList(
                    $defaultProjectSettings,
                    'defaultLabel',
                    CHtml::listData(Project::getLabels(), 'label_id', 'name'),
                    array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
                ) ?>
                <?php echo $form->error($defaultProjectSettings, 'defaultLabel'); ?>
            </span>
        </li>
    </ul>
    <?php
        echo CHtml::submitButton('Save', array (
            'class'=>'bkButtonBlueSmall normal fr',
            'style'=>'margin-top:5px')
        );
    ?>
<?php $this->endWidget(); ?>