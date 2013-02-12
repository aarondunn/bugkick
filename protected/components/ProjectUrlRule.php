<?php
/**
 * ProjectUrlRule
 *
 * @author f0t0n
 */
class ProjectUrlRule extends CBaseUrlRule {
	
	public $connectionID = 'db';
	
	public function createUrl($manager, $route, $params, $ampersand) {
		$routeRule1 = $route === 'bug/view' && isset($params['id']);
		$routeRule2 = preg_match('#^ticket/(\d+)$#', $route, $matches) > 0;
		$routeRule3 = preg_match('#^bug(/index)?$#', $route);
		if(!$routeRule1 && !$routeRule2 && !$routeRule3)
			return false;
		if($routeRule1) {
			$projectUrlName = $this->getProjectUrlName();
			if(!$projectUrlName)
				return false;
			return $projectUrlName . '/ticket/' 
					. $params['id'] . $this->getRoute($params, array('id'));
		}
		else if($routeRule2) {
			$params['id'] = $matches[1];
			$projectUrlName = $this->getProjectUrlName();
			if(!$projectUrlName)
				return false;
			return $projectUrlName . '/ticket/' 
					. $params['id'] . $this->getRoute($params, array('id'));
		}
		else if($routeRule3) {
			return $this->getProjectUrlName() . $this->getRoute($params);
		}
		return false;
	}
	
	protected function getRoute($params, $keysIgnore=array()) {
		$route ='';
		foreach($params as $k=>$v)
			if(!in_array($k, $keysIgnore))
				$route .= "{$k}={$v}&";
		return strlen($route) > 0 ? '?' . trim($route, ' &') : '';
	}
	
	protected function getProjectUrlName() {
		$project = Project::getCurrent();
		if(empty($project))
			return false;
		return Project::getProjectNameForUrl($project->name);
	}
	
	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
		if(preg_match(
			'#^/?([-_a-zA-Z0-9]+)/ticket/(\d+)/?$#', $pathInfo, $matches)) {
			$project = Project::model()->getByUrlName($matches[1]);
			if(empty($project))
				return false;
			$user = User::current();
			if(empty($user))
				return false;
			if(!$project->isCompanyAccessAllowed())
				return false;
			$currentProject = Project::getCurrent();
			if(empty($currentProject) 
					|| $currentProject->project_id != $project->project_id)
				Project::setCurrent($project);
			$_GET['id'] = $matches[2];
			return 'bug/view';
		}
		else if(preg_match('#^/?([-_\w]+)/?$#', $pathInfo, $matches)) {
			if($this->isRegularRoute($matches[1]))
				return false;
			$project = Project::getCurrent();
			if(empty($project)
				|| Project::getProjectNameForUrl($project->name) == $matches[1])
				return 'bug/';
		}
		return false;
	}
	
	protected function getProjectNameForUrl($projectName) {
		return Project::getProjectNameForUrl($projectName);
	}
	
	protected function isRegularRoute($pathInfoFirst) {
		if(empty($pathInfoFirst))
			return false;
		$controllersDir = Yii::getPathOfAlias('application.controllers') . '/';
		$controllersItems = scandir($controllersDir);
		$controllers = array();
		foreach($controllersItems as $item)
			if(is_file($controllersDir.$item)
				&& preg_match('/^(\w+)Controller\.php$/', $item, $matches)) {
				$controller = lcfirst($matches[1]);
				$controllers[$controller] = $controller;
			}
		return isset(Yii::app()->modules[$pathInfoFirst]) 
						|| isset($controllers[$pathInfoFirst]); 
	}
}