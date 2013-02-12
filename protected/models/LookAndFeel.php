<?php

/**
 * This is the model class for table "look_and_feel".
 *
 * The followings are the available columns in table 'look_and_feel':
 * @property string $name
 * @property string $css_file
 * @property string $img_preview
 */
class LookAndFeel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return LookAndFeel the static model class
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
		return '{{look_and_feel}}';
	}
	
	public function primaryKey() {
		return 'name';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, css_file, img_preview', 'required'),
			array('name, css_file, img_preview', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, css_file, img_preview', 'safe', 'on'=>'search'),
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
			'users'=>array(self::HAS_MANY, 'User', 'look_and_feel'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Name',
			'css_file' => 'CSS File',
			'img_preview' => 'Preview Image',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('css_file',$this->css_file,true);
		$criteria->compare('img_preview',$this->img_preview,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}