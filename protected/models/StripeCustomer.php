<?php

/**
 * This is the model class for table "{{stripe_customer}}".
 *
 * The followings are the available columns in table '{{stripe_customer}}':
 * @property string $customer_id
 * @property string $user_id
 * @property string $company_id
 * @property string $plan_id
 * @property string $payment_interval
 * @property string $last_payment_time
 * @property string $next_payment_time
 * @property integer $is_canceled
 * @property string $notified_at
 * @property string $expires_at
 */
class StripeCustomer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StripeCustomer the static model class
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
		return '{{stripe_customer}}';
	}
	
	public function primaryKey() {
		return 'customer_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, user_id, company_id', 'required'),
			array('is_canceled', 'numerical', 'integerOnly'=>true),
			array('customer_id, plan_id', 'length', 'max'=>255),
			array('user_id, company_id, last_payment_time, next_payment_time, notified_at, expires_at', 'length', 'max'=>20),
			array('plan_id, expires_at', 'default', 'value'=>null),
			array('payment_interval, last_payment_time, next_payment_time, is_canceled', 'default', 'value'=>0),
			array('payment_interval', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, user_id, company_id, plan_id, payment_interval, last_payment_time, next_payment_time, is_canceled, notified_at, expires_at', 'safe', 'on'=>'search'),
		);
	}
	
	protected function beforeSave() {
		$this->next_payment_time =
			$this->last_payment_time + $this->payment_interval;
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
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'company'=>array(self::BELONGS_TO, 'Company', 'company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_id' => 'Customer',
			'user_id' => 'User',
			'company_id' => 'Company',
			'plan_id' => 'Plan',
			'payment_interval' => 'Payment Interval',
			'last_payment_time' => 'Last Payment Time',
			'next_payment_time'=>'Next Payment Time',
			'is_canceled' => 'Is Canceled',
			'notified_at' => 'Notified At',
			'expires_at'=>'Expires At',
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

		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('plan_id',$this->plan_id);
		$criteria->compare('payment_interval',$this->payment_interval,true);
		$criteria->compare('last_payment_time',$this->last_payment_time,true);
		$criteria->compare('is_canceled',$this->is_canceled);
		$criteria->compare('notified_at',$this->notified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}