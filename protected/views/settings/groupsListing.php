<?php
$this->breadcrumbs=array(
	'Bugs'=>array('bug/'),
	'Settings'=>array('settings'),
	'Groups',
);
?>
<div class="settings">
<h2>Groups</h2>
    <p class="grey-tip"><?php echo Yii::t('main', 'Groups let\'s you organize people into teams,
        allowing you to assign a task to anyone in "marketing" or "engineering"
        so everyone is quickly notified at once. These are company wide, but
        you can always exclude a group member from a specific project by not adding them to it.'); ?></p>
    <?php //$this->renderPartial('_groupGrid', $this->viewData); ?>
    <?php
        $this->clientScript->registerCssFile(
            Yii::app()->theme->baseUrl.'/css/group-list.css'
        );
        $this->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/js/settings/groups/group-list.0.0.5.min.js'
        );
        $this->renderPartial('_groupList', $this->viewData);
    ?>
    <div class="create-group">
        <?php echo CHtml::link('Create Group', $this->createUrl('group/create'),
                    array(
                        'id'=>'createGroupBtn',
                        'class'=>'bkButtonBlueSmall normal'
                    )
               );
        ?>
    </div>
    <div class="clear"></div>
</div>