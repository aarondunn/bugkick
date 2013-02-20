<?php
if($project->archived==1){
    Yii::app()->clientScript->registerScript('archived_delete_new_ticket', '
        $(".new_ticket").hide();
    ', CClientScript::POS_READY );
}
$this->setPageTitle(CHtml::encode($project->name) . ' - ' . Yii::t('main', 'Tickets') );
$this->breadcrumbs = array(
    'Bugs' => array('index'),
);
?>
<?php
    Yii::app()->clientScript->registerScript('tips', 'renderTicketUsersAndLabels();addTooltip()', CClientScript::POS_READY );
    //floating ticket filters
    Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/jquery-scrolltofixed-min.js');
    Yii::app()->clientScript->registerScript('float', '
        $(".float-filters:first").scrollToFixed({
            marginTop: 15/*,
            limit:
                $("#footer-wrapper").offset().top -
                $(".float-filters").outerHeight() -
                40*/
        });
    ', CClientScript::POS_READY );
?>
<?php $this->renderFlash(); ?>
<div class="tickets-container">
<?php $this->renderPartial('_bugList', array('model'=>$model, 'pages'=>$pages, 'currentView'=>$currentView, 'textForSearch'=>$textForSearch), false, false); ?>
<?php //$this->renderPartial('_bugPagination', array('pages'=>$pages));?>
</div>

<?php $this->widget('BugRightClick'); ?>

<!--Updating Ticket-->
<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'updateBugDialog',
        'options'=>array(
            'title'=>'Edit Ticket',
            'autoOpen'=>false,
            'modal'=>true,
            'hide'=>'drop',
            'show'=>'drop',
            'width'=>500,
            'buttons'=>array(
    //            'Cancel'=>'js:function(){ $(this).dialog("close");}',
                'Save'=>'js:function(){ $("#bug-update-form").submit();}'
            ),
            'open'=>'js: function(event, ui) {
                 //setting tabindex for save button
                 $("button").attr("tabindex","5");
            }',
            'beforeClose'=> 'js: function(event, ui) {
                 //hack for datapicker
                 $(\'#BugForm_title\').focus();
            }'
        ),
    ));
?>
    <div id="bugUpdateForm"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!-- End Updating Ticket-->
<!-- Duplicate Ticket-->
    <?php
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'=>'duplicateBugDialog',
            'options'=>array(
                'title'=>'Duplicate Ticket',
                'autoOpen'=>false,
                'modal'=>true,
                'hide'=>'drop',
                'show'=>'drop',
                'buttons'=>array(
                    //'Cancel'=>'js:function(){ $(this).dialog("close");}',
                    'Save'=>'js:function(){ $("#bug-duplicate-form").submit();}',
                ),
            ),
        ));
    ?>
        <div id="bugDuplicateForm"></div>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!--End Duplicate Ticket-->

<?php
if(!Yii::app()->request->isAjaxRequest){
    $this->beginWidget(
    	'zii.widgets.jui.CJuiDialog',
    	array(
    		'id'=>'project-form-dialog',
    		'options'=>array(
    			'title'=>'Edit Project',
    			'autoOpen'=>false,
//    			'width'=>565,
    			//'height'=>440,
    			'modal'=>true,
    			'hide'=>'drop',
    			'show'=>'drop',
    			'buttons'=>array(
    				'Save'=>'js:submitProjectForm',
    				//'Cancel'=>'js:closeDialog',
                ),
    		)
    	)
    );
    $this->endWidget();
}
?>