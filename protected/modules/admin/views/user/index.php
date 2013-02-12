<?php $this->pageTitle = Yii::t('main', Yii::app()->name. '-Admin: Users'); ?>
<?php
$this->clientScript->registerScriptFile(
    $this->request->baseUrl . '/js/bugkick/modules/admin/user.js');
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo $this->pageTitle; ?></h2>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#users-grid-container">Users</a></li>
        <li><a data-toggle="tab" href="#statistics-container">Statistics</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="users-grid-container">
            <?php
            $this->renderPartial('_users_grid', array(
                'model'=>$model,
            ));
            ?>
        </div>
        <div class="tab-pane" id="statistics-container">
            <?php
            $this->renderPartial('_users_statistics', array(
                'usersStats'=>$usersStats,
                'model'=>$modelRecent,
                'daysCount'=>$daysCount,
            ));
            ?>
        </div>
    </div>
</div>
<?php
$this->widget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'update-user-dialog',
    'options'=>array(
        'title'=>'Edit User',
        'autoOpen'=>false,
        'modal'=>true,
        'hide'=>'drop',
        'show'=>'drop',
        'width'=>500,
        'buttons'=>array(
            'Save'=>'js:function() {
                $.flashMessage().progress();
                $("#update-user-dialog form:first").ajaxSubmit({
                    success: function(data) {
                        if(!data.length) {
                            $.fn.yiiGridView.update("users-grid", {});
                            $.flashMessage().success("User updated");
                            $("#update-user-dialog").dialog("close");
                        }
                    }
                });
            }'
        ),
        'open'=>'js:function(event, ui) {
            //setting tabindex for save button
            //$("button").attr("tabindex","5");
        }',
        'beforeClose'=> 'js:function(event, ui) {
            $("update-user-dialog").html("");
        }'
    ),
));
?>