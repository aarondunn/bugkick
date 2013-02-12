<?php

/**
 * This is the model class for table "company".
 *
 * The followings are the available columns in table 'company':
 * @property integer $company_id
 * @property string $created_at
 * @property string $company_name
 * @property string $company_url
 * @property string $company_logo
 * @property string $company_color
 * @property string $company_top_logo
 * @property string @api_key
 * @property StripeCustomer $stripeCustomer
 * @property integer $account_type
 * @property string $account_plan
 * @property integer $show_ads
 * @property integer owner_id
 * @property integer coupon_id
 * @property integer coupon_expires_at
 *
 */
class Company extends CActiveRecord
{

    private static $_current_company = null;
	
	const TYPE_FREE=0;
	const TYPE_PAY=1;

    /**
     * Creates and saves new Company record in database
     * with pre set stub-values for company_name and company_url fields.
     *
     * @param Company $company
     * @return Company New created Company model or null on failure.
     */
    public function createNew($company=null) {
        if(empty($company) || !($company instanceof Company) )
            $company = new Company();
        $company->setAttributes(array(
            'company_name'=>'Company example name',
            'company_url'=>'http://company-example.com',
        ));
        return $company->save() ? $company : null;
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Company the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{company}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company_name, company_url', 'required'),
            array('company_name, company_url', 'length', 'max' => 250),
            array('company_logo, company_top_logo, api_key', 'length', 'max' => 255),
            array('company_color', 'length', 'max' => 7),
            array('account_plan', 'length', 'max' => 100),
            array('coupon_id', 'numerical', 'integerOnly'=>true, 'message'=>'Incorrect coupon name'),
            array('coupon_expires_at', 'numerical', 'integerOnly'=>true),
            array('created_at, show_ads', 'safe'),
            array('company_url', 'url', 'defaultScheme' => 'http'),
            array('company_logo', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
			array('api_key', 'default', 'value'=>null),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('company_id, owner_id, created_at, company_name, company_url, company_logo, company_top_logo, company_color, api_key, account_plan, show_ads, coupon_id, coupon_expires_at', 'safe', 'on' => 'search'),
        );
    }

    public function isGitHubIntegrationAvailable() {
        if($this->account_type != self::TYPE_PAY) {
            return false;
        }
        $storageType = Yii::app()->params['stripe']['planConfigStorageType'];
        $planConfig = StripePlanConfigFactory::createPlanConfig(
                $this->account_plan, $storageType);
        return $planConfig->getIsGithubIntegrationAvailable();
    }
	
	public function refreshApiKey($forceSave=false) {
		$this->api_key=$this->generateApiKey();
		if($forceSave && !$this->save()) {
			throw new CDbException("Can't save the record inside the Company::refreshApiKey().");
		}
		return $this;
	}
	
	protected function generateApiKey() {
		if($this->isNewRecord) {
			throw new CDbException("Can't generate API key for new record");
		}
		$key=$this->company_id . '-' . microtime() . '-' . uniqid(null, true);
		$key=md5($key);
		$charArray=str_split($key);
		$apiKey='';
		foreach($charArray as $character) {
			$apiKey.=mt_rand(0, 1)===0?$character:strtoupper($character);
		}
		return $apiKey;
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
            'user' => array(
				self::MANY_MANY,
				'User',
				'{{user_by_company}}(company_id, user_id)',
				'joinType' => 'LEFT OUTER JOIN',
				'condition'=>'user_status != ' . User::STATUS_DELETED,
			),
			'projects'=>array(self::HAS_MANY, 'Project', 'company_id'),
            'bug' => array(self::HAS_MANY, 'Bug', 'company_id'),
            'bugCount' => array(self::STAT, 'Bug', 'company_id'),
            'label' => array(self::HAS_MANY, 'Label', 'company_id', 'order' => 'name'),
            'status' => array(self::HAS_MANY, 'Status', 'company_id', 'order' => 'label'),
            'group' => array(self::HAS_MANY, 'UserGroup', 'company_id', 'order' => 'name'),
            'project' => array(self::HAS_MANY, 'Project', 'company_id'),
			'stripeCustomer'=>array(self::HAS_ONE, 'StripeCustomer', 'company_id'),
            'userCount' => array(self::STAT, 'UserByCompany', 'company_id'),
			'projectCount' => array(self::STAT, 'Project', 'company_id'),
			'archivedProjectCount' => array(self::STAT, 'Project', 'company_id', 'condition'=>'archived=1'),
			'activeProjectCount' => array(self::STAT, 'Project', 'company_id', 'condition'=>'archived=0'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'company_id' => 'ID',
            'created_at' => 'Created At',
            'company_name' => 'Company Name',
            'company_url' => 'Company Url',
            'company_logo' => 'Company Logo',
            'company_top_logo' => 'Company Top Logo',
            'company_color' => 'Company Color',
            'show_ads'=>'Show Advertisements',
            'owner_id'=>'Owner',
            'coupon_id'=>'Coupon',
            'coupon_expires_at'=>'Coupon expires at',
        );
    }

