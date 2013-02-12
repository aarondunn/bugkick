<?php

class PasswordForm extends CFormModel
{
	public $password;
	public $password_new;
	public $password_new2;


	public function rules()
	{
		return array(
			array('password, password_new, password_new2', 'required'),
			array('password_new2', 'compare', 'compareAttribute'=>'password_new'),
            array('password', 'authenticate'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
        $user = User::current();
//         if($user->password !== Hash::sha256($this->password . $user->salt()))
        //if($user->password !== bin2hex(mhash(MHASH_SHA256, $this->password . Yii::app()->params['passwordSalt'])))

       $record = User::model()->findByAttributes(array('email' => $user->email));
        
        if(!$record->validatePassword($this->password))
        	$this->addError('password','Incorrect password.');
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*3000 : 0; // 3000 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
