<?php
/**
 * Checks if email is unique or account has "invited" status
 * Author: Alexey kavshirko@gmail.com
 * Date: 15.12.12
 * Time: 22:26
 */

class UniqueOrInvitedValidator extends CValidator
{

	public $allowEmpty = false;
    public $allowZero = false;

	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;

		if($this->allowEmpty && ($value===null || $value===''))
			return;

        if($this->allowZero && ($value===0 || $value==='0'))
			return;

        $user = User::model()->find('email=:email AND userStatus!='.User::STATUS_INVITED,array(
            ':email'=>$value,
        ));

		if(!empty($user)){
            $message=$this->message!==null
                ? $this->message
                : Yii::t('yii','Email Address "'.$value.'" has already been taken.');
			$this->addError($object,$attribute,$message);
        }
	}
}

