<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 05.08.12
 * Time: 1:17
 */

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-settings-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'action' => $this->createUrl('settings/userSettings')
));
?>
<ul>
    <li class="default_assignee">
        <span class="label"><?php echo $form->labelEx($userSettings, 'defaultAssignee'); ?>:</span>
        <span class="selectbox">
            <?php echo CHtml::activeDropDownList(
                $userSettings,
                'defaultAssignee',
                CHtml::listData(Company::getUsers(), 'user_id', 'name'),
                array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
            ) ?>
            <?php echo $form->error($userSettings, 'defaultAssignee'); ?>
        </span>
    </li>
    <!--				<li class="default_company">
        <span class="label"><?php /*echo $form->labelEx($userSettings, 'defaultCompany'); */?>:</span>
        <span class="selectbox">
            <?php /*echo CHtml::activeDropDownList(
                                $userSettings,
                                'defaultCompany',
                                CHtml::listData($userModel->company, 'company_id', 'company_name'),
                                array('prompt'=>'Please select company', 'class'=>'chzn-select selectbox',)
                            ) */?>
            <?php /*echo $form->error($userModel, 'defaultCompany'); */?>
        </span>
    </li>-->
    <li class="default_status">
        <span class="label"><?php echo $form->labelEx($userSettings, 'defaultStatus'); ?>:</span>
        <span class="selectbox">
            <?php echo CHtml::activeDropDownList(
                $userSettings,
                'defaultStatus',
                CHtml::listData(Company::getStatuses(), 'status_id', 'label'),
                array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
            ) ?>
            <?php echo $form->error($userSettings, 'defaultStatus'); ?>
        </span>
    </li>
    <li class="default_label">
        <span class="label"><?php echo $form->labelEx($userSettings, 'defaultLabel'); ?>:</span>
        <span class="selectbox">
            <?php echo CHtml::activeDropDownList(
                $userSettings,
                'defaultLabel',
                CHtml::listData(Company::getLabels(), 'label_id', 'name'),
                array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
            ) ?>
            <?php echo $form->error($userSettings, 'defaultLabel'); ?>
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