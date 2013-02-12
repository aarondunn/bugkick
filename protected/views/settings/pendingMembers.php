<?php
$this->breadcrumbs=array(
	'Settings',
);?>
<h1><?php echo Yii::t('main', 'Pending Members') ?></h1>


<?php if(!empty($userProvider)): ?>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'user-grid',
        'dataProvider' => $userProvider,
        'columns' => array(
            'name',
            'email',
            array(
                'type'=>'raw',
                'name'=>'action',
                'value'=>'CHtml::link("Reinvite", Yii::app()->createUrl("user/reinvite", array("id"=>$data->user_id)) ) . 
                    " | " . CHtml::link("Revoke Invite", Yii::app()->createUrl("user/rejectInvite", array("id"=>$data->user_id)) )'
            ),
    ),
));
    ?>
<?php else: ?>
    No Users<br>
<?php endif ?>
