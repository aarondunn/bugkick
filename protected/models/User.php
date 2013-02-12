<?php
Yii::import('application.controllers.RegistrationController');
/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $user_id
 * @property string $facebook_id
 * @property string $created_at
 * @property string $name
 * @property string $lname
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $encryption_algorithm
 * @property string $github_auth_token
 * @property string $github_user_id
 * @property integer $email_notify
 * @property integer $isadmin
 * @property integer $is_global_admin
 * @property string $profile_img
 * @property integer $email_preference
 * @property integer $hotkey_preference
 * @property integer $current_project_id
 * @property string $look_and_feel
 * @property string $randomPassword
 * @property integer $userStatus
 * @property integer $defaultAssignee
 * @property integer $defaultCompany
 * @property string $inviteToken
 * @property string $invited_by_id
 * @property string $registration_token
 * @property string $ticket_update_return
 * @property integer $use_wysiwyg
 * @property integer $bugCount The count of bugs which the user is assigned to.
 * @property integer $bugCreatedCount The count of bugs which the user has created.
 * @property User $invitedByUser
 * @property GithubUser $githubUser
 * @property User[] $invitedUsers 
 * @property integer $invitedUsersCount
 * @property StripeCustomer $stripeCustomer The Stripe customer, <br />
 * associated with BugKick user or null if the user is not a Stripe customer.
 * @property integer pro_status - flag shows if user has pro status. Pro status makes all user's
 * companies upgraded to Pro plan for ever (Gift)
 * 
 *													Relations properties:
 * 
 * @property Project $currentProject Selected project <br />
 * or null if none project selected.
 *													Relations properties END
 * @method User currentCompany() Returns the finder of users that belongs to current company.
 * 
 * @todo http://bugkick.com/BugKick/ticket/283
 */
class User extends CActiveRecord
{

