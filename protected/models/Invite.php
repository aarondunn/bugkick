<?php

/**
 * This is the model class for table "{{invite}}".
 *
 * The followings are the available columns in table '{{invite}}':
 * @property string $id
 * @property string $company_id
 * @property string $project_id
 * @property string $user_id
 * @property string $invited_by_id
 * @property string $token
 *
 * 
 * @property User $invitedBy
 * @property User $user
 * @property Project $project
 * @property Company $company
 */
class Invite extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Invite the static model class
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
		return '{{invite}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, project_id, user_id, invited_by_id, token', 'required'),
			array('company_id, project_id, user_id, invited_by_id', 'length', 'max'=>20),
			array('token', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, company_id, project_id, user_id, invited_by_id, token', 'safe', 'on'=>'search'),
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
            'company'=>array(self::BELONGS_TO, 'Company', 'company_id'),
            'project'=>array(self::BELONGS_TO, 'Project', 'project_id'),
            'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
            'invitedBy'=>array(self::BELONGS_TO, 'User', 'user_id'), // The User model of who has made this invite
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company_id' => 'Company',
			'project_id' => 'Project',
			'user_id' => 'User',
            'invited_by_id' => 'Invited By',
			'token' => 'Token',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('invited_by_id',$this->invited_by_id,true);
		$criteria->compare('token',$this->token,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     *
     * @return string sha1 hash of random value.
     */
    public function generateInviteToken() {
        $token = sha1(microtime() . '_' . uniqid(mt_rand(), true));
        if($this->exists('token=:token', array(':token'=>$token))) {
            return $this->generateInviteToken();
        }
        return $token;
    }
}