<?php

/**
 * This is the model class for table "{{project}}".
 *
 * The followings are the available columns in table '{{project}}':
 * @property string $project_id
 * @property string $company_id
 * @property string $name
 * @property string $url_name
 * @property string $description
 * @property string $logo
 * @property string $home_page
 * @property string $api_id
 * @property int $api_ticket_default_assignee
 * @property int $archived
 * @property string $github_auth_token The authorization token retrieved
 * @property string $github_user_id
 * @property GithubUser $githubUser
 * @property string $github_repo
 * @property string $translate_tickets
 * from GitHub during OAuth authorization process.
 *
 * @method Project currentCompany() Returns a finder of projects that belongs to current company.
 */
class Project extends CActiveRecord
{
	protected $_companyName = null;
	
	public function getCompanyName() {
		if(empty($this->_companyName) && !empty($this->company))
			$this->_companyName = $this->company->company_name;
		return $this->_companyName;
	}
	
	public function setCompanyName($companyName) {
		$this->_companyName = $companyName;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{project}}';
	}
	
	public function primaryKey() {
		return 'project_id';
	}
	
	protected function beforeSave() {
		$this->setUrlName();
		$this->setApiId();
		return parent::beforeSave();
	}
	
	protected function setUrlName() {
		$this->url_name=self::getProjectNameForUrl($this->name);
		$this->makeUrlNameUnique();
	}
	
	protected function makeUrlNameUnique() {
		if(empty($this->url_name)) {
			throw new CDbException('url_name is not initialized.');
		}
		 //case when we updating existing project
        if(!$this->isNewRecord){
            $isUrlNameExists=$this->exists(
                'url_name=:url_name AND project_id!=:project_id',
                array(':url_name'=>$this->url_name, ':project_id'=>$this->project_id)
            );
            if($isUrlNameExists)
            	$this->url_name.='-'.$this->project_id;
        }
        else{
            //case when we creating new project
            $isUrlNameExists=$this->exists(
                'url_name=:url_name',
                array(':url_name'=>$this->url_name)
            );
            if($isUrlNameExists){
                $projectID =  $this->dbConnection->createCommand('SELECT MAX(project_id)+1 FROM {{project}}')->queryScalar();
                $this->url_name.='-'.$projectID;
            }
        }
	}
	
	protected function setApiId() {
		if(empty($this->api_id)) {
			// Prefix "pid_" means "project id" to make user 
			// more easy determine what is his API key* 
			// and what is the project id.
			// *API key is just a 32 characters without any prefix.
			$this->api_id='pid_'.$this->generateApiId();
		}
	}
	
	protected function generateApiId() {
		$key=$this->project_id . '-' . microtime() . '-' . uniqid(null, true);
		$key=md5($key);
		$charArray=str_split($key);
		$apiId='';
		foreach($charArray as $character) {
			$apiId .= mt_rand(0, 1) === 0 ? $character : strtoupper($character);
		}
		return $apiId;
	}
	
	public static function getProjectNameForUrl($projectName) {
		$projectNameForUrl = preg_replace('/[^-_a-zA-Z0-9]/', '-', $projectName);
		$projectNameForUrl = preg_replace('/-+/', '-', $projectNameForUrl);
		return strtolower(
			trim($projectNameForUrl, '- ')
		);
	}
	
	public function getByUrlName($urlName) {
		return $this->find('url_name=:url_name', array(':url_name'=>$urlName));
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, name', 'required'),
			array('company_id', 'length', 'max'=>10),
			array('github_repo', 'length', 'max'=>255, 'allowEmpty'=>true),
            array('translate_tickets', 'boolean', 'trueValue'=>1, 'falseValue'=>0),
            array('translate_tickets', 'default', 'value'=>0, 'setOnEmpty'=>true),
			array('github_user_id', 'length', 'max'=>255, 'allowEmpty'=>true),
			array('name, logo, home_page, api_id', 'length', 'max'=>255),
			array(
                'api_id, api_ticket_default_assignee, github_repo, github_user_id',
                'default',
                'value'=>null
            ),
			array('description', 'safe'),
            array('archived, translate_tickets', 'in', 'range'=>array(0,1)),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('project_id, archived, github_user_id, github_repo, translate_tickets, company_id, companyName, name, description, logo, home_page, api_id, api_ticket_default_assignee',
                'safe',
                'on'=>'search'
            ),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'invites'=>array(self::HAS_MANY, 'Invite', 'company_id'),
			'company'=>array(self::BELONGS_TO, 'Company', 'company_id'),
			'bugs'=>array(self::HAS_MANY, 'Bug', 'project_id'),
            'users'=>array(
                self::MANY_MANY,
                'User',
                '{{user_by_project}}(project_id, user_id)',
                'order' => 'name'
            ),
            'githubUser'=>array(self::BELONGS_TO, 'GithubUser', 'github_user_id'),
            'labels'=>array(
                self::MANY_MANY,
                'Label',
                '{{label_by_project}}(project_id, label_id)',
                'order' => 'name'
            ),
            'project_settings'=>array(self::HAS_ONE, 'SettingsByProject', 'project_id'),
            'groups'=>array(
                self::MANY_MANY,
                'UserGroup',
                '{{project_by_group}}(project_id, group_id)',
                'order' => 'name'
            ),
		);
	}
	
	public function scopes() {
        $scopes = array(
			'currentCompany'=>array(
				'condition'=>'t.company_id=:company_id',
				'params'=>array(
					':company_id'=>Company::current(),
				),
			),
            'active'=>array('condition' => 'archived=0'),
            'archived'=>array('condition' => 'archived=1'),
		);
        if(Yii::app() instanceof CWebApplication) {
            $scopes['visibleOnly'] = array('condition'=> 't.project_id IN(
                SELECT ubp.project_id FROM {{user_by_project}} AS ubp WHERE 1
                    AND ubp.user_id = :user_id
                )',
                'params'=>array(':user_id'=>User::current()->user_id)
            );
            $scopes['ownedByCurrentUser'] = array('condition'=>'(
                    t.project_id IN(
                        SELECT ubp.project_id FROM {{user_by_project}} AS ubp
                            WHERE 1
                            AND ubp.user_id = :user_id
                            AND ubp.is_admin = 1
                    )
                    OR t.project_id IN(
                        SELECT ubc.company_id FROM {{user_by_company}} as ubc
                        WHERE 1
                        AND ubc.user_id = :user_id
                        AND ubc.company_id = t.company_id
                        AND ubc.is_admin = 1
                    )
                )',
                'params'=>array(':user_id'=>User::current()->user_id),
            );
        }
        return $scopes;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'project_id' => 'Project',
			'company_id' => 'Company',
			'name' => 'Name',
			'description' => 'Description',
			'logo' => 'Logo',
			'home_page' => 'Home Page',
			'api_ticket_default_assignee'=>'Default assignee for API tickets',
			'archived'=>'Archived',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('home_page',$this->home_page,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function gridSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;
		$userID = Yii::app()->user->id;
		$criteria->with = array(
			'company'=>array(
				'select'=>'company_name',
				'condition'=>
<<<SQL
			company.company_id IN (
				SELECT `company_id` FROM {{user_by_company}}
					WHERE 1
					AND user_id = $userID
			)
SQL
				,
			),
		);
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('company.company_name',$this->_companyName,true);
		$sort = new CSort(__CLASS__);
		$sort->attributes = array(
			'defaultOrder'=>'t.project_id ASC',
			'project_id'=>array(
				'asc'=>'t.project_id ASC',
				'desc'=>'t.project_id DESC',
			),
			'name'=>array(
				'asc'=>'t.name ASC',
				'desc'=>'t.name DESC',
			),
			'companyName'=>array(
				'asc'=>'company.company_name ASC',
				'desc'=>'company.company_name DESC',
			),
		);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
		));
	}
	
	/**
     * Returns path to the image or link when s3 enabled.
     * !important: For S3 available only 1 size: 70*70.
     * For local storage all sizes are available.
     * @param int $width
	 * @param int $height
	 * @param int $quality
	 * @return string The path to project image.
	 */
	public function getLogoSrc($width = 70, $height = 70, $quality = 85)
    {
        $path = Yii::app()->baseUrl . '/images/project_logo/';
        if(empty($this->logo)){
            return $path . 'thumb/default.jpg';
        }
        elseif(substr_count($this->logo, 'defaults/')>0){
            return $path . $this->logo;
        }
        else{
            switch (Yii::app()->params['storageType']){
                 case 's3':
                     return Storage::get('s3')->getFilePath( $width .'_'.$height.'_'. $this->logo, S3Storage::PROJECT_BUCKET);
                     break;
                 case 'local':
                     if(is_file('images/project_logo/' . $this->logo)){
                         return ImageHelper::thumb(
                             $width,
                             $height,
                             'images/project_logo/' . $this->logo,
                             $quality
                         );
                     }
                     break;
            }
        }
	}
	
	/**
	 *
	 * @return Project 
	 */
	public static function getCurrent() {
		$user = User::current(); //User::model()->findByPk(Yii::app()->user->id);
		return empty($user) ? false : $user->currentProject;
	}
	
	/**
	 *
	 * @param Project $project 
	 */
	public static function setCurrent($project) {
		$user = User::current();// User::model()->findByPk(Yii::app()->user->id);
		if(!$project->isCompanyAccessAllowed())
			return false;
		if(empty($user))
			return false;
		if(empty($project)) {
			$user->current_project_id = null;
            if($user->save()){
                User::updateCurrent(); //updating User singleton
                return true;
            }
            else{
                return false;
            }
		}
		else if($project->project_id == $user->current_project_id)
			return true;
		$user->current_project_id = $project->project_id;
        if($user->save()){
            User::updateCurrent(); //updating User singleton
            return true;
        }
        else{
            return false;
        }
		/*
		if(empty($project))
			Yii::app()->session->remove('menu_selected_project');
		else
			Yii::app()->session->add('menu_selected_project', $project);
		 */
	}
	
	public function isCompanyAccessAllowed()
    {
		$user = User::current();//User::model()->findByPk(Yii::app()->user->id);
		if(empty($user))
			return false;
		return UserByCompany::model()->exists(
			'company_id=:company_id AND user_id=:user_id',
			array(
				':company_id'=>$this->company_id, 
				':user_id'=>$user->id
			)
		);
	}

    public static function isProjectAccessAllowed($projectID = '', $userID = '')
    {
        if (empty($userID))
            $user = User::current();
        else
            $user = User::model()->findByPk($userID);

   		if(empty($user))
   			return false;

        if (empty($projectID))
            $projectID = $user->current_project_id;

        if (empty($projectID))
            return false;

   		return UserByProject::model()->exists(
   			'project_id=:project_id AND user_id=:user_id',
   			array(
   				':project_id'=>$projectID,
   				':user_id'=>$user->id
   			)
   		);
   	}

    //get default project settings
    public static function getProjectSettings()
    {
        $project = self::getCurrent();
		return empty($project) || empty($project->project_settings)
			? null
			: $project->project_settings;
    }

    //returns default bug label
    public static function getBugLabel()
    {
        $project = self::getCurrent();
        if(empty($project))
            return null;

        $criteria = new CDbCriteria();
        $criteria->condition = 't.name=:name AND t.pre_created=:pre_created';
        $criteria->params = array(
            ':name'=>'Bug',
            ':pre_created'=>1,
            ':project_id'=>$project->project_id,
        );
        $criteria->with = array(
            'projects'=>array(
                'condition'=>'projects.project_id=:project_id'
            )
        );
        return Label::model()->find($criteria);
    }

    public static function getUsers($projectID = '')
    {
        if (!empty($projectID))
            $project = self::model()->findByPk($projectID);
        else
            $project = self::getCurrent();

        if (!empty($project))
            return $project->users;
        else
            return null;
    }

    public static function getLabels($projectID = '')
    {
        if (!empty($projectID))
            $project = self::model()->findByPk($projectID);
        else
            $project = self::getCurrent();

        if (!empty($project))
            return $project->labels;
        else
            return null;
    }

    public static function getUserGroups($projectID = '')
    {
        if (!empty($projectID))
            $project = self::model()->findByPk($projectID);
        else
            $project = self::getCurrent();

        if (!empty($project))
            return $project->groups;
        else
            return null;
    }

    /**
     * Attempts to add given user to current project.
     * @param User $user
     * @param int $isAdmin
     * @return UserByProject UserByProject model or null on failure.
     */
    public function addUser(User $user,$isAdmin=1) {
        $userByProject = UserByProject::model()->findByAttributes(array(
            'project_id'=>$this->project_id,
            'user_id'=>$user->user_id,
        ));
        if(!empty($userByProject) && $this->company->addUser($user) !== null) {
            return $userByProject;
        }
        $userByProject = new UserByProject();
        $userByProject->project_id = $this->project_id;
        $userByProject->user_id = $user->user_id;
        $userByProject->is_admin = $isAdmin;
        return $userByProject->save() && $this->company->addUser($user,$isAdmin) !== null
            ? $userByProject
            : null;
    }

    public function removeUser(User $user) {
        return UserByProject::model()->deleteAllByAttributes(array(
            'project_id'=>$this->project_id,
            'user_id'=>$user->user_id
        ));
    }

    public static function getBugs($projectID = '')
    {
        if (empty($projectID))
            $project = Project::getCurrent();
        else
            $project = Project::model()->findByPk($projectID);

        return empty($project)? null : $project->bugs;
    }

    /**
     * Returns listData of users that are in the company but not in the project
     * @param string $projectID
     * @return array
     */
    public static function getInvitePeopleListData($projectID = '')
    {
        $users = array();
        if (empty($projectID))
            $project = Project::getCurrent();
        else
            $project = Project::model()->findByPk($projectID);

        if(!empty($project)){
            $projectUsers = Project::getUsers($project->project_id);
            $companyUsers = Company::getUsers();

            if(is_array($projectUsers) && is_array($companyUsers)){
                $users = array_diff(
                    CHtml::listData($companyUsers, 'user_id', 'name'),
                    CHtml::listData($projectUsers, 'user_id', 'name'));
            }
        }
            return $users;
    }
}