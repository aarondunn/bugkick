<?php

/**
 * This is the model class for table "label".
 *
 * The followings are the available columns in table 'label':
 * @property integer $label_id
 * @property string $name
 * @property integer $company_id
 * @property integer $pre_created
 */
class Label extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Label the static model class
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
		return '{{label}}';
	}
    
    public function primaryKey()
    {
        return 'label_id';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('company_id, pre_created', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30),
            array('label_color', 'length', 'max'=>10),
            array('projects', 'numArray'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('label_id, name, company_id, label_color, pre_created', 'safe', 'on'=>'search'),
		);
	}

    public function numArray($attribute, $params)
    {
        $allowEmpty = true;

        if($allowEmpty && ($this->$attribute==null))
            return;

        if(!is_array($this->$attribute) || !count($this->$attribute))
            $this->addError($attribute, 'Wrong list of identifiers');
        foreach($this->$attribute as $id)
            if(!(int)$id) {
                $this->addError($attribute, 'Wrong identifier');
                break;
            }
    }
    
    protected function beforeSave()
    {
        if( $this->scenario == 'insert'){
            $this->company_id = Yii::app()->user->company_id;
        }
        $this->name = htmlspecialchars($this->name);
        return true;
    }
    
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		$relations = array(
			'bug'=>array(
				self::MANY_MANY,
				'Bug',
				'{{bug_by_label}}(label_id, bug_id)'
			),
            'bugCount'=>array(
				self::STAT,
				'Bug',
				'{{bug_by_label}}(label_id, bug_id)'
			),
            'projects'=>array(
                self::MANY_MANY,
                'Project',
                '{{label_by_project}}(label_id, project_id)',
                'order' => 'projects.name'
            ),
//            'bugCount'=>array(self::STAT, 'Bug', 'label_id', 'condition'=>'company_id=' . Company::current(). ' AND project_id=' . Project::getCurrent()->project_id)
		);
        $currentProject = Project::getCurrent();
        if(!empty($currentProject)) {
            $relations['bugCountForProject'] = array(
                self::STAT,
                'Bug',
                '{{bug_by_label}}(label_id, bug_id)',
                'condition'=>'t.project_id=:current_project_id AND t.isarchive IS NULL',
                'params'=>array(
                    ':current_project_id'=>$currentProject->project_id,
                ),
            );
        }
        return $relations;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'label_id' => 'id',
			'name' => 'Name',
            'label_color'=> 'Label Color',
			'company_id' => 'Company',
            'pre_created'=> 'Created by default'
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

		$criteria->compare('label_id',$this->label_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('company_id',$this->company_id);
        $criteria->compare('label_color',$this->label_color);
        $criteria->compare('pre_created',$this->pre_created);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
    public static function getLabelByCompany($company_id='') {
        if(empty($company_id))
            $company_id = Yii::app()->user->company_id;
        $label = Label::model();
        return $label->findall('company_id=' . $company_id);
    }
    
    public static function getNameById($id) {
        if(!empty($id)) {
            $model = Label::model()->findByPk((int) $id);
            return $model->name;
        }
    }

    public static function createPresets($companyID)
    {
        $presets = Yii::app()->params['labels'];

        if ($companyID > 0 && (is_array($presets) && !empty($presets)) ){

            $sql='INSERT INTO {{label}} (name, label_color, company_id, pre_created) VALUES ';

            foreach($presets as $key=>$value){
                $sql .=  "('".$key."', '".$value."', ".$companyID.", '1' ),";
            }
            $sql = substr($sql, 0, -1);

            $cmd=Yii::app()->db->createCommand();
            $cmd->setText($sql)->execute();
        }
    }

    public static function getPresets($company_id = '')
    {
        if (empty($company_id))
            $company_id = Yii::app()->user->company_id;

        $labels = self::model()->findAll('company_id=:company_id AND pre_created=:pre_created',
            array(':company_id'=>$company_id, 'pre_created'=>1 ));
        return $labels;
    }

    public function scopes()
    {
   		return array(
            'ticketsList'=>array(
                'select'=>array(
                    't.label_id',
                    't.name',
                    't.label_color',
                )
            ),
   		);
   	}
}