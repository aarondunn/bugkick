<?php
$this->breadcrumbs=array(
	'Bugs'=>array('bug/'),
	'Settings'=>array('settings'),
	'Groups',
);
?>
<div class="settings">
<h2>Groups of members</h2>
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