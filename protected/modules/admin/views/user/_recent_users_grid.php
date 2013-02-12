<h4>Most recent users (registered or invited in past <?php echo $daysCount; ?> days):</h4><br />
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->gridSearchRecent(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name'=>'user_id',
            'htmlOptions'=>array('class'=>'w75'),
        ),
        array(
            'name'=>'email',
            'htmlOptions'=>array(
                'class'=>'w125  ',
            ),
        ),
        array(
            'name'=>'name',
            'htmlOptions'=>array(
                'class'=>'w100',
            ),
        ),
        array(
            'name'=>'lname',
            'htmlOptions'=>array(
                'class'=>'w100',
            ),
        ),
         array(
            'name'=>'created_at',
            'filter'=>false,
            'htmlOptions'=>array(
                'class'=>'w100',
            )
        ),
        array(
            'name'=>'bugCount',
            'header'=>'# of assigned tickets',
            'filter'=>false,
            'htmlOptions'=>array(
                'class'=>'w125',
            ),
        ),
        array(
            'name'=>'bugCreatedCount',
            'header'=>'# of created tickets',
            'filter'=>false,
            'htmlOptions'=>array(
                'class'=>'w100',
            ),
        ),
        array(
            'name'=>'invitedUsersCount',
            'header'=>'# of invited users',
            'filter'=>false,
            'htmlOptions'=>array(
                'class'=>'w100',
            ),
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{login}',
            'buttons'=>array
            (
                'login' => array
                (
                    'label'=>'Login as User',
                    'imageUrl'=>Yii::app()->theme->baseUrl .'/images/icons/i_logout.png',
                    'url'=>'Yii::app()->createUrl("admin/user/loginAs", array("id"=>$data->user_id))',
                ),
            ),
        )
    ),
    'htmlOptions'=>array(
        'style'=>'width: 950px;'
    ),
    'id'=>UserController::GRID_RECENT_USERS_ID,
    'ajaxUpdate'=>UserController::GRID_RECENT_USERS_ID,
));