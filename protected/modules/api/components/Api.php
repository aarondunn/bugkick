<?php
/**
 * Api
 * 
 * All class methods named as apiXXX are a public API interface. <br />
 * Clients have to call the API methods without 'api' prefix in "camel" case. <br />
 * E.g. call method createBug to run apiCreateBug method of Api class and so forth. <br />
 *
 * @author f0t0n
 */
class Api extends CComponent {
	
	const API_CALL_POST_KEY='apiCall';
	
	protected static $_instance=null;
	protected static $allowedTicketTypes=array(
		'Bug',
		'Feature request',
		'Suggestion',
	);
	
	/**
	 *
	 * @var array 
	 */
	protected $reqData;
	/**
	 *
	 * @var Company
	 */
	protected $company;
	
	/**
	 *
	 * @var array
	 */
	protected $response;
	
	public function init() {
		$this->reqData=array();
		$this->company=null;
		$this->response=array('success'=>true, 'error'=>false);
	}
	
	protected function __construct() {
		$this->init();
	}
	
	/**
	 * Returns a singleton instance of class Api.
	 * @return Api A singleton instance of class Api
	 */
	public static function instance() {
		if(self::$_instance===null) {
			self::$_instance=new Api();
		}
		return self::$_instance;
	}
	
	public function run() {
		try{
			$this->initReqData();
			$this->initCompany();
			$this->checkApiKey();
			$this->checkAccessRights();
			$this->processCall();
		} catch(ApiException $ex) {
			$this->setResponseError($ex->getMessage());
			$this->respond($this->response);
		} catch(Exception $ex) {
			$this->setResponseError('Internal error.');
			$this->respond($this->response);
		}
	}
	
	protected function setResponseError($error) {
		$this->response['success']=false;
		$this->response['error']=$error;
	}
	
	protected function initReqData() {
		$this->reqData=Yii::app()->request->getPost(self::API_CALL_POST_KEY);
		if(empty($this->reqData)) {
			throw new ApiException('Invalid request.');
		}
	}
	
	protected function initCompany() {
		$this->company=Company::model()->find(
			'api_key=:api_key AND account_type=:account_type',
			array(
				':api_key'=>$this->reqData['apiKey'],
				':account_type'=>Company::TYPE_PAY
			)
		);
	}
	
	protected function checkApiKey() {
		if(empty($this->reqData['apiKey'])) {
			throw new ApiException('Wrong API key.');
		}
	}
	
	protected function checkAccessRights() {
		if(empty($this->company)) {
			throw new ApiException('Company has no rights to access API or API key is not valid.');
		}
	}
	
	protected function processCall() {
		$method=$this->getMethod();
		$this->$method();
	}
	
	protected function getMethod() {
		if(empty($this->reqData['method'])) {
			$this->wrongMethod();
		}
		$method='api'.ucfirst($this->reqData['method']);
		if(!method_exists($this, $method)) {
			$this->wrongMethod();
		}
		return $method;
	}
	
	protected function wrongMethod() {
		throw new ApiException('Wrong method called.');
	}
	
	protected function respond($data) {
		header('Content-Type: application/json; charset=UTF-8');
		echo CJSON::encode($data);
		//Yii::app()->end();
	}
	
	/**
	 *
	 * @return Project
	 */
	protected function getProject() {
		if(empty($this->reqData['projectID'])) {
			throw new ApiException('Invalid projectID.');
		}
		$project=Project::model()->find(
			'api_id=:api_id',
			array(':api_id'=>$this->reqData['projectID'])
		);
		if(empty($project)) {
			throw new ApiException('Project not found.');
		}
		return $project;
	}
	
	protected function checkCreateBugData() {
        if(!$this->validateEmail($this->reqData['ticketEmail'])) {
            throw new ApiException('Email is not correct');
        }
		if(empty($this->reqData['ticketText'])) {
			throw new ApiException('Ticket data is absent');
		}
	}
	
	protected function getTicketType() {
		if(empty($this->reqData['ticketType'])
			|| !$this->validateTicketType()) {
			throw new ApiException('Wrong type of ticket');
		}
		return $this->reqData['ticketType'];
	}
	
	protected function validateTicketType() {
		return in_array($this->reqData['ticketType'], self::$allowedTicketTypes);
	}
	
