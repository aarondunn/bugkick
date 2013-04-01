<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$formID,
	'enableAjaxValidation'=>$enableAjaxValidation,
    'clientOptions'=>array(
		'validateOnSubmit'=>$validateOnSubmit,
        'validateOnChange'=>false,
	),
    'action'=>$action
)); ?>
<div class="row">
    <?php echo $form->labelEx($labelModel,'name'); ?>
    <?php echo $form->textField($labelModel,'name',array('size'=>20,'maxlength'=>30)); ?>
    <?php echo $form->error($labelModel,'name'); ?>
</div>


<div class="row">
    <?php //echo $form->labelEx($labelModel, 'Label Projects'); ?>
    <?php
    $projectData=$this->getProjectsData();
    /* echo '<pre>';
     print_r($this->getProjectsData());
     echo '</pre>';*/
    /*    echo CHtml::activeDropDownList(
        $labelModel,
        'projects',
        CHtml::listData(Company::getProjects(), 'project_id', 'name'),
            array(
               'id'=>'projects-multiple-'.$formID,
               'multiple'=>'multiple',
               'key'=>'project_id',
               'prompt'=>'&nbsp;',
               'class'=>'chzn-select',
               'style'=>'width: 220px',
            )
        );*/
     //echo '<input type="hidden" name="Label[projects][0]" value="'.$projectData['selected']['project_id'].'"';
     echo CHtml::hiddenField('Label[projects][0]',$projectData['selected']['project_id']);
    ?>
    <?php echo $form->error($labelModel, 'projects'); ?>
</div>

<div  class="row">
    <?php echo $form->labelEx($labelModel,'label_color'); ?>
    <?php
    $defaultColors = Yii::app()->params['labelDefaultColors'];
    $this->widget('ext.colorpicker.SColorPicker', array(
        'id'=>'Label_color_picker',
        'defaultValue'=>$labelModel->isNewRecord? $defaultColors[array_rand($defaultColors)] :  $labelModel->label_color,
        'hidden'=>true, // defaults to false - can be set to hide the textarea with the hex
        'options' => array(), // jQuery plugin options
        'htmlOptions' => array('class'=>'color_picker'), // html attributes
        ));
     ?>
    <?php echo $form->error($labelModel,'label_color'); ?>
</div>

<div class="row buttons" style="height: 18px;">
    <?php //echo CHtml::submitButton('Save Label', array('id'=>'label-form-submit-btn')); ?>
	<a href="#" class="label-form-submit-btn bkButtonBlueSmall normal" onclick="$('#<?php echo $formID; ?>').submit(); return false;">
		<?php echo Yii::t('main', 'Save Label'); ?>
	</a>
	<img alt="In progress" class="imgAjaxLoading" style="vertical-align: middle; display: none;" src="<?php echo Yii::app()->theme->baseUrl ?>/images/ajaxLoading16.png" />
    <?php //echo CHtml::linkButton('Save Label', array('class'=>'label-form-submit-btn bkButtonGraySmall,)); ?>
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