    const SALT_CHARS = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()[]{}_.,<>?+-~`';
    const STATUS_INVITED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_REJECTED = 2;
    const STATUS_DELETED = 3;
    
    const ALGORITHM_SHA256 = 1;
    const ALGORITHM_BCRYPT = 2;

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
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
        return '{{user}}';
    }

    public function primaryKey()
    {
        return 'user_id';
    }

    private static $_current_user = null;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('look_and_feel', 'lafExists', 'on' => 'update'),
            array('name, email, password', 'required', 'on'=>array('insert', 'registration')),
            array('email', 'application.extensions.validators.UniqueOrInvitedValidator', 'on' => 'registration'),
            array('email', 'application.extensions.validators.DomainsBlacklistValidator', 'on' => 'registration'),
            array('encryption_algorithm, invited_by_id, email_notify, isadmin, is_global_admin, email_preference, hotkey_preference, userStatus, defaultAssignee, defaultCompany, defaultStatus, defaultLabel, tickets_per_page, ticket_update_return, use_wysiwyg, pro_status', 'numerical', 'integerOnly' => true),
            array(
                'encryption_algorithm',
                'default',
                'value'=>RegistrationController::PASSWORD_ENCRYPTION_ALGORITHM,
                'setOnEmpty'=>true,
                'on'=>array('insert, registration')
            ),
            array('name, email, password', 'length', 'max' => 100, 'min' => 3, 'allowEmpty'=>false),
            array('ticket_update_return', 'default', 'value'=>'2', 'setOnEmpty'=>true),
            array('lname, randomPassword, registration_token, salt, look_and_feel, facebook_id', 'length', 'max' => 255),
            array('created_at, inviteToken', 'safe'),
            array('email', 'email'),
            array('profile_img', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true),
            array('look_and_feel', 'default', 'value' => 'Default', 'setOnEmpty' => true),
			array('current_project_id, github_user_id, github_auth_token', 'default', 'value'=>null),
            array('github_auth_token', 'length', 'max'=>40, 'allowEmpty'=>true),
			array('encryption_algorithm', 'default', 'value'=>self::ALGORITHM_SHA256),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, is_global_admin, pro_status, invited_by_id, facebook_id, created_at, name, lname, email, password, salt, email_notify, current_project_id, isadmin, profile_img, email_preference, hotkey_preference, look_and_feel, randomPassword, userStatus, defaultAssignee, defaultStatus, defaultLabel, use_wysiwyg', 'safe', 'on' => 'search'),
        );
    }

    public function lafExists($attribute, $params)
    {
        $lafName = $this->{$attribute};
        if (!LookAndFeel::model()->exists(
            'name=:name', array(':name' => $lafName))
        )
            $this->addError(
                $attribute,
                "Look-and-Feel scheme '{$lafName}' doesn't exists");
    }

    protected function beforeSave()
    {
        if ($this->scenario !== 'update') {
           $this->created_at = new CDbExpression('NOW()');
        }

        $this->name = htmlspecialchars($this->name);
        $this->lname = htmlspecialchars($this->lname);

        return parent::beforeSave();
    }

    protected function afterSave()
    {
        if ($this->scenario !== 'update') {
            $userByCompany = new UserByCompany;
            $userByCompany->company_id = Company::current();
            $userByCompany->user_id = $this->user_id;
            $userByCompany->save();
        }
        return parent::afterSave();
    }

    /**
     * 
     * @return string sha1 hash of random value.
     */
    public function generateInviteToken() {
        $token = sha1(microtime() . '_' . uniqid(mt_rand(), true));
        if($this->exists('inviteToken=:token', array(':token'=>$token))) {
            return $this->generateInviteToken();
        }
        return $token;
    }

    public static function getFacebookPictureSrc($facebook_id, $type)
    {
        return "https://graph.facebook.com/{$facebook_id}/picture?type={$type}";
    }

	public static function _getImageSrc( $profile_img, $facebook_id, $width = 50, $height = 50, $quality = 75)
    {
		if(!empty($profile_img)) {

            switch (Yii::app()->params['storageType']){
                 case 's3':
                     return Storage::get('s3')->getFilePath( $width .'_'.$height.'_'. $profile_img, S3Storage::PROFILE_BUCKET);
                     break;
                 case 'local':
                     if(is_file('images/profile_img/' . $width .'_'.$height.'_'. $profile_img)){
                         return ImageHelper::thumb(
                             $width,
                             $height,
                             'images/profile_img/' . $width .'_'.$height.'_'. $profile_img,
                             $quality
                         );
                     }
                     break;
            }
        }
        if (empty($profile_img) && !empty($facebook_id)){
            return self::getFacebookPictureSrc($facebook_id, 'square');
        }
        else{
            return ImageHelper::thumb(
                $width,
                $height,
                'images/profile_img/default.jpg',
                $quality
            );
        }
	}

    /**
     * Returns path to the image or link when s3 enabled.
     * !important: For S3 available only 2 sizes: 81*81, 31*31.
     * For local storage all sizes are available.
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return string path or link to image
     */
    public function getImageSrc($width = 50, $height = 50, $quality = 100)
    {
		return self::_getImageSrc(
			$this->profile_img, $this->facebook_id, $width, $height, $quality);
    }
//    public function getImageSrc($width = 50, $height = 50, $quality = 75) {
//		if (!empty($this->profile_img))
//            return ImageHelper::thumb(
//                $width,
//                $height,
//                'images/profile_img/' . $this->profile_img,
//                $quality
//            );
//        if (empty($this->profile_img) && !empty($this->facebook_id)){
//            return self::getFacebookPictureSrc($this->facebook_id, 'square');
//        }
//        else{
//            return ImageHelper::thumb(
//                $width,
//                $height,
//                'images/profile_img/default.jpg',
//                $quality
//            );
//        }
//    }

    public function setDefaultEmailPreferences() {
        if($this->getIsNewRecord()) {
            return false;
        }
        $prefs = EmailPreference::model()->findAll();
        foreach($prefs as $pref) {
            UserByEmailPreference::model()->createForUserAndPreference(
                $this, $pref, EmailPreference::STATE_ON);
        }
        return true;
    }

	public function scopes()
    {
		return array(
			'currentCompany'=>array(
				'condition'=>'user_id IN (SELECT user_id FROM {{user_by_company}} WHERE company_id=:company_id)',
				'params'=>array(
					':company_id'=>Company::current(),
				),
			),
            'ticketsList'=>array(
                 'select'=>array(
                     't.user_id',
                     't.name',
                     't.lname',
                     't.profile_img',
                     't.facebook_id',
                 )
            ),
		);
	}

    /**
     * Allows to determine is the current User record a company admin.
     * 
     * @param integer $company_id
     * @return boolean Is the current User record a company admin.
     */
    public function isCompanyAdmin($company_id) {
        return UserByCompany::model()->exists(
            'user_id = :user_id AND company_id =:company_id AND is_admin = 1',
            array(
                ':user_id'=>$this->user_id,
                ':company_id'=>$company_id,
            )
        );
    }

    public function isProjectAdmin($project_id) {
        return UserByProject::model()->exists(
            'user_id = :user_id AND project_id = :project_id AND is_admin = 1',
            array(
                ':user_id'=>$this->user_id,
                ':project_id'=>$project_id,
            )
        );
    }

    /**
     * Creates the scope to fetch the users which are assigned
     * to ticket and also are in project.
     * 
     * @param BugBase $bug
     * @return \User
     */
    public function bugRelated(BugBase $bug) {
        $this->getDbCriteria()->mergeWith(array(
            'with'=>array(
                'project'=>array(
                    'select'=>'project_id',
                    'condition'=>'project.project_id=:project_id',
                    'params'=>array(
                        ':project_id'=>$bug->project_id,
                    )
                ),
                'bug'=>array(
                    'select'=>'id',
                    'condition'=>'bug_id=:bug_id',
                    'params'=>array(
                        ':bug_id'=>$bug->id,
                    )
                ),
            ),
        ));
        return $this;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        $relations=array(
            'invites'=>array(self::HAS_MANY, 'Invite', 'user_id'),
            'company' => array(
				self::MANY_MANY,
				'Company',
				'{{user_by_company}}(user_id, company_id)'
			),
            'bug' => array(
				self::MANY_MANY,
				'Bug',
				'{{bug_by_user}}(user_id, bug_id)'
			),
			'emailPreferences'=>array(
				self::MANY_MANY,
				'EmailPreference',
				'{{user_by_email_preference}}(user_id, email_preference_id)'
			),
			'userGroups'=>array(
				self::MANY_MANY,
				'UserGroup',
				'{{user_by_group}}(user_id, group_id)'
			),
			'currentProject'=>array(
				self::BELONGS_TO,
				'Project',
				'current_project_id'
			),
            'project'=>array(
                self::MANY_MANY,
                'Project',
                '{{user_by_project}}(user_id, project_id)'
            ),
            'githubUser'=>array(self::BELONGS_TO, 'GithubUser', 'github_user_id'),
            //'bug' => array(self::HAS_MANY, 'Bug', 'user_id'),
            /*'bugCount' => array(
				self::STAT,
				'Bug',
				'user_id',
				'condition' => 'company_id=' . Company::current()
			),*/
            'companyCount' => array(
				self::STAT,
				'Company',
				'{{user_by_company}}(user_id, company_id)'
			),
            'laf' => array(self::BELONGS_TO, 'LookAndFeel', 'look_and_feel'),
            //'userByCompany' => array(self::HAS_MANY, 'UserByCompany', 'user_id'),
			'user_settings'=>array(self::HAS_ONE, 'SettingsByUser', 'user_id'),
			'stripeCustomer'=>array(self::HAS_ONE, 'StripeCustomer', 'user_id'),
            'notifications' => array(
                self::HAS_MANY,
                'Notification',
                'user_id',
                'order' => 'date DESC'
            ),
            'invitedByUser'=>array(self::BELONGS_TO, 'User', 'invited_by_id'),
            'invitedUsers'=>array(self::HAS_MANY, 'User', 'invited_by_id'),
            'invitedUsersCount'=>array(self::STAT, 'User', 'invited_by_id'),
        );
		if(!Yii::app() instanceof CConsoleApplication) {
			$relations['bugCount']=array(
				self::STAT,
				'Bug',
				'{{bug_by_user}}(user_id, bug_id)'
			);
            $relations['bugCreatedCount'] = array(
                self::STAT,
                'Bug',
                'owner_id',
            );
		}
		return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id' => 'ID',
            'facebook_id' => 'Facebook ID',
            'created_at' => 'Created At',
            'name' => 'First Name',
            'lname' => 'Last Name',
            'email' => 'Email Address',
            'password' => 'Password',
            'salt' => 'Salt',
            'email_notify' => 'Email when my tickets were changed',
            'isadmin' => 'Project Manager',
            'is_global_admin' => 'Global Admin',
            'profile_img' => 'Profile Img',
            'email_preference' => 'Email Preference',
            'hotkey_preference' => 'Use Shortcuts',
            'randomPassword' => 'Random Password',
            'userStatus' => 'User Status',
            'defaultAssignee' => 'Default Assignee',
            'defaultStatus' => 'Default Status',
            'defaultLabel' => 'Default Label',
			'tickets_per_page' => 'Tickets Per Page',
			'ticket_update_return' => 'Ticket Update Return',
            'look_and_feel' => 'Choose Theme',
            'use_wysiwyg' => 'Use WYSIWYG-editor'
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

        $criteria = new CDbCriteria;

        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('facebook_id', $this->facebook_id);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('lname', $this->lname, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('salt', $this->salt, true);
        $criteria->compare('email_notify', $this->email_notify);
        $criteria->compare('isadmin', $this->isadmin);
        $criteria->compare('is_global_admin', $this->is_global_admin);
        $criteria->compare('profile_img', $this->profile_img, true);
        $criteria->compare('email_preference', $this->email_preference);
        $criteria->compare('hotkey_preference', $this->hotkey_preference);
        $criteria->compare('randomPassword', $this->randomPassword, true);
        $criteria->compare('userStatus', $this->userStatus);
        $criteria->compare('defaultAssignee', $this->defaultAssignee);
        $criteria->compare('defaultStatus', $this->defaultStatus);
        $criteria->compare('defaultLabel', $this->defaultLabel);
        $criteria->compare('pro_status', $this->pro_status);

        return new CActiveDataProvider(get_class($this),
            array(
                'criteria' => $criteria,
        ));
    }

    public static function getUserByCompany($company_id = '', $adminsOnly = false)
    {
        if (empty($company_id))
            $company_id = Yii::app()->user->company_id;

        if ($adminsOnly){
            $criteria = new CDbCriteria();
            $criteria->condition = 't.isadmin = 1';
        }

        $users = User::model()->with(array('company' => array(
                                              'select' => false,
                                              'joinType' => 'INNER JOIN',
                                              'condition' => 'company.company_id=' . $company_id,
                                          )))->findAll(($adminsOnly)? $criteria: '');
        return $users;
    }

    public static function getId() {
        return empty(Yii::app()->user) ? null : Yii::app()->user->id;
    }
	
	public static function getTicketUpdRtnOptions() {
        return array(
			1 => 'Return home',
			2 => 'Stay in the ticket',
		);
    }

    /**
     * Returns current logged-in user's model or null if the user is guest.
	 * 
     * @return User
     */
    public static function current()
    {
		if(empty(Yii::app()->user) || Yii::app()->user->isGuest) {
			return null;
		}
		if(empty(self::$_current_user)) {
			self::$_current_user = User::model()->findByPk(self::getId());
		}
		return self::$_current_user;
    }

    public static function getNameById($id)
    {
        if (!empty($id)) {
            $model = User::model()->findByPk((int)$id);
            return $model->name;
        }
    }

    public static function getDefaultAssignee($id)
    {
        if (!empty($id)) {
            $model = User::model()->findByPk((int)$id);
            return $model->defaultAssignee;
        }
    }

    public static function getDefaultCompany($id)
    {
        if (!empty($id)) {
            $model = User::model()->findByPk((int)$id);
            return $model->defaultCompany;
        }
    }

    public static function getDefaultStatus($id)
    {
        if (!empty($id)) {
            $model = User::model()->findByPk((int)$id);
            return $model->defaultStatus;
        }
    }

    public static function getDefaultLabel($id)
    {
        if (!empty($id)) {
            $model = User::model()->findByPk((int)$id);
            return $model->defaultLabel;
        }
    }

    //get default project settings for current user
    public static function getUserSettings()
    {
        $user = User::current();
		return empty($user) || empty($user->user_settings)
			? null
			: $user->user_settings;
    }

    public static function checkHotkeyPreference()
    {
		$model=User::current();
		if(empty($model))
			return false;
		if ($model->hotkey_preference == 1)
			return true;
		return false;
    }

    public function generateSalt()
    {
        $salt = '';
        $saltLen = mt_rand(200, 255);
        $charsLen = strlen(self::SALT_CHARS);
        for ($i = 0; $i < $saltLen; $i++)
            $salt .= substr(self::SALT_CHARS, mt_rand(0, $charsLen - 1), 1);
        return self::model()->exists('salt=:salt', array(':salt' => $salt))
                ? $this->generateSalt()
                : $salt;
    }

    public function salt()
    {
        if (empty($this->salt))
            $this->salt = $this->generateSalt();
        return $this->salt;
    }
    
    /**
     * Performs the password encrypting and set the encrypted value.
     * @param string $password 
     */
    public function setPassword($password) {
        $this->password = $this->hashPassword($password);
    }
    
    /**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password) {
        $isValid = false;
        switch($this->encryption_algorithm) {
            case self::ALGORITHM_SHA256:
                $isValid = $this->validatePasswordSHA256($password);
                break;
            case self::ALGORITHM_BCRYPT:
                $isValid = $this->validatePasswordBcrypt($password);
                break;
            default:
                $isValid = $this->validatePasswordBcrypt($password);
        }
        if($isValid && $this->encryption_algorithm !=
            RegistrationController::PASSWORD_ENCRYPTION_ALGORITHM) {
            $this->reEncryptPassword($password);
        }
        return $isValid;
	}
    
    protected function validatePasswordSHA256($password) {
        return Hash::sha256($password . $this->salt()) == $this->password;
    }
    
    protected function validatePasswordBcrypt($password) {
        return Bcrypt::check($password . $this->salt(), $this->password);
    }
    
    protected function reEncryptPassword($password) {
        $this->encryption_algorithm =
            RegistrationController::PASSWORD_ENCRYPTION_ALGORITHM;
        $this->setPassword($password);
        if($this->save()) {
            $this->refresh();
            return true;
        }
        return false;
    }
    
	/**
	 * Generates Bcrypt hash of password and salt
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	public function hashPassword($password) {
        switch($this->encryption_algorithm) {
            case self::ALGORITHM_SHA256:
                return $this->getSHA256Hash($password);
            case self::ALGORITHM_BCRYPT:
                return $this->getBcryptHash($password);
            default:
                return $this->getBcryptHash($password);
        }
	}
    
    protected function getSHA256Hash($password) {
        return Hash::sha256($password . $this->salt());
    }
    
    protected function getBcryptHash($password) {
        return Bcrypt::hash($password . $this->salt(),
                Yii::app()->params['bcryptWorkFactor']);
    }

    /**
     * Updates user's password
     * @param string the password
     * @return string hashed password
     */
    public function updatePassword($password) {
        return $this->hashPassword($password, $this->salt);
    }

    public function getUserLastActivity($userID)
    {
        $comments = Comment::getLastUserComments($userID);
        $tickets = Bug::getLastUserBugs($userID);

        $activity['comments'] = $comments;
        $activity['tickets'] = $tickets;

        return $activity;
    }

    //check if current user is admin
    public static function isAdmin()
    {
        $model=User::current();
        if(empty($model))
            return false;
        if ($model->isadmin == 1)
            return true;
        return false;
    }

    //check if current user is global admin
    public function isGlobalAdmin()
    {
        if ($this->is_global_admin == 1)
            return true;
        return false;
    }

    public static function updateCurrent()
    {
        self::$_current_user = User::model()->findByPk(self::getId());
    }

    public static function updateCurrentProject($projectID)
    {
        $user = User::current();
        if(!empty($user) && $user->current_project_id != $projectID){
            $user->current_project_id = $projectID;
            if($user->save()){
                self::updateCurrent();
            }
        }
    }

    /**
     * Retrieves the existing Invite model or creates new one
     * if there is no existing record for this project and user.
     * 
     * @param Project $project
     * @return Invite Invite model or null on failure.
     */
    public function inviteToProject(Project $project) {
        $invite = Invite::model()->findByAttributes(array(
            'project_id'=>$project->project_id,
            'company_id'=>$project->company->company_id,
            'user_id'=>$this->user_id,
        ));
        if(!empty($invite)) {
            return $invite;
        }
        $invite = new Invite();
        $invite->project_id = $project->project_id;
        $invite->company_id = $project->company->company_id;
        $invite->user_id = $this->user_id;
        $invite->invited_by_id = User::current()->user_id;
        $invite->token = $invite->generateInviteToken();
        return $invite->save() ? $invite : null;
    }

    /**
     * Attempts to add current user to given project.
     * @param Project $project
     * @return UserByProject UserByProject model or null on failure.
     */
    public function addToProject(Project $project,$isAdmin=1) {
        return $project->addUser($this,$isAdmin);
    }

    public function removeFromProject(Project $project) {
        return $project->removeUser($this);
    }

    /**
     * Attempts to add current user to given company.
     * @param Company $company
     * @return UserByCompany UserByCompany model or null on failure.
     */
    public function addToCompany(Company $company) {
        return $company->addUser($this);
    }

    public function removeFromCompany(Company $company) {
        return $company->removeUser($this);
    }

    public function setStatusInCompany($status, $company_id) {
        return UserByCompany::model()->updateAll(
            array(
                'user_status'=>$status
            ),
            'user_id = :user_id AND company_id = :company_id',
            array(
                ':user_id'=>$this->user_id,
                ':company_id'=>$company_id,
            )
        );
    }

    /**
     * @param integer $company_id
     * @return integer User's status in company or -1 if user isn't in company.
     */
    public function getStatusInCompany($company_id) {
        $userByCompany = UserByCompany::model()->findByAttributes(array(
            'user_id'=>$this->user_id,
            'company_id'=>$company_id,
        ));
        return empty($userByCompany) ? -1 : (int)$userByCompany->user_status;
    }

    /**
     * Checks if user belongs to at least 1 company
     */
    public function belongsToCompanies()
    {
        $countUserByCompany = UserByCompany::model()->countByAttributes(array(
            'user_id'=>$this->user_id,
            'user_status'=>self::STATUS_ACTIVE,
        ));
        if($countUserByCompany>0)
            return true;
        else
            return false;
    }

    /**
     * repr() method for bugkick forum
     * @param null $user
     * @return string
     */
    public function repr($user = null) {
        if($user === null) {
            $user = $this;
        }
/*        $stringRepresentation = '';
        if($user->user_id) {
            $stringRepresentation .= '[#' . $user->user_id . '] ';
        }
        return $stringRepresentation . $user->name
                . ' (' . $user->email . ')';*/
        return CHtml::encode(trim($user->name . ' ' . $user->lname));
    }

    public function getUserName(User $user=null)
    {
        if(empty($user))
            $user = User::current();

        return empty($user)? ' ' : CHtml::encode(trim($user->name . ' ' . $user->lname));
    }

    /**
     * Returns path to user image for Forum module
     * @param null $user
     * @return string
     */
    public function getImage($user = null) {
        $user = $this->findByPk($user->user_id);
        return $user->getImageSrc(31,31);
    }
}
