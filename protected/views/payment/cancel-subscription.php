<div class="form">
<?php if($isSubscriptionCanceled) { ?>
<div class="row">
	<h2><?php echo Yii::t('main', 'Your subscription canceled.'); ?></h2>
	<br /><br />
	<h4>
	<?php
	echo Yii::t(
		'main', 
		'Your company will automatically downgraded after the end of current paid period at {date}.',
		array('{date}'=>date('Y-m-d', $expires_at))
	);
	?>
	</h4>
</div>
<?php } else { ?>
	<h3>
		<?php
		echo Yii::t(
			'main',
			'Click button below to cancel your subscription.{EOL}Your company will automatically downgraded after the end of current paid period.',
			array('{EOL}'=>'<br />')
		);
		?>
	</h3>
	<br /><br />
	<?php echo CHtml::beginForm(); ?>
	<div class="row buttons">
		<?php
		echo CHtml::submitButton(
			Yii::t('main', 'Cancel subscription'),
			array('class'=>'bkButtonBlueSmall normal submit-button'));
		?>
	</div>
	<?php echo CHtml::endForm(); ?>
<?php } ?>
</div>
