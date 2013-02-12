<?php
echo
    CHtml::activeHiddenField($this->bugForm, 'title', array(
        'id'=>$this->bugForm->getAttributeId('title'),
    )),
    CHtml::activeHiddenField($this->bugForm, 'description', array(
        'id'=>$this->bugForm->getAttributeId('description'),
    ));
?>
<div id="advancedOptions">
    <div class="fl advancedOption">
        <?php
        /*
        <select class="chzn-select">
            <option>Critical</option>
            <option>High</option>
            <option>Medium</option>
            <option>Low</option>
        </select>
        <label>Priority:</label>
        */
        ?>
        <?php echo CHtml::activeLabelEx($this->bugForm, 'status'); ?>
        <?php
            if(empty($this->bugForm->status_id)){
                if(!empty($defaultProjectSettings->defaultStatus))
                    $this->bugForm->status_id=$defaultProjectSettings->defaultStatus;
                elseif(!empty($defaultUserSettings->defaultStatus))
                    $this->bugForm->status_id=$defaultUserSettings->defaultStatus;
            }
            echo CHtml::activeDropDownList(
                $this->bugForm,
                'status_id',
                CHtml::listData(Company::getStatuses(), 'status_id', 'label'),
                array(
                    'prompt'=>'&nbsp;',
                    'class'=>'chzn-select',
                    'tabindex' => 1,
                    //'style'=>'width: 150px',
                    'id'=>$this->bugForm->getAttributeId('status_id'),
                )
            );
        ?>
    </div>
    <div class="fr advancedOption">
        <?php
        /*
        <select class="chzn-select">
            <option>Closed Beta</option>
            <option>Opened Beta</option>
            <option>Demo</option>
            <option>Alfa</option>
        </select>
        <label>Milestone:</label>
            */
        ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $this->bugForm,
            'attribute' => 'duedate',
            'id' => 'duedate-update_0',
            'options' => array(
                'dateFormat' => 'yy-mm-dd'
            ),
            'htmlOptions' => array(
                'class' => 'calendar-field',
                //'style'=>'margin-left:7px',
                'tabindex' => 2,
                'id'=>$this->bugForm->getAttributeId('duedate'),
            ),
        ));
        ?>
        <?php echo CHtml::activeLabelEx($this->bugForm, 'duedate'); ?>
    </div>
    <div class="advancedOption whole-row">
        <?php
        /*
        <select class="chzn-select">
            <option>Bug</option>
            <option>Feature</option>
            <option>Improvement</option>
            <option>Design</option>
        </select>
        <label>Category:</label>
            */
        ?>
        <?php echo CHtml::activeLabelEx($this->bugForm, 'labels'); ?>
        <?php
            echo CHtml::activeDropDownList(
                $this->bugForm,
                'labels',
                CHtml::listData(Project::getLabels(), 'label_id', 'name'),
                array(
                    'multiple'=>'multiple',
                    'key'=>'label_id',
                    'prompt'=>'&nbsp;',
                    'class'=>'chzn-select',
                    //'style'=>'width: 150px',
                    'tabindex' => 3,
                    'id'=>$this->bugForm->getAttributeId('labels')
                )
            );
        ?>
        
        <?php echo CHtml::error($this->bugForm, 'labels'); ?>
    </div>
    <div class="advancedOption whole-row">
        <?php
        /*
        <select class="chzn-select">
            <option>Boyan</option>
            <option>Evgeniy</option>
            <option>Alexey</option>
            <option>Aaron</option>
        </select>
        <label>Assignee:</label>
            */
        ?>
        <?php echo CHtml::activeLabelEx($this->bugForm, 'assignees'); ?>
        <?php
            echo CHtml::activeDropDownList(
                $this->bugForm,
                'assignees',
                CHtml::listData(Project::getUsers(), 'user_id', 'name'),
                array(
                    'multiple'=>'multiple',
                    'key'=>'label_id',
                    'prompt'=>'&nbsp;',
                    'class'=>'chzn-select',
                    //'style'=>'width: 150px',
                    'tabindex' => 4,
                    'id'=>$this->bugForm->getAttributeId('assignees'),
                )
            );
        ?>
       
        <?php echo CHtml::error($this->bugForm, 'assignees'); ?>
    </div>
    <div class="clear"></div>
</div>
<?php
Yii::app()->clientScript->registerScript('add_landing_widgets', '
    $(document).on("click", "#showAdvancedOptions", function(e) {
        e.preventDefault();
        var btn$ = $(this),
            advancedOptions$ = $("#advancedOptions");
        //console.log($(this).attr("class"));
        //if($(this).attr("class") != "button light-gray open") {
        if(btn$.hasClass("open")) {
            advancedOptions$.slideUp(100, function() {
                btn$.removeClass("open")
                    .find(".arrow-up")
                    .removeClass("arrow-up")
                    .addClass("arrow-down");
            });
        } else {
            advancedOptions$.slideDown(100);
            btn$.addClass("open")
                .find(".arrow-down")
                .removeClass("arrow-down")
                .addClass("arrow-up");
            
        }
    });
', CClientScript::POS_END);
?>