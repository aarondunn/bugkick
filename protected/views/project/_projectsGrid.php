<?php
$this->widget('zii.widgets.grid.CGridView',
	array(
		'id'=>'projects-grid',
		'dataProvider'=>$model->gridSearch(),
		'filter'=>$model,
		'ajaxUpdate'=>true,
		'enableSorting'=>true,
		'enablePagination'=>true,
		'template'=>'{pager}<br />{items}<br />{pager}',
		'columns'=>array(
			array(
				'header'=>Yii::t('main','Manage'),
				'type'=>'raw',
				'value'=>function($data) {
					$url=Yii::app()->createUrl(
						'//project/manage',
						array('project_id'=>$data->project_id)
					);
					return CHtml::link(
						Yii::t('main', 'Manage'),
						$url,
						array('class'=>'bkButtonBlueSmall manageProject')
					);
				},
				'htmlOptions'=>array(
					'style'=>'width:100px;text-align:center;'
				)
			),
			array(
				'header'=>Yii::t('main', 'Switch to'),
				'type'=>'raw',
				'value'=>'$this->grid->owner->getSwitchProjectBtn($data)',
				'htmlOptions'=>array(
					'style'=>'width:100px;text-align:center;'
				)
			),
			/*array(
				'name'=>'project_id',
				'header'=>'ID',
				'htmlOptions'=>array(
					'style'=>'width:100px;'
				)
			),*/
			array(
				'name'=>'name',
			),
			array(
				'name'=>'companyName',
				'header'=>'Company',
			),
			array(
				'name'=>'home_page',
				'type'=>'raw',
				'value'=>'$this->grid->owner->getHomePageHtml($data)',
				'sortable'=>false,
				'filter'=>false,
				'htmlOptions'=>array(
					'style'=>'text-align:center;'
				),
			),
			array(
				'class'=>'CButtonColumn',
				'deleteConfirmation'=>true,
				'htmlOptions'=>array('style'=>'width:40px;text-align:center;'),
				'updateButtonUrl'=>'Yii::app()->createUrl(\'project/edit\', array(\'id\'=>$data->project_id))',
				//'template'=>'{view}&nbsp;{update}',
				'updateButtonImageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/page_edit.png',
				'template'=>'{update}',
				'buttons'=>array(
					'update'=>array(
						'click'=>'editProject'
					)
				),
			),
		),
	)
);