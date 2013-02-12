<?php

class RegistrationController extends Controller
{
    const PASSWORD_ENCRYPTION_ALGORITHM = User::ALGORITHM_BCRYPT;
    const BK_NEW_USER = 'bugkick_new_user';

    /**
     * @var Coupon
     */
    protected $coupon = null;

	protected function fbValidateSignedRequest($signedRequest) {
		if(empty($signedRequest))
			return null;
		$response = FacebookHelper::parseSignedRequest($signedRequest);
		return $response;
	}
	
	protected function fbRegisterUser($response)
    {
		/*$company = new Company;
		$company->company_name = $response['registration']['company_name'];
		$company->company_url = $response['registration']['company_url'];*/
        $company = Company::model()->createNew();
		$user = new User('registration');
		$user->facebook_id = $response['user_id'];
		$user->email = $response['registration']['email'];
		$user->name = $response['registration']['first_name'];
		$user->lname = $response['registration']['last_name'];
		$user->userStatus = User::STATUS_ACTIVE;
		$user->defaultCompany = $company->company_id;
        $user->encryption_algorithm = self::PASSWORD_ENCRYPTION_ALGORITHM;
		$user->password = $user->hashPassword(
                $response['registration']['password']);
        if($user->validate() && $user->save()) {
            $user->setDefaultEmailPreferences();
            $this->session['fromRegistration'] = 1;
            $userByCompany = new UserByCompany;
            $userByCompany->company_id = $company->company_id;
            $userByCompany->user_id = $user->user_id;
            $company->owner_id = $user->user_id;
            if(!$userByCompany->save() || !$company->save()) {
                return;
            }
        }
        else{
            return;
        }
		Yii::app()->user->setFlash('success', 'Registration complete!');
		$this->forward('/site/login');
		//$this->redirect($this->createUrl('bug/'));
	}
	
	public function actionFacebook()
    {
		$graph = $this->session->get('fbGraph');
		if(empty($graph))
			$this->redirect($this->createUrl('/site/login'));
		$signedRequest = Yii::app()->request->getParam('signed_request');
		$response = $this->fbValidateSignedRequest($signedRequest);
		if(!empty($response))
			$this->fbRegisterUser($response);
		$this->render('v2/facebook');
	}
	
