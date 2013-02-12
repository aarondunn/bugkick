<?php
/**
 * EditAction
 *
 * @author f0t0n
 */
class EditAction extends Action {
	
	public function run() {
		$user = User::current();
        if(empty($user)
            || $user->getStatusInCompany(Company::current())
                != User::STATUS_ACTIVE) {
            throw new CHttpException(403, 'Action forbidden.');
        }
		if(empty($user))
			Yii::app()->end();
		$project_id = $this->request->getParam('id');
		$projectForm = new ProjectForm();
		$isNewProject = empty($project_id);
		$project = $isNewProject
			? new Project()
			: Project::model()->findByPk($project_id);
		if(!empty($project))
            $projectForm->setAttributes($project->getAttributes());

        if ($isNewProject) {
            //check if company can add one more project
            if ( !Company::canAddNewProject() ){
                $message = Yii::t('main', 'Please upgrade your company to have more projects.');
                $this->controller->respond(
                    array(
                        'html'=>$this->controller->renderPartial(
                            '//payment/need-upgrade',
                            array('message'=>$message),
                            true
                        ),
                        'success'=>false,
                        'limit'=>1
                    )
                );
                Yii::app()->end();
            }

            //adding current user to new project
            $projectForm->users[] = $user->user_id;

            //adding all pre-created labels to new project
            $labels = Label::getPresets();
            foreach($labels as $label)
                $projectForm->labels[]=$label->label_id;
        }
        else{
            //we doesn't allow non-admins to edit projects
            if(!User::current()->isCompanyAdmin(Company::current()))
                throw new CHttpException(403, 'Action forbidden.');

            foreach($project->users as $user)
                $projectForm->users[]=$user->user_id;
            foreach($project->labels as $label)
                $projectForm->labels[]=$label->label_id;
        }

        $projectSettings = $project->project_settings;
        if(empty($projectSettings))
            $projectSettings = new SettingsByProject();

		$attributes = $this->request->getPost('ProjectForm');
		$success = true;
		if(!empty($attributes) && !empty($project)) {
			$projectForm->setAttributes($attributes);
            $projectForm->name = htmlspecialchars($projectForm->name);
			$this->controller->performAjaxValidation(
				$projectForm,
				'project-form'
			);

            $projectSettings->attributes = $this->request->getPost('SettingsByProject');
            $this->controller->performAjaxValidation(
                $projectSettings,
                'project-form'
            );

			$success = false;
			if($projectForm->validate() && $projectSettings->validate()) {
				$project->setAttributes($projectForm->getAttributes());
				if(!empty($projectForm->tmpFileID)) {
					$canSetLogo = $isNewProject ? $project->save() : true;
					if($canSetLogo)
						$this->setProjectLogo(
							$project,
							$projectForm->tmpFileID
						);
				}
                else{
                    //setting random logo for new projects if no logo was uploaded
                    $canSetLogo = $isNewProject ? $project->save() : false;
                    if($canSetLogo){
                        // projects default logos are stored under \images\project_logo\defaults
                        $logo = rand(1, 17) . '.jpg';
                        $project->logo = 'defaults/' . $logo;
                    }
                }
                if ($project->save()){

                    /*Adding users and labels to project*/
                    $cmd=Yii::app()->db->createCommand();
                    $cmdParams=array(':project_id'=>$project->project_id);
                    $delSql='DELETE FROM {{user_by_project}} WHERE project_id=:project_id';
                    $delSql2='DELETE FROM {{label_by_project}} WHERE project_id=:project_id';
                    $cmd->setText($delSql)->execute($cmdParams);    //	delete existing users
                    $cmd->setText($delSql2)->execute($cmdParams);    //	delete existing labels
                    $getSqlValues=function(array $IDs, $forProject=false) {
                        $values=array();
                        foreach($IDs as $id) {
                            if($forProject) {
                                $values[]='('.(int)$id.', :project_id, '
                                    . ($id == User::current()->user_id ? '1' : '0')
                                    . ')';
                            } else {
                                $values[]='('.(int)$id.', :project_id)';
                            }
                        }
                        return $values;
                    };
                    if (is_array($projectForm->users)){
                        $values=$getSqlValues($projectForm->users, true);
                        if(!empty($values)) {
                            $sql='INSERT INTO {{user_by_project}} VALUES'
                                .implode(',', $values);
                            $cmd->setText($sql)->execute($cmdParams);	//	Insert the users that has been set
                        }
                    }
                    if(is_array($projectForm->labels)){
                        $values2=$getSqlValues($projectForm->labels);
                        if(!empty($values2)) {
                            $sql='INSERT INTO {{label_by_project}} VALUES'
                                .implode(',', $values2);
                            $cmd->setText($sql)->execute($cmdParams);	//	Insert the labels that has been set
                        }
                    }
                    /*end of adding users and labels to project*/

                    $projectSettings->project_id = $project->project_id;
                    if ( $projectSettings->save() ){
                        if(strstr(Yii::app()->request->getUrlReferrer(), '/new')){
                            Yii::import('application.controllers.NewController');
                            NewController::setCurrentStep(2);
                        }
                        $success = true;
                    }
                }
			}
		}
		$companies = empty($user->company) ? array() : $user->company;
		$this->viewData['companies'] = array();
		foreach($companies as $company)
			$this->viewData['companies'][$company->company_id] =
				$company->company_name;
		$this->viewData['formAction'] = $this->request->url;
		$this->viewData['projectForm'] = $projectForm;
		$this->viewData['project'] = $project;
        $this->viewData['projectSettings'] = $projectSettings;
        $this->viewData['project_id'] = $project_id;
		$this->controller->respond(
			array(
				'html'=>$this->controller->renderPartial(
                    'edit',
                    $this->viewData,
                    true
                ),
                'success'=>$success,
                'projectID'=>$project->project_id,
                'projectName'=>$project->name,
			)
		);
	}
	
