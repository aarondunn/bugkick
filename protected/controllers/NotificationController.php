<?php

class NotificationController extends Controller
{
    public $layout = '//layouts/column1';
    const PAGE_SIZE = 30;

	public function actionIndex()
	{
        MixPanel::instance()->registerEvent(MixPanel::UPDATES_PAGE_VIEW); // MixPanel events tracking
        $this->render('index');
	}

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
            array('allow', // allow authenticated user to perform actions
                'actions' => array(
                    'index',
                    'delete',
                    'notifications',
                ),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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
            if($model->user_id!=User::current()->user_id)
                throw new CHttpException(400,'Invalid request.');

            $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(array('/notification'));
        }
        else
            throw new CHttpException(400,'Invalid request.');
    }

    /**
   	 * Returns the data model based on the primary key given in the GET variable.
   	 * If the data model is not found, an HTTP exception will be raised.
   	 * @param integer the ID of the model to be loaded
   	 */
   	public function loadModel($id)
   	{
   		$model=Notification::model()->findByPk((int)$id);
   		if($model===null)
   			throw new CHttpException(404,'The requested page does not exist.');
   		return $model;
   	}
   	   	    
    public function actionNotifications()
    {
        //$criteria = new CDbCriteria();
        //$criteria->limit = 50;
        //$criteria->select = 'n.*, u.name';
        //$criteria->alias = 'n';
        //$criteria->join = 'LEFT JOIN bk_user u ON n.user_id = u.user_id';
        //$criteria->condition = 'user_id=:user_id';
        //$criteria->order = 'notification_id desc';
        //$criteria->params = array(
        //       ':user_id'=>90,
        //);
        //$notification = Notification::model()->findAll($criteria);
        //header('Content-type: application/json');
/*        $sql = '
            SELECT DISTINCT n.*, u.user_id, u.name, u.lname, u.facebook_id, u.profile_img
                FROM {{notification}} n
                    JOIN (
                        {{user}} u,
                        {{bug}} b,
                        {{user_by_project}} up
                    )
                    ON (
                        n.changer_id = u.user_id
                        AND n.bug_id IS NOT NULL
                        AND n.bug_id = b.id
                        AND b.project_id = up.project_id
                        AND up.project_id IN (
                            SELECT DISTINCT up.project_id FROM {{user_by_project}} up
                                WHERE 1
                                AND up.user_id = :current_user_id
                        )
                    )
                WHERE n.user_id =:current_user_id
                ORDER BY n.notification_id DESC LIMIT 50';*/
        $sql = '
            SELECT DISTINCT n.*, u.user_id, u.name, u.lname, u.facebook_id, u.profile_img
                FROM {{notification}} n
                    JOIN (
                        {{user}} u,
                        {{bug}} b,
                        {{user_by_project}} up
                    )
                    ON (
                        n.changer_id = u.user_id
                        AND n.bug_id IS NOT NULL
                        AND n.bug_id = b.id
                        AND b.project_id = :current_project_id
                    )
                WHERE n.user_id =:current_user_id
                ORDER BY n.notification_id DESC LIMIT 25';
        //$connection = Yii::app()->db;
        //$connection->active = true;
        $command = Yii::app()->db->createCommand($sql);
        $command->params = array(
            ':current_user_id'=>Yii::app()->user->id,
            ':current_project_id'=>Project::getCurrent()->project_id,
        );
        $notifications = $command->queryAll();
        $this->respond($notifications);
        /*echo CJSON::encode($notifications);
        Yii::app()->end();*/
    }
}
