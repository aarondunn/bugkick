<?php
/**
 * ProjectUrlRuleEx
 *
 * @author f0t0n
 */
class ProjectUrlRuleEx extends CBaseUrlRule {

	public $connectionID = 'db';

	public function createUrl($manager, $route, $params, $ampersand) {
		$routeRule1 = $route === 'bug/view' && isset($params['id']);
		$routeRule2 = preg_match('#^ticket/(\d+)$#', $route, $matches) > 0;
		$routeRule3 = preg_match('#^bug(/index)?$#', $route);
		if($routeRule1) {
			$projectUrlName = $this->getProjectUrlName();
			if(!$projectUrlName)
				return false;
			return  $projectUrlName
				. '/' . $params['id'] . $this->getRoute($params, array('id'));
		}
		else if($routeRule2) {
			$params['id'] = $matches[1];
			$projectUrlName = $this->getProjectUrlName();
			if(!$projectUrlName)
				return false;
			return $projectUrlName . '/'
				. $params['id'] . $this->getRoute($params, array('id'));
		}
		else if($routeRule3) {
            $projectName = $this->getProjectUrlName();
            if (!empty($projectName))
                return $projectName . $this->getRoute($params);
            else
                return 'project/';
		}
		return false;
	}

    protected function updateWebUser($project) {
        if(!empty(Yii::app()->user) && !Yii::app()->user->isGuest) {
            Yii::app()->user->setState('company_id',
                $project->company->company_id);
            Yii::app()->user->setState('company_name',
                $project->company->company_name);
            Yii::app()->user->setState('is_global_admin',
                User::current()->is_global_admin);
            Yii::app()->user->setState('is_company_admin',
                User::current()->isCompanyAdmin($project->company->company_id));
            Yii::app()->user->setState('is_project_admin',
                User::current()->isProjectAdmin($project->project_id));
        }
    }

	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
		//	is ticket url
		if(preg_match('#^/?([0-9]+)/(ticket/)?(\d+)/?$#', $pathInfo, $matches)) {
//			$project = Project::model()->getByUrlName($matches[2]);
			$project = Project::model()->findByPk($matches[1]);
			if(empty($project))
				return false;

            $this->updateWebUser($project);

            //check if user is guest
			if(!isset(Yii::app()->user)) {
				return 'bug/';
			}
			if(Yii::app()->user->isGuest) {
                Yii::app()->user->setFlash('error','Please log in to view this ticket.');
                return 'bug/';
            }

			$currentProject = Project::getCurrent();
            
			if(empty($currentProject) || $currentProject->project_id != $project->project_id) {
				Project::setCurrent($project);
            }
			$_GET['id'] = $matches[3];
			return 'bug/view';
		}
		//	is tickets listing url
		else if(preg_match('#^/?([0-9]+)/?$#', $pathInfo, $matches)) {

//            $project = Project::model()->getByUrlName($matches[1]);
            $project = Project::model()->findByPk($matches[1]);

            if(empty($project)) {
                return false;
            }

            $this->updateWebUser($project);

            //check if user is guest
			if(!isset(Yii::app()->user)) {
				return 'bug/';
			}
            if(Yii::app()->user->isGuest){
                Yii::app()->user->setFlash('error','Please log in to view this project.');
                return 'bug/';
            }

            $currentProject = Project::getCurrent();
            if(empty($currentProject)|| $currentProject->project_id != $project->project_id){
                Project::setCurrent($project);
                User::updateCurrent();
                return 'bug/';
            }
            else if(!empty($currentProject) && $currentProject->project_id == $matches[1]){
                return 'bug/';
            }
		}
		return false;
	}

	protected function getRoute($params, $keysIgnore=array()) {
		$route ='';
		foreach($params as $k=>$v)
			if(!in_array($k, $keysIgnore))
				$route .= "{$k}={$v}&";
		return empty($route) ? '' : '?' . trim($route, ' &');
	}

	protected function getProjectUrlName() {
		$project = Project::getCurrent();
		return empty($project) ? false : $project->project_id;
	}
}