	public function actionIndex()
    {
		if(!Yii::app()->user->isGuest)
			$this->redirect($this->createUrl('/bug'));
		$user = new User('registration');
        $company = new Company;
        $this->performAjaxValidation($user, $company);

        $subscriptions = array(
            'free'=>'Free',
            'pro'=>'Pro',
        );
        //saving selected subscription
        $subscription = $this->request->getParam('subscription');
        if(!empty($subscription)) {
            switch($subscription) {
                /*case 'premium':
                    $this->session['planType'] = 'premium';
                    break;
                case 'ultimate':
                    $this->session['planType'] = 'ultimate';
                    break;*/
                case 'pro':
                    $this->session['planType'] = 'pro';
                    break;
            }
        }

		//if(isset($_POST['User']) && isset($_POST['Company']))
		if(isset($_POST['User']))
		{
            /*
             * When invited user wants to register we allow him to do it.
             * In case when he lost his invite link and tries to register :)
             */
            $invitedUser = User::model()->find('email=:email AND userStatus='.User::STATUS_INVITED,array(
                ':email'=>$_POST['User']['email'],
            ));
            if(!empty($invitedUser)){
                $invitedUser->attributes = $_POST['User'];
                $invitedUser->encryption_algorithm = self::PASSWORD_ENCRYPTION_ALGORITHM;
                $invitedUser->password = $invitedUser->hashPassword($invitedUser->password);
                $invitedUser->userStatus = User::STATUS_ACTIVE;
                $invitedUser->inviteToken = '';
                $invitedUser->setDefaultEmailPreferences();
                $user_img = CUploadedFile::getInstance($invitedUser,'profile_img');
                if(!empty($user_img))
                    $invitedUser->profile_img = md5(uniqid(mt_rand(), true)) . '.' . $user_img->getExtensionName();
                if(!empty($invitedUser->profile_img)) {
                    Yii::import('ext.EWideImage.EWideImage');
                    $folder = 'images/profile_img/'; // folder for uploaded files
                    $user_img->saveAs($folder . $invitedUser->profile_img);

                    $fileName = $invitedUser->profile_img;
                    $filePath = $folder . $fileName;

                    //preview 81px*81px
                    $thumbName = '81_81_'.$fileName;
                    $thumbPath = $folder . $thumbName;
                    EWideImage::load($filePath)->resize(81, 81)->saveToFile($thumbPath);
                    $result['filename'] = $this->handleImage('user', $thumbName, $thumbPath); //we return path to this thumb

                    //preview 31px*31px
                    $thumbName = '31_31_'.$fileName;
                    $thumbPath = $folder . $thumbName;
                    EWideImage::load($filePath)->resize(31, 31)->saveToFile($thumbPath);
                    $this->handleImage('user', $thumbName, $thumbPath);

                    @unlink($filePath); //remove original image
                }
                if($invitedUser->save()){
                    $this->session['fromRegistration'] = 1;
                    $loginForm = new LoginForm();
                    $loginForm->username = $invitedUser->email;
                    $loginForm->login(false);
                    $this->redirect($this->createUrl('site/login'));
                }
                else
                    throw new CException('MySQL error #02 during registration invited user.');
            }
            /*
             * End of invited user registration
             */

            $user->attributes = $_POST['User'];

            if(!empty($this->coupon)){
                //user applied valid coupon
                $company->coupon_id = $this->coupon->id;
                $company->coupon_expires_at = time() + $this->coupon->period;
                $company->account_type = Company::TYPE_PAY;
                $company->account_plan = 'pro_year';
                $this->session['registersWithCoupon'] = 1;
            }

            $user->encryption_algorithm = self::PASSWORD_ENCRYPTION_ALGORITHM;
			$user->password = $user->hashPassword($user->password);

            $user->email_notify = 1;
            //remove the below to enable confirmation
            $user->userStatus = User::STATUS_ACTIVE;

            $user->isadmin = 1;
            
            $user_img = CUploadedFile::getInstance($user,'profile_img');
            if(!empty($user_img))
                $user->profile_img = md5(uniqid(mt_rand(), true)) . '.' . $user_img->getExtensionName();
            /*$company_logo = CUploadedFile::getInstance($company,'company_logo');
            if(!empty($company_logo))
                $company->company_logo = md5(uniqid(mt_rand(), true)) . '.' . $user_img->getExtensionName();
            */
			if($user->validate()
                && ($company = Company::model()->createNew($company)) !== null) {
                $user->save();
                $company->owner_id = $user->user_id;
                $company->save();
                $user->setDefaultEmailPreferences();
                $user->defaultCompany = $company->company_id;

				$user->registration_token = 
					Hash::sha256($user->id . uniqid(mt_rand(100,100500), true));
				if(!$user->save())
					throw new CException('MySQL error #01 during registration.');

                //uncomment the below to enable confirmation
				//Notificator::newRegistration($user);

                //save image files and thumb
                if(!empty($user->profile_img)) {
                    Yii::import('ext.EWideImage.EWideImage');
                    $folder = 'images/profile_img/'; // folder for uploaded files
                    $user_img->saveAs($folder . $user->profile_img);

                    $fileName = $user->profile_img;
                    $filePath = $folder . $fileName;

                    //preview 81px*81px
                    $thumbName = '81_81_'.$fileName;
                    $thumbPath = $folder . $thumbName;
                    EWideImage::load($filePath)->resize(81, 81)->saveToFile($thumbPath);
                    $result['filename'] = $this->handleImage('user', $thumbName, $thumbPath); //we return path to this thumb

                    //preview 31px*31px
                    $thumbName = '31_31_'.$fileName;
                    $thumbPath = $folder . $thumbName;
                    EWideImage::load($filePath)->resize(31, 31)->saveToFile($thumbPath);
                    $this->handleImage('user', $thumbName, $thumbPath);

                    @unlink($filePath); //remove original image
                }
//                if(!empty($company->company_logo)) {
//                    $company_logo->saveAs(Yii::getPathOfAlias('webroot.images.company_logo') . '/' . $company->company_logo);
//                    ImageHelper::thumb(100, 100, Yii::getPathOfAlias('webroot.images.company_logo') . '/' . $company->company_logo);
//                }
                          
                $userByCompany = new UserByCompany;
                $userByCompany->company_id = $company->company_id;
                $userByCompany->user_id = $user->user_id;
                $userByCompany->is_admin = 1; // Make user an admin in his company.
                if($userByCompany->save()) {

                    Status::createPresets($company->company_id);
                    Label::createPresets($company->company_id);

                    //delete the below to enable confirmation
                    Yii::app()->user->setFlash(
                        'success',
                        'Registration completed. You can sign in with your credentials'
                    );
                    $this->session['fromRegistration'] = 1;
                    $loginForm = new LoginForm();
                    $loginForm->username = $user->email;
                    $loginForm->login(false);
                    //END of delete

                    //uncomment the below to enable confirmation
                    /*
                    Yii::app()->user->setFlash(
						'success',
						'Please check your email to complete your registration.'
					);
                    */

                    $this->redirect($this->createUrl('site/login'));
                }
                Yii::app()->end();
            }
            $user->password = '';
            //Yii::app()->user->setFlash('registration','Error in the course of saving of the data');
            //$this->refresh();
		}

        MixPanel::instance()->registerEvent(MixPanel::SIGN_UP_PAGE_VIEW); // MixPanel events tracking

        $this->layout='/layouts/index-new';

		$this->render('v2/index',array(
			'user'=>$user,
			'company'=>$company,
            'subscription'=>$subscription,
            'subscriptions'=>$subscriptions,
		));
	}

