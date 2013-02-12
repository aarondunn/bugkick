<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.07.12
 * Time: 18:29
 */
Yii::app()->clientScript->registerScript('help', '
    $("#show-help").click(function(){
     var height = 440;
        $("#help-window").css("display", "block");
		$("#helpDialog").dialog("open");
                $("#help-window .help-content-wrapper").css({"height" : height});//l
                $("#help-window .help-content-wrapper").jScrollPane({hideFocus: true,animateScroll: true});
                $("#help-window .help-content-wrapper .jspContainer").css("width","600px");
		return false;
    });
    $("#contactUsButton").click(function(){
        $("#contact-us-form").show();
        $("#helpDialog").dialog("open");
        $("#helpDialog").dialog("close");
        $("#contact-us").dialog("open");
            return false;
    });
    ', CClientScript::POS_END
);
//Create ticket dialog
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'helpDialog',
    'options' => array(
        //'title'=>'Help',
        'dialogClass' => 'no-title',
        'draggable' => true,
        'autoOpen' => false,
        'position' => array(
            'at' => 'top',
            'collision' => 'fit',
            'using' => 'js: function(pos) {
                $(this).css({top: pos.top + 70, left: pos.left});
            }'
        ),
        'modal' => true,
        'width' => 600,
        'close' => 'js: function(event, ui) {
             //refreshing search results
              if ($(\'#help-form\').length != 0) {
                  $(\'#help-form\')[0].reset();
                  $(\'#help-search\').keyup();
              }
              
        }',
    ),
));
?>
<div id="help-window" style="display: none">
    <?php $this->render('help/_form'); ?>
    <div class="help-content-wrapper">
    <div class="help-content">
        <?php $this->render('help/_articleList', array('dataProvider' => $dataProvider)); ?>
    </div>
    </div>
    <div class="row buttons">
        <!-- <a href="javascript: $('#helpDialog').dialog('close');" class="button light-gray">Close</a> -->
        <a href="#" id="contactUsButton" class="buttonLandingStyle green">Contact Us &raquo;</a>
    </div>
</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'contact-us',
    'options' => array(
        'title' => 'Contact Us',
        'draggable' => true,
        'autoOpen' => false,
        'position' => array(
            'at' => 'top',
            'collision' => 'fit',
            'using' => 'js: function(pos) {
                $(this).css({top: pos.top + 70, left: pos.left});
            }'
        ),
        'modal' => true,
        'width' => 600
    ),
));
?>
<?php
/* $form = $this->beginWidget('CActiveForm', array(
  'id' => 'form-cuntact-us',
  'action' => CHtml::normalizeUrl(array('/site/SendMailCountactUs')),
  'enableAjaxValidation' => false
  )); 
 * 
 */
?>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contact-us-form',
        'action' => Yii::app()->createUrl('site/SendMailCountactUs'),
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'afterValidate' => 'js:function(form, data, hasError){ if(!hasError){
                $("#contact-us").dialog("close");  
                $(".row").removeClass("success");
                $(".row input").val("");
                $(".row textArea").val("");
                }
            return false; }',
            
        ),
        'htmlOptions'=>array('style'=>'display:none;')
            ));
    $model = new ContactUs();
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'comment'); ?>
        <?php echo $form->textArea($model, 'comment'); ?>
        <?php echo $form->error($model, 'comment'); ?>
    </div>
    <div class="row">
        <?php echo CHtml::submitButton('submit',array('class'=>"buttonLandingStyle green","id"=>"contactUsButtonSubmit")); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<!--<div class="row">
    <label for="ContactUs" class="required">Name <span class="required">*</span></label>
    <div class="clear"></div>
    <input name="ContactUs[Name]" id="ContactUsName" style="width:98%;" type="text" maxlength="255">		
    <div class="errorMessage" id="ContactUsNameError" style="display:none">Input Name</div>	
</div>
<div class="row">
    <label for="ContactUs" class="required">Email <span class="required">*</span></label>
    <div class="clear"></div>
    <input name="ContactUs[Email]" id="ContactUsEmail" style="width:98%;" type="text" maxlength="255">		
    <div class="errorMessage" id="ContactUsEmailError" style="display:none">Input Email</div>	
</div>
<div class="row">
    <label for="ContactUs" class="required">Comment <span class="required">*</span></label>
    <div class="clear"></div>
    <textarea name="ContactUs[Comment]" id="ContactUsComment" style="width:98%;" type="text" maxlength="255"></textarea>	
    <div class="errorMessage" id="ContactUsCommentError" style="display:none">Input Comment</div>	
</div>
<div class="row buttons">
<?php //echo CHtml::ajaxSubmitButton('submit','site/SendMailCountactUs',array(),array('class'=>"buttonLandingStyle green","id"=>"contactUsButtonSubmit"));  ?>
</div>-->
<?php //$this->endWidget(); ?>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>