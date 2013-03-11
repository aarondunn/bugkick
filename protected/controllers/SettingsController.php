<?php

class SettingsController extends Controller {

    public $layout = '//layouts/column2';
	public $viewData=array();

    const PAGE_SIZE = 30;

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'companySet'
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
					'index', 'view', 'create', 'update', 'delete', 'filter', 
					'EmailPreferences', 'LabelListing', 'StatusListing', 
					'UserListing', 'InviteMembers', 'PendingMembers','Members', 
					'PasswordChange', 'ExportTickets', 'UploadPhoto',
					'ShortcutsState','Company', 'ResetCompanyTopBar', 'Groups',
                    'Projects', 'projectSettings', 'EditFeedback'
				),
                'users' => array('@'),
            ),
            /*
              array('allow', // allow admin user to perform 'admin' and 'delete' actions
              'actions'=>array('delete'),
              'users'=>array('admin'),
              ),
             */
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
/*		$userSettings = User::current()->user_settings;
		if(empty($userSettings))
			$userSettings=new SettingsByUser();*/
	
        if (Yii::app()->request->isAjaxRequest) {
            $user = User::current();
            $user->attributes = $this->request->getPost('User');
			//project settings like defaultAssignee, defaultLabel
/*            $userSettings->attributes =
                    $this->request->getPost('SettingsByUser');
            $userSettings->user_id = User::current()->user_id;
            $userSettings->save()*/

            if ($user->save()){
                if(Yii::app()->user->name != $user->attributes['name']){
                    Yii::app()->user->name = $user->attributes['name'];
                    echo 'refresh';
                } else
                    echo 'Saved';
            }

            Yii::app()->end();
        }
        $model = new PasswordForm();
        $form = new CForm('application.views.settings.passwordChange', $model);
		Yii::app()->clientScript->registerCssFile('/css/body/laf_settings.css');
		//Yii::app()->clientScript->registerScriptFile('/js/settings/index/common.js');
        Yii::app()->clientScript->registerScriptFile('/js/settings/index/common.min.js');

        MixPanel::instance()->registerEvent(MixPanel::SETTINGS_PAGE_VIEW); // MixPanel events tracking

		$this->render(
			'index',
			array(
				'passwordForm' => $form, 
				'userModel' => User::current(),
//				'userSettings' => $userSettings,
				'lafSet'=>LookAndFeel::model()->findAll("name!='Default'"),
			)
		);
    }

    public function actionUploadPhoto($type, $for = 'user')
    {
        if (Yii::app()->request->isAjaxRequest) {

            $user = User::current();
            if ($user->user_id > 0) {
                Yii::import("ext.EAjaxUpload.qqFileUploader");

                switch ($type) {
                    case  'image':
                        if ($for == 'company')
                            $folder = 'images/company_top_logo/'; // folder for uploaded files
                        elseif ($for == 'user')
                            $folder = 'images/profile_img/'; // folder for uploaded files
                        $allowedExtensions = array("jpg", "jpeg", "gif", "png");
                        $sizeLimit = 2 * 1024 * 1024; // maximum file size in bytes
                        break;
                    default:
                        Yii::app()->end();
                        break;
                }

                $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
                $result = $uploader->handleUpload($folder);

                if (!empty($result['success'])) {

                    Yii::import('ext.EWideImage.EWideImage');

                    if ($for == 'company') {
                        if (Company::current() > 0) {
                            $company = Company::model()->findByPk(Company::current());
                            if (!empty($company)) {
                                $company->company_top_logo = $result['filename'];
                                $company->save();

                                //preview 81px*81px
                                $filePath = $folder . $result['filename'];
                                $thumbName = '132_33_'.$result['filename'];
                                $thumbPath = $folder . $thumbName;
                                EWideImage::load($filePath)->resize(132, 33)->saveToFile($thumbPath);
                                $result['filename'] = $this->handleImage('company', $thumbName, $thumbPath); //we return path to this thumb
                            }
                        } else {
                            throw new CHttpException(400, 'Invalid request.');
                        }
                    } elseif ($for == 'user') {
                        $user->profile_img = $result['filename'];
                        $user->save();

                        $fileName = $result['filename'];
                        $filePath = $folder . $result['filename'];

                        //preview 81px*81px
                        $thumbName = '81_81_'.$result['filename'];
                        $thumbPath = $folder . $thumbName;
                        EWideImage::load($filePath)->resize(81, 81)->saveToFile($thumbPath);
                        $result['filename'] = $this->handleImage('user', $thumbName, $thumbPath); //we return path to this thumb

                        //preview 31px*31px
                        $thumbName = '31_31_'.$fileName;
                        $thumbPath = $folder . $thumbName;
                        EWideImage::load($filePath)->resize(31, 31)->saveToFile($thumbPath);
                        $this->handleImage('user', $thumbName, $thumbPath);

                        @unlink($filePath); //remove original image
                    }
                }

                $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
                echo $result; // it's array
            }
        }
        Yii::app()->end();
    }

    /**
     * Handles upload image to s3(if enabled) and return path to file
     * @param $for = 'user' or 'company'
     * @param string $fileName - image name like 123.jpg
     * @param $filePath - full path to image like images/user/123.jpg
     * @return string path to image
     * @throws CHttpException
     */
    protected function handleImage($for, $fileName, $filePath)
    {
        switch (Yii::app()->params['storageType']){
             case 's3':
                 //upload to s3
                 if($for=='user')
                     $bucket = S3Storage::PROFILE_BUCKET;
                 elseif($for=='company')
                     $bucket = S3Storage::COMPANY_TOP_BUCKET;
                 else
                     return null;

                 $s3FilePath = Storage::get('s3')->upload(
                     $bucket,
                     $fileName,
                     $filePath
                 );
                 @unlink($filePath);
                 if (!empty($s3FilePath))
                     return $s3FilePath;
                 break;
             case 'local':
                 return $filePath;
                 break;
        }
    }

    public function actionPasswordChange()
    {
        $model = new PasswordForm();

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'password-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if( isset($_POST['PasswordForm']) && (CActiveForm::validate($model)=='[]') ){
            $user = User::current();
//             $user->password = Hash::sha256($_POST['PasswordForm']['password_new'] . $user->salt());
			$user->password = Bcrypt::hash($_POST['PasswordForm']['password_new'] . $user->salt(),
            		Yii::app()->params['bcryptWorkFactor']);
            
            if ($user->validate() && $user->save())
                $this->redirect(Yii::app()->createUrl('settings'));
        } else{
        	$this->redirect(Yii::app()->createUrl('settings'));
        }
    }

    public function actionLabelListing()
    {
        $project = Project::getCurrent();
        if(empty($project))
            $this->redirect($this->createUrl('/project/index'));

        //the line below is using for filtering labels by project name
        $projectID = (int) ( !empty($_GET['Label']['name']) )? $_GET['Label']['name'] : $project->project_id;

        $criteria = new CDbCriteria();
        $criteria->condition = 't.company_id=' . Company::current();
        $criteria->with=array(
            'projects'=>array(
                'condition'=>'projects.project_id=:project_id AND projects.archived=0',
                'together'=>true,
                'params'=>array(
                    ':project_id'=>$projectID,
                ),
            ),
        );
        $criteria->distinct=true;
        $criteria->together=false;

        $pages = new CPagination(Label::model()->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $model = new CActiveDataProvider(
            Label::model(),
            array(
                'criteria'=>$criteria,
                'pagination'=>$pages,
            )
        );

        $labelModel = new Label;
        $labelModel->name = $projectID;

        MixPanel::instance()->registerEvent(MixPanel::LABELS_PAGE_VIEW); // MixPanel events tracking

        $this->render('labelListing', array('labelProvider' => $model, 'labelModel' => $labelModel));
    }
    
    public function actionEditFeedback()
    {
        //default settings
    	$iPosition = 3;
    	$iStyle    = 2;
    	$iColor    = 2;
    	
    	MixPanel::instance()->registerEvent(MixPanel::FEEDBACK_SETTINGS_PAGE_VIEW); // MixPanel events tracking
    	Yii::app()->clientScript->registerScriptFile('/js/settings/index/feedbackEdit.js');
    	$this->render('editFeedback', array('iPosition' => $iPosition, 'iStyle' => $iStyle, 'iColor' => $iColor));
    }

    public function actionStatusListing()
    {
        $statuses = Company::getStatuses();
        $statusProvider = null;
        if (!empty($statuses)) {
            $statusProvider = new CArrayDataProvider($statuses, array(
                'keyField' => 'status_id',
                'pagination' => array(
                    'pageSize' => self::PAGE_SIZE,
                ),
            ));
        }

        $statusModel = new Status;

        MixPanel::instance()->registerEvent(MixPanel::STATUS_PAGE_VIEW); // MixPanel events tracking

        $this->render('statusListing', array('statusProvider' => $statusProvider, 'statusModel' => $statusModel));
    }

/*    public function actionUserListing() {

        $users = Company::getUsers();
        $userProvider = null;
        if (!empty($users))
            $userProvider = new CArrayDataProvider($users);

        $userModel = new User;

        $this->render('userListing', array('userProvider' => $userProvider, 'userModel' => $userModel));
    }*/

/*    public function actionInviteMembers()
    {
        $userProvider = new CActiveDataProvider('User', array(
                    'criteria' => array(
                        'condition' => 'userStatus=' . User::STATUS_INVITED,
                        'with' => array(
                            'company' => array(
                                'condition' => 'company.company_id=' . Company::current(),
                            )
                        )
                    )
                ));

        $this->render('inviteMembers', array('userProvider' => $userProvider));
    }*/

/*    public function actionPendingMembers()
    {
        $userProvider = new CActiveDataProvider('User', array(
                    'criteria' => array(
                        'condition' => 'userStatus=' . User::STATUS_INVITED,
                        'with' => array(
                            'company' => array(
                                'condition' => 'company.company_id=' . Company::current(),
                            )
                        )
                    )
                ));

        $this->render('pendingMembers', array('userProvider' => $userProvider));
    }*/

    public function actionMembers()
    {
        /*$users = Company::getUsers();
        $userProvider = null;
        if(!empty($users)) {
            $userProvider = new CArrayDataProvider($users);
        }*/

        $userProvider = new CActiveDataProvider('User', array(
            'criteria'=>array(
                'condition'=>'t.user_id != :current_user_id AND userStatus = :active',
                'with'=>array(
                    'company'=>array(
                        'select'=>'company.company_id',
                        'condition'=>'company.company_id = :current_company_id
                                        AND user_status = :active',
                        'params'=>array(
                            ':current_company_id'=>Company::current(),
                            ':active'=>User::STATUS_ACTIVE,
                        )
                    ),
                ),
                'params'=>array(
                    ':current_user_id'=>User::current()->user_id,
                    ':active'=>User::STATUS_ACTIVE,
                ),
                'together'=>true,
            )
        ));

        $userModel = new User;

        $pendingUserProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'condition' => '(t.defaultCompany='.Company::current().' AND t.userStatus='.User::STATUS_INVITED.')
                    OR  t.user_id IN (
                            SELECT ubc.user_id FROM {{user_by_company}} AS ubc
                            WHERE 1
                            AND ubc.company_id = ' . Company::current()
                            . ' AND ubc.user_status = ' . User::STATUS_INVITED
                    . ')',
                'with' => array(
                    'company' => array(
                        'condition' => 'company.company_id=' . Company::current(),
                    ),
                )
            )
        ));

