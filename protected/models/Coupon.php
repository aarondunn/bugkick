<?php

/**
 * This is the model class for table "{{coupon}}".
 *
 * The followings are the available columns in table '{{coupon}}':
 * @property string $id
 * @property string $code
 * @property integer $enabled
 * @property integer $period
 */
class Coupon extends CActiveRecord
{
    const PERIOD_HALF_YEAR = 15811200; //183 days
    const PERIOD_YEAR = 31622400; //366 days
    const PERIOD_2_YEARS = 63158400; //366+365 days

	/**
	 * Returns the static model of the specified AR class.
	 * @return Coupon the static model class
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
		return '{{coupon}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, enabled, period', 'required'),
			array('enabled, period', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, enabled, period', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'enabled' => 'Enabled',
			'period' => 'Period',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('period',$this->period);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Returns perioads
     * @return array
     */
    public static function getPeriods()
    {
        return array(
            self::PERIOD_HALF_YEAR=>'6 months',
            self::PERIOD_YEAR=>'1 year',
            self::PERIOD_2_YEARS=>'2 years',
        );
    }

    /**
     * Returns perioads
     * @param int $period
     * @return array
     */
    public static function getPeriodLabel($period)
    {
        $periods = self::getPeriods();
        return (isset($periods[$period])) ? $periods[$period] : '';
    }

    public function scopes() {
        return array(
            'enabled' => array(
                'condition' => 't.enabled=1',
            )
        );
    }
}