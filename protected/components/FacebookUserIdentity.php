<?php
/**
 * FacebookUserIdentity
 * 
 * @property User $model
 *
 * @author f0t0n
 */
class FacebookUserIdentity extends UserIdentity {
	
	public $facebook_id;
	protected $graph;
	
	public function __construct($graph) {
		$this->graph = $graph;
		$this->facebook_id = $this->graph['id'];
	}
	
	public function authenticate() {
		$user = $this->getUser();
		if(empty($user))
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
		else if($user->userStatus != User::STATUS_ACTIVE)
			 $this->errorCode = self::ERROR_USER_STATUS;
		else
			$this->init($user);
		return !$this->errorCode;
	}
	
	/**
	 *
	 * @return User 
	 */
	protected function getUser() {
		$user = User::model()->findByAttributes(
			array('facebook_id'=>$this->facebook_id)
		);
		if(empty($user)) {
			$email = $this->getFbEmail();
			if(!empty($email)) {
				$user = User::model()->findByAttributes(
					array('email'=>$email)
				);
				if(!empty($user) && empty($user->facebook_id)) {
					$user->facebook_id = $this->facebook_id;
					$user->save();
				}
			}
		}
		return $user;
	}
	
	protected function getFbEmail() {
		return empty($this->graph['email']) ? null : $this->graph['email'];
	}
	
	/**
	 *
	 * @param User $user 
	 */
	protected function init(User $user) {
		$this->username = $user->email;
		parent::init($user);
	}
}