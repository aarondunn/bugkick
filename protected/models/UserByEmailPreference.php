<?php

/**
 * This is the model class for table "{{user_by_email_preference}}".
 *
 * The followings are the available columns in table '{{user_by_email_preference}}':
 * @property string $user_id
 * @property string $email_preference_id
 * @property string $state
 */
class UserByEmailPreference extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserByEmailPreference the static model class
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
		return '{{user_by_email_preference}}';
	}
	
	protected function beforeSave() {
		$this->state = strtolower($this->state);
		return parent::beforeSave();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, email_preference_id, state', 'required'),
			array('email_preference_id', 'length', 'max'=>10),
			array('user_id', 'length', 'max'=>20),
			array('state', 'length', 'max'=>3),
			array('state', 'stateEnum'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, email_preference_id, state', 'safe', 'on'=>'search'),
		);
	}
	
	public function stateEnum($attribute, $params) {
		if(!empty($this->{$attribute}))
			$this->{$attribute} = strtolower($this->{$attribute});
		$allowedStates = array(
			'on',
			'off',
		);
		if(!in_array($this->{$attribute}, $allowedStates))
			$this->addError(
				$attribute,
				'The state "'.$this->{$attribute}.'" is not allowed.'
			);
	}

    public function createForUserAndPreference(
            User $user, EmailPreference $pref, $state) {
        $model = new UserByEmailPreference();
        $model->setAttributes(array(
            'user_id'=>$user->user_id,
            'email_preference_id'=>$pref->email_preference_id,
            'state'=>$state,
        ));
        return $model->save() ? $model : null;
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'emailPreference'=>array(
                self::BELONGS_TO,
                'EmailPreference',
                'email_preference_id'
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'email_preference_id' => 'Email Preference',
			'state' => 'State',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('email_preference_id',$this->email_preference_id,true);
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}