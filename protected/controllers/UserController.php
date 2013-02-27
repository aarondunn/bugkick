<?php

class UserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    //'index',
                    'view',
                    'create',
                    'update',
                    'admin',
                    'delete',
                    'restore',
                    'Invite',
                    'Reinvite',
                    'getUser',
                    'saveFilter',
                    'deleteFilter',
                    'adminPanel',
                    'adminUserStats',
                    'fiveNewest',
                    'topFiveActive',
                    'AdminUserUpdate',
                    'testConfirmInviteForm',
                    'invitePeople',
                ),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('ConfirmInvite', 'RejectInvite', 'ResetPassword'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('ResetPassword'),
                'users' => array('?'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView() {
        $id = Yii::app()->getRequest()->getParam('id', Yii::app()->user->id);
        $user = $this->loadModel($id);

        $activity = $user->getUserLastActivity($id);

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
        $baseUrl = Yii::app()->request->baseUrl;
        Yii::app()->clientScript->registerScript(
                null, <<<JS
	SyntaxHighlighter.all({toolbar:'false'});
JS
                , CClientScript::POS_END
        );

        if ($id == Yii::app()->user->id) {
            MixPanel::instance()->registerUser($user); // MixPanel events tracking
        } else {
            MixPanel::instance()->registerEvent(MixPanel::PROFILE_PAGE_VIEW, array('User Name' => $user->name . ' ' . $user->lname)); // MixPanel events tracking
        }

        $this->render('view', array(
            'model' => $user,
            'activity' => $activity,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User('registration');

        $userProvider = new CActiveDataProvider('User', array(
                    'criteria' => array(
                        'with' => array(
                            'company' => array(
                                //'select'=>false,
                                //'joinType'=>'INNER JOIN',
                                'condition' => 'company.company_id=' . Company::current(),
                            )
                        )
                    )
                ));

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, 'user-form');

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $password = md5(uniqid(mt_rand(), true));
            $password = substr($password, 20);
            $model->password = Hash::sha256($password . $model->salt());
            //$model->password = bin2hex(mhash(MHASH_SHA256, $password . Yii::app()->params['passwordSalt']));
            $model->randomPassword = $password;
            $model->save();
        }

        if (Yii::app()->request->isAjaxRequest && isset($_POST['ajax'])) {
            $this->renderPartial('application.views.settings._users', array('model' => $userProvider));
        } else {
            $this->redirect(Yii::app()->createUrl('settings/userListing'));
        }
        Yii::app()->end();
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @throws CHttpException
     */
    public function actionUpdate($id) {
        //only admin can update users info
        if (!User::current()->isCompanyAdmin(Company::current()))
            throw new CHttpException(400, 'Invalid request.');

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $form = new UserForm();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-update-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (isset($_POST['UserForm'])) {
            $form->setAttributes($_POST['UserForm']);
            if ($form->validate()) {
                $model->attributes = $_POST['UserForm'];
                if (!empty($_POST['UserForm']['projects'])) {
                    $cmd = Yii::app()->db->createCommand();
                    $cmdParams = array(':user_id' => $model->user_id);
                    $delSql = 'DELETE FROM {{user_by_project}} WHERE user_id=:user_id';
                    $cmd->setText($delSql)->execute($cmdParams); //	delete projects
                    $getSqlValues = function(array $IDs) {
                                $values = array();
                                foreach ($IDs as $id) {
                                    $values[] = '(:user_id,' . (int) $id . ', '
                                            . ($id == User::current()->user_id ? '1' : '0')
                                            . ')';
                                }
                                return $values;
                            };
                    $values = $getSqlValues($form->projects);
                    if (!empty($values)) {
                        $sql = 'INSERT INTO {{user_by_project}} VALUES'
                                . implode(',', $values);
                        $cmd->setText($sql)->execute($cmdParams); //	Insert the labels that has been set
                    }
                }
                UserByCompany::model()->updateAll(
                    array('is_admin'=>($form->is_company_admin)? 1 : 0),
                    'user_id=:user_id AND company_id=:company_id', array(
                        ':user_id'=>$model->user_id,
                        ':company_id'=>Company::current(),
                ));
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', "Saved!");
                    $this->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        Yii::app()->user->setFlash('error', "An error has occurred, please try again.");
        $this->redirect(Yii::app()->user->returnUrl);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $user = $this->loadModel($id);
            if (!empty($user)) {
                UserByProject::model()->deleteAll('user_id=:user_id', array(':user_id' => $id));
                UserByGroup::model()->deleteAll('user_id=:user_id', array(':user_id' => $id));
                BugByUser::model()->deleteAll('user_id=:user_id', array(':user_id' => $id));
                //we keep user info, just set status to STATUS_DELETED
                //UserByCompany::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
                //UserByEmailPreference::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
                //SettingsByUser::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
                //$user->userStatus = User::STATUS_DELETED;
                //
                // Instead of set the global user status to "deleted",
                // just set it to "deleted" for current company only.
                $user->current_project_id = new CDbExpression('NULL');
                $user->setStatusInCompany(User::STATUS_DELETED, Company::current());

                //Check if user was deleted from ALL companies,
                //then we create new company for him
                if (!$user->belongsToCompanies()) {
                    $company = Company::model()->createNew();
                    if ($company) {
                        $company->owner_id = $user->user_id;
                        $company->save();
                        $userByCompany = new UserByCompany;
                        $userByCompany->company_id = $company->company_id;
                        $userByCompany->user_id = $user->user_id;
                        $userByCompany->is_admin = 1; // Make user an admin in his company.
                        if ($userByCompany->save()) {
                            Status::createPresets($company->company_id);
                            Label::createPresets($company->company_id);
                            $user->defaultCompany = $company->company_id;  //needed for UserIdentity
                        }
                    }
                }

                $user->save();
            }
            else
                throw new CHttpException(400, 'Invalid request.');

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(Yii::app()->createUrl('settings/userListing'));
        }
        else
            throw new CHttpException(400, 'Invalid request.');
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionRestore($id) {
        if (User::current()->isAdmin()) {
            // we only allow deletion via POST request
            $user = $this->loadModel($id);
            if (!empty($user)) {
                $user->userStatus = User::STATUS_ACTIVE;
                $user->save();
                $user->setStatusInCompany(User::STATUS_ACTIVE, Company::current());
            }
            else
                throw new CHttpException(400, 'Invalid request.');

            Notificator::restoreUser($user);
            Yii::app()->user->setFlash('success', 'User was successfully restored.');
            $this->redirect(Yii::app()->createUrl('settings/members'));
        }
        else
            throw new CHttpException(403, 'You don\'t have permission to access this page.');
    }

    /**
     * Lists all models.
     */
/*    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }*/

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionInvite() {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'invite-form') {
            $user = new User('invite');
            echo CActiveForm::validate($user);
            Yii::app()->end();
        }
        if (!empty($_POST['User']['email'])) {
            $project = Project::getCurrent();
//            $project = Project::model()->findByPk(
//                (int)$_POST['User']['project']);
            if (empty($project)) {
                throw new CHttpException(404,
                        'Wrong request. Project not found');
            }
            $user = User::model()->findByAttributes(array(
                'email' => $_POST['User']['email']
            ));
            $isAdmin = 0;
            if (User::current()->isCompanyAdmin(Company::current())) {
                if (isset($_POST['User']['isadmin']) && $_POST['User']['isadmin'] == 1)
                    $isAdmin = 1;
            }
            $isUserInvited = false;
            if (empty($user)) {
                $isUserInvited = $this->inviteNewUser($project, $isAdmin);
            } elseif ($user->getStatusInCompany(Company::current()) != -1) {
                //case when user was already invited to this company
                //we just resend an invitation email
                if($user->userStatus == User::STATUS_INVITED){
                    Notificator::newInvite($user);
                    $isUserInvited = true;
                }
                else{
                    //user already a member of the company
                    //we just add him to project
                    $isUserInvited = $user->addToProject($project, $isAdmin);
                }
            } else {
                $isUserInvited = $this->inviteExistingUser($project, $user, $isAdmin);
            }
            if ($isUserInvited) {
                Yii::app()->user->setFlash('success', 'User has been invited.');
            }
        }
        if (strstr(Yii::app()->request->getUrlReferrer(), '/people')) {
            $this->redirect(Yii::app()->createUrl('project/people'));
        } elseif (strstr(Yii::app()->request->getUrlReferrer(), '/new')) {
            Yii::import('application.controllers.NewController');
            NewController::setCurrentStep(3);
            $this->redirect(Yii::app()->createUrl('new/2',array('completed_step_2'=>1)));
        } else {
            $this->redirect(Yii::app()->createUrl('settings/members'));
        }
    }

    protected function inviteExistingUser(Project $project, User $user, $isAdmin = 0) {
        $invite = $user->inviteToProject($project);
        if (empty($invite)) {
            return false;
        }
        $user->addToProject($project, $isAdmin);
        $user->setStatusInCompany(User::STATUS_ACTIVE, $project->company_id);
        Notificator::newInvite($user, $invite);
        return true;
    }

    protected function inviteNewUser(Project $project, $isAdmin = 0) {
        $user = new User('invite');
        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if(empty($user->name) && empty($user->lname))
                $user->name = 'Pending user';
            $password = md5(uniqid(mt_rand(), true));
            $password = substr($password, 20);
            $user->password = $user->hashPassword($password);
            $user->randomPassword = $password;
            $user->userStatus = User::STATUS_INVITED;
            $user->defaultCompany = Company::current();
            $user->inviteToken = $user->generateInviteToken();
            $user->invited_by_id = Yii::app()->user->id;
            $user->isadmin = $isAdmin;
        }
        if ($user->save()){
            $user->addToProject($project, $isAdmin);
            $user->setStatusInCompany(User::STATUS_ACTIVE, $project->company_id);
            Notificator::newInvite($user);
            return true;
        }
        return false;
    }

    public function actionReinvite() {
        if (isset($_GET["id"]) && !empty($_GET["id"])) {
            $model = User::model()->findByPk((int) $_GET["id"]);

            $inviteToken = sha1(uniqid(mt_rand(), true));
            $model->inviteToken = $inviteToken;

            $mailSubject = '[' . Yii::app()->name . '] ';
            $mailSubject .= Yii::app()->user->name . ' invites you to join the team for "' . Yii::app()->user->company_name . '"';
            $mailMessage = $this->renderFile(Yii::getPathOfAlias('application.views.mailTemplate.inviteMember') . '.php', array('token' => $inviteToken), true);
            $headers = "Content-type: text/html; charset=utf-8 \r\n";

            if ($model->save())
                mail($model->email, $mailSubject, $mailMessage, $headers);

            Yii::app()->user->setFlash('success', "Reinvite complete");
            $this->redirect(Yii::app()->createUrl('settings/members'));
            Yii::app()->end;
        }
    }

    public function actionTestConfirmInviteForm() {
        $this->render('setPassword', array('model' => new SetPasswordForm()));
    }

    protected function setNewPassword($user) {
        $hasPassword = $this->session->get('hasPassword');
        if (!empty($hasPassword))
            return null;
        $model = new SetPasswordForm();
        $model->name = $user->name;
        $model->lname = $user->lname;

        $attributes = $this->request->getPost('SetPasswordForm');
        if (!empty($attributes)) {
            $model->attributes = $attributes;
            if ($model->validate()) {
                $user->name = $model->name;
                $user->lname = $model->lname;
                $user->password = $user->hashPassword(
                        $model->password1);
                if ($user->save()) {
                    $this->session->add('hasPassword', true);
                    return $model->password1;
                }
            }
        }
        $this->render('setPassword', array('model' => $model));
        Yii::app()->end();
    }

    public function _404($message = 'The requested page does not exist.') {
        parent::_404($message);
    }

    protected function confirmNewUserInvite($token) {
        $user = User::model()->findByAttributes(array('inviteToken' => $token));
        if (empty($user)) {
            $this->_404();
        }
        $password = $this->setNewPassword($user);
        $user->userStatus = User::STATUS_ACTIVE;
        $user->inviteToken = '';
        if ($user->save())
            $user->setDefaultEmailPreferences();
        //	Send mail:
        /* 			$mailSubject = 'Welcome to ' . Yii::app()->name;
          $mailMessage = $this->renderFile(
          Yii::getPathOfAlias('application.views.mailTemplate.inviteConfirmed') . '.php',
          array('model' => $user),
          true
          );
          //$headers = "Content-type: text/html; charset=utf-8 \r\n";
          $mailer = new SESMail();
          $mailer->send(
          $user->email,
          '',
          $mailSubject,
          $mailMessage
          ); */
        //mail($user->email, $mailSubject, $mailMessage, $headers);
        $userIdentity = new UserIdentity($user->email, $password);
        $userIdentity->authenticate();
        Yii::app()->user->login($userIdentity);
    }

    protected function confirmExistingUserInvite($token, $user_id, $project_id, $company_id) {
        $invite = Invite::model()->findByAttributes(array(
            'token' => $token,
            'user_id' => $user_id,
            'project_id' => $project_id,
            'company_id' => $company_id,
        ));
        if (!empty($invite)) {
            if ($invite->user->addToProject($invite->project) !== null) {
                $invite->user->userStatus = User::STATUS_ACTIVE;
                return $invite->user->save();
            }
        }
        return false;
    }

    /**
     *
     * @param string $t Invitation token
     * @param string $u User ID
     * @param string $p Project ID
     * @param string $c Company ID
     */
    public function actionConfirmInvite($t, $u = null, $p = null, $c = null) {
        if (empty($t)) {
            $this->_404();
        }
        if (!empty($u) && !empty($p) && !empty($c)) {
            $this->confirmExistingUserInvite($t, $u, $p, $c);
        } else {
            $this->confirmNewUserInvite($t);
        }
        $this->redirect(Yii::app()->createUrl('bug'));
    }

    protected function rejectInviteByExistingUser($token, $user_id, $project_id, $company_id) {
        $invite = Invite::model()->findByAttributes(array(
            'token' => $token,
            'project_id' => $project_id,
            'company_id' => $company_id,
            'user_id' => $user_id,
                ));
        if (!empty($invite)) {
            $invite->delete();
            $project = Project::model()->with('company')->findByPk($project_id);
            $company = $project->company;
            $user = User::model()->findByPk($user_id);
            if (!empty($project) && !empty($company) && !empty($user)) {
                $user->removeFromProject($project);
                $user->removeFromCompany($company);
            }
        }
        Yii::app()->user->setFlash('success', 'Invitation rejected.');
        $this->redirect($this->request->getBaseUrl(true));
    }

    public function actionRejectInvite() {
        if (null !== ($token = $this->request->getQuery('t'))) {
            $project_id = $this->request->getQuery('p');
            $user_id = $this->request->getQuery('u');
            $company_id = $this->request->getQuery('c');
            //  If the existing user has been invited:
            if (!empty($project_id) && !empty($user_id) || !empty($company_id)) {
                $this->rejectInviteByExistingUser($token, $user_id, $project_id, $company_id);
            }
            $user = User::model()->findByAttributes(array('inviteToken' => $token));
        } elseif (!empty($_GET['id'])) {
            $user = User::model()->findByPk((int) $_GET['id']);
        }

        if (!empty($user)) {

            $user->userStatus = User::STATUS_REJECTED;
            $user->inviteToken = '';
            $user->save();
            Yii::app()->user->setFlash('success', "Reject invite complete");
            $this->redirect(Yii::app()->createUrl('settings/members'));
            Yii::app()->end;
        }
        throw new CHttpException(404, 'The requested page does not exist.');
    }

    public function actionResetPassword() {
        $cookie_name = null;
        $value = null;
        if (isset($_GET['email'])) {
            $cookie_name = $_GET['email'];
            $cookie_name = trim(str_replace('.', '_', $cookie_name . '.bugkick'));
            if (isset(Yii::app()->request->cookies[$cookie_name])) {
                $value = unserialize(Yii::app()->request->cookies[$cookie_name]->value);
            }
        }
        $email = $this->request->getPost('email');
        $token = $this->request->getParam('token');
        if (isset($cookie_name)) {
            if (isset($value['token'])) {
                $token = $value['token'];
            }
            if (isset($value['email'])) {
                $email = $value['email'];
            }
        }
        if ((isset($cookie_name) || Yii::app()->request->isPostRequest) && isset($email)) {

            $user = User::model()->findByAttributes(array('email' => $email));
            if (!empty($user)) {

                $resetToken = sha1(uniqid(mt_rand(), true));
                $user->resetToken = $resetToken;

                $user->save();
                if (!isset($value['attempt_limit'])) {
                    Notificator::resetPassword($user);
                }

                Yii::app()->user->setFlash('success', "Your password was successfully reset. Email with the instructions was sent to " . $email);

                if (isset($value['auto_reset_password']) && isset($cookie_name)) {
                    Yii::app()->user->setFlash('success', "You've entered wrong password 4 times.Email was automatically sent with a link to generate a new password to " . $email . ' or you can try to login in 5 minutes');
                }
                if (isset($value['attempt_limit']) && isset($cookie_name)) {
                    Yii::app()->user->setFlash('success', "You can try to login in 5 minutes");
                }
            } else {
                Yii::app()->user->setFlash('error', "User with such email was not found.");
            }
            $this->redirect(Yii::app()->createUrl('site/login'));
            Yii::app()->end();
        } else if (!empty($token)) {
            $user = User::model()->findByAttributes(array('resetToken' => $token));
            if (!empty($user)) {
                $password = $this->setNewPassword($user);
                $user->resetToken = '';
                $user->save();
                $cookie_name = str_replace('.', '_', $user->email . '.bugkick');
                unset(Yii::app()->request->cookies[$cookie_name]);
                $userIdentity = new UserIdentity($user->email, $password);
                $userIdentity->authenticate();
                Yii::app()->user->login($userIdentity);
                $this->redirect(Yii::app()->createUrl('bug/index'));
            } else {
                throw new CHttpException(400, 'Invalid request.');
            }
            Yii::app()->end();
        } else {
            throw new CHttpException(400, 'Invalid request.');
        }
    }

    public function actionGetUser($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $model = $this->loadModel($id);
            $form = new UserForm();
            $form->setAttributes($model->attributes);
            $form->is_company_admin = $model->isCompanyAdmin(Company::current());
            foreach ($model->project as $project)
                $form->projects[] = $project->project_id;
            $this->layout = 'layout';
            $this->render('_userForm', array('model' => $form));
            Yii::app()->end();
        } else {
            throw new CHttpException(400, 'Invalid request.');
        }
    }

    //Search Filters save
    public function actionSaveFilter() {
        $model = new Filter();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'filter-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }


        $model->name = htmlspecialchars($_POST['Filter']['name']);
        $model->user_id = User::getId();
        $model->filter = serialize(BugFilter::getFilterState());

        if ($model->validate()) {
            $model->save();
            Yii::app()->user->setFlash('success', "Saved!");
        }
        $this->redirect(Yii::app()->createUrl('bug/index'));
    }

    //Search Filters delete
    public function actionDeleteFilter($id) {
        if (Filter::model()->deleteAll('user_id=:user_id AND filter_id=:filter_id', array('user_id' => User::getId(), 'filter_id' => $id)) > 0) {

            $filters = BugFilter::getFilterState();
            if (isset($filters['filter'][$id])) {
                unset($filters['filter'][$id]);
                BugFilter::setFilterState($filters);
            }

            Yii::app()->user->setFlash('success', "Deleted!");
            $this->redirect(Yii::app()->createUrl('bug/index'));
        }
        throw new CHttpException(400, 'Invalid request.');
    }

    public function actionAdminUserStats() {
        if (User::current()->isadmin) {
            header('Content-type: application/json');
            $sql = "SELECT S.*
             ,COUNT(C.comment_id) AS commentCount
             ,COUNT(RC.comment_id) AS commentCountPastMonth
            FROM (
                SELECT U.user_id
                 ,U.name
                 ,U.email
                 ,COUNT(BU.id) AS bugCount
                 ,COUNT(RB.id) AS bugCountPastMonth
                FROM bk_user U
                LEFT JOIN bk_bug BU ON U.user_id = BU.user_id
                LEFT JOIN bk_bug RB ON U.user_id = RB.user_id AND RB.created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH)
                GROUP BY U.user_id, U.name, U.email
            ) AS S
            LEFT JOIN bk_comment C ON S.user_id = C.user_id
            LEFT JOIN bk_comment RC ON S.user_id = RC.user_id AND RC.created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH)
            GROUP BY S.user_id, S.name, S.email";
            $connection = Yii::app()->db;
            $connection->active = true;
            $command = $connection->createCommand($sql);
            $rows = $command->queryAll();

            echo CJSON::encode($rows);
        }
        Yii::app()->end();
    }

    public function actionFiveNewest() {
        if (User::current()->isadmin) {
            header('Content-type: application/json');
            $sql = "SELECT U.name, DATE_FORMAT(U.created_at, '%d/%m/%Y') AS date
            FROM bk_user U
            ORDER BY U.created_at DESC
            LIMIT 5";
            $connection = Yii::app()->db;
            $connection->active = true;
            $command = $connection->createCommand($sql);
            $rows = $command->queryAll();

            echo CJSON::encode($rows);
        }
        Yii::app()->end();
    }

    public function actionTopFiveActive() {
        if (User::current()->isadmin) {
            header('Content-type: application/json');
            $sql = "SELECT U.name, COUNT(N.notification_id) AS count
            FROM bk_user U
            INNER JOIN bk_notification N ON U.user_id = N.user_id AND N.date > DATE_SUB(NOW(), INTERVAL 1 MONTH)
            GROUP BY U.user_id
            ORDER BY count DESC
            LIMIT 5";
            $connection = Yii::app()->db;
            $connection->active = true;
            $command = $connection->createCommand($sql);
            $rows = $command->queryAll();

            echo CJSON::encode($rows);
        }
        Yii::app()->end();
    }

    public function actionAdminUserUpdate($id) {
        if (User::current()->isadmin) {
            $model = $this->loadModel($id);
            $model->email = $_GET['email'];
            $messageType = 'error';
            $messageText = 'Error updating model';

            if ($_GET['password'] === '') {
                if ($model->save()) {
                    $messageType = 'success';
                    $messageText = "Email updated";
                }
            } else {
                if (strlen($_GET['password']) > 5) {
                    $model->password = Hash::sha256($_GET['password'] . $model->salt());
                    if ($model->save()) {
                        $this->session->add('hasPassword', true);
                        $messageType = 'success';
                        $messageText = "Email and password updated";
                    }
                } else {
                    $messageType = 'error';
                    $messageText = 'Password must be atleast 6 characters';
                }
            }
            echo CJSON::encode(array($messageType, $messageText));
        }
        Yii::app()->end();
    }

    public function actionInvitePeople() {
        $model = new InvitePeopleForm();
        $user = User::current();
        if (!empty($user) && isset($_POST['ajax']) && $_POST['ajax'] === 'invite-people-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        $model->setAttributes($_POST['InvitePeopleForm']);
        if (InvitePeople::decreaseInvites() && Notificator::invitePeople($user, $model->email)) {
            MixPanel::instance()->registerEvent(MixPanel::MARKETING_INVITE); // MixPanel events tracking
            Yii::app()->user->setFlash('success', 'User has been invited.');
        } else {
            Yii::app()->user->setFlash('error', 'An error has occurred. Please try again later.');
        }
        $this->redirect(Yii::app()->createUrl('bug/index'));
    }

}