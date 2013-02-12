<?php
/**
 * ManageAction
 *
 * @author f0t0n
 */
class ManageAction extends Action {

	protected $project_id;
	protected $action;
	protected $response;
	
	protected function init() {
		$this->project_id=(int)$this->request->getParam('project_id');
		$this->action=$this->request->getParam('action');
		if(empty($this->project_id) || empty($this->action))
			$this->controller->_404('Invalid set of parameters.');
		$this->response=array();
	}
	
	public function run() {
		$this->init();
        $user = User::current();
        if(empty($user)
            || $user->getStatusInCompany(Company::current())
                != User::STATUS_ACTIVE) {
            throw new CHttpException(403, 'Action forbidden.');
        }
		$method='action'.ucfirst($this->action);
		if(!method_exists($this, $method))
			$this->controller->_404('Invalid action.');
		$this->$method();
	}
	
	protected function getProjectUsers() {
		$usersArray=array();
		/**
		 * @todo Change the currentCompany scope usage
		 * to query by selected project 
		 * when linkage of users to projects will implemented.
		 */
		$users=User::model()->currentCompany()->findAll();
		foreach($users as $user) {
			$usersArray[$user->user_id]=array(
				'name'=>$user->name . ' ' . $user->lname,
				'url'=>Yii::app()->createUrl(
					'user/view',
					array('id'=>$user->user_id)
				),
				'picture'=>$user->getImageSrc(31, 31),
			);
		}
		return $usersArray;
	}
	
	protected function getProjectTasks() {
		$projectTasks=array();
		$criteria=new CDbCriteria();
		$criteria->condition='project_id=:project_id';
		$criteria->with=array(
			'user'=>array(
				'select'=>'user_id',
			),
		);
		$criteria->params=array(':project_id'=>$this->project_id);
		$criteria->order='t.created_at';
		$bugs=Bug::model()->resetScope()->findAll($criteria);
		$getDueDate=function($bug) {
			if(!empty($bug->duedate) && $bug->duedate !== '0000-00-00')
				return strtotime($bug->duedate);
			return strtotime($bug->created_at) + 604800; // created_at + 7 days
		};
		/*
{ 
	"name": "  Planning ",
	"desc": "Inception",
	"values": [{
		"from": "/Date(1320192000000)/", 
		"to": "/Date(1321401600000)/", 
		"desc": "Id: 1<br/>Name:   Planning <br/>Date: 2011-11-02 to 2011-11-16", 
		"label": "  Planni...", 
		"customClass": "ganttRed"
		}]
}
		*/
		foreach($bugs as $bug) {
			$projectTasks[]=array(
				//	required data:
				'name'=>$bug->title,
				'desc'=>'',
				'values'=>array(
					array(
						'from'=>'/Date(' . strtotime($bug->created_at) . '000)/',
						'to'=>'/Date(' . $getDueDate($bug) . '000)/',
						'desc'=>'test description',
						'label'=>'0%',
						'customClass'=>'ganttBlue',
					)
				),
				//	additional data:
				'id'=>$bug->number,
				'usersIDs'=>array_map(
					function($n) {
						return $n->user_id;
					},
					$bug->user
				),
			);
		}
		return $projectTasks;
	}
	protected function getTasksCounts() {
		$sql='SELECT totalScalar.total, completedScalar.completed FROM
				(
					SELECT COUNT(*) AS total FROM {{bug}} WHERE 1
					AND project_id=:project_id
				) AS totalScalar,
				(
					SELECT COUNT(*) AS completed FROM {{bug}} WHERE 1
					AND project_id=:project_id
					AND isarchive IS NOT NULL
					AND isarchive <> 0
				) AS completedScalar';
		$params=array(':project_id'=>$this->project_id);
		$cmd=Yii::app()->db->createCommand();
		return $cmd->setText($sql)->queryRow(true, $params);
	}
	protected function actionGantt() {
		$this->response['source']=$this->getProjectTasks();
		$this->response['project']=
			Project::model()->findByPk($this->project_id);
		$this->response['users']=$this->getProjectUsers();
		$this->response['tasksCounts']=$this->getTasksCounts();
		//$this->response['tasks']=$this->getProjectTasks();
		$this->controller->respond($this->response);
	}
	
	protected function actionGanttByUser() {
		$user_id=(int)$this->request->getParam('user_id');
		if(empty($user_id))
			$this->controller->_404('Invalid set of parameters.');
	}
}