<?php
/**
 * TopicController
 *
 * @author Alexey Kavshirko kavshirko@gmail.com
 *
 */
class TopicController extends BaseForumController
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
				'actions'=>array('update','admin','delete','hide'),
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
	public function actionView($id)
	{
        $posts = new CActiveDataProvider('BKPost',
            array('criteria' => array(
                'condition' => 'topic_id=:topic_id',
                'params' => array(':topic_id' => $id)
            ))
        );

        $form=new BKPost;

		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'posts'=>$posts,
			'form'=>$form,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $forumID
	 */
	public function actionCreate($forumID)
	{
		$model=new BKTopic;
        $forum = BKForum::model()->findByPk($forumID);
        if(empty($forum))
            throw new CHttpException(400,'Invalid request.');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BKTopic']))
		{
			$model->attributes=$_POST['BKTopic'];
            $model->topic_starter_id = Yii::app()->user->id;
            $model->time = date('Y-m-d H:i:s');
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'forum'=>$forum,
		));
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

		if(isset($_POST['BKTopic']))
		{
			$model->attributes=$_POST['BKTopic'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

    /**
   	 * Archives a particular model.
   	 * If archiving is successful, the browser will be redirected to the 'admin' page.
   	 * @param integer $id the ID of the model to be deleted
   	 */
   	public function actionHide($id){
   		$model = $this->loadModel($id);
        $model->archived = ($model->archived==1)? 0 : 1;
        $model->save();
   		// if AJAX request (triggered by archiving via admin grid view), we should not redirect the browser
   		if(!isset($_GET['ajax']))
   			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
   	}
	/**
	 * Lists all models.
	 */
/*	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('BKTopic');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BKTopic('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BKTopic']))
			$model->attributes=$_GET['BKTopic'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=BKTopic::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='bktopic-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
