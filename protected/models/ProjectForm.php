<?php
/**
 * ProjectForm
 *
 * @author f0t0n
 */
class ProjectForm extends FormModel {

	public $company_id;
	public $name;
	public $description;
	public $logo;
	public $home_page;
	public $tmpFileID;
	public $api_id;
    public $api_ticket_default_assignee;
    public $users;
    public $labels;
    public $archived;
    public $github_user_id;
    public $github_repo;
    public $translate_tickets;
    public $connectToGitHub = false;
    
    public function init() {
        parent::init();
        $this->initConnectToGitHub();
    }

    protected function initConnectToGitHub() {
        $company = Company::model()->findByPk(Company::current());
        $this->connectToGitHub = !empty($company)
            && $company->isGitHubIntegrationAvailable();
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		$rules = array_merge(
			Project::model()->rules(),
			array(
				array('tmpFileID', 'numerical', 'allowEmpty'=>true),
				array('tmpFileID', 'default', 'value'=>0),
                array('users, labels', 'numArray'),
				/*array(
					'logo',
					'length', 
					'allowEmpty'=>true,
					'types'=>'jpeg, jpeg, gif, png',
					'maxFiles'=>1,
					'maxSize'=>'524288'	//	512 KB
				),*/
			)
		);
        return $rules;
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

    public static function itemAlias($type,$code=NULL)
    {
        $_items = array(
            'archived' => array(
                '0' => 'No',
                '1' => 'Yes',
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    public function beforeValidate(){
        $this->company_id = Company::current();
        return parent::beforeValidate();
    }

}