    protected function beforeSave()
    {
        if ($this->scenario == 'insert') {
            $this->created_at = new CDbExpression('NOW()');
        }
        $this->company_name = htmlspecialchars($this->company_name);
        return true;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('company_url', $this->company_url, true);
        $criteria->compare('company_logo', $this->company_logo, true);
        $criteria->compare('owner_id', $this->owner_id, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public static function current()
    {
		if(empty(self::$_current_company)
			&& isset(Yii::app()->user->company_id))
			self::$_current_company = Yii::app()->user->company_id;
		return self::$_current_company;
    }

    public static function getLabels($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $company = Company::model()->findByPk($company_id);
        return empty($company) ? array() : $company->label;
    }

    public static function getPreCreatedLabels($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        if(empty($company_id))
            return array();

        $criteria = new CDbCriteria();
        $criteria->condition = 'company_id=:company_id AND pre_created=1';
        $criteria->params = array(':company_id'=>$company_id);
        $criteria->group = 't.name';
        return Label::model()->findAll($criteria);
    }

    public static function getStatuses($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $company = Company::model()->findByPk($company_id);
        return empty($company) ? array() : $company->status;
    }

    public static function getUsers($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $company = Company::model()->findByPk($company_id);
        return empty($company) ? array() : $company->user;
    }

    public static function getUserGroups($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $project = Project::getCurrent();

        $criteria = new CDbCriteria();
        $criteria->with=array(
                'group'=>empty($project)
                ? array()
                : array('condition'=>'project_id=' . $project->project_id),
        );

        $criteria->condition = 't.company_id=' . $company_id;
        $company = Company::model()->find($criteria);
        if ($company)
            return $company->group;
        else
            return null;
    }

    public static function getProjects($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $company = Company::model()->findByPk($company_id);
        return $company->project;
    }

    public static function getBugs($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $company = Company::model()->findByPk($company_id);
        return $company->bug;
    }

    public static function getBugsCount($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $company = Company::model()->findByPk($company_id);
        return $company->bugCount;
    }

    public static function getUsersCount($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $company = Company::model()->findByPk($company_id);
        return $company->userCount;
    }

    public static function getArchivedBugsCount($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $rows = Yii::app()->db->createCommand()
        ->from('{{bug}}', 'COUNT(*)')
        ->where('company_id=:company_id AND isarchive=1', array(':company_id'=>$company_id, ))
        ->queryAll();
        return (is_array($rows))? count($rows) : 0;
    }

    //return array of bugs with 'archive' field
    public static function getTodayBugsCount($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $rows = Yii::app()->db->createCommand()
        ->select('isarchive')
        ->from('{{bug}}')
        ->where('company_id=:company_id AND DATE(created_at) = DATE(NOW())', array(':company_id'=>$company_id, ))
        ->queryAll();
        return $rows;
    }

    //return array of bugs with 'archive' field
    public static function getRecentChangedBugs($company_id = '')
    {
        if (empty($company_id))
            $company_id = Company::current();

        $rows = Yii::app()->db->createCommand()
        ->select(array('isarchive', 'DATE(created_at)', 'DATE(archiving_date)'))
        ->from('{{bug}}')
        ->where('company_id=:company_id AND (DATE(created_at) > date_sub(DATE(NOW()), INTERVAL 14 DAY) OR DATE(archiving_date) > date_sub(DATE(NOW()), INTERVAL 14 DAY)  )', array(':company_id'=>$company_id, ))
        ->queryAll();
        return $rows;
    }

    public static function canAddNewProject($companyID = null)
    {
        if (empty($companyID))
            $companyID = self::current();

        $company = self::model()->findByPk( $companyID );
        if (!empty($company)){
            $currentProjectsCount = $company->activeProjectCount;

            if ($company->account_type == self::TYPE_PAY){
                $planName = $company->account_plan;
                $storageType = Yii::app()->params['stripe']['planConfigStorageType'];
                $planConfig = StripePlanConfigFactory::createPlanConfig($planName, $storageType);
                $maxProjectsCount = $planConfig->getMaxProjectsCount();
            }
            else{
                $maxProjectsCount = Yii::app()->params['projects_number_for_free'];
            }

            if ($maxProjectsCount > $currentProjectsCount)
                return true;
        }
            return false;
    }

    /**
     * Returns path to the image or link when s3 enabled.
     * !important: For S3 available only 1 size: 132*33.
     * For local storage all sizes are available.
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return string path or link to image
     */
    public function getImageSrc($width = 132, $height = 33, $quality = 100)
    {
        $image = $this->company_top_logo;
        if(!empty($image)) {
            switch (Yii::app()->params['storageType']) {
                case 's3':
                    return Storage::get('s3')->getFilePath($width . '_' . $height . '_' . $image, S3Storage::COMPANY_TOP_BUCKET);
                    break;
                case 'local':
                    if (is_file('images/company_top_logo/' . $image)) {
                        return ImageHelper::thumb(
                            $width,
                            $height,
                            'images/company_top_logo/' . $image,
                            $quality
                        );
                    }
                    break;
            }
        }
        else{
            return ImageHelper::thumb(
                $width,
                $height,
                'images/logo.png',
                $quality
            );
        }
    }

    /**
     * Attempts to add given user to current company.
     * @param User $user
     * @param int $isAdmin
     * @return UserByCompany UserByCompany model or null on failure.
     */
    public function addUser(User $user,$isAdmin=1) {
        $userByCompany = UserByCompany::model()->findByAttributes(array(
            'user_id'=>$user->user_id,
            'company_id'=>$this->company_id
        ));
        if(!empty($userByCompany)) {
            //update is_admin field (yii doesn't allow
            //to update a model without primary key, so we have to use updateAll())
            UserByCompany::model()->updateAll(array(
                    'is_admin'=>$isAdmin
                ),
                'user_id=:user_id AND company_id=:company_id',
                array(
                    'user_id'=>$user->user_id,
                    'company_id'=>$this->company_id
                )
            );
            return $userByCompany;
        }
        $userByCompany = new UserByCompany();
        $userByCompany->company_id = $this->company_id;
        $userByCompany->user_id = $user->user_id;
        $userByCompany->is_admin = $isAdmin;
        return $userByCompany->save() ? $userByCompany : null;
    }

    public function removeUser(User $user) {
        return UserByCompany::model()->deleteAllByAttributes(array(
            'company_id'=>$this->company_id,
            'user_id'=>$user->user_id,
        ));
    }

    public function downgradeFeaturesToFree()
    {
        $this->show_ads = 1;
        $this->company_top_logo = '';
    }

    //returns default new status
    public static function getNewStatus($companyID=null)
    {
        if (empty($companyID))
            $companyID = self::current();

        if (empty($companyID))
            return null;

        $criteria = new CDbCriteria();
        $criteria->condition = 't.label=:label AND t.company_id=:company_id';
        $criteria->params = array(
            ':label'=>'New',
            ':company_id'=>$companyID,
        );
        return Status::model()->find($criteria);
    }
}