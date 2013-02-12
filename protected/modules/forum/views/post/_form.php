<?php
/* @var $this PostController */
/* @var $model BKPost */
/* @var $form CActiveForm */
/* @var $topic BKTopic */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bkpost-form',
	'enableAjaxValidation'=>false,
    'action'=>$model->isNewRecord
        ? '/forum/post/create/topicID/' . $topic->id
        : '/forum/post/update/id/' . $model->id
)); ?>

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
        <?php echo $form->textArea($model,'body',array('id'=>'Comment_message')); ?>
        <?php /*$this->widget('forum.extensions.redactorjs.Redactor',
            array( 'model' => $model, 'attribute' => 'body' ,
                'htmlOptions'=>array('style'=>'height:100px; font-size:10px; font-weight: normal'),
                'editorOptions' => array('autoresize' => true, 'fixed' => true),
            ));*/?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'topic_id', array('value'=>$topic->id)); ?>
		<?php //echo $form->textField($model,'topic_id'); ?>
		<?php echo $form->error($model,'topic_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Post Comment' : 'Save',array('class'=>'bkButtonBlueSmall normal')); ?>
        <?php /*echo CHtml::button('Cancel', array('class'=>'btn','onclick'=>'history.go(-1)'));*/ ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
 /*
  * Usernames auto-complete
  */
 Yii::app()->clientScript->registerCssFile( Yii::app()->baseUrl.'/js/plug-in/at-username/jquery.at-username.css' );?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/plug-in/at-username/jquery.at-username.js' );?>
<?php Yii::app()->clientScript->registerScript('atUsername', '
    $("#Comment_message").atUsername({
        xhrUsernames: "'.$this->createUrl('/company/getUsersList').'"
    });
', CClientScript::POS_READY);
?>

<?php
/*
 * Resizable comment area
 */
Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/plug-in/autoresize/jquery.autoresize.min.js' );
Yii::app()->clientScript->registerScript('autoresize', '
    $("#Comment_message").autoResize({});
', CClientScript::POS_READY);
?>