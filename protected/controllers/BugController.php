<?php

class BugController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    public $currentView;  // 'closed' when view closed tickets
    public $textSearch; // For storing search keywords

    public function init()
    {
        if(Yii::app()->user->isGuest) {
            Yii::app()->user->setFlash('error','Please log in to access this page.');
            $this->redirect('/site/login');
        }
    }

    /**
     *
     * @var Bug
     */
    protected $bug;
    protected $bugForm;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'companySet'
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform actions
                'actions' => array(
                    'create',
                    'update',
                    'admin',
                    'delete',
                	'DeleteComment',
                    'UpdateDuedate',
                    'PaginationUpdate',
                    'GetBugById',
                    'SetArchived',
                    'Closed',
                    'SetPriority',
                    'GetDuplicateFormByBugId',
                    'duplicate',
                    'DndPrioritySort',
                    'DndAddLabel',
                    'DndAddUser',
                    'DndAddStatus',
                    'GetTicketOrder',
                    'UpdateTicketOrder',
            		'UpdateAjaxComment',
            		'upload',
                	'summaryTickets',
                ),
                'users' => array('@'),
            ),
            //check if authorized user has permissions to see project
            array('allow',
                  'actions'=>array('index','view'),
                  'users'=>array('@'),
                  'expression'=>'Project::isProjectAccessAllowed()',
                 ),
            array('deny', // deny all users
                'users' => array('*'),
                'message'=>'You have no permissions to access this project.'
            ),
        );
    }

     public function actions()
     {
         return array(
             'upload'=>array(
                 'class'=>'application.controllers.bug.UploadAction',
                 'path' =>Yii::app() -> getBasePath() . '/../temp/user_files',
                 'publicPath' => Yii::app() -> getBaseUrl() . '/temp/user_files',
                 'subfolderVar' => 'parent_id',
             ),
         );
     }
       
    public function actionUpdateAjaxComment(){
        $comment = Yii::app()->request->getPost('Comment');
        $bugForm = Yii::app()->request->getPost('BugForm');
        $model = new Comment;
        if (isset($comment)) {
            $bug = Bug::model()->resetScope()->findByPk($comment['bugId']);
            if (empty($bug))
                _400();
            $model->attributes = $comment;
            $model->message = ActivateLinks::perform($model->message);
            $p = new CHtmlPurifier($this);
            $p->options = array(
                'HTML.AllowedElements' =>
                'p,div,span,ul,ol,li,a,hr,pre,br,h1,h2,h3,h4,h5,h6,b,i,u,strike,big,small',
                'HTML.AllowedAttributes' => 'style,class,width,size,href, align',
            );
            $model->message = $p->purify($model->message);
            $model->message = preg_replace(
                    '/({{{#!)([^\s]*)\s+((.|\s)+?)\s*(!#}}})/', '<pre class="language-$2">$3</pre>', $model->message
            );
            $model->message = preg_replace(
                    '/(<pre class=")(language-)(">)/', '$1highlight$3', $model->message
            );
            $model->bug_id = (int) $bug->id;
            if ($model->save()) {
                //send notifications
                Notificator::newComment($model, array($model->user_id));

                //check if user changed project during writing a comment
                User::updateCurrentProject($bug->project_id);

                //close ticket if "Comment and Close" button was pressed
                $commentAndClose = $this->request->getParam('comment-and-close');
                if ($commentAndClose == 1) {
                    $bug->isarchive = 1;
                    $bug->archiving_date = new CDbExpression('NOW()');
                    $changes = array(0 => array('field' => 'archived', 'value' => 1));
                    if ($bug->save()) {
                        $changeLog = new BugChangelog();
                        $changeLog->populateChanges($bug, $changes);
                        $changeLog->save();
                    }
                }
                if (isset($bugForm)) {
                    $this->forward('/bug/update/id/' . $bug->id);
                }
                $this->renderPartial('_commentsList', array('comments' => $bug->comment));
                Yii::app()->end();
            }
        }
    }

    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
    	//file_put_contents('/home/user01/web/bugkick/protected/controllers/test', array('id'=>$id));
		$project_id = null;
		$currentProject = null;
		$user = User::current();
		if(!empty($user))
			$currentProject = $user->currentProject;
		if(empty($currentProject))
			$this->redirect('/project/index');
        Yii::app()->minScript->generateScriptMap(array(
            '/js/plug-in/jquery-syntaxhighlighter/scripts/shCore.js',
            '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushJScript.js',
            '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushCss.js',
            '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushPhp.js',
            '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushSql.js',
            '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushXml.js',
            '/themes/bugkick_theme/js/comments-0.0.1.min.js',
            '/js/bugkick/bug/view.js',
            '/js/plug-in/autoresize/jquery.autoresize.min.js',
        ));
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl . '/js/plug-in/jquery-syntaxhighlighter/scripts/shCore.js'
		);
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl . '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushJScript.js'
		);
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl . '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushCss.js'
		);
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl . '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushPhp.js'
		);
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl . '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushSql.js'
		);
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl . '/js/plug-in/jquery-syntaxhighlighter/scripts/shBrushXml.js'
		);
		Yii::app()->clientScript->registerCssFile(
			Yii::app()->baseUrl . '/js/plug-in/jquery-syntaxhighlighter/styles/shCoreDefault.css'
		);
		Yii::app()->clientScript->registerScriptFile(
			//Yii::app()->theme->baseUrl . '/js/comments-0.0.1.js');
            Yii::app()->theme->baseUrl . '/js/comments-0.0.1.min.js');

		Yii::app()->clientScript->registerScriptFile(
            Yii::app()->request->baseUrl . '/js/bugkick/bug/view.js');
		$baseUrl=Yii::app()->request->baseUrl;
		Yii::app()->clientScript->registerScript(
			__CLASS__.'#SyntaxHighlighter',
<<<JS
	SyntaxHighlighter.all({toolbar:'false'});
JS
			,
			CClientScript::POS_END
		);  
		/*
		$relatedToBug = array(
			'comment'=>array(
				'order'=>'comment.created_at ASC',
				'joinType'=>'INNER JOIN',
			)
		);
        $bug = Bug::model()->resetScope()->with($relatedToBug)->findByPk($id);
		*/
        $bug = $this->bug = Bug::model()->resetScope()
			->with(array('comment'=>array('order'=>'comment.created_at ASC')))
			->find(
				'number=:number AND project_id=:project_id',
				array(':number'=>$id, ':project_id'=>$currentProject->project_id)
		);
        if (!empty($bug)) {
            Yii::app()->clientScript->registerScript(
                __CLASS__.'#bugkick.viewData',
<<<JS
    bugkick.viewData.bug = {
        id: {$bug->id}
    };
JS
                    ,
                CClientScript::POS_END
            );
			if(empty($bug->owner)) {
				$bug->owner=new User();
			}
            $this->initBugForm();
            $criteria = new CDbCriteria();
            $criteria->order= 'date DESC';
            $criteria->compare('bug_id', $bug->id);
            $bugChanges = new CActiveDataProviderViewMore('BugChangelog',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>5,
                ),
            ));

            MixPanel::instance()->registerEvent(MixPanel::TICKET_PAGE_VIEW); // MixPanel events tracking

            $this->render('view', array(
                'model' => $bug,
                'changesDataProvider' => $bugChanges,
                'users' => User::model()->bugRelated($bug)->findAll(),
            ));
        }
        else
            throw new CHttpException(404, 'The ticket does not exist.');
    }

    protected function initBugForm() {
        $this->bugForm = new BugForm(BugForm::SCENARIO_EDIT);
        if($this->bug->title != $this->bug->description) {
            $this->bug->description = 
                str_replace('&#133;', '', $this->bug->title)
                . $this->bug->description;
        }
        $this->bugForm->setAttributes($this->bug->attributes);
        if($this->bugForm->duedate=='0000-00-00') {
            $this->bugForm->duedate='';
        }
        foreach($this->bug->label as $label) {
            $this->bugForm->labels[]=$label->label_id;
        }
        foreach($this->bug->user as $user) {
            $this->bugForm->assignees[]=$user->user_id;
        }
    }

    /**
     * Creates a new model.
     */
    public function actionCreate() {
        $model = new Bug;
		$form=new BugForm();
        $_POST['BugForm']['title']='t';
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($form, 'bug-form');
        if(isset($_POST['BugForm'])) {
			$form->setAttributes($_POST['BugForm']);
			if($form->validate()) {
				$project = Project::getCurrent();
				if(empty($project)) {
					Yii::app()->user->setFlash(
						'error',
						'Please choose the project first.'
					);
					$this->redirect(Yii::app()->createUrl('bug/'));
				}
				if(!empty($_POST['BugForm']['labels']))
					$_POST['BugForm']['label_id']= $_POST['BugForm']['labels'][0];
				if(!empty($_POST['BugForm']['assignees']))
					$_POST['BugForm']['user_id']= $_POST['BugForm']['assignees'][0];

                //set 'New' status by default
                $openStatus = Status::model()->find(
                    'label=:label AND company_id=:company_id',
                    array(':label'=>'New', 'company_id'=>Company::current())
                );
                if (!empty($openStatus)){
                    $model->status_id = $openStatus->status_id;
                }

				$hasBugCreated = $this->createBug($model, $project);
				if($hasBugCreated){
                    $this->updateRelatedToBug($model, $form);
                    $ticket = $this->loadModel($model->number);

                    $changeLog = new BugChangelog();
                    $changes[0]['field'] = 'created_at';
                    $changes[0]['value'] = Helper::formatDate12();
                    $changeLog->populateChanges($ticket, $changes);
                    $changeLog->save();

                    Notificator::newBug($ticket);

                    if(strstr(Yii::app()->request->getUrlReferrer(), '/new')){
                        $result = htmlspecialchars(json_encode(array(
                            'ticketNumber'=>$model->number,
                            'redirect'=>Yii::app()->createAbsoluteUrl('/bug', array('completed_step_3'=>1)))), ENT_NOQUOTES);
                    }
                    else{
                        $result = htmlspecialchars(json_encode(array('ticketNumber'=>$model->number)), ENT_NOQUOTES);
                    }
                    echo $result; // it's array
                }
			}
            else{
                throw new CHttpException(400, 'Invalid request.');
            }
		}
       // $this->redirect(Yii::app()->createUrl('bug/'));
        Yii::app()->end();
    }

    /*
     * @param $updateOnly string. Default - false: update both assignees and labels,
     *                                    'label': update labels only
     *                                    'assignee' update assignees only
     * */
	protected function updateRelatedToBug(BugBase $model, BugForm $form, $updateOnly = false) {
		$cmd=Yii::app()->db->createCommand();
		$cmdParams=array(':bug_id'=>$model->id);
		$delLabelsSql='DELETE FROM {{bug_by_label}} WHERE bug_id=:bug_id';
		$delAssigneesSql='DELETE FROM {{bug_by_user}} WHERE bug_id=:bug_id';
        if ($updateOnly != 'assignee')
		    $cmd->setText($delLabelsSql)->execute($cmdParams);		//	delete existing labels
        if ($updateOnly != 'label')
		    $cmd->setText($delAssigneesSql)->execute($cmdParams);	//	delete existing assignees
		$getSqlValues=function(array $IDs) {
			$values=array();
			foreach($IDs as $id)
				$values[]='(:bug_id,'.(int)$id.')';
			return $values;
		};
        if ($updateOnly != 'assignee'){
            $values=$getSqlValues($form->labels);
            if(!empty($values)) {
                $sql='INSERT INTO {{bug_by_label}} VALUES'
                    .implode(',', $values);
                $cmd->setText($sql)->execute($cmdParams);			//	Insert the labels that has been set
            }
            $model->label_set = CJSON::encode($form->labels);
            $model->save();
        }
        if ($updateOnly != 'label'){
            $values=$getSqlValues($form->assignees);
            if(!empty($values)) {
                $sql='INSERT INTO {{bug_by_user}} VALUES'
                    .implode(',', $values);
                $cmd->setText($sql)->execute($cmdParams);			//	Insert the assignees that has been set
            }
            $model->user_set = CJSON::encode($form->assignees);
            $model->save();
        }
	}
	
	protected function createBug(BugBase $model, Project $project) {
        $transactionResult = false;
        $model->getDbConnection()->setAutoCommit(false);
		$transaction = $model->getDbConnection()->beginTransaction();
		try {
			$model->attributes = $_POST['BugForm'];
            $this->prepareTitleAndDescription($model);
			$globalPrevBug = null;
			/*$prevBug = Bug::model()->resetScope()->find(
				'project_id=:project_id AND next_number=0',
				array(':project_id'=>$project->project_id)
			);*/
            $prevBug = Bug::model()->resetScope()->findBySql(
                'SELECT * FROM {{bug}} WHERE 1
                    AND next_number = :zero
                    AND project_id = :project_id
                    FOR UPDATE',
                array(
                    ':zero'=>0,
                    ':project_id'=>$project->project_id
                ));
			$model->project_id = $project->project_id;
			$model->next_id = 0;
			$model->next_number = 0;
			if(empty($prevBug)) {
				/*$globalPrevBug = Bug::model()->resetScope()->find('next_id=0');*/
                $globalPrevBug = Bug::model()->resetScope()->findBySql(
                'SELECT * FROM {{bug}} WHERE 1
                    AND next_id = :zero
                    FOR UPDATE',
                array(
                    ':zero'=>0,
                ));
				$model->prev_id = empty($globalPrevBug) ? 0 : $globalPrevBug->id;
				$model->prev_number = 0;
				$model->number = 1;
                $model->priority_order = 1;
			} else {
				$model->prev_id = $prevBug->id;
				$model->prev_number = $prevBug->number;
				$model->number = $prevBug->number + 1;
                $model->priority_order = $prevBug->id + 1;
			}
			if($model->save()) {
				if(!empty($prevBug)) {
					$prevBug->next_id = $model->id;
					$prevBug->next_number = $model->number;
					if(!$prevBug->save())
						throw new Exception('Previous bug has not been saved');
				}
				if(!empty($globalPrevBug)) {
					$globalPrevBug->next_id = $model->id;
					if(!$globalPrevBug->save())
						throw new Exception('Previous bug has not been saved');
				}
				Notificator::newBug($model);	//send notification
			} else {
				throw new Exception('New bug has not been saved');
            }
			$transaction->commit();
			$transactionResult = true;
		} catch(Exception $e) {
			$transaction->rollback();
		}
        $model->getDbConnection()->setAutoCommit(true);
        return $transactionResult;
	}

    protected function prepareTitleAndDescription(BugBase $model)
    {
        $p = new CHtmlPurifier($this);
        $ticket = $p->purify($_POST['BugForm']['description']);
        $titleLength = 60;
        if(strlen($ticket) > $titleLength) {
           $title = Helper::neatTrim($ticket, $titleLength, '');
           $newLineIndex=strpos($title, "\n\n");
           if($newLineIndex)
               $title = substr($title, 0, $newLineIndex);
           $model->description = str_replace($title, "", $ticket);
           $model->title = $title . '&#133;';
        }
        else{
            $model->title =  $ticket;
            $model->description = $ticket;
        }
    }

    protected function onBugUpdateSuccess(Bug $model) {
        $user = User::current();
        // Ticket update return page is set on settings page.
        // By default it's home page
        if($user->ticket_update_return == 1) {
            // return to home page
            $this->redirect(Yii::app()->createUrl('bug/'));
        } else if($user->ticket_update_return == 2) {
            //stay on same ticket view page
            $this->redirect(
                Yii::app()->createUrl('bug/view/',array('id'=>$model->number)));
        }
    }
    
    public function updateBug(Bug $bugModel,
            $formId = null, $onSuccess = null) {
        $temp = $bugModel; // Save old state bug model before changes
        $tempData['user'] = $temp->user;
        $tempData['label'] = $temp->label;
        $_POST['BugForm']['title']='t';
        $model = Bug::model()->resetScope()->findByPk($bugModel->id);
		$form = $this->bugForm = new BugForm(BugForm::SCENARIO_EDIT);
        if(!empty($formId)) {
            $this->performAjaxValidation($form, $formId);
        }
        if(isset($_POST['BugForm'])) {
			$form->setAttributes($_POST['BugForm']);
			if($form->validate()) {
				$model->attributes = $form->getAttributes();
                $this->prepareTitleAndDescription($model);
				if ($model->save()) {
					$this->updateRelatedToBug($model, $form);
					$changes = $this->getTicketChanges($temp, $model, $tempData);
					if (is_array($changes) && !empty($changes)) {
						$changeLog = new BugChangelog();
						$changeLog->populateChanges($model, $changes);
						$changeLog->save();
						Notificator::updateBug($model, $changes);
					}
                    //case when user changed project in another tab
                    //and then returned to previous tab and updated the ticket
                    User::updateCurrentProject($model->project_id);
                    $bugModel = $model;
                    if(!empty($onSuccess)) {
                        call_user_func($onSuccess, $model);
                    }
				}
			}
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $bugModel = $this->bug =  Bug::model()->resetScope()->findByPk($id);
        $this->updateBug($bugModel, 'bug-update-form',
            array($this, 'onBugUpdateSuccess'));
        Yii::app()->end();
    }

    protected function getTicketChanges(BugBase $oldModel, BugBase $model, array $oldData = null)
    {
       $changesArray = array_diff_assoc($model->getAttributes(), $oldModel->getAttributes());

        if (!empty($oldData)){
            //changes in assignees
            $assignees = $model->user;
            if (isset($oldData['user'])&&is_array($oldData['user'])){
                $usersOld = $users = array();
                foreach ($oldData['user'] as $usr){
                    $usersOld[$usr->user_id] = $usr->name;
                }
                foreach ($assignees as $usr){
                    $users[$usr->user_id] = $usr->name;
                }
                if (!empty($users) && $users !== $usersOld ){
                    //new assignee added
                     $changesArray['user_id'] = $users;
                }
                elseif(empty($users) && $users !== $usersOld ){
                    //assignee removed
                    $changesArray['user_id'] = array('0'=>0);
                }
            }
            //changes in labels
            $types = $model->label;
            if (isset($oldData['label'])&&is_array($oldData['label'])){
                $labelsOld = $labels = array();
                foreach ($oldData['label'] as $lbl){
                    $labelsOld[$lbl->label_id] = $lbl->name;
                }
                foreach ($types as $lbl){
                    $labels[$lbl->label_id] = $lbl->name;
                }
                if (!empty($labels) && $labels !== $labelsOld ){
                     $changesArray['label_id'] = $labels;
                }
                elseif(empty($labels) && $labels !== $labelsOld ){
                    //labels removed
                    $changesArray['label_id'] = array('0'=>0);
                }
            }
        }

       if(!empty($changesArray)) {
            $i=0;
			$changes=array();
            foreach($changesArray as $k=>$v) {
                if($k == 'user_set' || $k == 'label_set') //skip these fields
                    continue;

                if (!empty($v)){
                    $changes[$i] = array(
                        'field'=>$k,
                        'name'=>$model->getAttributeLabel($k),
                        'value'=>$v
                    );
                    if($k == 'status_id')
                        $changes[$i]['value'] = Status::getNameById($v);
                    $i++;
                }
            }
            return $changes;
       }
       else{
            return false;
       }

    }

	protected function getProjectBugByNumber($bugNumber, $projectID) {
        return Bug::model()->resetScope()->findBySql(
            'SELECT * FROM {{bug}} WHERE 1
                AND number = :number
                AND project_id = :project_id
                FOR UPDATE',
            array(
                ':number'=>$bugNumber,
                ':project_id'=>$projectID,
            )
        );
	}

	protected function tranFault() {
		throw new CException('Transaction failed.');
	}
	
	/**
     * Deletes a selected comment.
     * If deletion is successful, clearing selected comment from page.
     * @param integer $id the ID of the model to be deleted
     */
/*    public function actionDeleteComment($id)
    {
        if (Yii::app()->request->isPostRequest) {
            try{
                $oComment = Comment::model()->resetScope()->findByPk($id);
                if( empty($oComment) ){
	                    $sErrEcho = 'There is no comment with ID ' . $id;
	            } elseif( Yii::app()->user->id == $oComment->user_id ){
	                $oComment->delete();
	            } else {
	            	$sErrEcho = 'You have no rights for this action.';
	            }
            } catch(CException $ex) {
				 $sErrEcho = 'Comment deleting failed...';
			}
            
            if( !isset($sErrEcho) ){
				echo json_encode(array("status" => 200));
			} else{
				echo json_encode(array("status" => $sErrEcho));
			}
        }
    }*/
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            Bug::model()->getDbConnection()->setAutoCommit(false);
			$tran=Bug::model()->getDbConnection()->beginTransaction();
			try{
                //$bug=$this->loadModel($id);
                $bug=Bug::model()->resetScope()->findByPk($_GET['id']);
                if(empty($bug))
                    $this->_404('There is no bug with number '.$id);
                $bug_id=$bug->id;
                $projectID=$bug->project_id;
                //$project=Project::getCurrent();
				$prevBug=$this->getProjectBugByNumber(
					$bug->prev_number,
					$bug->project_id
				);
				$nextBug=$this->getProjectBugByNumber(
					$bug->next_number,
					$bug->project_id
				);
				if(!empty($prevBug) && !empty($nextBug)) {
					$prevBug->next_id=$nextBug->id;
					$prevBug->next_number=$nextBug->number;
					$nextBug->prev_id=$prevBug->id;
					$nextBug->prev_number=$prevBug->number;
				}
				if(empty($prevBug) && !empty($nextBug)) {
					$nextBug->prev_id=0;
					$nextBug->prev_number=0;
				}
				if(!empty($prevBug) && empty($nextBug)) {
					$prevBug->next_id=0;
					$prevBug->next_number=0;
				}
				if(!empty($nextBug) && !$nextBug->save())
					$this->tranFault();
				if(!empty($prevBug) && !$prevBug->save())
					$this->tranFault();
				if(!$bug->delete())
					$this->tranFault();
				$delCondition='bug_id=:bug_id';
				$delParams=array(':bug_id'=>$bug_id);
				BugByLabel::model()->deleteAll($delCondition, $delParams);
				BugByUser::model()->deleteAll($delCondition, $delParams);
				$tran->commit();
			} catch(CException $ex) {
				$tran->rollback();
				$this->_404('Bug deleting failed...');
			}
            Bug::model()->getDbConnection()->setAutoCommit(true);

            //case when user changed project in another tab
            //and then returned to previous tab and updated the ticket
            if(isset($projectID)) {
                User::updateCurrentProject($projectID);
            }
            
            // if AJAX request (triggered by deletion via admin grid view),
            // we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(
                    isset($_POST['returnUrl']) 
                        ? $_POST['returnUrl']
                        : array('index'));
            }
        } else {
            throw new CHttpException(400, 
                'Invalid request. Please do not repeat this request again.');
        }
    }
    
    /**
     * Lists all models.
     */
    public function actionIndex()
    {
		$project = Project::getCurrent();
		if(empty($project))
			$this->redirect($this->createUrl('/project/index'));

        if(!Yii::app()->request->isAjaxRequest){
            $baseUrl = Yii::app()->assetManager->publish('protected/extensions/EAjaxUpload/assets');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/fileuploader.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerCssFile($baseUrl.'/fileuploader.css');
            //Edit Project Form
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl . '/js/project/index/common.js'
            );
        }

        /*calendar view*/
        if ($this->request->getParam('show') == 'calendar'){
            $this->layout = 'column1';
            $bugs = Project::getBugs();
            $bugsArray = array();
            if(!empty($bugs)) {
                foreach($bugs as $key=>$bug) {
                    $bugsArray[$key]['id'] = $bug->id;
                    $bugsArray[$key]['title'] = Helper::truncateString('#' . $bug->number .' '. $bug->title, 25, '', '...') ;
                    $bugsArray[$key]['start'] = $bug->duedate;
                    $bugsArray[$key]['url'] = $this->createUrl('bug/view',array('id'=>$bug->number));
                }
            }

            MixPanel::instance()->registerEvent(MixPanel::CALENDAR_PAGE_VIEW); // MixPanel events tracking
            $this->render('/site/calendar', array('bugs'=>$bugsArray));
            Yii::app()->end();
        }
        /*END of calendar view*/

		$filterText = $this->request->getParam('filterText');
        //get search widget data
        if (is_array($filterText)){
            BugFilter::setFilterState($filterText);
        }

        $filter = BugFilter::getFilterState();

        //check if user selected Saved Search
        if (isset($filter['filter']) && is_array($filter['filter'])){
            foreach ($filter['filter'] as $value){
                if ((int) $value > 0)
                    $filterID = (int) $value;
            }
        }
        if (isset($filterID) && $filterID > 0){
            $userFilter = Filter::model()->find('user_id=:user_id AND filter_id=:filter_id',
                           array('user_id'=>User::getId(), 'filter_id'=>$filterID) );
            if ($userFilter){
                $filter = unserialize($userFilter->filter);
            }
        }

        //search by keyword
        if(is_string($filterText)){
            $this->textSearch = $filterText;
            Yii::app()->user->setState('searchKeyword', $filterText);
        }
        elseif(Yii::app()->user->getState('searchKeyword')){
            $this->textSearch = Yii::app()->user->getState('searchKeyword');
        }

        //get filter widget data
        $statusFilter = $labelFilter = $archiveFilter = $userFilter = $groupFilter = array();
        $statusNegativeFilter = $labelNegativeFilter = $userNegativeFilter = $groupNegativeFilter = array();
        if(!empty($filter['status']))
            $statusFilter = $filter['status'];
        if(!empty($filter['label']))
            $labelFilter = $filter['label'];
        if(!empty($filter['user']))
            $userFilter = $filter['user'];
        if(!empty($filter['group']))
            $groupFilter = $filter['group'];
        if(!empty($filter['status-negative']))
            $statusNegativeFilter = $filter['status-negative'];
        if(!empty($filter['label-negative']))
            $labelNegativeFilter = $filter['label-negative'];
        if(!empty($filter['user-negative']))
            $userNegativeFilter = $filter['user-negative'];
        if(!empty($filter['group-negative']))
            $groupNegativeFilter = $filter['group-negative'];

        if($this->currentView == 'closed')
            $archiveFilter = array('1'=>1);

        $archiveFilter = array_diff($archiveFilter, array(''));
        $statusFilter = array_diff($statusFilter, array(''));
        $labelFilter = array_diff($labelFilter, array(''));
        $userFilter = array_diff($userFilter, array(''));
        $groupFilter = array_diff($groupFilter, array(''));
        $statusNegativeFilter = array_diff($statusNegativeFilter, array(''));
        $labelNegativeFilter = array_diff($labelNegativeFilter, array(''));
        $userNegativeFilter = array_diff($userNegativeFilter, array(''));
        $groupNegativeFilter = array_diff($groupNegativeFilter, array(''));

        if (!empty($groupFilter)){
            $usersByGroup = UserGroup::getUserIDsByGroupIDs($groupFilter);
            if (!empty($usersByGroup)){
                $userFilter = array_merge($userFilter, $usersByGroup);
            }
        }

        if (!empty($groupNegativeFilter)){
            $usersByGroupNegative = UserGroup::getUserIDsByGroupIDs($groupNegativeFilter);
            if (!empty($usersByGroupNegative)){
                $userNegativeFilter = array_merge($userNegativeFilter, $usersByGroupNegative);
            }
        }

        $criteria = new CDbCriteria();
        $criteria->condition = 'project_id=' . $project->project_id;
        if(!empty($statusFilter))
            $criteria->addInCondition('t.status_id', $statusFilter);
        if(!empty($statusNegativeFilter))
            $criteria->addNotInCondition('t.status_id', $statusNegativeFilter);
        if(!empty($archiveFilter))
            $criteria->condition .= ' AND isarchive=1';

        //add criteria from search widget
        if(!empty($this->textSearch)){
            $criteria->condition .=  " AND (title LIKE '%" . $this->textSearch . "%' OR description LIKE '%". $this->textSearch . "%' OR number LIKE '%". $this->textSearch . "%')";
        }
        $criteria->order = 'priority_order ASC';
        //$criteria->order = 'duedate';

        $createdWithAPI = '';
        if (!empty($labelFilter) || !empty($labelNegativeFilter)){
            //Filter API tasks
            if (isset($labelFilter[0]) || isset($labelNegativeFilter[0])){
                if ( count($labelFilter)>1 || count($labelNegativeFilter)>1 ){
                    $createdWithAPI = ' OR t.is_created_with_api=1';
                }
                elseif(isset($labelFilter[0])){
                    $criteria->condition .= ' AND t.is_created_with_api=1';
                }
                elseif(isset($labelNegativeFilter[0])){
                    $criteria->condition .= ' AND t.is_created_with_api!=1';
                }
                unset($labelFilter[0]);
                unset($labelNegativeFilter[0]);
            }

            if(!empty($labelFilter) && !empty($labelNegativeFilter)){
                $labelsSQL = '(label.label_id IN('.implode(',', $labelFilter).') AND
                    label.label_id NOT IN('.implode(',', $labelNegativeFilter).')';
            }
            elseif(!empty($labelFilter) && empty($labelNegativeFilter)){
                $labelsSQL = '(label.label_id IN('.implode(',', $labelFilter).')';
            }
            elseif(empty($labelFilter) && !empty($labelNegativeFilter)){
                $labelsSQL = '(label.label_id NOT IN('.implode(',', $labelNegativeFilter).')';
            }
        }

        //Filter unassigned tickets and tickets with assignees.
        if (!empty($userFilter) || !empty($userNegativeFilter)){
            if (isset($userFilter[0])){
                if ( count($userFilter)>1 && !empty($userNegativeFilter) ){
                    unset($userFilter[0]);
                    $usersSQL = '(user.user_id IN('.implode(',', $userFilter).') AND
                        user.user_id NOT IN('.implode(',', $userNegativeFilter).')) OR user.user_id IS NULL';
                }
                elseif( count($userFilter)>1 && empty($userNegativeFilter) ){
                    unset($userFilter[0]);
                    $usersSQL = 'user.user_id IN('.implode(',', $userFilter).') OR user.user_id IS NULL';
                }
                elseif(count($userFilter)<=1 && !empty($userNegativeFilter)){
                    unset($userFilter[0]);
                    $usersSQL = 'user.user_id NOT IN('.implode(',', $userNegativeFilter).') OR user.user_id IS NULL';
                }
                else{
                    $usersSQL = 'user.user_id IS NULL';  //unassigned tickets
                }
            }
            elseif(isset($userNegativeFilter[0])){
                if ( count($userNegativeFilter)>1 && !empty($userFilter) ){
                    unset($userNegativeFilter[0]);
                    $usersSQL = '(user.user_id NOT IN('.implode(',', $userNegativeFilter).') AND
                        user.user_id IN('.implode(',', $userFilter).')) OR user.user_id IS NOT NULL';
                }
                elseif( count($userNegativeFilter)>1 && empty($userFilter) ){
                    unset($userNegativeFilter[0]);
                    $usersSQL = 'user.user_id NOT IN('.implode(',', $userNegativeFilter).') OR user.user_id IS NOT NULL';
                }
                elseif(count($userNegativeFilter)<=1 && !empty($userFilter)){
                    unset($userNegativeFilter[0]);
                    $usersSQL = 'user.user_id IN('.implode(',', $userFilter).') OR user.user_id IS NOT NULL';
                }
                else{
                    unset($userNegativeFilter[0]);
                    $usersSQL = 'user.user_id IS NOT NULL';  //not unassigned tickets
                }
            }
            elseif(!empty($userFilter) && !empty($userNegativeFilter)){
                $usersSQL = '(user.user_id IN('.implode(',', $userFilter).') AND
                    user.user_id NOT IN('.implode(',', $userNegativeFilter).') )';
            }
            elseif(!empty($userFilter) && empty($userNegativeFilter)){
                $usersSQL = 'user.user_id IN('.implode(',', $userFilter).')';
            }
            elseif(empty($userFilter) && !empty($userNegativeFilter)){
                $usersSQL = 'user.user_id NOT IN('.implode(',', $userNegativeFilter).') OR user.user_id IS NULL'; //show unassigned tickets for negative filter too
            }
        }

        $statusCondition = empty($statusFilter)
            ? 'is_visible_by_default = 1 OR t.status_id = 0'
            : '';
		$criteria->with=array(
			'status'=>array(
                'condition'=>$statusCondition,
            ),
            'label'=>!isset($labelsSQL)
				? array()
				: array('condition'=>$labelsSQL.' '.$createdWithAPI.' )', 'together'=>true),
			'user'=>!isset($usersSQL)
				? array()
				: array('condition'=>$usersSQL, 'together'=>true),
            'user',
			'company',
			'commentCount',
		);

        /* Exclude tickets if they has params from the negative filter*/
        if(!empty($labelNegativeFilter)) {
            $criteria->addCondition('
                t.id NOT IN (
                    SELECT bug_id FROM {{bug_by_label}}
                        WHERE label_id IN (
                            ' . implode(',', $labelNegativeFilter) .'
                        )
                    )
            ');
        }
        if(!empty($userNegativeFilter)) {
            $criteria->addCondition('
                t.id NOT IN (
                    SELECT bug_id FROM {{bug_by_user}}
                        WHERE user_id IN (
                            ' . implode(',', $userNegativeFilter) .'
                        )
                    )
            ');
        }
        /* END of Exclude tickets if they has params from the negative filter*/

		$criteria->distinct=true;
		$criteria->together=false;

        //create pagination
        if(empty($archiveFilter))
            $pages = new CPagination(Bug::model()->currentCompany()->count($criteria));
        else
            $pages = new CPagination(Bug::model()->resetScope()->currentCompany()->count($criteria));

		$user = User::current();
		//tickets per page is set on settings page, by default, it's 30
        $pages->pageSize = $user->tickets_per_page;

        $pages->applyLimit($criteria);

        //create model
        if(empty($archiveFilter))
            $bugFinder = Bug::model();//$bugFinder = Bug::model()->currentCompany();
        else
            $bugFinder = Bug::model()->resetScope();//$bugFinder = Bug::model()->currentCompany();

		$model = new CActiveDataProvider(
			$bugFinder,
			array(
				'criteria'=>$criteria,
				'pagination'=>$pages,
			)
		);
		
        if (Yii::app()->request->isAjaxRequest){
         // $this->renderPartial('_bugGrid', array(
            $this->renderPartial('_bugList', array(
                'model' => $model,
                'pages' => $pages,
                'currentView'=>$this->currentView,
                'textForSearch'=>$this->textSearch,
            ));
            Yii::app()->end();
            //$this->renderPartial('_bugPagination', array('pages'=>$pages));
        }

        $this->clientScript->registerScript(
            __CLASS__.'#disable_selection',
            '$("body").attr("unselectable", "on").addClass("unselectable");',
            CClientScript::POS_READY
        );

        if($this->currentView == 'closed')
            MixPanel::instance()->registerEvent(MixPanel::CLOSED_TICKETS_PAGE_VIEW); // MixPanel events tracking
        else
            MixPanel::instance()->registerEvent(MixPanel::TICKETS_LIST_PAGE_VIEW); // MixPanel events tracking

        if(Yii::app()->request->getParam('completed_step_3'))
            MixPanel::instance()->registerEvent(MixPanel::COMPLETE_STEP, array('step' => 3)); // MixPanel events tracking
        elseif(Yii::app()->request->getParam('skip_step_3'))
            MixPanel::instance()->registerEvent(MixPanel::SKIP_STEP, array('step' => 3)); // MixPanel events tracking

        $this->render('index', array(
            'project'=>$project,
            'model' => $model,
            'pages' => $pages,
            'currentView'=>$this->currentView,
            'textForSearch'=>$this->textSearch,
        ));
    }

    public function actionClosed()
    {
        $this->currentView = 'closed';
        $this->actionIndex();
    }

    /**
     * Manages all models.
     */
/*    public function actionAdmin()
    {
        $model = new Bug('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Bug']))
            $model->attributes = $_GET['Bug'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }*/

    /**
     * Returns the data model based on the number of bug given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
		$project=Project::getCurrent();
		if(empty($project))
			$this->_404('The requested page does not exist.');
        $model = Bug::model()->resetScope()->find(
			'number=:number AND project_id=:project_id',
			array(
				':number'=>(int)$id,
				':project_id'=>$project->project_id
			)
		);
        if(empty($model))
            $this->_404('The requested page does not exist.');

        return $model;
    }

    public function actionUpdateDuedate()
    {
        if (Yii::app()->request->isAjaxRequest && !empty($_POST['id'])) {
            $bug = Bug::model()->findByPk((int) $_POST['id']);
            if (!empty($bug->duedate)) {
                $dayDelta = (int) $_POST['dayDelta'];
                $newDate = strtotime($bug->duedate) + $dayDelta * 24 * 60 * 60; //24*60*60 second in day
                $bug->duedate = date('Y-m-d', $newDate);
                $bug->save();
            }
        }
    }

    public function actionPaginationUpdate()
    {
        $archived = $this->request->getParam('archivedView');

        if (Yii::app()->request->isAjaxRequest) {

            if (!empty( $archived ) ) {
                $this->currentView = 'closed';
            }

            $project = Project::getCurrent();

            $filterText = $this->request->getParam('filterText');
            //get search widget data
            if (is_array($filterText)){
                BugFilter::setFilterState($filterText);
            }

            $filter = BugFilter::getFilterState();

            if(is_string($filterText)){
                $this->textSearch = $filterText;
            }

            // $filter = empty($_POST['filterText']) ? '' : $_POST['filterText'];
            $criteria = new CDbCriteria();
            //$criteria->compare('company_id', Yii::app()->user->company_id);
            $criteria->condition = 'project_id=' . $project->project_id;
            //add criteria from search widget
            if(!empty($this->textSearch)){
                $criteria->condition .=  " AND (title LIKE '%" . $this->textSearch . "%' OR description LIKE '%". $this->textSearch . "%')";
            }

            if ( $this->currentView == 'closed' ){
                $criteria->condition = 'isarchive=1';
                $pages = new CPagination(Bug::model()->resetScope()->currentCompany()->count($criteria));
            }
            else{
                $pages = new CPagination(Bug::model()->currentCompany()->count($criteria));
            }

            $pages->pageSize = 30;
            $pages->applyLimit($criteria);
            $pages->params = array('filterText' => $filter);
            $pages->route = '/bug/'. $this->currentView;

            //$model = Bug::model()->currentCompany()->findAll($criteria);
            //$model = new CArrayDataProvider($model);

            $this->renderPartial('_bugPagination', array('pages'=>$pages));
            Yii::app()->end();
        }
    }

    public function actionGetBugById()
    {
        if (Yii::app()->request->isAjaxRequest && !empty($_GET['id'])) {
            $this->bug = Bug::model()->resetScope()->findByPk($_GET['id']);
            $this->initBugForm();
            $this->layout = 'layout';         
            $this->render('_form', array('model'=>$this->bugForm));
            Yii::app()->end();
        }
    }
    
    /**
     * Move ticket to the archive
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionSetArchived($id)
    {
        //$model = $bug=$this->loadModel((int) $id);
        $model = Bug::model()->resetScope()->findByPk($id);
        if($model){
            if ($model->isarchive == 1){
                $model->isarchive = NULL;
                $model->archiving_date = NULL;
                $changes = array(0=>array('field'=>'archived', 'value'=>0));
            }
            else{
                $model->isarchive = 1;
                $model->archiving_date = new CDbExpression('NOW()');
                $changes = array(0=>array('field'=>'archived', 'value'=>1));
            }
            
            if ($model->save()){
                if(isset($changes) && !empty($changes)){
                    $changeLog = new BugChangelog();
                    $changeLog->populateChanges($model, $changes);
                    $changeLog->save();
                    Notificator::updateBug($model, $changes);
                }
                //case when user changed project in another tab
                //and then returned to previous tab and updated the ticket
                User::updateCurrentProject($model->project_id);
            }
        }
        $this->redirect(Yii::app()->createUrl('bug/'));
        Yii::app()->end();
    }

/*    public function actionSetPriority($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            $model = Bug::model()->resetScope()->findByPk($id);
            if($model){
                if ($model->priority == 1){
                    $model->priority = 0;
                    $changes = array(0=>array('field'=>'priority', 'value'=>0));
                }
                else{
                    $model->priority = 1;
                    $changes = array(0=>array('field'=>'priority', 'value'=>1));
                }

                if ($model->save()){
                    $changeLog = new BugChangelog();
                    $changeLog->populateChanges($model, $changes);
                    $changeLog->save();
                }
            }
        }
        Yii::app()->end();
    }*/

    public function actionGetDuplicateFormByBugId()
    {
        if (Yii::app()->request->isAjaxRequest && !empty($_GET['id'])) {
            //$model = $this->loadModel($_GET['id']);
            $model = Bug::model()->resetScope()->findByPk($_GET['id']);
            if(!empty($model)){
                User::updateCurrentProject($model->project_id);
                $form=new DuplicateForm();
                $form->setAttributes($model->attributes);
                $this->layout = 'layout';
                $this->render('_duplicateForm', array('model'=>$form));
                Yii::app()->end();
            }
            throw new CHttpException(400, 'Invalid request.');
        }
    }

    /**
     * Duplicate tickets.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the Number of the model to be updated
     */
    public function actionDuplicate($id)
    {
        //save old state bug model before changes
       // $temp = $this->loadModel($id);
        $temp = Bug::model()->resetScope()->findByPk($id);
       // $model = $this->loadModel($id);
        $model = clone $temp;
		$form=new DuplicateForm();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'bug-duplicate-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }
        if (isset($_POST['DuplicateForm'])) {
			$form->setAttributes($_POST['DuplicateForm']);
			if($form->validate()) {
				$model->attributes=$form->getAttributes();
				if ($model->save()) {
                    if(empty($model->duplicate_number))
                        $model->duplicate_number = -1; //removed duplicate state flag

					$changes = $this->getTicketChanges($temp, $model);
					if (is_array($changes)) {
						$changeLog = new BugChangelog();
						$changeLog->populateChanges($model, $changes);
						$changeLog->save();
						Notificator::updateBug($model, $changes);
					}
                    if($model->duplicate_number >0)
                         Yii::app()->user->setFlash('success', "Merged!");
                    elseif($temp->duplicate_number>0 && $model->duplicate_number==0)
                        Yii::app()->user->setFlash('success', "Duplicate status removed.");

                    //case when user changed project in another tab
                    //and then returned to previous tab and updated the ticket
                    User::updateCurrentProject($model->project_id);

					$this->redirect(Yii::app()->createUrl('bug/view', array('id'=>$model->number)));
				}
			}
        }
        Yii::app()->end();
    }

/*
    Drag and Drop actions:
*/        
    public function actionDndPrioritySort()
    {
        if (Yii::app()->request->isAjaxRequest){

            //old ticket position
            $element = (int) $this->request->getParam('elem');
            //new position of next ticket
            $nextElement = (int) $this->request->getParam('nextElem');
            //new position of prev ticket
            $prevElement = (int) $this->request->getParam('prevElem');
            //if current ticket is last now
            if (!$nextElement) $nextElement = $prevElement + 1;

            if( $element > $nextElement ){
                /* element was dragged up */

                //new position of the ticket
                $currentPosition = $nextElement;

                //saving temporary position of the ticket
                $ticket = Bug::model()->findByAttributes(array('priority_order'=>$element));
                if ($ticket){
                    $ticket->priority_order = 0;
                    $ticket->save();
                }

                //updating tickets that are below the new position of the ticket
                for($current = $currentPosition; $current < $element; $current++ ){
                    $ids[] = $current;
                }
                if (isset($ids) && is_array($ids)){
                    $cmd=Yii::app()->db->createCommand();
                    $sql='UPDATE {{bug}} SET priority_order=priority_order+1 WHERE priority_order IN ('.implode($ids, ',').')';
                    $cmd->setText($sql)->execute();
                }

                //saving new position of the ticket
                $ticket = Bug::model()->findByAttributes(array('priority_order'=>0));
                if ($ticket){
                    $ticket->priority_order = $currentPosition;
                    $ticket->save();
                }
            }
            else{
                /* element was dragged down */

                //new position of the ticket
                $currentPosition = $prevElement;

                //saving temporary position of the ticket
                $ticket = Bug::model()->findByAttributes(array('priority_order'=>$element));
                if ($ticket){
                    $ticket->priority_order = 0;
                    $ticket->save();
                }

                //updating tickets that are above the new position of the ticket
                for($current = $element+1; $current <= $currentPosition; $current++ ){
                    $ids[] = $current;
                }
                if (isset($ids) && is_array($ids)){
                     $cmd=Yii::app()->db->createCommand();
                     $sql='UPDATE {{bug}} SET priority_order=priority_order-1 WHERE priority_order IN ('.implode($ids, ',').')';
                     $cmd->setText($sql)->execute();
                }

                //saving new position of the ticket
                $ticket = Bug::model()->findByAttributes(array('priority_order'=>0));
                if ($ticket){
                    $ticket->priority_order = $currentPosition;
                    $ticket->save();
                }
            }
			InstantMessage::instance()->sendToAll(
				Project::getCurrent()->project_id,
				MessageType::TICKETS_ORDER_CHANGED
			);
            Yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request.');
    }

    public function actionDndAddLabel()
    {
        $ticketIDs = $this->request->getParam('elemID');
        $labelID = (int) $this->request->getParam('labelID');
        if(Yii::app()->request->isAjaxRequest
                && !empty($ticketIDs)
                && $labelID > 0) {

            $tickets = Bug::model()->findAllByPk($ticketIDs);
            foreach($tickets as $ticket) {
                $tempData['label'] = $ticket->label;
                $ticketLabels = $ticket->label;

                if (is_array($ticketLabels)){
                    foreach($ticketLabels as $label){
                        $labelsArr[] = $label->label_id;
                    }
                    //if label already exists - delete it
                    if (!empty($labelsArr) && in_array($labelID, $labelsArr)){
                        unset($labelsArr[array_search($labelID,$labelsArr)]);
                        $labelsArr = array_values($labelsArr);
                    }
                    else{
                        //else add new label
                        $labelsArr[] = $labelID;
                    }
                }
                else{
                    $labelsArr[] = $labelID;
                }

                //saving new labels
                $form = new BugForm(BugForm::SCENARIO_EDIT);
                $form->labels = $labelsArr;

                $this->updateRelatedToBug($ticket, $form, 'label');
                $ticketUpdated = Bug::model()->findByPk($ticket->id);
                $changes = $this->getTicketChanges($ticket, $ticketUpdated, $tempData);
                if (is_array($changes)) {
                    $changeLog = new BugChangelog();
                    $changeLog->populateChanges($ticketUpdated, $changes);
                    $changeLog->save();
                    Notificator::updateBug($ticketUpdated, $changes);
                }
                unset($labelsArr);
            }

            Yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request.');
    }

    public function actionDndAddUser()
    {
        $ticketIDs = $this->request->getParam('elemID');
        $userID = (int) $this->request->getParam('userID');
        if(Yii::app()->request->isAjaxRequest && !empty($ticketIDs)) {
            $tickets = Bug::model()->findAllByPk($ticketIDs);
            foreach($tickets as $ticket) {
                $tempData['user'] = $ticket->user;

                if ($userID > 0){
                    $ticketUsers = $ticket->user;
                    if (is_array($ticketUsers)){
                        foreach($ticketUsers as $user){
                           $usersArr[] = $user->user_id;
                        }
                        //if user already exists - delete it
                        if (!empty($usersArr) && in_array($userID, $usersArr)){
                            unset($usersArr[array_search($userID,$usersArr)]);
                            $usersArr = array_values($usersArr);
                        }
                        else{
                            //else add new user
                            $usersArr[] = $userID;
                        }
                    }
                    else{
                        $usersArr[] = $userID;
                    }
                }
                elseif($userID == 0){
                    //ticket is unassigned
                    $usersArr = array();
                }

                //saving new users
                $form = new BugForm(BugForm::SCENARIO_EDIT);
                $form->assignees = $usersArr;

                $this->updateRelatedToBug($ticket, $form, 'assignee');
                $ticketUpdated = Bug::model()->findByPk($ticket->id);
                $changes = $this->getTicketChanges($ticket,$ticketUpdated, $tempData);

                if (is_array($changes)) {
                    $changeLog = new BugChangelog();
                    $changeLog->populateChanges($ticketUpdated, $changes);
                    $changeLog->save();
                    Notificator::updateBug($ticketUpdated, $changes);
                }
                unset($usersArr);
            }

            Yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request.');
    }

    public function actionDndAddStatus()
    {
        $ticketIDs = $this->request->getParam('elemID');
        $statusID = (int) $this->request->getParam('statusID');
        if(Yii::app()->request->isAjaxRequest 
                && !empty($ticketIDs)
                && $statusID > 0) {

            $tickets = Bug::model()->findAllByPk($ticketIDs);
            foreach($tickets as $ticket) {
                $ticketOld = clone $ticket;
                $ticketStatusID = $ticket->status_id;

                //check if the ticket already has this status then remove it
                if (!empty($ticketStatusID) && $ticketStatusID == $statusID )
                    $ticket->status_id = 0;
                else
                    $ticket->status_id = $statusID;

                //saving ticket
                $ticket->save();
                $changes = $this->getTicketChanges($ticketOld, $ticket);
                if (is_array($changes)) {
                    $changeLog = new BugChangelog();
                    $changeLog->populateChanges($ticket, $changes);
                    $changeLog->save();
                    Notificator::updateBug($ticket, $changes);
                }
            }

            Yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request.');
    }
    
    public function actionGetTicketOrder($id)
    {
      header('Content-type: application/json');
      $id = (int) $id;
      $sql = "SELECT id, priority_order FROM bk_bug WHERE project_id = " . $id . " ORDER BY priority_order";      
      $connection = Yii::app()->db;
      $connection->active = true;
      $command = $connection->createCommand($sql);
      $rows = $command->queryAll();

      echo CJSON::encode($rows);

      Yii::app()->end();
    }
    
    public function actionUpdateTicketOrder()
    {
        if (Yii::app()->request->isAjaxRequest){
            $json = $this->request->getParam('newpositions');
            $new_positions = CJSON::decode($json);

            $sql = 'UPDATE bk_bug SET priority_order = CASE id';
            $idarray = array();
            foreach ($new_positions as $value) {  
                $id = (int) $value['id'];        
                $position = (int) $value['position'];
                array_push($idarray, $id);
                $sql .= ' WHEN '.$id.' THEN '.$position.' ';                
            }
            
            $sql .= ' END WHERE id IN ('.implode(",", $idarray).')';

            $connection = Yii::app()->db;
            $connection->active = true;
            $command = $connection->createCommand($sql);
            $rows = $command->execute();

			InstantMessage::instance()->sendToAll(
				Project::getCurrent()->project_id,
				MessageType::TICKETS_ORDER_CHANGED
			);
            Yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request.');
    }
    
    public function actionSummaryTickets(){
    	$this->layout = '//layouts/column1';
    	 
    	$user    = User::current();
    	$project = Project::getCurrent();
    	 
    	$criteria = new CDbCriteria();
    	$criteria->with = array(
    			'label'   => array(),
    			'user'    => array('condition'=>'user.user_id='.$user->id, 'together'=>true),
    			'project' => array('condition'=>'project.archived=0', 'together'=>true),
    			'company',
    			'commentCount',
    	);
    	$criteria->distinct = true;
    	$criteria->together = false;
    	$criteria->order    = 'project.name ASC';
    	 
    	$pages = new CPagination(Bug::model()->currentCompany()->count($criteria));
    	//tickets per page is set on settings page, by default, it's 30
    	$pages->pageSize = $user->tickets_per_page;
    	$pages->applyLimit($criteria);
    	 
    	//create model
    	$bugFinder = Bug::model()->currentCompany();
    	 
    	$model = new CActiveDataProvider(
    			$bugFinder,
    			array(
    					'criteria'   => $criteria,
    					'pagination' => $pages,
    			)
    	);
    	 
    	//Tickets closed per day
    	$iLastDaysCount = 30;
    	$criteria = new CDbCriteria();
    	$criteria->order = 'date DESC';
    	$criteria->addSearchCondition('t.change', 'closed the ticket at');
    	$criteria->with = array(
    			'user' => array('condition'=>'user.user_id='.$user->id, 'together'=>true),
    	);
    	$last30Days  = date('Y-m-d H:i:s', time() - 3600 * 24 * $iLastDaysCount);
    	$criteria->addCondition("t.date > '". $last30Days . "'");
    
    	$bugChanges = new CActiveDataProvider('BugChangelog', array(
    			'criteria'  => $criteria,
    	));
    	$bugChanges->setPagination(false);
    
    	$arrClosedTickets = array();
    	$iMaxClosed = 0;
    	for($i = $iLastDaysCount; $i >= 1; $i--){
    		$iCount = 0;
    		$dDate = date('Y-m-d', time() - 3600 * 24 * $i);
    		foreach($bugChanges->getData() as $record) {
    			if( date('Y-m-d', strtotime($record->date)) == $dDate )
    				$iCount++;
    		}
    		if( $iMaxClosed < $iCount )
    			$iMaxClosed = $iCount;
    		array_push($arrClosedTickets, array('date' => date('m/d/Y', strtotime($dDate)), 'count' => $iCount));
    	}
    	////////////////////////////////////////////
    	 
    	$this->render('summaryTickets', array(
    			'project'       => $project,
    			'model'         => $model,
    			'bugChanges'    => $arrClosedTickets,
    			'max_closed'    => $iMaxClosed,
    			'pages'         => $pages,
    			'currentView'   => $this->currentView,
    			'textForSearch' => $this->textSearch,
    	));
    }
}
