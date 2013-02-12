<?php
$this->breadcrumbs=array(
	'Settings',
);?>
<div class="settings">
<h2 class="listing-title"><?php echo Yii::t('main', 'Status Listing') ?></h2>

<?php if(!empty($statusProvider)): ?>
    <?php $this->renderPartial('_statuses',array(
        'model' => $statusProvider,
    )); ?>
<?php else:?>
    <?php echo Yii::t('main', 'No statuses') ?><br>
<?php endif;?>

<?php echo CHtml::link('New status', '#',
                        array(
                            'id'=>'newStatus',
                            'class'=>'bkButtonBlueSmall normal'
                        )
       )
?>
    <div class="clear"></div>
<?php
Yii::app()->clientScript->registerScript('raiser', '
    $("#newStatus").click(function(){       
        $("#statusDialog").dialog("open");
    });

', CClientScript::POS_READY);
?>

<?php $this->renderPartial('_newStatusDialog', array('statusModel'=>$statusModel)); ?>

<?php
Yii::app()->clientScript->registerScript('status_update', '

jQuery("#status-grid a.update").live("click",function() {

    $.post(
        $(this).attr("href"),
        {YII_CSRF_TOKEN:YII_CSRF_TOKEN},
          function(data){
            jQuery("#statusUpdateForm").html(data);
            jQuery("#statusDialog-edit").dialog("open");
          },
          "html"
    );

	return false;
});
', CClientScript::POS_END);
?>

<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'statusDialog-edit',
        'options'=>array(
            'title'=>'Edit Status',
            'autoOpen'=>false,
            'modal'=>true,
            'hide'=>'drop',
	     	'show'=>'drop',
            'width'=>350,
            'buttons'=>array(
                //'Cancel'=>'js:function(){ $(this).dialog("close");}',
                //'Save'=>'js:savePassword',
            ),
        ),
    ));
?>

<div id="statusUpdateForm"></div>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>