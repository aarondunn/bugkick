<?php

class StatusController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'delete', 'GetStatusById'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','update', 'GetStatusById'),
				'users'=>array('admin'),
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
    public function actionCreate()
	{
            $model=new Status;

            $statusProvider = new CActiveDataProvider('Status', array(
                        'criteria'=>array(
                            'condition'=>'company_id=' . Company::current() ,
                        )
                    ));

            if(isset($_POST['ajax']) && $_POST['ajax']==='status-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            if(isset($_POST['Status']))
            {
                if($_POST['Status_color_picker']!='') $_POST['Status']['status_color'] = $_POST['Status_color_picker'];
                $model->attributes=$_POST['Status'];
                $model->save();
            }

            if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax'])) {
                $this->renderPartial('application.views.settings._statuses', array('model'=>$statusProvider));
            }
            else {
                $this->redirect(Yii::app()->createUrl('settings/statusListing'));
            }
            Yii::app()->end();

	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        $companyID = Company::current();
        if(empty($companyID) || $model->company_id!=$companyID)
            throw new CHttpException(400,'Invalid request.');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model, 'status-form');

		if(isset($_POST['Status']))
		{
			$model->attributes=$_POST['Status'];
			if($model->save())
//				$this->redirect(array('view','id'=>$model->status_id));
            $this->redirect(array('settings/statusListing'));
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
		if(Yii::app()->request->isPostRequest || Yii::app()->request->isAjaxRequest)
		{
			// we only allow deletion via POST request
            $companyID = Company::current();
            $model = $this->loadModel($id);
            if(empty($companyID) || $model->company_id!=$companyID)
                throw new CHttpException(400,'Invalid request.');

            $model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('settings/statusListing'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
/*	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Status');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
/*	public function actionAdmin()
	{
		$model=new Status('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Status']))
			$model->attributes=$_GET['Status'];

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
		$model=Status::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function actionGetStatusById(){
        if (Yii::app()->request->isAjaxRequest && !empty($_GET['id'])) {
            $model = $this->loadModel($_GET['id']);
            $this->layout = 'layout';
            $this->render('_form', array('model'=>$model));
            Yii::app()->end();
        }
    }

}
