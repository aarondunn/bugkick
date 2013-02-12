<?php
$this->widget('zii.widgets.grid.CGridView',
	array(
		'id'=>'projects-grid',
		'dataProvider'=>$model->gridSearch(),
		//'filter'=>$model,
        'hideHeader'=>true,  //hide Header
		'ajaxUpdate'=>true,
        'summaryText'=>'', //hide Total: 10 result(s)
		'enableSorting'=>false,
		'enablePagination'=>true,
		//'template'=>'{pager}<br />{items}<br />{pager}',
		'columns'=>array(
/*			array(
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
			),*/
			/*array(
				'header'=>Yii::t('main', 'Switch to'),
				'type'=>'raw',
				'value'=>'$this->grid->owner->getSwitchProjectBtn($data)',
				'htmlOptions'=>array(
					'style'=>'width:100px;text-align:center;'
				)
			),*/
			/*array(
				'name'=>'project_id',
				'header'=>'ID',
				'htmlOptions'=>array(
					'style'=>'width:100px;'
				)
			),*/
			array(
				'name'=>'name',
                'header'=>'Project Name',
                'value'=>'Helper::truncateString($data->name, 100)'
			),
			/*array(
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
			),*/
			array(
				'class'=>'CButtonColumn',
				'deleteConfirmation'=>true,
				'htmlOptions'=>array('style'=>'width:45px;text-align:center;'),
				'updateButtonUrl'=>'Yii::app()->createUrl(\'project/edit\', array(\'id\'=>$data->project_id))',
                'updateButtonImageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/edit_icon.png',
				'template'=>'{archive} &nbsp; {update}',
				'buttons'=>array(
					'update'=>array(
						'click'=>'editProject'
					),
                    'archive' => array(
                        'label'=>($projectView == 'archived')?'Unarchive':'Archive',
                        'imageUrl'=>($projectView == 'archived')?
                            Yii::app()->theme->baseUrl .'/images/icons/unarchive-icon.png':
                            Yii::app()->theme->baseUrl .'/images/icons/archive-icon.png',
                        'url'=>'Yii::app()->createUrl("/project/setarchived", array("id"=>$data->project_id))',
                    ),
				),
			),
		),
	)
);