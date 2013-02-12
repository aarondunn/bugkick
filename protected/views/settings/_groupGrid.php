<?php
$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'id'=>'group-grid',
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			array(
				'name'=>'group_id',
				'header'=>'ID',
			),
			'name',
			array(
				'name'=>'companyName',
				'header'=>'Company',
			),
			array(
				'name'=>'projectName',
				'header'=>'Project',
				'value'=>function($data) {
					return empty($data->projectName)
						? Yii::t('main', 'All')
						: $data->projectName;
				},
			),
			'color',
			'image',
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update} {delete}',
				'deleteConfirmation'=>true,
				'deleteButtonUrl'=>function($data) {
					return CHtml::normalizeUrl(
						array('group/delete', 'group_id'=>$data->group_id)
					);
				},
				'updateButtonUrl'=>function($data) {
					return Yii::app()->createAbsoluteUrl(
						'group/edit',
						array('group_id'=>$data->group_id)
					);
				},
			)
		),
	)
);