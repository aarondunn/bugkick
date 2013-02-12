<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @property User $model
 */
class UserIdentity extends CUserIdentity {
    const ERROR_USER_STATUS = 3;

    private $_id;

    public function authenticate($comparePassword=true) {
        $record = User::model()->findByAttributes(array('email' => $this->username));

        if ($record === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if($comparePassword && !$record->validatePassword($this->password))
        //else if ($record->password !== bin2hex(mhash(MHASH_SHA256, $this->password . Yii::app()->params['passwordSalt'])))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if ($record->userStatus != User::STATUS_ACTIVE)
            $this->errorCode = self::ERROR_USER_STATUS;
        else
			$this->init($record);
        return !$this->errorCode;
    }

	/**
	 *
	 * @param User $user
	 */
	protected function init(User $user) {
		$this->_id = $user->user_id;
		$this->setState('name', $user->name);
		$this->setState('lname', $user->lname);
		$this->setState('email', $user->email);
		//if the user is bound to only one company
		if ($user->companyCount == 1) {
			$company = $user->company;
			$this->setState('company_id', $company[0]->company_id);
		}
		//if isset default company - set default company
		if (!empty($user->defaultCompany)) {
			//company exist?
			$company = Company::model()->findByPk($user->defaultCompany);
			if(!empty($company)) {
				$userByCompany = UserByCompany::model()->findByAttributes(
					array(
						'company_id' => $company->company_id,
						'user_id' => $user->user_id
					)
				);
				//exist user in company?
				if (!empty($userByCompany))
					$this->setState('company_id', $user->defaultCompany);
			}
		}
		$companyID = $this->getState('company_id');
		if(!empty($companyID)) {
			$company = Company::model()->cache(300)->findByPk($companyID);
			if(!empty($company))
				$this->setState('company_name', $company->company_name);
		}

		$this->errorCode = self::ERROR_NONE;
	}

    public function getId() {
        return $this->_id;
    }

    public function getName() {
        return 'User#'.$this->_id;
    }
}