<div class="userGroupItem round3" style="background-color:<?php echo empty($data->color) ? '#fff' : $data->color; ?>;">
	<span class="group_id invis"><?php echo $data->group_id; ?></span>
	<div class="delete" title="<?php echo Yii::t('main', 'Delete group \'{groupName}\'', array('{groupName}'=>$data->name)); ?>"></div>
	<a class="update" href="<?php echo $this->createUrl('group/edit', array('group_id'=>$data->group_id)); ?>">
        <div class="edit" title="<?php echo Yii::t('main', 'Edit group \'{groupName}\'', array('{groupName}'=>$data->name)); ?>">
            <img alt="" src="<?php echo Yii::app()->theme->baseUrl.'/images/icons/edit_icon.png'; ?>" />
        </div>
    </a>
<!--	<div class="project">--><?php //echo empty($data->project) ? Yii::t('main', 'All projects') : $data->project->name; ?><!--</div>-->
	<div class="clear"></div>
	<div class="nameContainer">
		<div class="name round3">
			<span class="groupName"><?php echo $data->name; ?></span>&nbsp;(<span id="usersCount_<?php echo $data->group_id; ?>"><?php echo $data->usersCount; ?></span>)
		</div>
	</div>
</div>