<?php
$this->breadcrumbs = array(
    'Settings'=>'settings/',
	'Email Preferences'
);
?>
<div class="settings">
<h2><?php echo Yii::t('main', 'Email Preferences'); ?></h2>
<?php //$this->renderFlash(); ?>
<div class="form" id="email-preferences-form">
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<?php if($emailPrefForm->hasErrors()) { ?>
	<div class="row"><?php echo $form->errorSummary($emailPrefForm); ?></div>
	<?php } ?>
    <div class="settings-email-row">
          <div class="row-left">
               <?php  $cbOptions=array('class'=>'cbEmailPref');
               echo $form->label($emailPrefForm, 'email_notify');?> 	
          </div>
          <div class="row-left">
               <?php echo $form->checkBox($emailPrefForm, 'email_notify'); ?>
               <?php echo $form->error($emailPrefForm, 'email_notify'); ?>
          </div>
    </div>
    <div class="clear"></div>

	<h3><?php echo Yii::t('main', 'Email me for the following '); ?>:</h3>
	<div class="row" id="emailPreferences" <?php if(!$emailPrefForm->email_notify) echo 'style="display:none"'?>>
		<?php
            echo $form->checkBoxList(
                $emailPrefForm,
                'turnedOn',
                $emailPrefForm->preferences,
                $cbOptions
            );
		?>
		<hr />
	</div>
	<div id="flashPlaceHolder" style="display:none;"></div>
	<div class="row buttons" style="height:24px;">
		<img alt="Progress..." class="ajaxLoading"
			src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajaxLoading16.png" style="vertical-align: middle; display: none;"
		/>
		<?php
		echo CHtml::ajaxLink(
			Yii::t('main', 'Save'),
			'?ajaxSubmit=1',
			array(
				'type'=>'POST',
				'beforeSend'=>'js:function() {
					$("#submitBtn").hide().parent("div.row.buttons").find("img.ajaxLoading").show();
				}',
				'success'=>'js:function(data) {
					var placeHolder=jQuery("#flashPlaceHolder").html(data);
					if(!placeHolder.is(":visible"))
						placeHolder.slideDown("slow");
					var submitBtn=$("#submitBtn");
					submitBtn
						.parent("div.row.buttons")
						.find("img.ajaxLoading").hide();
					submitBtn.show();
				}'
			),
			array('class'=>'bkButtonBlueSmall normal', 'id'=>'submitBtn')
		);
//		echo CHtml::ajaxSubmitButton(
//			Yii::t('main', 'Save'),
//			'?ajaxSubmit=1',
//			array(),
//			array('class'=>'bkButtonBlueSmall normal')
//		);
		?>
	</div>
<?php $this->endWidget(); ?>
</div>
</div>