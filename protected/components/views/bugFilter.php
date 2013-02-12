<?php
//Including tree-menu script
Yii::app()->clientScript->registerScriptFile(
    Yii::app()->baseUrl.'/js/jquery-simpleTreeMenu-1.3.0.min.js'
//	 Yii::app()->baseUrl.'/js/jquery-simpleTreeMenu-1.3.0.js'
);
Yii::app()->clientScript->registerScriptFile(
	Yii::app()->baseUrl.'/js/__components/bugFilter/common.min.js'
//	Yii::app()->baseUrl.'/js/__components/bugFilter/common.js'
);
$act = (Yii::app()->getController()->currentView == 'closed')? '/bug/closed' : '/bug';
Yii::app()->clientScript->registerScript(
	'filter',
<<<JS
	var get='$filterText';
	var act='$act';
JS
	,
	CClientScript::POS_END
);
?>

<?php if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->company_id)) $this->widget('BugSearch'); ?>

<?php
     $currentCompanyID =  Company::current();
     $cacheSettings=array(
         'duration'=>(Yii::app()->user->getState('clearCompanyCache') == 1 )? 0 : 3000,
         'varyByExpression'=>"'company_{$currentCompanyID}'",
         'varyByRoute'=>false,
     );
     if($this->beginCache('side_commercial', $cacheSettings)) {
         $company=Company::model()->findByPk($currentCompanyID);
         if(!empty($company) && ( $company->account_type == Company::TYPE_FREE || $company->show_ads == 1 ) ) {
            // echo '<div><img src="'.Yii::app()->baseUrl.'/images/ad2.png" width="205"></div>';
         }
         $this->endCache();
     }

     //remove clearing Company cache flag
     Yii::app()->user->setState('clearCompanyCache', 0);
?>

<?php echo CHtml::form(Yii::app()->createAbsoluteUrl($act), 'post', array('id'=>'filterForm')); ?>


<?php $groupList = Project::getUserGroups() ?>
    <div>
        <ul class="operations tree"  id="groupTree"  style="display: none;">
            <li>
            <span class="menu-title">
                <?php echo Yii::t('main', 'Groups') ?>
            </span>
                <?php
                    echo CHtml::link('',
                        'settings/groups',
                        array(
                            'class'=>'filter-edit-link',
                        )
                    )
                ?>
                <ul id="target-group">
<?php if (!empty($groupList)): ?>
            <?php foreach ($groupList as $group): ?>
                <li class="group" groupID="<?php echo $group->group_id ?>">
                    <?php echo CHtml::link($group->name, '#', array('id' => 'group_' . $group->group_id)) ?>
                    <?php echo CHtml::hiddenField("filterText[group][{$group->group_id}]", '', array('name' => 'group[]')) ?>
                    <?php echo CHtml::hiddenField("filterText[group-negative][{$group->group_id}]", '', array('class'=>'negative')) ?>
                </li>
            <?php endforeach; ?>
<?php else: ?>
         <a href="<?php echo Yii::app()->createUrl('settings/groups'); ?>" class="new-item"><?php echo '<span class="new-item-icon"></span>' . Yii::t('main','New Group'); ?></a>
<?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>


