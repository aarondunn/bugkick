<?php
$this->breadcrumbs = array(
    'Projects' => array('index'),
);
?>
<div id="projects-list-wrapper">
	<h2><?php echo Yii::t('main', 'Projects'); ?></h2>
	<?php
        /*echo '<pre>';
        print_r($project->gridSearch());
        echo '</pre>';*/
		$this->renderPartial(
			'_projectsList',
			array(
				'model'=>$project,
				'dataProvider'=>$project->gridSearch(),
				'pager'=>$pager,
			)
		);
        echo CHtml::openTag('div', array('class'=>'al-left', 'style'=>'margin: 10px;'));
        if($this->request->getParam('archived')) {
            echo CHtml::link(
                'Back to projects',
                $this->createurl('/projects/index', array('archived'=>'0')),
                array('id'=>'switch-view-btn')
            );
        } elseif(Company::model()->findByPk(Company::current())->archivedProjectCount>0) {
            echo CHtml::link(
                'View deleted projects',
                $this->createUrl('/projects/index', array('archived'=>'1')),
                array('id'=>'switch-view-btn')
            );
        }
        echo CHtml::closeTag('div');

    if(!$ajax){
        $this->clientScript->registerScript('switch-view-btn-click',
<<<JS
$(document).on('click', '#switch-view-btn', function() {
    $.get(this.href, function(d) {
        $('#projects-list-wrapper').replaceWith(d);
    });
    return false;
});
JS
        ,
            CClientScript::POS_END
        );
		$forceCreate=$this->request->getParam('forceCreate');
		$this->beginWidget(
			'zii.widgets.jui.CJuiDialog',
			array(
				'id'=>'project-form-dialog',
				'options'=>array(
					'title'=>'New Project',
					'autoOpen'=>!empty($forceCreate),
					//'width'=>350,
					//'height'=>440,
					'modal'=>true,
					'hide'=>'drop',
					'show'=>'drop',
					'buttons'=>array(
						'Save'=>'js:submitProjectForm',
						//'Cancel'=>'js:closeDialog',
		            ),
				)
			)
		);

		$this->renderPartial(
			'edit',
			array(
				'projectForm'=>$projectForm,
				'project'=>$project,
		        'projectSettings'=>$projectSettings,
				'companies'=>$companies,
				'formAction'=>$formAction
			)
		);
		$this->endWidget();

		$this->beginWidget(
			'zii.widgets.jui.CJuiDialog',
			array(
				'id'=>'update-dialog',
				'options'=>array(
					'title'=>'Please Upgrade',
					'autoOpen'=>!empty($forceCreate),
					//'width'=>350,
					//'height'=>440,
					'modal'=>true,
					'hide'=>'drop',
					'show'=>'drop',
		            'buttons'=>array(
		                'Upgrade'=>'js:function(){
		                    document.location.href="'.$this->createUrl('payment/chooseSubscription').'"
		                }',
		            ),
				)
			)
		);
		$this->endWidget();
    }
?>
</div>