	protected function setProjectLogo(Project $project, $tmpFileID) {
		$tmpFile = TmpFile::model()->findByPk($tmpFileID);
		if(empty($tmpFile))
			return false;
		$source = Yii::getPathOfAlias('webroot.temp') . '/' . $tmpFile->path;
		$filename = pathinfo($source, PATHINFO_BASENAME);
		$destDir = Yii::getPathOfAlias('webroot.images.project_logo') . '/';
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$newFilename = $project->project_id . '_' . time() . '.' . $extension;
		$dest = $destDir . $newFilename;
		$moved = $tmpFile->moveFileTo($dest);
		if($moved) {
			if(!empty($project->logo)) {
				$oldLogo = $destDir . $project->logo;
				$oldPathInfo = pathinfo($oldLogo);
				$oldFileName = $oldPathInfo['filename'];
				$oldExtension = $oldPathInfo['extension'];
				@unlink(
					$destDir . '.tmb/thumb_' 
						. $oldFileName . '_50_50.' . $oldExtension
				);
                @unlink(
                    $destDir . '.tmb/thumb_'
                        . $oldFileName . '_70_70.' . $oldExtension
                );
                if(Yii::app()->params['storageType'] == 'local'){
                    if(is_file($oldLogo) && $project->logo != $newFilename)
                        unlink($oldLogo);
                }
            }

            if(Yii::app()->params['storageType'] == 's3'){
                //preview 70px*70px
                $thumbName = '70_70_' . $newFilename;
                $thumbPath = $destDir . $thumbName;
                Yii::import('ext.EWideImage.EWideImage');
                EWideImage::load($dest)->resize(70, 70)->saveToFile($thumbPath);

                //upload to s3
                $bucket = S3Storage::PROJECT_BUCKET;
                Storage::get('s3')->upload(
                    $bucket,
                    $thumbName,
                    $thumbPath
                );
                @unlink($thumbPath); //resized original
                @unlink($dest); //original image
            }

			$project->logo = $newFilename;
		}
		return true;
	}
}