<?php

class LabelController extends Controller
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
				'actions'=>array('create', 'createOnFly', 'update', 'delete', 'GetLabelById', 'updateCount'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','update', 'GetLabelById'),
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
        $model=new Label;
        $labelProvider = new CActiveDataProvider('Label', array(
            'criteria'=>array(
                'condition'=>'company_id=' . Company::current() ,
            )
        ));

        if(isset($_POST['ajax']) && $_POST['ajax']==='label-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['Label']))
        {
            if($_POST['Label_color_picker'] != '')
                $_POST['Label']['label_color'] = $_POST['Label_color_picker'];

            $model->attributes=$_POST['Label'];
            if($model->save()){
                /*Adding labels to project*/
                $cmd=Yii::app()->db->createCommand();
                $cmdParams=array(':label_id'=>$model->label_id);
                $delSql='DELETE FROM {{label_by_project}} WHERE label_id=:label_id';
                $cmd->setText($delSql)->execute($cmdParams);    //	delete existing projects
                $getSqlValues=function(array $IDs) {
                    $values=array();
                    foreach($IDs as $id)
                        $values[]='(:label_id, '.(int)$id.')';
                    return $values;
                };
                if (is_array($model->projects)){
                    $values=$getSqlValues($model->projects);
                    if(!empty($values)) {
                        $sql='INSERT INTO {{label_by_project}} VALUES'
                            .implode(',', $values);
                        $cmd->setText($sql)->execute($cmdParams);	//	Insert the projects that has been set
                    }
                }
                /*end of adding labels to project*/
            }
        }

        if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax'])) {
            $this->renderPartial('application.views.settings._labels', array('model'=>$labelProvider));
        }
        else {
            $this->redirect(Yii::app()->createUrl('settings/labelListing'));
        }
        Yii::app()->end();
	}
	
	public function actionCreateOnFly() {
		$model = new Label();
		$response = array('success'=>true, 'html'=>false);
		$attributes = $this->request->getPost('Label');
		if(!empty($attributes)) {
			$color = $this->request->getPost('Label_color_picker');
			if(!empty($color))
				$attributes['label_color'] = $color;
			$model->setAttributes($attributes);

			if($model->validate())
				if($model->save()) {
                    /*Adding labels to project*/
                    $cmd=Yii::app()->db->createCommand();
                    $cmdParams=array(':label_id'=>$model->label_id);
                    $delSql='DELETE FROM {{label_by_project}} WHERE label_id=:label_id';
                    $cmd->setText($delSql)->execute($cmdParams);    //	delete existing projects
                    $getSqlValues=function(array $IDs) {
                        $values=array();
                        foreach($IDs as $id)
                            $values[]='(:label_id, '.(int)$id.')';
                        return $values;
                    };
                    if (is_array($attributes['projects'])){
                        $values=$getSqlValues($attributes['projects']);
                        if(!empty($values)) {
                            $sql='INSERT INTO {{label_by_project}} VALUES'
                                .implode(',', $values);
                            $cmd->setText($sql)->execute($cmdParams);	//	Insert the projects that has been set
                        }
                    }
                    /*end of adding labels to project*/

					$response['label'] = array(
						'name'=>$model->name,
						'label_id'=>$model->label_id,
					);
					$this->respond($response);
				}
		}
		$response['success']=false;
		$response['html']=$this->renderPartial(
			'application.views.settings._labelForm',
			array(
				'labelModel'=>$model,
				'formID'=>'common-label-form',
				'enableAjaxValidation'=>true,
				'validateOnSubmit'=>true,
				'action'=>CHtml::normalizeUrl(array('label/createOnFly')),
			),
			true
		);
		$this->respond($response);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='label-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

		if(isset($_POST['Label']))
		{
			$model->attributes=$_POST['Label'];
			if($model->save()){
                /*Adding labels to project*/
                $cmd=Yii::app()->db->createCommand();
                $cmdParams=array(':label_id'=>$model->label_id);
                $delSql='DELETE FROM {{label_by_project}} WHERE label_id=:label_id';
                $cmd->setText($delSql)->execute($cmdParams);    //	delete existing projects
                $getSqlValues=function(array $IDs) {
                    $values=array();
                    foreach($IDs as $id)
                        $values[]='(:label_id, '.(int)$id.')';
                    return $values;
                };
                if (is_array($model->projects)){
                    $values=$getSqlValues($model->projects);
                    if(!empty($values)) {
                        $sql='INSERT INTO {{label_by_project}} VALUES'
                            .implode(',', $values);
                        $cmd->setText($sql)->execute($cmdParams);	//	Insert the projects that has been set
                    }
                }
                /*end of adding labels to project*/
            }
//				$this->redirect(array('view','id'=>$model->label_id));
                $this->redirect(array('settings/labelListing'));
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
			$model = $this->loadModel($id);
            $companyID = Company::current();
            if(empty($companyID) || $model->company_id!=$companyID)
                throw new CHttpException(400,'Invalid request.');

            $model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('settings/labelListing'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
/*	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Label');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
/*	public function actionAdmin()
	{
		$model=new Label('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Label']))
			$model->attributes=$_GET['Label'];

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
		$model=Label::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function actionGetLabelById(){
        if (Yii::app()->request->isAjaxRequest && !empty($_GET['id'])) {
            $model = $this->loadModel($_GET['id']);
            $this->layout = 'layout';
            $this->render('_form', array('model'=>$model));
            Yii::app()->end();
        }
    }

    public function actionUpdateCount()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $labelList = Project::getLabels();
            if (is_array($labelList)){
                foreach($labelList as $label)
                    $labels[$label->label_id] = $label->bugCountForProject;
            }
            $labels[0] = Bug::getAPITasksCount();
            $result = htmlspecialchars(json_encode(array('labels'=>$labels)), ENT_NOQUOTES);
            echo $result;
        }
        Yii::app()->end();
    }
}
