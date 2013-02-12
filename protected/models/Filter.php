<?php

/**
 * This is the model class for table "{{filter}}".
 *
 * The followings are the available columns in table '{{filter}}':
 * @property string $filter_id
 * @property string $user_id
 * @property string $name
 * @property string $filter
 */
class Filter extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Filter the static model class
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
		return '{{filter}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name, filter', 'required'),
			array('user_id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('filter_id, user_id, name, filter', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'filter_id' => 'FilterID',
			'user_id' => 'User',
			'name' => 'Filter Name',
			'filter' => 'Filter',
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

		$criteria->compare('filter_id',$this->filter_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('filter',$this->filter,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
   	 * Retrieves a list of saved filters for current user.
   	 */
    public static function getSavedFilters()
    {
        $userID = User::getId();
        return Filter::model()->findAll('user_id = :user_id', array('user_id'=>$userID));
    }

}