<div id="groupList">
	<div id="users">
	<?php
	$this->widget(
		'zii.widgets.CListView',
		array(
			'template'=>'{items}',
			'id'=>'user-list',
			'dataProvider'=>$usersDataProvider,
			'itemView'=>'_userListItem',
			'enablePagination'=>false,
		)
	);
	?>
<?php //<!--		<div class="usersContainer" style="width:200px;height:200px;border:1px solid black;"></div>--> ?>
	</div>
    <div id="group" class="invis round5">
        <div class="gBack"><?php echo Yii::t('main', 'Back to groups'); ?></div>
        <div class="groupCaption"></div>
        <div class="clear"></div>
        <div class="usersContainer"></div>
    </div>
	<div id="groups" class="round5">
	<?php
        $this->widget(
            'zii.widgets.CListView',
            array(
                'template'=>'{items}',
                'id'=>'group-list',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_groupListItem',
                'emptyText' => '',
                'enablePagination'=>false,
                'afterAjaxUpdate'=>'js:groupsAfterAjaxUpdate',
            )
        );
	?>
	</div>
</div>