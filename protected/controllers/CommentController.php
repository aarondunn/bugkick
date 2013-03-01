<?php

class CommentController extends Controller
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
				'actions'=>array('index','view','emaillistener'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Called by comment email replies to notifications@bugkick.com
	 * We need to extract 
	 */
	public function actionEmaillistener()
	{
		var_dump($_POST);
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
	public function actionCreate($bugId) {
		//$_SESSION['myComment']=$_POST['Comment']
		function _400() {
			throw new CHttpException(400,
				'Invalid request. Please do not repeat this request again.');
		}
		$model=new Comment;
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model, 'comment-form');
		if(isset($_POST['Comment'])) {
            $bug = Bug::model()->resetScope()->findByPk($bugId);
			if(empty($bug))
				_400();
			$model->attributes=$_POST['Comment'];
            $model->message = ActivateLinks::perform($model->message);
			$p = new CHtmlPurifier($this);
			$p->options=array(
				'HTML.AllowedElements'=>
					'p,div,span,ul,ol,li,a,hr,pre,br,h1,h2,h3,h4,h5,h6,b,i,u,strike,big,small',
				'HTML.AllowedAttributes'=>'style,class,width,size,href, align',
			);
			$model->message = $p->purify($model->message);
			// <editor-fold defaultstate="collapsed" desc="markdown handling commented">
			/*			$reCodeBlock='/{{{#!(\s|.)*?!#}}}/';
			$textBlocks=preg_split($reCodeBlock, $model->message);
			$hasCodeBlocks=preg_match_all(
				$reCodeBlock, $model->message, $codeBlocks) > 0;
*/
/*VarDumper::dd($textBlocks);
VarDumper::dd($codeBlocks);
die;*/
/*
			$model->message='';
			$parser=new CMarkdownParser();
			foreach($textBlocks as $k=>$v) {
				$model->message.=$parser->safeTransform($v);
				if(isset($codeBlocks[0][$k]))
					$model->message.=$codeBlocks[0][$k];
			}
*/
			//</editor-fold>
			$model->message = preg_replace(
				'/({{{#!)([^\s]*)\s+((.|\s)+?)\s*(!#}}})/',
				'<pre class="language-$2">$3</pre>',
				$model->message
			);
			$model->message = preg_replace(
				'/(<pre class=")(language-)(">)/',
				'$1highlight$3',
				$model->message
			);
            $model->bug_id = (int) $bug->id;
			if($model->save()) {
                //send notifications
				Notificator::newComment($model, array($model->user_id));

                //check if user changed project during writing a comment
                User::updateCurrentProject($bug->project_id);

                //close ticket if "Comment and Close" button was pressed
                $commentAndClose = $this->request->getParam('comment-and-close');
                if($commentAndClose == 1){
                    $bug->isarchive = 1;
                    $bug->archiving_date = new CDbExpression('NOW()');
                    $changes = array(0=>array('field'=>'archived', 'value'=>1));
                    if ($bug->save()){
                        $changeLog = new BugChangelog();
                        $changeLog->populateChanges($bug, $changes);
                        $changeLog->save();
                    }
                }
                if(isset($_POST['BugForm'])) {
                    $this->forward('/bug/update/id/' . $bug->id);
                }
                $this->redirect(array('/bug/view', 'id'=>$bug->number));
                Yii::app()->end();
            }
		}
		_400();
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
/*	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->comment_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}*/

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
/*	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}*/

	/**
	 * Lists all models.
	 */
/*	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Comment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
/*	public function actionAdmin()
	{
		$model=new Comment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Comment']))
			$model->attributes=$_GET['Comment'];

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
		$model=Comment::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
   
}
