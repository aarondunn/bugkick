<div class="form">
<?php /*<div class="project-pic" style="float: right; width: 20%;">
    <div>
	    <img id="projectLogo" alt="Project logo" src="<?php echo $project->getLogoSrc(true); ?>" />
    </div>
    <?php
    $this->widget(
	    'AjaxUpload',
	    array(
		    'id'=>'uploadProjectLogo',
		    'buttonText'=>'Choose a logo',
		    'posInitUploader'=>AjaxUpload::INIT_POS_DIRECTLY,
		    'config'=>array(
			    'action'=>$this->createUrl('project/uploadLogo'),
			    'allowedExtensions'=>array('jpg', 'jpeg', 'gif', 'png'),
			    'sizeLimit'=>2097152, // 2 MB - the maximum file size
			    //'minSizeLimit'=>10*1024*1024,// minimum file size in bytes
			    'onComplete'=>"js:function(id, fileName, responseJSON) {
				    if(responseJSON.filename != 'undefined' 
					    && responseJSON.tmpFileID != '0') {
					    var projectLogo = $('#projectLogo')
					    projectLogo.attr(
						    'src',
						    '/temp/project_logo/' + responseJSON.filename
					    );
					    $('#ProjectForm_tmpFileID').val(responseJSON.tmpFileID);
					    var prop = projectLogo.width() > projectLogo.height()
						    ? 'width'
						    : 'height';
					    projectLogo.css(prop, '50px');
				    }
			    }"
		    )
	    )
    );
    ?>
</div>
 */
?>

<?php
$form = $this->beginWidget(
	'CActiveForm',
	array(
		'id'=>'project-form',
		'action'=>$formAction,
		'method'=>'POST',
		'enableAjaxValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>false,
		),
        'htmlOptions'=>array(
			//'enctype'=>'multipart/form-data',
			'name'=>'projectForm',
			'style'=>'float:left;'
		),
	)
);
?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#project-common-info" data-toggle="tab">Common Info</a></li>
        <li><a href="#project-users-and-labels" data-toggle="tab">Users and Labels</a></li>
        <li><a href="#project-defaults" data-toggle="tab">Defaults</a></li>
        <?php if($projectForm->connectToGitHub) { ?>
        <li><a href="#extra-features" data-toggle="tab">Extra Features</a></li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="project-common-info">
            <div class="main-info">
                 <div class="project-pic">
                    <div>
                        <img id="projectLogo" alt="Project logo" src="<?php echo $project->getLogoSrc(70,70); ?>" />
                    </div>
                    <?php
                    $this->widget(
                        'AjaxUpload',
                        array(
                            'id'=>'uploadProjectLogo',
                            'buttonText'=>'Choose a logo',
                            'posInitUploader'=>AjaxUpload::INIT_POS_DIRECTLY,
                            'config'=>array(
                                'action'=>$this->createUrl('project/uploadLogo'),
                                'allowedExtensions'=>array('jpg', 'jpeg', 'gif', 'png'),
                                'sizeLimit'=>2097152, // 2 MB - the maximum file size
                                //'minSizeLimit'=>10*1024*1024,// minimum file size in bytes
                                'onComplete'=>"js:function(id, fileName, responseJSON) {
                                    if(responseJSON.filename != 'undefined'
                                        && responseJSON.tmpFileID != '0') {
                                        var projectLogo = $('#projectLogo')
                                        projectLogo.attr(
                                            'src',
                                            '/temp/project_logo/' + responseJSON.filename
                                        );
                                        $('#ProjectForm_tmpFileID').val(responseJSON.tmpFileID);
                                        var prop = projectLogo.width() > projectLogo.height()
                                            ? 'width'
                                            : 'height';
                                        projectLogo.css(prop, '70px');
                                    }
                                }"
                            )
                        )
                    );
                    ?>
                </div>

                <div class="row">
                    <div class="basic-project-text">
                        <div>
                        <?php
                        echo//name
                            $form->labelEx($projectForm, 'name'),
                            $form->textField($projectForm, 'name'),
                            $form->error($projectForm, 'name');
                        ?>
                        </div>
                        <div>
                        <?php
                            echo//description
                                $form->labelEx($projectForm, 'description'),
                                $form->textArea($projectForm, 'description'),
                                $form->error($projectForm, 'description');
                            ?>
                        </div>
                    </div>
                </div>
           </div>
           
            <div id="archived-row" class="row">
                <?php
                    if(!$project->isNewRecord && User::current()->isCompanyAdmin($project->company->company_id)
                        || User::current()->isProjectAdmin($project->project_id)){
                        echo CHtml::link(
                            Yii::t('main', $project->archived==1? 'Restore project' : 'Delete project'),
                            array('project/setArchived', 'id'=>$project->project_id),
                            array(
                                'style'=>'float:right; color:red',
                                'onclick'=>'return confirm("After confirming of this action you will lose the access to this project.\n\nContinue?");',
                            )
                        );
                    }
