<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->user_id), array('view', 'id'=>$data->user_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lname')); ?>:</b>
	<?php echo CHtml::encode($data->lname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_notify')); ?>:</b>
	<?php echo CHtml::encode($data->email_notify); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('isadmin')); ?>:</b>
	<?php echo CHtml::encode($data->isadmin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profile_img')); ?>:</b>
	<?php echo CHtml::encode($data->profile_img); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_preference')); ?>:</b>
	<?php echo CHtml::encode($data->email_preference); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('randomPassword')); ?>:</b>
	<?php echo CHtml::encode($data->randomPassword); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userStatus')); ?>:</b>
	<?php echo CHtml::encode($data->userStatus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('defaultAssignee')); ?>:</b>
	<?php echo CHtml::encode($data->defaultAssignee); ?>
	<br />

	*/ ?>

</div>