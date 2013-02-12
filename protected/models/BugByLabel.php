<?php

/**
 * This is the model class for table "{{bug_by_label}}".
 *
 * The followings are the available columns in table '{{bug_by_label}}':
 * @property string $bug_id
 * @property string $label_id
 */
class BugByLabel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return BugByLabel the static model class
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
		return '{{bug_by_label}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bug_id, label_id', 'required'),
			array('bug_id, label_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('bug_id, label_id', 'safe', 'on'=>'search'),
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
			'bug'=>array(self::BELONGS_TO, 'Bug', 'bug_id'),
			'label'=>array(self::BELONGS_TO, 'Label', 'label_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bug_id' => 'Bug',
			'label_id' => 'Label',
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

		$criteria->compare('bug_id',$this->bug_id,true);
		$criteria->compare('label_id',$this->label_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}