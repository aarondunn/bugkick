<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'Email address',
			'password'=>'Password',
			'rememberMe'=>'Remember me',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate()) {
                switch($this->_identity->errorCode) {
                    case UserIdentity::ERROR_USERNAME_INVALID : 
                   		$this->addError('username', 'Username invalid'); 
                   		Yii::app()->session['username_valid']=false;
                    	break;
                    case UserIdentity::ERROR_PASSWORD_INVALID :
                    	$this->addError('password', 'Password invalid'); break;
                    case UserIdentity::ERROR_USER_STATUS :
                    	$this->addError('username', 'Account disable'); 
                    break;  
                }
            }
				//$this->addError('password','Incorrect username or password.');
		}
	}

    public function validate($attributes = null, $clearErrors = true) {
        $this->rememberMe = (bool)$this->rememberMe;
        return parent::validate($attributes, $clearErrors);
    }

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login($comparePassword=true)
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate($comparePassword);
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*3000 : 0; // 3000 days
			Yii::app()->user->login($this->_identity,$duration);
			$cookie_name=str_replace('.','_',Yii::app()->user->email.'.bugkick');
			unset(Yii::app()->request->cookies[$cookie_name]);
			return true;
		}
		else
			return false;
	}
}