/*        $deletedUserProvider = new CActiveDataProvider('User', array(
            'criteria'=>array(
                //'condition'=>'userStatus = :deleted',
                'with'=>array(
                    'company'=>array(
                        'select'=>'company.company_id',
                        'condition'=>'company.company_id = :current_company_id
                                        AND user_status = :deleted',
                        'params'=>array(
                            ':current_company_id'=>Company::current(),
                            ':deleted'=>User::STATUS_DELETED,
                        )
                    ),
                ),
                'together'=>true,
            )
        ));*/

        MixPanel::instance()->registerEvent(MixPanel::MEMBERS_PAGE_VIEW); // MixPanel events tracking

        $this->render('members',
            array(
                'pendingUserProvider' => $pendingUserProvider,
                'userProvider' => $userProvider,
                'userModel' => $userModel,
               // 'deletedUserProvider' => $deletedUserProvider,
            )
        );
    }

/*    public function actionProjects()
    {
        if(isset($_GET['archived']))
            $projectView = 'archived';
        else
            $projectView = 'active';

        if(Yii::app()->user->isGuest)
            $this->redirect($this->createUrl('site/login'));
        $user = User::model()->findByPk(Yii::app()->user->id);
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/plug-in/jquery-json/jquery.json.min.js'
        );
        $this->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/plug-in/fprogress-bar/fprogress-bar.js'
        );
        $this->clientScript->registerCssFile(
            Yii::app()->baseUrl . '/js/plug-in/fprogress-bar/fprogress-bar.css'
        );
        $this->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/plug-in/jquery-gantt/js/jquery.fn.gantt.min.js'
        );
        $this->clientScript->registerCssFile(
            Yii::app()->baseUrl . '/js/plug-in/jquery-gantt/style.css'
        );
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/plug-in/jquery-form/jquery.form.min.js'
        );
        Yii::app()->clientScript->registerScriptFile(
            //Yii::app()->baseUrl . '/js/project/index/common.js'
            Yii::app()->baseUrl . '/js/project/index/common.min.js'
        );
        $projectSettings = new SettingsByProject();
        $project = new Project('search');
        $attributes = $this->request->getParam('Project');
        if(!empty($attributes))
            $project->setAttributes($attributes);
        $companies = empty($user->company) ? array() : $user->company;
        $viewData['companies'] = array();
        foreach($companies as $company)
            $viewData['companies'][$company->company_id] =
                $company->company_name;
        $viewData['formAction'] = $this->createUrl('project/create');
        $viewData['projectForm'] = new ProjectForm();
        if ($projectView == 'archived'){
            $viewData['project'] = $project->archived()->ownedByCurrentUser();
        }
        else{
            $viewData['project'] = $project->active()->ownedByCurrentUser();
        }
        $viewData['projectSettings'] = $projectSettings;
        $viewData['projectView'] = $projectView;
        $viewData['pager'] = array('pageSize'=>self::PAGE_SIZE);
        if($this->request->isAjaxRequest)
            echo $this->renderPartial('projects', $viewData);
        else
            $this->render('projects', $viewData);
    }*/

    public function actionExportTickets()
    {
        $project = Project::getCurrent();
        if(empty($project))
            $this->redirect($this->createUrl('/project/index'));

        $criteria = new CDbCriteria();
        $criteria->limit = 1000;
        $criteria->order= 'id DESC';
        $criteria->condition = 'project_id=:project_id';
        $criteria->params = array(
            ':project_id'=>$project->project_id,
        );
        $bugs = Bug::model()->resetScope()->findAll($criteria);

        if (!empty($bugs)) {
            // Turn off our amazing library autoload
            spl_autoload_unregister(array('YiiBase','autoload'));
            Yii::import('application.vendors.Excel.PHPExcel',true);
            spl_autoload_register(array('YiiBase','autoload'));

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('Tickets list');

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'Ticket #')
                        ->setCellValue('B1', 'Created')
                        ->setCellValue('C1', 'Title')
                        ->setCellValue('D1', 'Description')
                        ->setCellValue('E1', 'Status')
                        ->setCellValue('F1', 'Label')
                        ->setCellValue('G1', 'Company')
                        ->setCellValue('H1', 'Created by')
                        ->setCellValue('I1', 'Assigned to')
                        ->setCellValue('J1', 'Due date');

            foreach ($bugs as $bug) {
                $bugsArray[$bug->id][0] = $bug->number;
                $bugsArray[$bug->id][1] = $bug->created_at;
                $bugsArray[$bug->id][2] = $bug->title;
                $bugsArray[$bug->id][3] = $bug->description;
                $bugsArray[$bug->id][4] = empty($bug->status) ? '' : $bug->status->label;
				$bugsArray[$bug->id][5] = '';
                if (is_array($bug->label)){
                    foreach($bug->label as $label){
                        $bugsArray[$bug->id][5] .= $label->name. ', ';
                    }
                    $bugsArray[$bug->id][5] = substr($bugsArray[$bug->id][5], 0, -2);
                }
                $bugsArray[$bug->id][6] = empty($bug->company) ? '' : $bug->company->company_name;
                $bugsArray[$bug->id][7] = empty($bug->owner)? '' : $bug->owner->getUserName($bug->owner);
				$bugsArray[$bug->id][8] = '';
                if (is_array($bug->user)){
                    foreach($bug->user as $user){
                        $bugsArray[$bug->id][8] .= $user->getUserName($user) . ', ';
                    }
                    $bugsArray[$bug->id][8] = substr($bugsArray[$bug->id][8], 0, -2);
                }
                $bugsArray[$bug->id][9] = ($bug->duedate != '0000-00-00')? $bug->duedate : '';
            }

            // Loop through the result set
             $objPHPExcel->getActiveSheet()->fromArray($bugsArray,NULL,'A2');

            // Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('temp/Tickets_list.xls');

           // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Tickets_list.xls"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

            @unlink('temp/Tickets_list.xls');

            Yii::app()->end();
        }
        else{
            Yii::app()->user->setFlash('error',
                'No tickets yet.'
            );
            $this->redirect(Yii::app()->createUrl('bug/index'));
        }
    }

    public function actionShortcutsState()
    {
        $user = User::current();
        if (!empty($user)){

            if ($user->hotkey_preference == 1)
                $user->hotkey_preference = 0;
            else
               $user->hotkey_preference = 1;
            
            $user->save();
        }
        $this->redirect(Yii::app()->createUrl('/settings'));
    }

	public function actionEmailPreferences() {
		Yii::app()->clientScript->registerScriptFile(
			//$this->request->baseUrl.'/js/settings/email-preferences/common.js'
            $this->request->baseUrl.'/js/settings/email-preferences/common.min.js'
		);
		$formClass='EmailPreferenceForm';
		$emailPrefForm=new $formClass();
		$attributes=$this->request->getPost($formClass);
		if(!empty($attributes)) {
			$emailPrefForm->setAttributes($attributes);
			$success = EmailPreference::setUserPreferences(
				User::current(),
				$emailPrefForm
			);
			if($success) {
				$type='success';
				$message='Your changes have been saved';
			} else {
				$type='error';
				$message='An error occurred. Please try again';
			}
			Yii::app()->user->setFlash($type, Yii::t('main', $message));
		}
		$viewData['emailPrefForm']=$emailPrefForm;
		$viewData['emailPreferences']=EmailPreference::model()->findAll();

        MixPanel::instance()->registerEvent(MixPanel::EMAIL_PREFERENCES_PAGE_VIEW); // MixPanel events tracking

		if($this->request->isAjaxRequest && !empty($_GET['ajaxSubmit']))
			$this->renderPartial('application.views.site._flash');
		else
			$this->render('emailPreferences', $viewData);
    }
	
	public function actionGroups() {
		if($this->request->isAjaxrequest)
			$this->handleGroupsAjaxRequest();

        $project = Project::getCurrent();
        if(empty($project))
            $this->redirect($this->createUrl('/project/index'));

		Yii::app()->clientScript->registerScriptFile(
			//$this->request->baseUrl.'/js/settings/groups/common.js'
            $this->request->baseUrl.'/js/settings/groups/common.min.js'
		);
		$this->viewData['dataProvider']=UserGroup::model()->gridSearch();
        $this->viewData['dataProvider']->criteria->mergeWith(array(
            'condition'=>'t.company_id=:current_company_id',
            'params'=>array(':current_company_id'=>Company::current()),
        ));
		$usersFinder=User::model()->currentCompany();
		$this->viewData['usersDataProvider']=new CActiveDataProvider(
				$usersFinder
		);

        MixPanel::instance()->registerEvent(MixPanel::GROUPS_PAGE_VIEW); // MixPanel events tracking

		if($this->request->isAjaxRequest)
			$this->renderPartial('_groupList', $this->viewData);
		else
			$this->render('groupsListing', $this->viewData);
	}

	protected function handleGroupsAjaxRequest() {
		$action=$this->request->getPost('action');
		if(!empty($action))
			if(method_exists(get_class($this), $action))
				$this->$action();
			else
				$this->_404("Action '$action' does not exists.");
	}

	protected function getGroupMembers() {
		$group_id=(int)$this->request->getPost('group_id');
		if(empty($group_id))
			$this->_404('Invalid set of parameters');
		$sql='
		SELECT u.user_id, u.facebook_id, u.name, u.lname, u.profile_img 
			FROM {{user}} AS u, {{user_by_group}} AS u2g, {{user_group}} AS g
			WHERE 1
			AND g.group_id=:group_id 
			AND g.group_id=u2g.group_id 
			AND u2g.user_id=u.user_id
		';
		$groupMembers=Yii::app()->db->createCommand($sql)->queryAll(
			true,
			array(':group_id'=>$group_id)
		);
		foreach($groupMembers as &$member) {
			$member['profile_img']=User::_getImageSrc(
				$member['profile_img'],
				$member['facebook_id'],
				31,
				31
			);
			unset($member['facebook_id']);
		}
		$this->respond($groupMembers);
	}

	protected function getGroupActionParams() {
		$group_id=(int)$this->request->getPost('group_id');
		$user_id=(int)$this->request->getPost('user_id');
		if(empty($group_id) || empty($user_id))
			$this->_404('Invalid set of parameters.');
		return array(
			'group_id'=>$group_id,
			'user_id'=>$user_id
		);
	}

	protected function addToGroup() {
		$params=$this->getGroupActionParams();
		$isUserInGroup=UserByGroup::model()->exists(
			'user_id=:user_id AND group_id=:group_id',
			array(
				':user_id'=>$params['user_id'],
				':group_id'=>$params['group_id'],
			)
		);
		if($isUserInGroup) {
			$response['success']=false;
			$this->respond($response);
		}
		$userByGroup=new UserByGroup();
		$userByGroup->user_id=$params['user_id'];
		$userByGroup->group_id=$params['group_id'];
		$response['success']=$userByGroup->save();
		$this->respond($response);
	}

	protected function removeFromGroup() {
		$params=$this->getGroupActionParams();
		$delNum=UserByGroup::model()->deleteAllByAttributes(
			array(
				'user_id'=>$params['user_id'],
				'group_id'=>$params['group_id'],
			)
		);
		$response['success']=$delNum>0;
		$this->respond($response);
	}

	protected function deleteGroup() {
		$group_id=(int)$this->request->getPost('group_id');
		if(empty($group_id))
			$this->_404('Invalid set of parameters.');
		$cmd=Yii::app()->db->createCommand();
		$params=array(':group_id'=>$group_id);
		$response['success']=true;
		try {
			$sql='DELETE FROM {{user_group}} WHERE group_id=:group_id';
			$cmd->setText($sql)->execute($params);
			$sql='DELETE FROM {{user_by_group}} WHERE group_id=:group_id';
			$cmd->setText($sql)->execute($params);
		} catch(CException $ex) {
			$response['success']=false;
		}
		$this->respond($response);
	}

    public function actionCompany()
    {
        $model = Company::model()->findByPk( Company::current() );

        if (isset($_POST['Company'])) {
            $form = new CompanySettingsForm();
            $form->setAttributes($_POST['Company']);
            if($form->validate()) {
                $model->attributes=$form->getAttributes();

                //do not allow free companies to turn off the ads and have top logo
                if ($model->account_type == Company::TYPE_FREE){
                    $model->downgradeFeaturesToFree();
                }

                if ($model->save()) {
                    Yii::app()->user->setState('clearCompanyCache', 1);
                    Yii::app()->user->setFlash('success', "Saved.");
                }
            }
        }

        MixPanel::instance()->registerEvent(MixPanel::COMPANY_SETTINGS_PAGE_VIEW); // MixPanel events tracking

        $this->render('company', array( 'model' => $model ) );
    }

    public function actionResetCompanyTopBar()
    {
        $model = Company::model()->findByPk( Company::current() );

        if(!empty($model->company_top_logo)){
            @unlink('images/company_top_logo/' . $model->company_top_logo );
        }

        $model->company_color = '';
        $model->company_top_logo = '';

        if($model->save())
            Yii::app()->user->setFlash('success', "Restored.");
        else
            Yii::app()->user->setFlash('error', "An error has occurred. Please try again.");

        Yii::app()->user->setState('clearCompanyCache', 1);
        $this->redirect(Yii::app()->createUrl('settings/company'));
    }

    /*
     * Project Settings
     * */
    public function actionProjectSettings()
    {
        if (Yii::app()->request->isAjaxRequest || Yii::app()->request->isPostRequest) {
            $defaultProjectSettings = Project::getProjectSettings();
            if(empty($defaultProjectSettings))
                $defaultProjectSettings=new SettingsByProject;

            if(isset($_POST['SettingsByProject']) && !empty($_POST['SettingsByProject'])){
                $defaultProjectSettings->attributes =
                    $this->request->getPost('SettingsByProject');
                $defaultProjectSettings->project_id = Project::getCurrent()->project_id;
                if ($defaultProjectSettings->save()){
                    $this->redirect(Yii::app()->createUrl('bug'));
                }
                throw new CHttpException(400, 'Invalid request.');
            }
            $this->renderPartial(
                'projectSettings',
                array(
                    'defaultProjectSettings' => $defaultProjectSettings,
                )
            );
        }
        else{
            throw new CHttpException(400, 'Invalid request.');
        }
    }
}