<?php

/**
 * This is the model class for table "{{site_settings}}".
 *
 * The followings are the available columns in table '{{site_settings}}':
 * @property string $id
 * @property integer $invites_module
 * @property string $invites_count
 * @property integer $invites_limit
 */
class SiteSettings extends CActiveRecord
{
    const BUGKICK_SETTINGS_ID = 1;

    private static $_settings = null;

    public static function getSiteSettings()
    {
        if(empty(self::$_settings)) {
            self::$_settings = self::getBugkickSettings();
        }
        return self::$_settings;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @return SiteSettings the static model class
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
		return '{{site_settings}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invites_module, invites_count, invites_limit', 'required'),
			array('invites_module, invites_limit', 'numerical', 'integerOnly'=>true),
			array('invites_count', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, invites_module, invites_count, invites_limit', 'safe', 'on'=>'search'),
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
			'invites_module' => 'Invites Module',
			'invites_count' => 'Invites Count',
			'invites_limit' => 'Invites Limit',
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
		$criteria->compare('invites_module',$this->invites_module);
		$criteria->compare('invites_count',$this->invites_count,true);
		$criteria->compare('invites_limit',$this->invites_limit);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function itemAlias($type,$code=NULL)
    {
        $_items = array(
            'yesNo' => array(
                '0' => 'No',
                '1' => 'Yes',
            ),
            'onOff' => array(
                '0' => 'Off',
                '1' => 'On',
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    /**
     * Returns settings for Bugkick
     * @return SiteSettings
     * @throws CHttpException
     */
    public static function getBugkickSettings()
    {
        $model = SiteSettings::model()->findByPk(
            SiteSettings::BUGKICK_SETTINGS_ID);
        if(empty($model))
            throw new CHttpException(500, 'Settings are not found.');
        else
            return $model;
    }
}