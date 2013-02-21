<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 17.04.12
 * Time: 23:47
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
        'name' => 'name',
        'value' => '$data->name . " " . $data->lname',
    ),
    'email',
    array(
        'name' => 'created_at',
        'htmlOptions' => array('style'=>'width:150px'),
        'value' => ' "Date joined: " . Helper::formatDateSlash($data->created_at)',
    ),
);

if(User::current()->isCompanyAdmin(Company::current())) {
    $columns[] = array(
        'class' => 'CButtonColumn',
        'template' => '{restore}',
        'htmlOptions'=>array('style'=>'width:60px;text-align:right;'),
        'buttons'=>array(
            'restore' => array(
                'label'=>'Restore User',
                'imageUrl'=>Yii::app()->theme->baseUrl .'/images/icons/unarchive-icon.png',
                'url'=>'Yii::app()->createUrl("/user/restore", array("id"=>$data->user_id))',
            ),
        ),
    );
}

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'deleted-user-grid',
    'dataProvider' => $model,
    //'filter'=>$model,
    'columns' => $columns,
    'summaryText'=>'',
    'hideHeader'=>true,
));
?>