<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.11.12
 * Time: 19:17
 */

$columns = array(
    array(
        'name' => 'user',
        'type' => 'raw',
        'htmlOptions' => array('style'=>'width:40px'),
        'value' => 'CHtml::link(CHtml::image($data->getImageSrc(31,31), "image", array("class"=>"bug-profile-pic")),
            array(\'user/view\', \'id\'=>$data->user_id),
            array(\'title\'=>$data->name .\' \'.$data->lname)
        );',
    ),
    array(
        'name' => 'name',
        'value' => '$data->name . " " . $data->lname',
        'htmlOptions' => array('style'=>'width:220px'),
    ),
    array(
        'name' => 'created_at',
        'value' => ' "Date joined: " . Helper::formatDateSlash($data->created_at)',
        'htmlOptions' => array('style'=>'width:150px'),
    ),
    array(
        'name'=>'email',
        'htmlOptions' => array('style'=>'width:150px'),
    ),
);

if(User::current()->isCompanyAdmin(Company::current())) {
    $columns[] = array(
        'class' => 'CButtonColumn',
        'template' => '{update} &nbsp; {delete}',
        'htmlOptions'=>array('style'=>'width:60px;text-align:right;'),
        'deleteButtonUrl' => 'CHtml::normalizeUrl(array("user/delete", "id"=>$data->user_id))',
        'deleteConfirmation'=>'Are you sure you want to remove this user from the project?',
        'updateButtonUrl' => 'CHtml::normalizeUrl(array("user/getUser", "id"=>$data->user_id))',
        'updateButtonImageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/edit_icon.png',
    );
}
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $dataProvider,
    //'filter'=>$model,
    //'afterAjaxUpdate'=>'function(id,options){$.fn.yiiListView.update("deleted-user-grid", {data: searchKeywords}); $(".deleted-title").css("display", "block")}',
    'columns' => $columns,
    'summaryText'=>'',
    'hideHeader'=>true,
));
?>

<?php
Yii::app()->clientScript->registerScript('people_update', '
jQuery("#managePeopleBtn").live("click",function() {
    $.post(
        $(this).attr("href"),
        {YII_CSRF_TOKEN:YII_CSRF_TOKEN},
          function(data){
            jQuery("#peopleUpdateForm").html(data);
            jQuery("#projectDialog-edit").dialog("open");
            jQuery(".chzn-select").chosen();
          },
          "html"
    );
	return false;
});
', CClientScript::POS_END);
?>
<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'projectDialog-edit',
        'options'=>array(
            'title'=>'Project Users',
            'autoOpen'=>false,
            'modal'=>true,
            'hide'=>'drop',
		    'show'=>'drop',
            'buttons'=>array(
                //'Cancel'=>'js:function(){ $(this).dialog("close");}',
                //'Save'=>'js:savePassword',
            ),
        ),
    ));
?>
<div id="peopleUpdateForm"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


<?php
Yii::app()->clientScript->registerScript('user_update', '
jQuery("#user-grid a.update").live("click",function() {
    $.post(
        $(this).attr("href"),
        {YII_CSRF_TOKEN:YII_CSRF_TOKEN},
          function(data){
            jQuery("#userUpdateForm").html(data);
            jQuery("#userDialog-edit").dialog("open");
            jQuery(".chzn-select").chosen();
          },
          "html"
    );
	return false;
});
', CClientScript::POS_END);
?>
<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'userDialog-edit',
        'options'=>array(
            'title'=>'Edit User',
            'autoOpen'=>false,
            'modal'=>true,
            'hide'=>'drop',
		    'show'=>'drop',
            'buttons'=>array(
                //'Cancel'=>'js:function(){ $(this).dialog("close");}',
                //'Save'=>'js:savePassword',
            ),
        ),
    ));
?>
    <div id="userUpdateForm"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>