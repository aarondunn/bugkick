<?php

class SiteController extends Controller {

    const BK_USER_COOKIE = 'bugkick_user';
    const BK_NEW_USER = 'new_bugkick_user';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
                //'companySet'
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
                'actions' => array('index', 'SendMailCountactUs' ,'logout', 'sendDateNotification', 'design'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('dashboard', 'calendar'),
                'expression' => 'isset($user->company_id)',
                'users' => array('@')
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('login', 'index', 'help', 'articles', 'getArticle'),
                'users' => array('*'),
            //'users'=>array('?'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('error'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
            'design' => array(
                'class' => 'CViewAction',
                'basePath' => 'design',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        if (!Yii::app()->user->isGuest) {
            $this->redirect($this->createUrl('bug/'));
        }

        //if user already registered then redirect
        //to the login page instead of showing main page
        if (Yii::app()->request->cookies->contains(self::BK_USER_COOKIE)
                && Yii::app()->request
                ->cookies[self::BK_USER_COOKIE]->value == 1) {
            $this->redirect($this->createUrl('site/login'));
        }

        MixPanel::instance()->registerEvent(MixPanel::HOME_PAGE_VIEW); // MixPanel events tracking

        $this->layout = '/layouts/index-new';
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail('ylavrynovich@gmail.com', $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    protected function fbSignUp($graph) {
        $this->session->add('fbGraph', $graph);
        $this->redirect('/registration/facebook');
    }

    protected function fbLogin($graph) {
        $userIdentity = new FacebookUserIdentity($graph);
        if (!$userIdentity->authenticate())
            $this->fbSignUp($graph);
        Yii::app()->user->login($userIdentity);
        Yii::app()->request->redirect($this->createUrl('bug/'));
    }

    protected function setBkUserCookie() {
        //mark as existing user
        $cookie = new CHttpCookie(self::BK_USER_COOKIE, 1);
        $cookie->expire = time() + 60 * 60 * 24 * 180; //180days
        Yii::app()->request->cookies[self::BK_USER_COOKIE] = $cookie;
        //mark user as new
        $cookie = new CHttpCookie(self::BK_NEW_USER, 1);
        $cookie->expire = time() + 60 * 60 * 24 * 360; //360days
        Yii::app()->request->cookies[self::BK_NEW_USER] = $cookie;
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $user = new UserBlock;
        $block = $user->isBlock($_SERVER['REMOTE_ADDR']);
        if ($block) {
            Yii::app()->user->setFlash('success', "You exceeded 20 attempts to login. You can try in an hour.");
            Yii::app()->user->setFlash('block', "block login form");
        } else {
            Yii::app()->user->setFlash('block', null);
        }
        $cookie_name = null;
        if (!Yii::app()->user->isGuest) {
            $plan = $this->session['planType'];
            if ($this->session['fromRegistration'] == 1) {
                $this->setBkUserCookie();
                $this->session['fromRegistration'] = 0;
                if (!empty($plan) && $this->session['registersWithCoupon']!=1) {
                    $this->redirect($this->createUrl('payment/pro-account', array('subscription' => $plan)));
                }
            }
            $user->delete($_SERVER['REMOTE_ADDR']);
            $this->redirect($this->createUrl('bug/'));
        }
        $model = new LoginForm;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        $fb = FacebookHelper::getFacebook();
        $fbUser = $fb->getUser();
        $fbLoginUrl = null;
        $graph = null;
        if (empty($fbUser)) {
            $fbLoginUrl = $fb->getLoginUrl(
                array(
                    'scope' => 'email',
                    'redirect_uri' => $this->createAbsoluteUrl('/site/login'),
                )
            );
        } else {
            try {
                $graph = $fb->api('/me');
            } catch (FacebookApiException $e) {
                FacebookHelper::handleFacebookApiException($e);
            }
        }
        if (!empty($graph))
            $this->fbLogin($graph);
        // collect user input data
        //echo $this->request->getCsrfToken();
        if (isset($_POST['LoginForm'])) {

            Yii::app()->session['username_valid'] = true; //set username validation flag "true" by default
            $cookie_name = trim(str_replace('.', '_', $_POST['LoginForm']['username'] . '.bugkick'));
            $attempt_limit = 1;
            if (isset(Yii::app()->request->cookies[$cookie_name])) {
                $value = unserialize(Yii::app()->request->cookies[$cookie_name]->value);
                $attempt_limit = $value['username_invalid_count'];
            }
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($attempt_limit < 4 && $model->validate() && $model->login()) {
                $this->setBkUserCookie();
                
                $user = User::current();
                
                if( empty($user->profile_img) ){
	                $profile_img_url  = 'images/profile_img/';
	                $profile_img_name = md5(uniqid(mt_rand(), true)) . '.jpg';
	                $gr_img_31 = $this->getGravatar($user->email, 31);
	                $gr_img_81 = $this->getGravatar($user->email, 81);
	                
	                if( @fopen($gr_img_31,"r") ){
	                	file_put_contents($profile_img_url . '31_31_' . $profile_img_name, file_get_contents($gr_img_31));
	                	file_put_contents($profile_img_url . '81_81_' . $profile_img_name, file_get_contents($gr_img_81));
	                
	                	$user->profile_img = $profile_img_name;
	                	$user->save();
	                }
                }
                
                $this->redirect(Yii::app()->user->returnUrl);
            } else {
                $user->addBlock($_SERVER['REMOTE_ADDR']);
                if (isset(Yii::app()->session['username_valid'])) {// if user validation flag wasn't been changed to "false" - we create cookie, or overwrite current,and record quantity of failed attempts to login using certain email
                    if (isset(Yii::app()->request->cookies[$cookie_name])) {
                        $cookie = Yii::app()->request->cookies[$cookie_name];
                        Yii::app()->request->cookies[$cookie_name] = $cookie;
                        $value = unserialize(Yii::app()->request->cookies[$cookie_name]->value);
                        $value['username_invalid_count'] = $value['username_invalid_count'] + 1;
                        if ($value['username_invalid_count'] == 4) {// if there is more that 4 failed attempts, then we send email to user with offer to change pass and user will not be able to login for 5 minutes since last attempt.For every failed attempt(during 5 ban minutes, time will be prolonged 5 minutes since latest failed attempt)
                            $value['email'] = $_POST['LoginForm']['username'];
                            $value['token'] = $this->request->getCsrfToken();
                            $value['auto_reset_password'] = true;
                            $cookie = new CHttpCookie($cookie_name, serialize($value));
                            $cookie->expire = time() + 300;
                            Yii::app()->request->cookies[$cookie_name] = $cookie;
                            $this->redirect(Yii::app()->createUrl('user/resetPassword') . '?email=' . $value['email']);
                        } else if ($value['username_invalid_count'] > 4) {
                            $value['email'] = $_POST['LoginForm']['username'];
                            $value['token'] = $this->request->getCsrfToken();
                            $value['attempt_limit'] = true;
                            $cookie = new CHttpCookie($cookie_name, serialize($value));
                            $cookie->expire = time() + 300;
                            Yii::app()->request->cookies[$cookie_name] = $cookie;
                            $this->redirect(Yii::app()->createUrl('user/resetPassword') . '?email=' . $value['email']);
                        } else {
                            Yii::app()->request->cookies[$cookie_name] = new CHttpCookie($cookie_name, serialize($value));
                        }
                    } else {
                        $cookie = new CHttpCookie($cookie_name, serialize(array('username_invalid_count' => '1')));
                        $cookie->expire = time() + 300;
                        Yii::app()->request->cookies[$cookie_name] = $cookie;
                    }
                }
            }
        }
        //Yii::app()->clientScript->registerCssFile('/themes/bugkick_theme/css/site/login/common.css');
        $viewData = array(
            'fbLoginUrl' => $fbLoginUrl,
            'model' => $model,
        );

        MixPanel::instance()->registerEvent(MixPanel::LOGIN_PAGE_VIEW); // MixPanel events tracking

        if ($this->session['fromRegistration'] == 1 && empty($plan))
            MixPanel::instance()->registerEvent(MixPanel::SIGN_UP, array('type' => 'free')); // MixPanel events tracking


            
// display the login form
        $this->layout = '/layouts/index-new';
        $this->render('login', $viewData);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        $token = $this->request->getParam('token');
        if ($token !== $this->request->csrfToken)
            throw new CHttpException(
                    404,
                    Yii::t(
                            'main', 'Invalid request. Please do not repeat this request again.'
                    )
            );
        FacebookHelper::getFacebook()->destroySession();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionDashboard() {
        $project = Project::getCurrent();
        if (empty($project))
            $this->redirect($this->createUrl('/project/index'));

        $bugCount = Company::getBugsCount();
        $archivedBugCount = Company::getArchivedBugsCount();
        $usersCount = Company::getUsersCount();
        $bugsPerUser = ($usersCount > 0) ? round($bugCount / $usersCount, 1) : $bugCount;

        $newBugsArr = Company::getTodayBugsCount();
        $recentBugsArr = Company::getRecentChangedBugs();

        $intervalStart = date('Y-m-d');
        $recentBugs["$intervalStart"] = array();

        for ($i = 0; $i <= 14; $i++) {
            $curDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - $i, date('Y')));
            $recentBugs[$curDate] = '';

            $countArchivedForDate = 0;
            $countOpenForDate = 0;

            if (is_array($recentBugsArr)) {
                foreach ($recentBugsArr as $value) {
                    if ($value['DATE(created_at)'] == $curDate) {
                        $countOpenForDate++;
                    } elseif (($value['DATE(archiving_date)'] == $curDate) && ($value['isarchive'] == 1)) {
                        $countArchivedForDate++;
                    }
                }
            }

            $recentBugs[$curDate]['archived'] = $countArchivedForDate;
            $recentBugs[$curDate]['open'] = $countOpenForDate;
        }

        $newBugsToday = 0;
        $openBugsToday = 0;

        if (is_array($newBugsArr)) {
            $newBugsToday = count($newBugsArr);
            foreach ($newBugsArr as $value) {
                if ($value['isarchive'] == 0)
                    $openBugsToday++;
            }
        }

        $users = Company::getUsers();
        $statuses = Company::getStatuses();
        $labels = Company::getLabels();

        $this->render('dashboard', array(
            'bugCount' => $bugCount,
            'archivedBugCount' => $archivedBugCount,
            'bugsPerUser' => $bugsPerUser,
            'openBugsToday' => $openBugsToday,
            'newBugsToday' => $newBugsToday,
            'recentBugs' => $recentBugs,
            'users' => $users,
            'labels' => $labels,
            'statuses' => $statuses,
                )
        );
    }

    public function actionCalendar() {
        $bugs = Company::getBugs();

        $bugsArray = array();
        if (!empty($bugs)) {
            foreach ($bugs as $key => $bug) {
                $bugsArray[$key]['id'] = $bug->id;
                $bugsArray[$key]['title'] = Helper::truncateString('#' . $bug->number . ' ' . $bug->title, 25, '', '...');
                $bugsArray[$key]['start'] = $bug->duedate;
                $bugsArray[$key]['url'] = $this->createUrl('bug/view', array('id' => $bug->number));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('calendar', array('bugs' => $bugsArray), false, true);
            Yii::app()->end();
        }

        $this->render('calendar', array('bugs' => $bugsArray));
    }

    public function actionSendDateNotification() {
        $data = Bug::getOutdatedTickets();
        if (is_array($data))
            Notificator::sendDueDateNotification($data);
        else
            print 'No Outdated tickets.';
        Yii::app()->end();
    }

    public function actionPreviewMarkdown() {
        $markdownContent = $this->request->getPost('data');
        if (empty($markdownContent))
            Yii::app()->end();
        $parser = new CMarkdownParser();
        $parsedText = $parser->safeTransform($markdownContent);
        $this->respond($parsedText, ResponseType::HTML);
    }

    public function actionHelp() {
        $this->render('help');
    }

    public function actionArticles() {
        $searchQuery = Yii::app()->getRequest()->getParam('helpSearch');
        $dataProvider = Article::getSearch($searchQuery);
        $this->renderPartial(
                'application.components.views.help._articleList', array('dataProvider' => $dataProvider), false, false
        );
    }

    public function actionGetArticle($id) {
        $article = Article::model()->findByPk($id);
        if (empty($article))
            $this->_404('Article was not found.');

        $this->renderPartial(
                'application.components.views.help._article', array('model' => $article), false, false
        );
    }
    
    public function actionSendMailCountactUs(){
        $data = $this->request->getPost('ContactUs');
        $r=CActiveForm::validate(new ContactUs());
        if($r=="[]"){
            $e= new Notificator();//aaron@bugkick.com
            $e->sendEmail("aaron@bugkick.com", $data['email'], 'CuntactUs','Name:'.$data['name'].PHP_EOL.'Comment:'.$data['comment']);
            echo $r;
        }else{
            echo $r;
        }
    }

    public function getGravatar( $email, $s = 31, $d = 404, $r = 'g', $img = false, $atts = array() ) {
    	$url = 'http://www.gravatar.com/avatar/';
    	$url .= md5( strtolower( trim( $email ) ) );
    	$url .= "?s=$s&d=$d&r=$r";
    	if ( $img ) {
    		$url = '<img src="' . $url . '"';
    		foreach ( $atts as $key => $val )
    			$url .= ' ' . $key . '="' . $val . '"';
    		$url .= ' />';
    	}
    	return $url;
    }
}