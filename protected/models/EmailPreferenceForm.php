<?php
/**
 * EmailPreferenceForm
 * 
 * @property array $preferences All existing e-mail options
 * @property array $turnedOn The array of turned-on mailing options
 *
 * @author f0t0n
 */
class EmailPreferenceForm extends FormModel {
	
	protected $_preferences;
	public $email_notify;
	public $turnedOn;
	
	public function getPreferences() {
		return $this->_preferences;
	}
	
	public function init() {
		$this->_preferences=array();
		$this->email_notify=User::current()->email_notify;
		$this->turnedOn=array();
		$preferences=EmailPreference::model()->findAll();
		foreach($preferences as $pref)
			$this->_preferences[$pref->email_preference_id]=$pref->name . ': '; //Added to display the colon on the email settings page
		$user = User::current();
		if(!empty($user))
			foreach($user->emailPreferences as $pref)
				$this->turnedOn[] = $pref->email_preference_id;
		parent::init();
	}
	
	public function rules() {
		return array(
			array('email_notify', 'numerical', 'integerOnly'=>true),
			array('turnedOn, email_notify', 'safe'),
		);
	}
	
	public function setAttributes($values, $safeOnly = true) {
		if(empty($values['turnedOn'])||!is_array($values['turnedOn']))
			$values['turnedOn']=array();
		parent::setAttributes($values, $safeOnly);
	}
	
	public function attributeLabels() {
		return array(
			'email_notify'=>'Email when my tickets were changed: ',
		);
	}
}