<?php
/**
 * UserGroupForm
 *
 * @author f0t0n
 */
class UserGroupForm extends FormModel {
	
	public $company_id;
	public $project_id;
	public $name;
	public $color;
	public $image;
	/**
	 *
	 * @var array
	 */
	public $user_ids;
	public $project_ids;

	public function init() {
		$this->company_id=Company::current();
		$this->project_id=Project::getCurrent()->project_id;
		$this->color='#fff';
		$this->image=null;
		$this->user_ids=array();
		$this->project_ids=array();
		parent::init();
	}
	public function rules() {
		return array(
			array('name', 'required'),
			array('company_id, project_id', 'numerical'),
			array('company_id', 'default', 'value'=>Company::current()),
			array('project_id', 'default', 'value'=>Project::getCurrent()->project_id),
			array('name, image', 'length', 'max'=>255),
			array('color', 'length', 'max'=>7),
			array('user_ids, project_ids', 'numArray'),
			array('user_ids, project_ids', 'default', 'value'=>array()),
			array('user_ids, project_ids, company_id, project_id, name, color, image', 'safe')
		);
	}
	
	public function numArray($attribute, $params) {
		if(!is_array($this->$attribute))
			$this->addError($attribute, 'Wrong list of identifiers');
		foreach($this->$attribute as $id)
			if(!(int)$id) {
				$this->addError($attribute, 'Wrong identifier');
				break;
			}
	}
	
	public function attributeLabels() {
		return array(
			'company_id'=>Yii::t('main','Company'),
			'project_id'=>Yii::t('main','Project'),
			'name'=>Yii::t('main','Name'),
			'color'=>Yii::t('main','Color'),
			'image'=>Yii::t('main','Image'),
			'user_ids'=>Yii::t('main', 'Members'),
			'project_ids'=>Yii::t('main', 'Projects'),
		);
	}
}