<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 19.12.12
 * Time: 22:30
 */

class DomainsBlacklistValidator extends CValidator
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

        $domain = $this->getDomainFromEmail($value);
        if(empty($domain))
            return;

        $blackDomain = DomainsBlacklist::model()->find('domain=:domain',array(
            ':domain'=>$domain
        ));

        if(!empty($blackDomain)){
            $message=$this->message!==null ? $this->message:Yii::t('yii','Please use a different email provider.');
            $this->addError($object,$attribute,$message);
        }
	}

    protected function getDomainFromEmail($email)
    {
        return substr(strrchr($email, "@"), 1);
    }
}