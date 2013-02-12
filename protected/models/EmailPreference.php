<?php

/**
 * This is the model class for table "{{email_preference}}".
 *
 * The followings are the available columns in table '{{email_preference}}':
 * @property string $email_preference_id
 * @property string $name
 */
class EmailPreference extends CActiveRecord
{
	const STATE_ON = 'on';
	const STATE_OFF = 'off';

    const NEW_TICKET = 1;
	const TICKET_UPDATE = 2;
    const NEW_COMMENT = 3;
    const DUE_DATE = 4;
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmailPreference the static model class
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
		return '{{email_preference}}';
	}

	public function primaryKey() {
		return 'email_preference_id';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('email_preference_id, name', 'safe', 'on'=>'search'),
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
            'userPreferences'=>array(
                self::HAS_MANY,
                'UserByEmailPreference',
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
			'email_preference_id' => 'ID',
			'name' => 'Name',
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

		$criteria->compare('email_preference_id',$this->email_preference_id,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function setUserPreferences(
		User $user, EmailPreferenceForm $emailPrefForm) {
		$user->email_notify=(int)$emailPrefForm->email_notify;
		if(!$user->save())
			return false;
		$genInsertRow=function($n) {
			return '(:user_id,'.(int)$n.",'".EmailPreference::STATE_ON."')";
		};
		$insertValuesArray=array_map($genInsertRow, $emailPrefForm->turnedOn);
		$insertSql=
			'INSERT INTO {{user_by_email_preference}} 
			(user_id, email_preference_id, state) VALUES'
			.implode(',', $insertValuesArray);
		$deleteSql=
			'DELETE FROM {{user_by_email_preference}} WHERE user_id=:user_id';
		$tran=Yii::app()->db->beginTransaction();
		$params=array(':user_id'=>$user->user_id);
		try {
			Yii::app()->db->createCommand($deleteSql)->execute($params);
			if(!empty($emailPrefForm->turnedOn) && !empty($user->email_notify))
				Yii::app()->db->createCommand($insertSql)->execute($params);
			$tran->commit();
			return true;
		} catch(CException $ex) {
			$tran->rollback();
		}
		return false;
	}
}