<?php $labelList = Project::getLabels(); ?>
    <div>
        <ul class="operations tree" id="labelTree"  style="display: none;">
            <li>
                <span class="menu-title">
                    <?php echo Yii::t('main', 'Labels') ?>
                </span>
                <?php
                    echo CHtml::link('',
                        'settings/labelListing',
                        array(
                            'class'=>'filter-edit-link',
                        )
                    )
                ?>
                <ul id="target-label">
                    <?php if (!empty($labelList)): ?>
                    <?php   foreach ($labelList as $label): ?>
                    <li class="label"> 
                        <div class="label-container" labelID="<?php echo $label->label_id ?>" title="<?php echo $label->name?>">
                            <?php echo CHtml::link(Helper::truncateString($label->name). '<span class="label-count">('.$label->bugCountForProject.')</span>', '#', array('id' => 'label_' . $label->label_id)) ?>
                            <?php echo CHtml::hiddenField("filterText[label][{$label->label_id}]", '', array('name' => 'label[]')) ?>
                            <?php echo CHtml::hiddenField("filterText[label-negative][{$label->label_id}]", '', array('class'=>'negative')) ?>
                        </div>
                    </li>
                    <?php   endforeach; ?>
                    <?php if ($proAccount == 1) { ?>
                    <li class="label">
                        <div class="label-container" labelID="0" title="<?php echo Yii::t('main','User submitted')?>">
                            <?php echo CHtml::link(Yii::t('main','User submitted'). '<span class="label-count">('.Bug::getAPITasksCount().')</span>', '#', array('id' => 'label_0')) ?>
                            <?php echo CHtml::hiddenField("filterText[label][0]", '', array('name' => 'label[]')) ?>
                            <?php echo CHtml::hiddenField("filterText[label-negative][0]", '', array('class'=>'negative')) ?>
                        </div>
                    </li>
                    <?php } ?>
                    <?php else: ?>
                             <a href="<?php echo Yii::app()->createUrl('settings/labelListing'); ?>" class="new-item"><?php echo '<span class="new-item-icon"></span>' . Yii::t('main','New Label'); ?></a>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>

<?php
 /* Old Labels Code 
                <ul id="target-label">
<?php if (!empty($labelList)): ?>
                    <li>
            <?php foreach ($labelList as $label): ?>
                <div class="label-container" labelID="<?php echo $label->label_id ?>" title="<?php echo $label->name?>">
                    <span class="label-start">&nbsp;</span>
                    <span class="label">
                        <?php echo CHtml::link(Helper::truncateString($label->name). '<span class="label-count">'.$label->bugCountForProject.'</span>', '#', array('id' => 'label_' . $label->label_id)) ?>
                        <?php echo CHtml::hiddenField("filterText[label][{$label->label_id}]", '', array('name' => 'label[]')) ?>
                        <?php echo CHtml::hiddenField("filterText[label-negative][{$label->label_id}]", '', array('class'=>'negative')) ?>
                    </span>
                    <span class="label-end">&nbsp;</span>
                </div>
            <?php endforeach; ?>
            <?php if ($proAccount == 1) { ?>
                <div class="label-container" labelID="0" title="<?php echo Yii::t('main','User submitted')?>">
                    <span class="label-start">&nbsp;</span>
                    <span class="label">
                        <?php echo CHtml::link(Yii::t('main','User submitted'). '<span class="label-count">'.Bug::getAPITasksCount().'</span>', '#', array('id' => 'label_0')) ?>
                        <?php echo CHtml::hiddenField("filterText[label][0]", '', array('name' => 'label[]')) ?>
                        <?php echo CHtml::hiddenField("filterText[label-negative][0]", '', array('class'=>'negative')) ?>
                    </span>
                    <span class="label-end">&nbsp;</span>
                </div>
            <?php } ?>
                    </li>
<?php else: ?>
         <a href="<?php echo Yii::app()->createUrl('settings/labelListing'); ?>" class="new-item"><?php echo '<span class="new-item-icon"></span>' . Yii::t('main','New Label'); ?></a>
<?php endif; ?>
                  </ul>
                </li>
            </ul>

*/
?>
        <div class="clear"></div>
    </div>


<?php $statusList = Company::getStatuses(); ?>
    <div>
        <ul class="operations tree" id="statusTree" style="display: none;">
            <li>
            <span class="menu-title">
                <?php echo Yii::t('main', 'Status') ?>
            </span>
                <?php
                    echo CHtml::link('',
                        'settings/statusListing',
                        array(
                            'class'=>'filter-edit-link'
                        )
                    )
                ?>
                <ul id="target-status">
<?php if (!empty($statusList)): ?>
            <?php foreach ($statusList as $status): ?>
                <li class="status" statusID="<?php echo $status->status_id ?>">
                    <?php echo CHtml::link($status->label, '#', array('id' => 'status_' . $status->status_id)) ?>
                    <?php echo CHtml::hiddenField("filterText[status][{$status->status_id}]", '') ?>
                    <?php echo CHtml::hiddenField("filterText[status-negative][{$status->status_id}]", '', array('class'=>'negative')) ?>
                </li>
            <?php endforeach; ?>
