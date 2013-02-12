<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('label_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->label_id), array('view', 'id'=>$data->label_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_id')); ?>:</b>
	<?php echo CHtml::encode($data->company_id); ?>
	<br />


</div>