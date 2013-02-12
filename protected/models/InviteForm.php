<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.12.12
 * Time: 23:03
 */
class InviteForm extends CFormModel
{
	public $name;
	public $lname;
	public $email;
	public $user;
	public $isadmin;

	public function rules()
	{
		return array(
            array('email', 'email'),
            array('name, lname', 'length', 'max' => 100, 'min' => 3, 'allowEmpty'=>true),
            array('user, isadmin', 'numerical', 'integerOnly' => true),
//            array('email', 'unique', 'className'=>'User', 'message'=>'User with such email is already Bugkick member'),
            array('user', 'exist', 'attributeName'=>'user_id', 'className'=>'User'),
            array('email', 'notEmptyEmailOrUser'),
		);
	}

    public function notEmptyEmailOrUser($attribute, $params)
    {
        if (empty($this->email)&& empty($this->user)) {
            $this->addError($attribute, Yii::t('user', 'Please enter email of new user or choose existing user'));
            return false;
        }
        return true;
   	}

    public function attributeLabels() {
 		return array(
 			'name'=>'First Name',
 			'lname'=>'Last Name',
 			'email'=>'Email',
 			'user'=>'User',
 			'isadmin'=>'Is Admin',
 		);
 	}
}
