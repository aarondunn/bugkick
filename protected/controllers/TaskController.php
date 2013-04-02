<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 16.03.13
 * Time: 20:02
 */

class TaskController extends Controller
{
    public function init()
    {
        if(Yii::app()->user->isGuest) {
            Yii::app()->user->setFlash('error','Please log in to access this page.');
            $this->redirect('/site/login');
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        throw new CHttpException(404, 'Page not found.');
    }

    public function actionCreate()
    {
        $ticketID = Yii::app()->request->getParam('ticketID');
        $ticket = Bug::model()->with('project')->findByPk($ticketID);
        $user = User::current();
        if(empty($user) || !Project::isProjectAccessAllowed($ticket->project->project_id, $user->user_id))
            throw new CHttpException(403, 'You don\'t have access to this area.');

        if(empty($ticket))
            throw new CHttpException(404, 'Ticket is not found.');

        $model = new TaskForm;
        if (isset($_POST['TaskForm'])) {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'task-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            $model->setAttributes($_POST['TaskForm']);
            if($model->validate()) {
                $task = new Task;
                $task->attributes=$model->getAttributes();
                $task->ticket_id = $ticket->id;
                $task->user_id = $user->user_id;
                $task->date = date('Y-m-d H:i:s');
                $task->status = Task::STATUS_NEW;
                if (!$task->save()) {
                    throw new CHttpException(500, 'Task was not saved.');
                }
            }
            Yii::app()->end();
        }
        $this->renderPartial('_form',array('model'=>$model,'ticketID'=>$ticketID));
    }

    public function actionComplete()
    {
        $taskID = (int) $_POST['taskID'];
        $user = User::current();

        if (empty($taskID) || empty($user))
            throw new CHttpException(400,'Invalid request.');

        $task = Task::model()->findByPk($taskID);
        if(empty($task))
            throw new CHttpException(400,'Invalid request.');

        if(!Project::isProjectAccessAllowed($task->ticket->project->project_id, $user->user_id))
            throw new CHttpException(403,'You don\'t have permissions to perform this action.');

        $task->status = ($task->status == Task::STATUS_COMPLETED)
            ? Task::STATUS_NEW
            : Task::STATUS_COMPLETED;
        $task->save();
    }
    
	public function actionDelete($id)
	{
        $model = $this->loadModel($id);
        if($model->user_id == Yii::app()->user->id)
            $model->delete();
        else
            throw new CHttpException(400,'Invalid request');

        $this->redirect(Yii::app()->request->getUrlReferrer());
	}
    
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Task::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
