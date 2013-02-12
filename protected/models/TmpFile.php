<?php

/**
 * This is the model class for table "{{tmp_file}}".
 *
 * The followings are the available columns in table '{{tmp_file}}':
 * @property string $id
 * @property string $path
 * @property string $created_at
 */
class TmpFile extends CActiveRecord
{
	const TMP_ROOT = 'webroot.temp';
	/**
	 * Returns the static model of the specified AR class.
	 * @return TmpFile the static model class
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
		return '{{tmp_file}}';
	}

	public function primaryKey() {
		return 'id';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('path', 'required'),
			array('path', 'length', 'max'=>255),
			array('created_at', 'default', 'value'=>new CDbExpression('CURRENT_TIMESTAMP')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, path, created_at', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'path' => 'Path',
			'created_at' => 'Created At',
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
		$criteria->compare('path',$this->path,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * <b>moveFileTo</b> method moving the temporary file <br />
	 * that the current record represents to new location $destinationPath, then
	 * deletes the record from the database.
	 * 
	 * @param string $destinationPath New file's location to move to.
	 * @return bool Is the file has been moved correctrly <br />
	 * and the record has been deleted.
	 */
	public function moveFileTo($destinationPath) {
		$root = Yii::getPathOfAlias(self::TMP_ROOT);
		$relativePath = preg_match('#^/#', $this->path)
			? $this->path
			: '/' . $this->path;
		$tmpPath = $root . $relativePath;
		if(copy($tmpPath, $destinationPath)) {
			unlink($tmpPath);
			return $this->delete();
		}
		return false;
	}
}