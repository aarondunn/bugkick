<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 18.04.12
 * Time: 0:03
 */
$columns = array(
    array(
        'name' => 'user',
        'type' => 'html',
        'htmlOptions' => array('style'=>'width:40px'),
        'value' => 'CHtml::link(CHtml::image($data->getImageSrc(31,31), "image",
                        array("class"=>"bug-profile-pic")),
                            array(\'user/view\', \'id\'=>$data->user_id),
                            array(\'title\'=>$data->name .\' \'.$data->lname)
                        );',
    ),
    array(
        'name' => 'created_at',
        'htmlOptions' => array('style'=>'width:150px'),
        'value' => ' "Date joined: " . Helper::formatDateSlash($data->created_at)',
    ),
    array(
        'name' => 'name',
        'value' => '$data->name . " " . $data->lname',
    ),
    'email',
);

if(User::current()->isCompanyAdmin(Company::current())) {
    $columns[] = array(
        'htmlOptions' => array('style'=>'width:150px'),
        'type'=>'raw',
        'name'=>'action',
        'value'=>'CHtml::link("Reinvite", Yii::app()->createUrl("user/reinvite", array("id"=>$data->user_id)) ) .
            " | " . CHtml::link("Revoke Invite", Yii::app()->createUrl("user/rejectInvite", array("id"=>$data->user_id)) )'
    );
}

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'pending-user-grid',
    'dataProvider' => $model,
    //'filter'=>$model,
    'columns' => $columns,
    'summaryText'=>'',
    'hideHeader'=>true,
));