<?php else: ?>
         <a href="<?php echo Yii::app()->createUrl('settings/statusListing'); ?>" class="new-item"><?php echo '<span class="new-item-icon"></span>' . Yii::t('main','New Status'); ?></a>
<?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>

<?php $userList = Project::getUsers() ?>
    <div>
        <ul class="operations tree"  id="userTree"  style="display: none;">
            <li>
            <span class="menu-title">
                <?php echo Yii::t('main', 'People') ?>
            </span>
                <?php
                    echo CHtml::link('',
                        'settings/members',
                        array(
                            'class'=>'filter-edit-link',
                        )
                    )
                ?>
                <ul id="target-user">
<?php if (!empty($userList)): ?>
            <?php foreach ($userList as $user): ?>
                <li class="user" userID="<?php echo $user->user_id ?>">
                    <?php echo CHtml::link($user->name, '#', array('id' => 'user_' . $user->user_id)) ?>
                    <?php echo CHtml::hiddenField("filterText[user][{$user->user_id}]", '', array('name' => 'user[]')) ?>
                    <?php echo CHtml::hiddenField("filterText[user-negative][{$user->user_id}]", '', array('class'=>'negative')) ?>
                </li>
            <?php endforeach; ?>
                <li class="user" userID="0">
                    <?php echo CHtml::link(Yii::t('main','Unassigned'), '#', array('id' => 'user_0')) ?>
                    <?php echo CHtml::hiddenField("filterText[user][0]", '', array('name' => 'user[]')) ?>
                    <?php echo CHtml::hiddenField("filterText[user-negative][0]", '', array('class'=>'negative')) ?>
                </li>
<?php else: ?>
         <a href="<?php echo Yii::app()->createUrl('settings/members'); ?>" class="new-item"><?php echo '<span class="new-item-icon"></span>' . Yii::t('main','Invite User'); ?></a>
<?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>

<?php $filterList = Filter::getSavedFilters() ?>
    <div>
        <ul class="operations tree"  id="filterTree"  style="display: none;">
            <li>
            <span class="menu-title"><?php echo Yii::t('main', 'Saved Filters') ?></span>
                <ul id="target-filter">
<?php if (!empty($filterList)): ?>
            <?php foreach ($filterList as $filter): ?>
                <li class="filter" filterID="<?php echo $filter->filter_id ?>">
                    <?php echo CHtml::link($filter->name . '<span class="delete-filter" title="Delete" id="'. $filter->filter_id .'"></span>', '#', array('id' => 'filter_' . $filter->filter_id, 'class'=>'saved-filters-link')) ?>

                    <?php echo CHtml::hiddenField("filterText[filter][{$filter->filter_id}]", '', array('name' => 'filter[]', 'class'=>'saved-filters-input')) ?>
                    <?php echo CHtml::hiddenField("filterText[filter-negative][{$filter->filter_id}]", '', array('name' => 'filter-negative[]', 'class'=>'saved-filters-input-negative')) ?>
                </li>
            <?php endforeach; ?>
<?php endif; ?>
                    <a href="#" id="saveFilter"><?php echo '<span class="save-search-icon"></span>' . Yii::t('main','Save filter state'); ?></a>
                </ul>
            </li>
        </ul>
    </div>

<?php echo CHtml::endForm(); ?>


<?php
Yii::app()->clientScript->registerScript(
	'saveFilter',
	'$("#saveFilter").click(function(){
		$("#filter-form").css("display", "block");
		$("#saveFilterDialog").dialog("open");
			return false;
	});',
	CClientScript::POS_END
);
if($this->beginCache('filter_static_html', array('duration'=>3600))) {
	//Create ticket dialog
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		'id'=>'saveFilterDialog',
		'options'=>array(
			'title'=>'Save Filter',
			'autoOpen'=>false,
			'modal'=>true,
			'hide'=>'drop',
			'show'=>'drop',
			'buttons'=>array(
				'Save'=>'js:function(){ $("#filter-form").submit(); }',
			)
		),
	));
	$this->render('bugFilter/_form', array('model' => new Filter()));
	$this->endWidget('zii.widgets.jui.CJuiDialog');
	$this->endCache();
}
?>