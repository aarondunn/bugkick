<?php

/**
 * This is the model class for table "status".
 *
 * The followings are the available columns in table 'status':
 * @property integer $id
 * @property string $label
 * @property string $status_color
 * @property string $is_visible_by_default
 * @property integer $company_id
 */
class Status extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Status the static model class
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
		return '{{status}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label', 'required'),
			array('company_id, is_visible_by_default', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>30),
            array('status_color', 'length', 'max'=>10),
            array('is_visible_by_default', 'default', 'value'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('status_id, label, company_id, status_color, is_visible_by_default',
                'safe', 'on'=>'search'),
		);
	}
    
    protected function beforeSave()
    {
        if( $this->scenario == 'insert'){
            $this->company_id = Yii::app()->user->company_id;
        }
        $this->label = htmlspecialchars($this->label); 
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
            'bugCount'=>array(self::STAT, 'Bug', 'status_id', 'condition'=>'company_id=' . Company::current())
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'status_id' => 'ID',
			'label' => 'Status',
            'status_color'=> 'Status Color',
            'is_visible_by_default'=>(
                $this->isNewRecord
                    ? 'Tickets with this status will be visible by default'
                    : 'Tickets with status <i>"' . $this->label
                            . '"</i> are visible by default'
             ),
			'company_id' => 'Companyid',
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

		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('company_id',$this->companyid);
        $criteria->compare('status_color',$this->status_color);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
    public static function getStatusByCompany($company_id='') {
        if(empty($company_id))
            $company_id = Yii::app()->user->company_id;
        $status = Status::model();
        return $status->findall('company_id=' . $company_id);
    }
    
    public static function getNameById($id) {
        if(!empty($id)) {
            $model = Status::model()->findByPk((int) $id);
            return $model->label;
        }
    }

    public static function createPresets($companyID)
    {
        $presets = Yii::app()->params['statuses'];

        if ($companyID > 0 && (is_array($presets) && !empty($presets)) ) {
            $sql='INSERT INTO {{status}} (label, status_color, is_visible_by_default, company_id) VALUES ';
            foreach($presets as $row) {
                $sql .=  "('{$row['label']}', '{$row['status_color']}', '{$row['is_visible_by_default']}', '{$companyID}'),";
            }
            $sql = substr($sql, 0, -1);
            $cmd=Yii::app()->db->createCommand();
            $cmd->setText($sql)->execute();
        }
    }

}