<?php

/**
 * This is the model class for table "{{user_group}}".
 *
 * The followings are the available columns in table '{{user_group}}':
 * @property string $group_id
 * @property integer $company_id
 * @property string $project_id
 * @property string $name
 * @property string $color
 * @property string $image
 * 
 * @method UserGroup currentCompany() Returns the user's groups that belongs to current company.
 */
class UserGroup extends CActiveRecord
{
	protected $_companyName=null;
	protected $_projectName=null;
	
	public function getCompanyName() {
		if(empty($this->_companyName) && !empty($this->company))
			$this->_companyName = $this->company->company_name;
		return $this->_companyName;
	}
	
	public function setCompanyName($companyName) {
		$this->_companyName = $companyName;
	}
	public function getProjectName() {
		if(empty($this->_projectName) && !empty($this->project))
			$this->_projectName = $this->project->name;
		return $this->_projectName;
	}
	
	public function setProjectName($projectName) {
		$this->_projectName = $projectName;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserGroup the static model class
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
		return '{{user_group}}';
	}
	
	public function primaryKey() {
		return 'group_id';
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
			array('company_id', 'numerical', 'integerOnly'=>true),
			array('project_id', 'length', 'max'=>20),
			array('name, image', 'length', 'max'=>255),
			array('color', 'length', 'max'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('group_id, company_id, project_id, name, color, image', 'safe', 'on'=>'search'),
		);
	}

    protected function beforeSave()
    {
        $this->name = htmlspecialchars($this->name);
        return true;
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'company'=>array(self::BELONGS_TO, 'Company', 'company_id'),
			'project'=>array(self::BELONGS_TO, 'Project', 'project_id'),
			'usersCount'=>array(
				self::STAT,
				'User',
				'{{user_by_group}}(group_id, user_id)'
			),
			'users'=>array(
				self::MANY_MANY, 
				'User', 
				'{{user_by_group}}(group_id, user_id)'
			),
            'projects'=>array(
                self::MANY_MANY,
                'Project',
                '{{project_by_group}}(group_id,project_id)'
            ),
		);
	}
	
	public function scopes() {
		return array(
			'currentCompany'=>array(
				'condition'=>'company_id=:company_id',
				'params'=>array(':company_id'=>Company::current()),
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'group_id' => 'Group',
			'company_id' => 'Company',
			'project_id' => 'Project',
			'name' => 'Name',
			'color' => 'Color',
			'image' => 'Image',
		);
	}

	/**
	 * @param int $pageSize
	 * @return CActiveDataProvider 
	 */
	public function gridSearch($pageSize=30) {
		$sort = new CSort(__CLASS__);
		$sort->attributes = array(
			'defaultOrder'=>'t.id ASC',
			'group_id',
			'name',
			'color',
			'image',
			'companyName'=>array(
				'asc'=>'company.company_name ASC',
				'desc'=>'company.company_name DESC',
			),
			'projectName'=>array(
				'asc'=>'project.name ASC',
				'desc'=>'project.name DESC',
			),
		);
		return new CActiveDataProvider(
			$this,
			array(
				'criteria'=>array(
//					'condition'=>'t.project_id=:project_id',
//					'params'=>array(
//						':project_id'=>Project::getCurrent()->project_id,
//					),
					'with'=>array(
						'usersCount',
						'users',
						'project'=>array(
							'select'=>'project_id, name',
						),
//						'project.company'=>array(
//							'select'=>'company_id, company_name',
//						),
                        'projects'=>array(
                            'select'=>'project_id, name',
                        ),
					),
				),
				'sort'=>$sort,
				'pagination'=>array(
					'pageSize'=>$pageSize,
				),
			)
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

		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /*
     * Receives an array of group IDs, returns an array of users IDs
     * */
    public static function getUserIDsByGroupIDs(array $groups)
    {
        $usersArray = array();
        if (!empty($groups) && is_array($groups)){
            foreach ($groups as $groupID){
                if ($groupID > 0)
                    $group = self::model()->findByPk($groupID);
                if ($group)
                    $users = $group->users;
                if (is_array($users)){
                    $groupUsers = array();
                    foreach($users as $usr){
                        $groupUsers[] = $usr->user_id;
                    }
                    $usersArray = array_merge($usersArray, $groupUsers);
                }
            }
        }
        return $usersArray;
    }
}