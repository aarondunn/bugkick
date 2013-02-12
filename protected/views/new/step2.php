<div class="steps step<?php echo $this->currentStep; ?>">
    <div class="step-item">
        <?php if($this->currentStep==1 && Company::canAddNewProject()): ?>
            <a id="createProjectBtn" class="bkButtonBlueSmall normal"
               href="<?php echo $this->createUrl('project/create'); ?>">
                <?php echo Yii::t('main', 'New Project'); ?>
            </a>
        <?php else: ?>
            <a class="disabled-button bkButtonGraySmall normal" href="#" onclick="return false;">
                <?php echo Yii::t('main', 'New Project'); ?>
            </a>
        <?php endif;?>
    </div>
    <div class="step-item">
        <?php if($this->currentStep==2): ?>
            <?php
                $currentProject = Project::getCurrent();
                if (!empty($currentProject))
                    $this->widget('InviteMember');
            ?>
        <?php else: ?>
            <a class="disabled-button bkButtonGraySmall normal" href="#" onclick="return false;">
                <?php echo Yii::t('main', 'Invite new member'); ?>
            </a>
        <?php endif;?>
    </div>
    <div class="step-item">
        <?php if($this->currentStep==3): ?>
            <a href="#" class="bkButtonBlueSmall normal fr"
                onclick="$('#createBug').trigger('click')"><?php echo Yii::t('main', 'New Ticket'); ?>
            </a>
        <?php else: ?>
            <a class="disabled-button bkButtonGraySmall normal" href="#" onclick="return false;">
                <?php echo Yii::t('main', 'New Ticket'); ?>
            </a>
        <?php endif;?>
    </div>
    <?php  if($this->currentStep==3): ?>
    <a href="<?php echo Yii::app()->createUrl('/bug',array('skip_step_3'=>1))?>" class="bkButtonBlueSmall normal fr" style="margin-left: 5px !important">Skip</a>
    <?php else: ?>
    <a href="<?php echo Yii::app()->createUrl('/new/2',array('skipStep'=>1))?>" class="bkButtonBlueSmall normal fr" style="margin-left: 5px !important">Skip</a>
    <?php endif;?>
</div>

<?php
$this->beginWidget(
    'zii.widgets.jui.CJuiDialog',
    array(
        'id'=>'project-form-dialog',
        'options'=>array(
            'title'=>'New Project',
            'autoOpen'=>false,
            //'width'=>350,
            //'height'=>440,
            'modal'=>true,
            'hide'=>'drop',
            'show'=>'drop',
            'buttons'=>array(
                'Save'=>'js:submitProjectForm',
                //'Cancel'=>'js:closeDialog',
            ),
            'open'=>'js: function(event, ui) {
                 $("#project-form").css("display", "block");
            }'
        ),
    )
);
$this->renderPartial(
    '/project/edit',
    array(
        'projectForm'=>$projectForm,
        'project'=>$project,
        'companies'=>$companies,
        'projectSettings'=>$projectSettings,
        'formAction'=>$formAction
    )
);
$this->endWidget();