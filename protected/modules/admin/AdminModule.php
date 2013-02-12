<?php
/**
 * AdminModule
 *
 * @author f0t0n
 */
class AdminModule extends CWebModule {

    public $defaultController = 'user';
	public function init() {
		$uri = Yii::app()->request->requestUri;
		/*if(!preg_match('#^/admin/login.*?$#', $uri) && !$this->hasAccess())
			Yii::app()->request->redirect(Yii::app()->createUrl('/admin/login'));*/
		$this->layout = 'main';
		$this->setImport(array(
			'admin.components.*',
			'admin.models.*',
		));
	}
	
	/**
	 *
	 * @return boolean 
	 */
	protected function hasAccess() {
		return $this->debugHasAccess();
	}
	
	/**
	 *
	 * @return boolean 
	 */
	protected function debugHasAccess() {
		return Yii::app()->session->get('hasAdminAccess') === true;
	}
}