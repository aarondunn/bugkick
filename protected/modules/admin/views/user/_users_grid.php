<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->gridSearch(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name'=>'user_id',
            'htmlOptions'=>array('class'=>'w75'),
        ),
        array(
            'name'=>'email',
        ),
        array(
            'name'=>'isadmin',
            'header'=>'Is Admin',
            'filter'=>CHtml::activeDropDownList($model, 'isadmin', array(
                ''=>'---',
                '1'=>'Yes',
                '0'=>'No',
            )),
            'type'=>'html',
            'value'=>function($row) {
                return $row->isadmin
                    ? CHtml::tag('img', array(
                        'src'=>Yii::app()->theme->baseUrl . '/images/icons/yes.png',
                    ))
                    : null;
            },
            'htmlOptions'=>array('class'=>'w75 al-center'),
        ),
        array(
            'name'=>'is_global_admin',
            'header'=>'Is Global Admin',
            'filter'=>CHtml::activeDropDownList($model, 'is_global_admin', array(
                ''=>'---',
                '1'=>'Yes',
                '0'=>'No',
            )),
            'type'=>'html',
            'value'=>function($row) {
                return $row->is_global_admin
                    ? CHtml::tag('img', array(
                        'src'=>Yii::app()->theme->baseUrl . '/images/icons/yes.png',
                    ))
                    : null;
            },
            'htmlOptions'=>array('class'=>'w100 al-center'),
        ),
        array(
            'name'=>'name',
        ),
        array(
            'name'=>'lname',
        ),
        array(
            'name'=>'userStatus',
            'header'=>'Status',
            'filter'=>CHtml::activeDropDownList($model, 'userStatus', array(
                ''=>'---',
                User::STATUS_ACTIVE=>'Active',
                User::STATUS_INVITED=>'Invited',
                User::STATUS_REJECTED=>'Rejected',
                User::STATUS_DELETED=>'Deleted',
            ), array('class'=>'w100')),
            'value'=>function($row) {
                switch($row->userStatus) {
                    case User::STATUS_ACTIVE:
                        return 'Active';
                    case User::STATUS_INVITED:
                        return 'Invited';
                    case User::STATUS_REJECTED:
                        return 'Rejected';
                    case User::STATUS_DELETED:
                        return 'Deleted';
                    default:
                        return 'Unknown';

                }
            },
            'htmlOptions'=>array('class'=>'w100'),
        ),
        array(
            'class'=>'CButtonColumn',
            'htmlOptions'=>array('style'=>'width:80px'),
            'template'=>'{update} {login} {upgrade} {downgrade} {delete}',
            'updateButtonImageUrl'=>
                Yii::app()->theme->baseUrl . '/images/icons/admin_edit.png',
            'buttons'=>array(
                'login' => array(
                    'label'=>'Login as User',
                    'imageUrl'=>Yii::app()->theme->baseUrl .'/images/icons/i_logout.png',
                    'url'=>'Yii::app()->createUrl("admin/user/loginAs", array("id"=>$data->user_id))',
                ),
                'upgrade'=>array(
                    'label'=>'Upgrade user\'s companies to Pro plan(Gift Upgrade)',
                    'imageUrl'=>Yii::app()->theme->baseUrl .'/images/icons/i_logout2.png',
                    'url'=>'Yii::app()->createUrl("admin/user/upgrade", array("id"=>$data->user_id))',
                    'visible'=> '$data->pro_status == 0',
                ),
                'downgrade'=>array(
                    'label'=>'Downgrade user\'s companies to Free plan',
                    'imageUrl'=>Yii::app()->theme->baseUrl .'/images/icons/i_blog.png',
                    'url'=>'Yii::app()->createUrl("admin/user/upgrade", array("id"=>$data->user_id))',
                    'visible'=> '$data->pro_status == 1',
                ),
            ),
        )
    ),
    'htmlOptions'=>array(
        'style'=>'width: 950px;'
    ),
    'id'=>UserController::GRID_USERS_ID,
    'ajaxUpdate'=>UserController::GRID_USERS_ID,
));