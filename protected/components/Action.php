<?php
/**
 * Action
 * 
 * @property Controller $controller
 * @property CHttpSession $session An equivalent of call Yii::app()->session
 * @property CHttpRequest $request The value returned by calling Yii::app()->getRequest().
 * 
 * @todo Put overrides for general cases here
 * @author f0t0n
 */
class Action extends CAction {
	
	/**
	 * @var array The data that will used to render within view.
	 */
	protected $viewData;
	
	public function __construct($controller, $id) {
		parent::__construct($controller, $id);
		$this->viewData = array();
	}
	
	/**
	 *
	 * @return array Action's viewData.
	 */
	public function getViewData() {
		return $this->viewData;
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
	 * @return CHttpSession Equivalent of call Yii::app()->session 
	 */
	public function getSession() {
		return Yii::app()->session;
	}
	
}