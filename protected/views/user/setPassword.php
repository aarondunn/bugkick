<h2>Please set up the password for your account</h2>
<div class="form">
	<?php
	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'id'=>'set-password-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)
	);
	?>
	<?php foreach($model->attributes as $attr=>$val) { ?>
	<div class="row">
		<?php
            if(strstr($attr,'password')){
                echo
                $form->labelEx($model,$attr),
                $form->passwordField($model,$attr),
                $form->error($model,$attr);
            }
		?>
	</div>
	<?php } ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save', array('class'=>'bkButtonBlueSmall')); ?>
	</div>
<?php $this->endWidget(); ?>
</div>

<?php Yii::app()->getClientScript()->registerCss('546765454653454',"
	#sidebar{ display: none; }
	#content #main {
		float: none;
		margin: 0 auto;
	}
"
); ?>