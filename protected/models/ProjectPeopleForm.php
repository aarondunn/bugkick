<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.11.12
 * Time: 21:41
 */

class ProjectPeopleForm extends FormModel {

    public $project_id;
	public $users = array();

	public function rules() {
		return array(
            array('project_id', 'numerical'),
			array('users', 'numArray'),
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
            'project_id'=>'Project',
			'users'=>'Users',
		);
	}
}