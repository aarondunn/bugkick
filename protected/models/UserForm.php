<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 14.12.11
 * Time: 14:39
 */

class UserForm extends FormModel {

    public $user_id;
	public $projects = array();
    public $is_company_admin;

	public function rules() {
		return array(
            array('user_id', 'numerical'),
			array('projects', 'numArray'),
			array('is_company_admin', 'numerical', 'integerOnly' => true),
			array('user_id, projects', 'safe', 'on'=>'search'),
		);
	}

	public function numArray($attribute, $params) {
		if(!is_array($this->$attribute) || !count($this->$attribute))
			$this->addError($attribute, 'Wrong list of identifiers');
		foreach($this->$attribute as $id)
			if(!(int)$id) {
				$this->addError($attribute, 'Wrong identifier');
				break;
			}
	}

	public function attributeLabels() {
		return array(
            'user_id'=>'User',
			'projects'=>'Projects',
			'is_company_admin'=>'Company Admin',
		);
	}
}