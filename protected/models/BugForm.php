<?php
/**
 * BugForm
 *
 * @author f0t0n
 */
class BugForm extends FormModel {
	
	const SCENARIO_CREATE='create';
	const SCENARIO_EDIT='edit';

    protected static $instancesCount = 0;
	
	public $number=0;
	public $id=0;
	public $title;
	public $description;
	public $duedate;
	public $status_id;
	public $duplicate_number;
	public $assignees=array();
	public $labels=array();
    protected $instanceNumber;
	
	public function __construct($scenario=self::SCENARIO_CREATE) {
		parent::__construct($scenario);
        $this->instanceNumber = self::$instancesCount++;
	}
	
	public function rules() {
		return array(
			array('title, description', 'required'),
			//array('number', 'required', 'on'=>self::SCENARIO_EDIT),
			array('id', 'required', 'on'=>self::SCENARIO_EDIT),
			array('title', 'length', 'max'=>255),
			array('description', 'length', 'max'=>65536),
			array('status_id, number, duplicate_number', 'numerical'),
            array('duplicate_number', 'application.extensions.validators.TicketNumberValidator'),
			array('assignees, labels', 'numArray'),
			array('number, id, title, description, duedate, status_id, duplicate_number', 'safe', 'on'=>self::SCENARIO_EDIT),
		);
	}
	
	public function numArray($attribute, $params) {

        $allowEmpty = true;

        if($allowEmpty && ($this->$attribute==null))
        	return;

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
			'title'=>'Title',
			'description'=>'Description',
			'duedate'=>'Due date',
			'status'=>'Status',
			'assignees'=>'Assignees',
			'labels'=>'Labels',
		);
	}

    public function getAttributeId($attributeName) {
        return get_class($this) 
            . '_' . $this->instanceNumber . '_' . $attributeName;
    }
}