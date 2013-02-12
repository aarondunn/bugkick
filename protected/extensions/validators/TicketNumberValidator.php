<?php

class TicketNumberValidator extends CValidator
{

	public $allowEmpty = true;
    public $allowZero = true;

	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;

		if($this->allowEmpty && ($value===null || $value===''))
			return;

        if($this->allowZero && ($value===0 || $value==='0'))
			return;

        $project=Project::getCurrent();
		if(empty($project)){
            $message=$this->message!==null?$this->message:Yii::t('yii','Please select project first.');
			$this->addError($object,$attribute,$message);
        }
        else{
            $model = Bug::model()->resetScope()->find(
                'number=:number AND project_id=:project_id',
                array(
                    ':number'=>(int)$value,
                    ':project_id'=>$project->project_id
                )
            );
            if(empty($model)){
                $message=$this->message!==null?$this->message:Yii::t('yii','Ticket number is not correct.');
		     	$this->addError($object,$attribute,$message);
            }
        }

	}
}

