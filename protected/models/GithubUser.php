<?php

/**
 * This is the model class for table "{{github_user}}".
 *
 * The followings are the available columns in table '{{github_user}}':
 * @property string $id
 * @property string $is_active
 * @property string $login
 * @property string $html_url
 * @property string $avatar_url
 */
class GithubUser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return GithubUser the static model class
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
		return '{{github_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_active, login, html_url, avatar_url', 'required'),
			array('login, html_url', 'length', 'max'=>255),
			array('is_active', 'in', 'range'=>array(0, 1)),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, is_active, login, html_url, avatar_url',
                'safe', 'on'=>'search'),
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
            'users'=>array(self::HAS_MANY, 'User', 'github_user_id'),
            'projects'=>array(self::HAS_MANY, 'Project', 'github_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Login',
			'html_url' => 'Html Url',
			'avatar_url' => 'Avatar Url',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('html_url',$this->html_url,true);
		$criteria->compare('avatar_url',$this->avatar_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}