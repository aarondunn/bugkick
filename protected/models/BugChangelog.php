<?php

/**
 * Last changes on tickets
 */
class BugChangelog extends CActiveRecord
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
		return '{{bug_changelog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('user_id, bug_id', 'numerical', 'integerOnly'=>true),
			array('id, bug_id, user_id, change, date', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Change ID',
			'bug_id' => 'Bug ID',
			'user_id' => 'User ID',
			'change' => 'Change',
			'date' => 'Date',
		);
	}

    /**
	 * Populates model's attributes with a data
     * @param BugBase $model
     * @param array $changes
     * @param int $userID user who made change in ticket
     */
    public function populateChanges(BugBase $model,array $changes,$userID=null)
    {
        $this->bug_id =  $model->id;
        $this->user_id = (empty($userID) && $userID!==0)? Yii::app()->user->id : $userID;
        $changesData = '';

        foreach($changes as $value){

            switch ($value['field']) {
                case "user_id":
                    if (isset($value['value'][0]) && $value['value'][0] === 0){
                        $changesData .= 'removed Assignee  ';
                    }
                    else{
                        $changesData .= 'assigned the ticket to ';
                        foreach ($value['value'] as $userID => $userName){
                            $changesData .= '<b>'
                                . CHtml::link(
                                    $userName,
                                    Yii::app()->createUrl('user/view', array('id'=>$userID))
                                )
                                . '</b>, ';
                        }
                    }
                    break;
                case "status_id":
                    $changesData .= 'changed the ticket Status to <b>'. $value['value'] . '</b>, ';
                    break;
                case "label_id":
                    if (isset($value['value'][0]) && $value['value'][0] === 0){
                        $changesData .= 'removed Labels  ';
                    }
                    elseif(is_array($value['value'])){
                        $changesData .= 'changed the ticket Type to ';
                        foreach ($value['value'] as $lbl){
                            $changesData .= '<b>' . $lbl . '</b>, ';
                        }
                    }
                    break;
                case "description":
                    $changesData .= 'changed the ticket <b>Description</b>, ';
                    break;
                case "title":
                    if( $this->inMultiarray('description', $changes) === false) //we show message about title change only when description stays the same(case when it has difference just in spaces)
                        $changesData .= 'changed the ticket <b>Title</b>, ';
                    break;
                case "archived":
                    if($value['value'] == 1)
                        $changesData .= 'closed the ticket at <b>'.Helper::formatDateSlashFull().'</b>, ';
                    elseif($value['value'] == 0)
                         $changesData .= 'opened the ticket at <b>'.Helper::formatDateSlashFull().'</b>, ';
                    break;
                case "duplicate_number":
                    if($value['value'] == -1){
                        $changesData .= 'removed <b>Duplicate</b> status, ';
                    }
                    elseif($value['value'] > 0){
                        $changesData .= 'set ticket status as <b>Duplicate</b> of Ticket <a href="'.Yii::app()->createUrl("bug/view", array('id'=>$value['value'])).'">#' . $value['value'] . '</a>, ';
                        //Updating parent ticket
                        $parentBugNumber = $value['value'];
                        $project=Project::getCurrent();
                        $modelParent = Bug::model()->resetScope()->find(
                            'number=:number AND project_id=:project_id',
                            array(':number'=>$parentBugNumber, ':project_id'=>$project->project_id)
                        );
                        if (!empty($modelParent)){
                            $changeLog = new self();
                            $changeLog->bug_id = $modelParent->id;
                            $changeLog->user_id = Yii::app()->user->id;
                            $changeLog->change  = ' set status of Ticket <a href="'.Yii::app()->createUrl("bug/view", array('id'=>$model->number)).'">#' . $model->number . '</a> as <b>Duplicate</b> of this ticket';
                            $changeLog->save();
                            unset($changeLog);
                        }
                    }
                    break;
                case "priority":
                    if($value['value'] == 1)
                        $changesData .= 'marked the ticket as <b>Priority</b>, ';
                    elseif($value['value'] == 0)
                        $changesData .= 'marked the ticket as <b>Non Priority</b>, ';
                    break;
                case "created_at":
                        $changesData .= 'created ticket at <b>'.$value['value'].'</b>, ';
                    break;
                default:
                    $changesData .= 'changed the ticket ' . $value['name'] . ' to <b>"'. $value['value'] . '"</b>, ';
            }
        }
        $this->change = substr($changesData, 0, -2);
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

		$criteria->compare('id',$this->id);
		$criteria->compare('bug_id',$this->bug_id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('change',$this->change,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function inMultiarray($needle, $haystack, $strict = true)
    {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->inMultiarray($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }
}