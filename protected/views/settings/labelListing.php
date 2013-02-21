<?php
$this->breadcrumbs=array(
	'Labels',
);?>
<div class="settings">
<h2 class="listing-title"><?php echo Yii::t('main', 'Labels'); ?></h2>

<?php if(!empty($labelProvider)): ?>
    <?php $this->renderPartial('_labels',array(
        'model' => $labelProvider,
        'labelModel' => $labelModel,
    )); ?>
<?php else: ?>
    <?php echo Yii::t('main', 'No labels'); ?><br>
<?php endif ?>

<?php echo CHtml::link('New label', '#',
                        array(
                            'id'=>'newlabel',
                            'class'=>'bkButtonBlueSmall normal'
                        )
       )
?>
    <div class="clear"></div>
<?php
Yii::app()->clientScript->registerScript('raiser', '
    $("#newlabel").click(function(){       
        $("#labelDialog").dialog("open");
    });
', CClientScript::POS_READY);
?>

<?php
    $labelModel->name = '';
    $this->renderPartial('_newLabelDialog', array('labelModel'=>$labelModel));
?>

<?php
Yii::app()->clientScript->registerScript('label_update', '
jQuery("#label-grid a.update").live("click",function() {

    $.post(
        $(this).attr("href"),
        {YII_CSRF_TOKEN:YII_CSRF_TOKEN},
          function(data){
            jQuery("#labelUpdateForm").html(data);
            jQuery("#labelDialog-edit").dialog("open");
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
        'id'=>'labelDialog-edit',
        'options'=>array(
            'title'=>'Edit Label',
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
<div id="labelUpdateForm"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
 </div>