	protected function createBug(BugBase $bug, Project $project) {
		$transaction = $bug->getDbConnection()->beginTransaction();
		try {
            $this->prepareTitleAndDescription($bug);
            $bug->api_user_email = $this->reqData['ticketEmail'];
			$globalPrevBug = null;
			$prevBug = $this->getPrevBug($project);
			$bug->project_id = $project->project_id;
			$bug->next_id = 0;
			$bug->next_number = 0;
			if(empty($prevBug)) {
				$globalPrevBug = Bug::model()->resetScope()->find('next_id=0');
				$bug->prev_id = empty($globalPrevBug) ? 0 : $globalPrevBug->id;
				$bug->prev_number = 0;
				$bug->number = 1;
                $bug->priority_order = 1;
			} else {
				$bug->prev_id = $prevBug->id;
				$bug->prev_number = $prevBug->number;
				$bug->number = $prevBug->number + 1;
                $bug->priority_order = $prevBug->id + 1;
			}
			$this->saveBugChain($bug, $prevBug, $globalPrevBug);
			$transaction->commit();
			return true;
		} catch(Exception $ex) {
			$transaction->rollback();
			throw new ApiException($ex->getMessage());
			return false;
		}
	}
	
	/**
	 *
	 * @param Project $project
	 * @return Bug 
	 */
	protected function getPrevBug(Project $project) {
		return Bug::model()->resetScope()->find(
			'project_id=:project_id AND next_number=0',
			array(':project_id'=>$project->project_id)
		);
	}
	
	protected function saveBugChain(BugBase $bug, $prevBug, $globalPrevBug) {
		$this->saveNewBug($bug);
		$this->savePrevBug($bug, $prevBug);
		$this->saveGlobalPrevBug($bug, $globalPrevBug);
	}
	
	protected function saveNewBug(BugBase $bug) {
		if(!$bug->save()) {
			throw new Exception('New bug has not been saved');
		}
	}
	
	protected function savePrevBug(BugBase $bug, $prevBug) {
		if(!empty($prevBug)) {
			$prevBug->next_id = $bug->id;
			$prevBug->next_number = $bug->number;
			if(!$prevBug->save()) {
				throw new Exception('Previous bug has not been saved');
			}
		}
	}
	
	protected function saveGlobalPrevBug(BugBase $bug, $globalPrevBug) {
		if(!empty($globalPrevBug)) {
			$globalPrevBug->next_id = $bug->id;
			if(!$globalPrevBug->save()) {
				throw new Exception('Previous bug has not been saved');
			}
		}
	}
	
	protected function prepareTitleAndDescription(BugBase $bug) {
		$p=new CHtmlPurifier();
		$ticket=$p->purify($this->reqData['ticketText']);
		$titleLength=60;
		if(strlen($ticket) > $titleLength) {
			$title=Helper::neatTrim($ticket, $titleLength, '');
			$newLineIndex=strpos($title, "\n\n");
			if($newLineIndex) {
				$title=substr($title, 0, $newLineIndex);
			}
			$bug->description=substr($ticket, strlen($title));
			$bug->title=$title . '...';
		} else {
			$bug->title=$ticket;
			$bug->description=$ticket;
		}
    }
	
	//													API interface	BEGIN
	protected function apiCreateTicket() {
		$project=$this->getProject();
		$this->checkCreateBugData();
		$bug=new Bug();
		$bug->company_id=$this->company->company_id;
		$bug->project_id=$project->project_id;
		$bug->is_created_with_api=1;
		$bug->label_id=0;
		$bug->user_id=0;
		$bug->type=$this->getTicketType();
		if($this->createBug($bug, $project)) {
			$this->setDefaultAssignee($bug, $project);
			Notificator::newBug($bug);	//send notification
		} else {
			throw new ApiException('Internal error. Ticket has not been created.');
		}
		$this->respond($this->response);
	}
	
	protected function setDefaultAssignee(BugBase $bug, Project $project) {
		if(!empty($project->api_ticket_default_assignee)) {
			$bugByUser=new BugByUser();
			$bugByUser->bug_id=$bug->id;
			$bugByUser->user_id=$project->api_ticket_default_assignee;
			$bugByUser->save();
		}
	}
	//													API interface	END

    // Widget methods
    public function getAllowedTicketTypes()
    {
        return self::$allowedTicketTypes;
    }

    /**
     * @param int $projectID
     * @return Project
     */
    public function checkProjectID($projectID)
    {
        $project=Project::model()->find(
            'api_id=:api_id',
            array(':api_id'=>$projectID)
        );
        if(empty($project))
            return false;
        else
            return $project;
    }
     // Widget methods END

    /**
     * Checks if email is valid
     * @param $email
     * @return bool
     */
    protected function validateEmail($email)
    {
        $validator = new CEmailValidator;
        return $validator->validateValue($email);
    }
}