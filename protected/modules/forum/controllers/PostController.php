<?php
/**
 * PostController
 *
 * @author Alexey Kavshirko kavshirko@gmail.com
 *
 */
class PostController extends BaseForumController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
            array('allow',  // all users
                'actions'=>array('index','view'),
                'users'=>array('*'),
            ),
            array('allow', // user
                'actions'=>array('create'),
                'roles'=>array('user'),
            ),
            array('allow', // moderator
                'actions'=>array('update','admin','delete'),
                'roles'=>array('moderator'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
/*	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}*/

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($topicID)
	{
		$model=new BKPost;
        $topic = BKTopic::model()->findByPk($topicID);
        if(empty($topic))
            throw new CHttpException(400,'Invalid request.');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BKPost']))
		{
			$model->attributes=$_POST['BKPost'];

            $this->sendNotifications($model, $topic);
            $model->body = BKActivateLinks::perform($model->body);
            $p = new CHtmlPurifier($this);
            $p->options=array(
                'HTML.AllowedElements'=>
                    'p,div,span,ul,ol,li,a,hr,pre,br,h1,h2,h3,h4,h5,h6,b,i,u,strike,big,small',
                'HTML.AllowedAttributes'=>'style,class,width,size,href, align',
            );
            $model->body = $p->purify($model->body);
            $model->user_id = Yii::app()->user->id;
            $model->time = date('Y-m-d H:i:s');
			if($model->save())
				$this->redirect(array('topic/view','id'=>$topic->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'topic'=>$topic,
		));
	}

    /**
     * Parses message, finds user names and send notification to them and to other participants
     * @param BKPost $model
     * @param BKTopic $topic
     * @return bool
     */
    protected function sendNotifications($model, $topic)
    {
        $currentCompany = Company::current();
        $message = $model->body;
        if(empty($currentCompany) || empty($message) || empty($topic))
            return false;

        $members = array();

        //get users mentioned in the topic
        if (preg_match_all('#@([^ ]{3,}) ([^ @]{1,})?#i', $message, $matches)) {
            if(!empty($matches) && is_array($matches)){
                if(isset($matches[1]) && is_array($matches[1]) && !empty($matches[1])){
                    foreach($matches[1] as $key=>$name){
                        $criteria = new CDbCriteria();
                        $criteria->with = array(
                            'company'=>array(
                                'condition'=>'company.company_id=:company_id',
                                'params'=>array(
                                    ':company_id'=>$currentCompany
                                ),
                            ),
                        );
                        if(isset($matches[2][$key]) && !empty($matches[2][$key])){
                            $criteria->condition = 'name=:name AND lname=:lname';
                            $criteria->params = array(
                                ':name'=>$matches[1][$key],
                                ':lname'=>$matches[2][$key],
                            );
                            $users = User::model()->findAll($criteria);
                            if(empty($users)){
                                $criteria->condition = 'name=:name';
                                $criteria->params = array(
                                    ':name'=>$matches[1][$key],
                                );
                                $users = User::model()->findAll($criteria);
                            }
                        }
                        else{
                            $criteria->condition = 'name=:name';
                            $criteria->params = array(
                                ':name'=>$matches[1][$key],
                            );
                            $users = User::model()->findAll($criteria);
                        }
                        $members = array_merge($users,$members);
                    }
                }
            }
        }

        //get all other participants of the topic
        $topicUsers = $topic->getTopicParticipants();
        $members = array_merge($members, $topicUsers);

        if(!empty($members)){
            $membersUnique = array();
            foreach($members as $member){
                if(Yii::app()->user->id != $member->user_id)
                    $membersUnique[$member->user_id]=$member;
            }
            $this->notifyMembers($membersUnique, $model);
        }
        return true;
    }

    /**
     * Notifies members that are mentioned in forum post
     * @param User[] $members
     * @param BKPost $post
     */
    public function notifyMembers($members, $post)
    {
        if(!empty($members) && is_array($members)){
            foreach($members as $member){
                Notificator::forumMessage($member, $post);
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BKPost']))
		{
			$model->attributes=$_POST['BKPost'];
			if($model->save())
                $this->redirect(array('topic/view','id'=>$model->topic->id));
		}

		$this->render('update',array(
			'model'=>$model,
            'topic'=>$model->topic,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        if($this->loadModel($id)->delete())
            Yii::app()->end();
        else
            throw new CHttpException(500,'Server error.');
	}

	/**
	 * Lists all models.
	 */
/*	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('BKPost');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
/*	public function actionAdmin()
	{
		$model=new BKPost('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BKPost']))
			$model->attributes=$_GET['BKPost'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=BKPost::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	public function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bkpost-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
