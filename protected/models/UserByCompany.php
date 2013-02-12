<?php

/**
 * This is the model class for table "user_by_company".
 *
 * The followings are the available columns in table 'user_by_company':
 * @property integer $id
 * @property integer $user_id
 * @property integer $company_id
 * @property integer $user_status The user's status in company.
 * @property integer $is_admin Defines is the user an admin of this company.
 */
class UserByCompany extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return UserByCompany the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_by_company}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, company_id, is_admin, user_status', 'required'),
            array('user_id, company_id, is_admin, user_status', 'numerical', 'integerOnly' => true),
            array('user_status', 'default', 'value'=>'1', 'setOnEmpty'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, company_id, is_admin, user_status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'company_id' => 'Company',
            'is_admin'=>'Is Company Admin',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

//        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('is_admin', $this->company_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}