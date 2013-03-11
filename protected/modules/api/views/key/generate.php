<?php
$this->breadcrumbs = array(
    'API keys',
);
Yii::app()->clientScript->registerScriptFile($this->request->baseUrl.'/js/__modules/api/key/register/common.js');
?>
<?php $this->renderFlash(); ?>

<h2><?php echo Yii::t('main', 'API key')?></h2>
<input type="text" name="api-key" readonly="readonly" class="apikey-textarea" value="<?php echo $company->api_key ?>" />

<div class="generate-api-key">
<?php
    echo  CHtml::link(
        Yii::t('main', 'Refresh API Key'),
        $this->createUrl('key/generate', array('refresh'=>1)),
        array('class'=>'bkButtonGraySmall medium')
    );
?>
</div>
<?php
$criteria = new CDbCriteria();
$criteria->condition = 't.project_id IN(
    SELECT ubp.project_id FROM {{user_by_project}} AS ubp WHERE 1
       AND ubp.user_id = :user_id
) AND archived=0';
$criteria->params = array(':user_id'=>User::current()->user_id);

$projects = Project::model()->findAll($criteria);
if(!empty($projects) && is_array($projects)) { ?>
<h2><?php echo Yii::t('main', 'Project IDs'); ?></h2>
	<?php foreach($projects as $project) { ?>
	<div class="project-settings-item">
        <h3><?php echo $project->name; ?></h3>
        <p>Project ID:</p>
		<input type="text"
			   name="pid[<?php echo $project->project_id; ?>]"
			   readonly="readonly"
			   class="apikey-textarea"
			   value="<?php echo $project->api_id; ?>" />
<!--        <p>Embed code for submitting tickets, <br />place it at the end of BODY tag of your site's page:</p>-->
        <?php
/*        echo CHtml::tag(
            'textarea',
            array(
                'readonly'=>'readonly',
                'class'=>'apikey-textarea embed-textarea'
            ),
            CHtml::encode(
                $this->renderPartial(
                    '_js-snippet',
                    array(
                        'company'=>$company,
                        'project'=>$project,
                    ),
                    true
                )
            )
        );*/
        ?>
        <?php
//        echo htmlspecialchars('<iframe src="'.$this->createAbsoluteUrl('/api/widget/', array(
//            'width'=>250,
//            'height'=>250,
//            'projectID'=>$project->api_id,
//        )).'" scrolling="no" frameborder="0" style="border:none;overflow:hidden;width:250px; height:250px;"></iframe>');
        ?>
    </div>
	<?php } ?>
<?php } ?>