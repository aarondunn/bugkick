<?php

/**
 * This is the model class for table "{{file}}".
 *
 * The followings are the available columns in table '{{file}}':
 * @property string $id
 * @property string $name
 * @property string $public_name
 * @property string $user_id
 * @property string $ticket_id
 * @property string $box_file_id
 * @property string $size
 * @property string $date
 *
 * The followings are the available model relations:
 * @property Bug $ticket
 */
class File extends CActiveRecord
{
    /**
     * Endpoint for downloading shared files http://www.box.net/s/<public_name>
     */
    const BOX_FILES_END_POINT = 'http://www.box.net/s/';

	/**
	 * Returns the static model of the specified AR class.
	 * @return File the static model class
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
		return '{{file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, public_name, user_id, ticket_id, size', 'required'),
			array('name, public_name, box_file_id', 'length', 'max'=>255),
			array('user_id, ticket_id, size', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, public_name, user_id, ticket_id, box_file_id, size, date', 'safe', 'on'=>'search'),
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
			'ticket' => array(self::BELONGS_TO, 'Bug', 'ticket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'public_name' => 'Public Name',
			'user_id' => 'User',
			'ticket_id' => 'Ticket',
			'box_file_id' => 'Box File',
			'size' => 'Size',
			'date' => 'Date',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('public_name',$this->public_name,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('ticket_id',$this->ticket_id,true);
		$criteria->compare('box_file_id',$this->box_file_id,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getFileUrl()
    {
        return self::BOX_FILES_END_POINT . $this->public_name;
    }
}