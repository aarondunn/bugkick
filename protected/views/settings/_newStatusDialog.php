<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 18.02.12
 * Time: 0:52
 */
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'statusDialog',
        'options'=>array(
            'title'=>'New Status',
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
<div class="form">
<?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'status-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>false
        ),
        'action'=>CHtml::normalizeUrl(array('status/create')),
//        'htmlOptions'=>array(
//            'style'=>'margin: 10px;',
//        ),
    ));
?>

<div class="row">
    <?php echo $form->labelEx($statusModel,'label'); ?>
    <?php echo $form->textField($statusModel,'label',array('size'=>20,'maxlength'=>30)); ?>
    <?php echo $form->error($statusModel,'label'); ?>
</div>

<?php /*?>
<div class="row">
        <div style="clear: both;">
		<?php echo $form->labelEx($statusModel,'is_visible_by_default'); ?>
		<?php echo $form->checkBox($statusModel,'is_visible_by_default',array('class'=>'iPhone-checkbox')); ?>
		<?php echo $form->error($statusModel,'is_visible_by_default'); ?>
        </div>
</div>
<?php */?>

<br>

<div  class="row">
    <?php echo $form->labelEx($statusModel,'status_color'); ?>
    <?php
    $this->widget('ext.colorpicker.SColorPicker', array(
        'id'=>'Status_color_picker',
        'defaultValue'=>'#DFE2FF',
        'hidden'=>true, // defaults to false - can be set to hide the textarea with the hex
        'options' => array(), // jQuery plugin options
        'htmlOptions' => array(), // html attributes
        ));
     ?>
    <?php echo $form->error($statusModel,'status_color'); ?>
</div>


<div class="row buttons" style="clear:both;">
<?php echo CHtml::submitButton($statusModel->isNewRecord ? 'Create' : 'Save', array (
            'class'=>'bkButtonBlueSmall normal',
            'style'=>'box-shadow:none;')
        );
?>
    <div class="clear"></div>
<?php
    /*
     echo CHtml::ajaxSubmitButton('Save',
            CHtml::normalizeUrl(array('label/create')),
            array(
                'dataType'=>'json',
                'success'=>'js:function(data){
                    //$("#labelDialog").dialog("close");
                    $("#label-grid").html(data);
                    }'
            ),
            array('id'=>'labelButton')
    );
      */
?>

</div>

<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(function() {
        $('#status-form .iPhone-checkbox').iphoneStyle({
            resizeContainer: false,
            resizeHandle: false,
            checkedLabel: 'YES',
            uncheckedLabel: 'NO'
        });
    })
</script>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>