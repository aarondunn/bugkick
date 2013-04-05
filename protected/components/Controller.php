<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 * 
 * @property CHttpSession $session An equivalent of call Yii::app()->session.
 * @property CHttpRequest $request The value returned by calling Yii::app()->getRequest().
 * @property CClientScript $clientScript The value returned by calling Yii::app()->clientScript.
 */
class Controller extends CController
{
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        //if(Yii::app()->user->isGuest)
                //$this->redirect('/login');
        //if(empty(Yii::app()->user->company_id))
                //$this->redirect('/company');
    }
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
    
    public function complete($message, $status, $stop = false){
		if ( Yii::app()->request->isAjaxRequest ){
			echo json_encode(array('status' => $status, 'message' => $message));
			die;
		}else{
			Flashes::addFlash($status, $message);
			//if ( $stop )
				//$this->redirect('/site/message/');
		}
	}
    
    //if for current user not set company redirect to company
    public function filterCompanySet($filterChain) {

        if (empty(Yii::app()->user->company_id))
            //$this->render('application.views.company.index');
            $this->redirect($this->createUrl('company/'));

        $filterChain->run();
    }
 
	/**
	 *
	 * @return CHttpRequest The value returned by calling Yii::app()->getRequest();
	 */
	public function getRequest() {
		return Yii::app()->getRequest();
	}
	
	/**
	 *
	 * @return CClientScript The value returned by calling Yii::app()->clientScript;
	 */
	public function getClientScript() {
		return Yii::app()->clientScript;
	}
	/**
	 *
	 * @return CHttpSession Equivalent of call Yii::app()->session 
	 */
	public function getSession() {	
		return Yii::app()->session;
	}
	
	public function lookAndFeel() {
		$user = User::current();
		$lookAndFeel = empty($user) ? null : $user->laf;
		return empty($lookAndFeel) ? null : $lookAndFeel;
	}
	
	public function getProjectsDropDown($htmlOptions) {
		$sql = '
		SELECT p.project_id, p.name FROM {{project}} AS p
			WHERE p.company_id IN (
				SELECT ubc.company_id FROM {{user_by_company}} AS ubc
					WHERE ubc.user_id = :user_id
			)
		';
		$projects = Yii::app()->db->createCommand($sql)->queryAll(
			true,
			array(':user_id'=>Yii::app()->user->id)
		);
		$data = array(''=>'Select a project');
		foreach($projects as $p)
			$data[$p['project_id']] = $p['name'];
		unset($projects);
		$selectedProject = Project::getCurrent();
		return CHtml::dropDownList(
			'menu_project_id',
			empty($selectedProject) ? null : $selectedProject->project_id,
			$data,
			array_merge(array('style'=>'margin:0 5px 0 5px;'), $htmlOptions)
		);
	}

    public function getProjectsData() {
		/*$sql = '
		SELECT p.project_id, p.name FROM {{project}} AS p
			WHERE p.company_id IN (
				SELECT ubc.company_id FROM {{user_by_company}} AS ubc
					WHERE ubc.user_id = :user_id
			)
			AND p.archived=0
		';
        $sql = '
        SELECT p.project_id, p.name FROM {{project}} AS p
            JOIN {{user_by_company}} AS ubc
                ON p.company_id = ubc.company_id AND ubc.user_id = :user_id
            JOIN {{user_by_project}} AS ubp
                ON  p.project_id = ubp.project_id AND ubp.user_id = :user_id
            WHERE p.archived = 0
        ';*/
        $sql = '
        SELECT p.project_id, p.name ,p.archived FROM {{project}} AS p
            JOIN (
                {{user_by_company}} AS ubc,
                {{user_by_project}} AS ubp
            )
            ON (
                ubc.company_id = p.company_id
                AND ubc.user_id = :user_id
                AND ubp.user_id = :user_id
                AND ubp.project_id = p.project_id
            )
            WHERE p.archived = 0
            ORDER BY p.name
        ';
		$projects = Yii::app()->db->createCommand($sql)->queryAll(
			true,
			array(':user_id'=>Yii::app()->user->id)
		);
		$data = array();
		foreach($projects as $p)
			$data[$p['project_id']] = $p['name'];
		unset($projects);
		$selectedProject = Project::getCurrent();
		if(empty($selectedProject)) {
			$projectsData['selected']['project_id'] = null;
			$projectsData['selected']['name'] = null;
		}
		else {
			$projectsData['selected']['project_id'] = $selectedProject->project_id;
			$projectsData['selected']['name'] = $selectedProject->name;
                        $projectsData['selected']['archived'] = $selectedProject->archived;
		}
        $projectsData['data'] = $data;
        return $projectsData;
	}

    protected static function getMimeType($contentType) {
		$leftPart =$contentType==ResponseType::JSON ? 'application/' : 'text/';
		return $leftPart . $contentType;
	}

	/**
	 * AJAX response helper method.
	 *
	 * @param mixed $response
	 * If var is a string, <br />
	 * it will be converted to UTF-8 format first before being encoded.
	 *
	 * @param string $contentType Set one of the following formats: <br />
	 * 'html', 'xml', 'json', 'plain'. Default format is 'json'. <br />
	 * You can use constants of ResponseType class to pass into method.
	 *
	 * @author f0t0n
	 */
	public function respond($response, $contentType = ResponseType::JSON) {
		$contentTypes = array(
			ResponseType::PLAIN,
			ResponseType::JSON,
			ResponseType::HTML,
			ResponseType::XML,
		);
		$_contentType = in_array($contentType, $contentTypes)
			? $contentType
			: ResponseType::JSON;
        $mimeType=self::getMimeType($_contentType);
		header("Content-Type: {$mimeType}; charset=UTF-8");
		echo $_contentType == ResponseType::JSON
			? CJSON::encode($response)
			: $response;
		Yii::app()->end();
	}
	
	/**
     * Performs the AJAX validation.
     * @param CModel $model the model to be validated
	 * @param string $formID The ID of form to validate.
     */
    public function performAjaxValidation($model, $formID) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $formID) {
			header('Content-Type: text/json; charset=UTF-8');
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
    }
	
	public function renderFlash() {
		$this->renderPartial('application.views.site._flash');
	}
	
	/**
	 *
	 * Throws a CHttpException with status 404 and message $message.
	 * @param string $message
     * @throws CHttpException
	 */
	public function _404($message) {
		throw new CHttpException(404, $message);
	}

    public function redirectToDefaultPage()
    {
        $this->redirect($this->getDefaultPage());
    }

    public function getDefaultPage()
    {
        $user = User::current();
        if(!empty($user)){
            switch($user->default_page){
                case User::DEFAULT_PAGE_DASHBOARD:
                    return $this->createAbsoluteUrl('/site/dashboard');
                    break;
                case User::DEFAULT_PAGE_TICKETS_LIST:
                    return $this->createAbsoluteUrl('/bug/');
                    break;
            }
        }
        return $this->createAbsoluteUrl('/');
    }
}