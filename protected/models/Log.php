<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $log_id
 * @property string $timestamp
 * @property integer $user_id
 * @property string $action_id
 * @property string $comment
 * @property string $success
 */
class Log extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Log the static model class
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
		return '{{log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, action_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('action_id, comment', 'length', 'max'=>255),
			array('success', 'length', 'max'=>1),
            array('log_id, timestamp, user_id, action_id, comment, success', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('log_id, timestamp, user_id, action_id, comment, success', 'safe', 'on'=>'search'),
		);
	}

    protected function beforeSave() {
        $this->timestamp = new CDbExpression('NOW()');
        return parent::beforeSave();
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
			'log_id' => 'Log',
			'timestamp' => 'Timestamp',
			'user_id' => 'User',
			'action_id' => 'Action',
			'comment' => 'Comment',
			'success' => 'Success',
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

		$criteria->compare('log_id',$this->log_id);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('action_id',$this->action_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('success',$this->success,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}