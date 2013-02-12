<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->company_id), array('view', 'id'=>$data->company_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_name')); ?>:</b>
	<?php echo CHtml::encode($data->company_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_url')); ?>:</b>
	<?php echo CHtml::encode($data->company_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_logo')); ?>:</b>
	<?php echo CHtml::encode($data->company_logo); ?>
	<br />


</div>