//                    echo $form->labelEx($projectForm,'archived');
//                    echo CHtml::activeDropDownList($projectForm,
//                        'archived',
//                        ProjectForm::itemAlias('archived'),
//                        array(
//                            'class'=>'chzn-select'
//                        )
//                    );
//                    echo $form->error($projectForm,'archived');
                ?>
            </div>
            <div class="clear"></div>
        </div> <!-- #project-coomon-info -->

        <div class="tab-pane" id="project-users-and-labels">
            <div class="row">
                <?php echo $form->labelEx($projectForm, 'Project Users'); ?>
                <?php
                    echo CHtml::activeDropDownList(
                    $projectForm,
                    'users',
                    CHtml::listData(Company::getUsers(), 'user_id', 'name'),
                        array(
                        'multiple'=>'multiple',
                        'key'=>'label_id',
                        'prompt'=>'&nbsp;',
                        'class'=>'chzn-select'
                        )
                    );
                ?>
                <?php echo $form->error($projectForm, 'users'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($projectForm, 'Project Labels'); ?>
                <?php
                    $labels = ($project->isNewRecord)? Company::getPreCreatedLabels() : Company::getLabels();
                    echo CHtml::activeDropDownList(
                    $projectForm,
                    'labels',
                    CHtml::listData($labels, 'label_id', 'name'),
                        array(
                        'multiple'=>'multiple',
                        'key'=>'label_id',
                        'prompt'=>'&nbsp;',
                        'class'=>'chzn-select'
                        )
                    );
                ?>
                <?php echo $form->error($projectForm, 'labels'); ?>
            </div>
        </div> <!-- #project-users-and-labels -->

        <div class="tab-pane" id="project-defaults">
            <?php echo  $form->hiddenField($projectForm, 'tmpFileID');	//	temporary logo file ?>
            <div class="row">
                <?php echo $form->labelEx($projectSettings, 'defaultAssignee'); ?>
                <?php if($project->isNewRecord) $projectSettings->defaultAssignee=Yii::app()->user->id;
                    echo CHtml::activeDropDownList(
                        $projectSettings,
                        'defaultAssignee',
                        CHtml::listData(empty($project_id)? array(User::current()) : Project::getUsers($project_id), 'user_id', 'name'),
                        array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox')
                ) ?>
            </div>
            <div class="row">
                <?php echo $form->error($projectSettings, 'defaultAssignee'); ?>
                <?php echo $form->labelEx($projectSettings, 'defaultStatus'); ?>
                <?php
                    if($project->isNewRecord){
                        $newStatus = Company::getNewStatus();
                        if(!empty($newStatus))
                            $projectSettings->defaultStatus= $newStatus->status_id;
                    }
                    echo CHtml::activeDropDownList(
                        $projectSettings,
                        'defaultStatus',
                        CHtml::listData(Company::getStatuses(), 'status_id', 'label'),
                        array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox')
                ) ?>
            </div>
            <div class="row">
                <?php echo $form->error($projectSettings, 'defaultStatus'); ?>
                <?php echo $form->labelEx($projectSettings, 'defaultLabel'); ?>
                <?php
                    if($project->isNewRecord){
                        $bugLabel = Project::getBugLabel();
                        if(!empty($bugLabel))
                            $projectSettings->defaultLabel= $bugLabel->label_id;
                    }
                    $labels = ($project->isNewRecord)? Company::getPreCreatedLabels() : Company::getLabels();
                    echo CHtml::activeDropDownList(
                        $projectSettings,
                        'defaultLabel',
                        CHtml::listData($labels, 'label_id', 'name'),
                        array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox')
                ) ?>
                <?php echo $form->error($projectSettings, 'defaultLabel'); ?>
            </div>

        </div> <!-- #project-defaults -->

        <?php if($projectForm->connectToGitHub) { ?>
        <div class="tab-pane" id="extra-features">
            <div class="row">
                <label>Integration with GitHub:</label>
                <?php
                echo CHtml::link(
                    'Connect to GitHub',
                    array(
                        '/github/repo/connect',
                        'project_id'=>$project->project_id
                    ),
                    array('class'=>'bkButtonBlueSmall medium',)
                );
                ?>
            </div>
        </div>
        <?php } ?>
    </div> <!-- .tab-content -->
<?php $this->endWidget();?>
    <div class="clear"></div>
</div>