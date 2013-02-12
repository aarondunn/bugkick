<?php
Yii::app()->clientScript->registerScript('createBug', '
    $("#createBug").click(function(){
        $("#bug-form").css("display", "block");
        $(".popup-content").css("display", "block");
		$("#createBugDialog").dialog("open");
		return false;
    });',
	CClientScript::POS_END
);
$dlgButtons = empty($project_id)
	? array('OK'=>'js:function(){$(this).dialog("close");}')
	: array(
			'Save'=>'js:function(){
		          	$(\'#bug-form\').trigger(\'submit\');
			}'
	);
//Create ticket dialog
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'createBugDialog',
	'options'=>array(
		'title'=>'New Ticket',
		'autoOpen'=>false,
		'modal'=>true,
//      'hide'=>'drop',
//		'show'=>'drop',
        'width'=>500,
		'buttons'=>$dlgButtons,
        'open'=>'js: function(event, ui) {
             //refreshing form
             if ($(\'#bug-form\').length != 0 &&
                (typeof store.get("bug_create_newBugDescription") === "undefined")) {
                 $(\'#bug-form\')[0].reset();
                 $(\'#BugForm_assignees, #BugForm_labels\').trigger("liszt:updated");
                 $("#bug-form #createBug_bugTitle").html("");
                 //setting tabindex for save button
                 $("button").attr("tabindex","4");
             }
        }',
        'beforeClose'=> 'js: function(event, ui) {
             //hack for datepicker
             $(\'#BugForm_description\').focus();
        }'
	),
));
if(!empty($project_id))
	$this->render('bugCreate/_form', array('model' => $model));
else
	$this->render('bugCreate/_chooseTheProject');
$this->endWidget('zii.widgets.jui.CJuiDialog');