    /**
     * Handles upload image to s3(if enabled) and return path to file
     * @param $for = 'user' or 'company'
     * @param string $fileName - image name like 123.jpg
     * @param $filePath - full path to image like images/user/123.jpg
     * @return string path to image
     * @throws CHttpException
     */
    protected function handleImage($for, $fileName, $filePath)
    {
        switch (Yii::app()->params['storageType']){
             case 's3':
                 //upload to s3
                 if($for=='user')
                     $bucket = S3Storage::PROFILE_BUCKET;
                 elseif($for=='company')
                     $bucket = S3Storage::COMPANY_TOP_BUCKET;
                 else
                     return null;

                 $s3FilePath = Storage::get('s3')->upload(
                     $bucket,
                     $fileName,
                     $filePath
                 );
                 @unlink($filePath);
                 if (!empty($s3FilePath))
                     return $s3FilePath;
                 break;
             case 'local':
                 return $filePath;
                 break;
        }
    }

	public function actionVerify()
    {
		$token = $this->request->getParam('t');
		if(empty($token))
			$this->forward('/registration');
		$user = User::model()->find(
			'registration_token=:registration_token',
			array(':registration_token'=>$token)
		);
		if(empty($user))
			$this->forward('/registration');
		$user->userStatus = User::STATUS_ACTIVE;
		if(!$user->save())
			throw new CException(404, 'Error during registration verification.');
		Yii::app()->user->setFlash(
			'success',
			'Registration completed. You can sign in with your credentials'
		);
		$loginForm = new LoginForm();
		$loginForm->username = $user->email;
		$loginForm->login(false);
		$this->redirect($this->createUrl('site/login'));
	}
    
    public function performAjaxValidation($user, $company)
    {
        //validate coupon
        $this->validateCoupon($company);

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            //echo CActiveForm::validate(array($model, $model2));
            $user->attributes = $this->request->getPost(
                get_class($user), array());
            echo CActiveForm::validate($user);
            Yii::app()->end();
        }
    }

    /**
     * Ajax validation for coupon
     * @param Company $model
     * @return array of errors
     */
    protected function validateCoupon($model)
    {
        $errors = array();
        if (isset($_POST['Company']['coupon_id'])) {
            $couponCode = $_POST['Company']['coupon_id'];
            if (!empty($couponCode)){
                $coupon =  Coupon::model()->enabled()->find('code=:code',array(':code'=>$couponCode));
                if(empty($coupon))
                    $errors[CHtml::activeId($model,'coupon_id')] = array('Incorrect Coupon Code.');
                else
                    $this->coupon = $coupon;
            }
        }
        if(!empty($errors)){
            echo function_exists('json_encode') ? json_encode($errors) : CJSON::encode($errors);
            Yii::app()->end();
        }
    }
}