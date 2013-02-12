<?php
/**
 * UsersController
 *
 * @author f0t0n
 */
class UserController extends AdminController {
    
    const GRID_USERS_ID = 'users-grid';
    const GRID_RECENT_USERS_ID = 'recent-users-grid';
    
    public function actionIndex() {
        $usersStats = UsersStats::instance();
        $user = new AUser('search');
        $userRecent = new AUser('search');
        $daysCount = 30;
        
        if($this->request->isAjaxRequest) {
            $this->handleGridAjaxRequest($user, $userRecent, $daysCount);
        } else {
            $this->render('index', array(
                'model'=>$user,
                'modelRecent'=>$userRecent,
                'usersStats'=>$usersStats,
                'daysCount'=>$daysCount,
            ));
        }
    }
    
    protected function handleGridAjaxRequest(
            AUser $user, AUser $userRecent, $daysCount = 30) {
        $ajaxVar = $this->request->getParam('ajax');
        $attributes = $this->request->getParam('AUser');
        if(!empty($attributes)) {
            if($ajaxVar == self::GRID_USERS_ID) {
                $user->setAttributes($attributes);
            } else if($ajaxVar == self::GRID_RECENT_USERS_ID) {
                $userRecent->setAttributes($attributes);
            }
        }
        if($ajaxVar == self::GRID_USERS_ID) {
            $this->renderPartial('_users_grid', array(
                'model'=>$user,
            ));
        } else if($ajaxVar == self::GRID_RECENT_USERS_ID) {
            $this->renderPartial('_recent_users_grid', array(
                'model'=>$userRecent,
                'daysCount'=>$daysCount,
            ));
        }
    }
    
    public function actionUpdate($id) {
        $user = AUser::model()->findByPk($id);
        if(empty($user)) {
            throw new CHttpException(404, 'User not found.');
        }
        $formID = 'user-update-form';
        $attributes = $this->request->getPost('AUser');
        $this->performAjaxValidation($user, $formID);
        if(!empty($attributes)
                && $this->updateUser($user, $attributes)) {
            Yii::app()->end();
        } else {
            $user->password = '';
        }
        $this->renderPartial('update', array(
            'model'=>$user,
            'formID'=>$formID,
        ));
    }
    
    protected function updateUser(AUser $user, array $attributes) {
        $this->skipEmptyFields($attributes, array(
            'email',
            'password',
        ));
        if(!empty($attributes['password'])) {
            $user->setPassword($attributes['password']);
            unset($attributes['password']);
        }
        $user->setAttributes($attributes);
        return $user->validate() && $user->save();
    }
    
    /**
     * @param array $attributes
     * @param array $attributeNames The keys in $attributes array
     * which should be unset if they're empty.
     */
    protected function skipEmptyFields(&$attributes, $attributeNames) {
        foreach($attributeNames as $key) {
            if(empty($attributes[$key])) {
                unset($attributes[$key]);
            }
        }
    }

    public function actionLoginAs($id)
    {
        $user = User::model()->findByPk($id);
        if(!empty($user)){
            $identity = new UserIdentity($user->email,100500);
            if($identity->authenticate(false)){
                $duration=3600*24*3000; // 3000 days
                Yii::app()->user->login($identity,$duration);
                $this->redirect('/user/view');
            }
        }
        $this->_404('User is not active.');
    }

    public function actionUpgrade($id){
        $user = User::model()->findByPk($id);
        if(!empty($user)){
            if($user->pro_status == 0){
                $user->pro_status = 1;
                $user->save();
                Company::model()->updateAll(
                    array(
                        'account_type'=>1,
                        'account_plan'=>'pro_year'
                    ),
                    'owner_id=:owner_id',
                    array(':owner_id'=>$user->user_id)
                );
            }
            else{
                $user->pro_status = 0;
                $user->save();
                Company::model()->updateAll(
                    array(
                        'account_type'=>0,
                        'account_plan'=>''
                    ),
                    'owner_id=:owner_id',
                    array(':owner_id'=>$user->user_id)
                );
            }
            $this->redirect('/admin/user');
        }
        else{
            $this->_404('User was not found.');
        }
    }

    /**
   	 * Deletes a particular model.
   	 * If deletion is successful, the browser will be redirected to the 'admin' page.
   	 * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
   	 */
   	public function actionDelete($id)
   	{
   		if(Yii::app()->request->isPostRequest)
   		{
   			// we only allow deletion via POST request
            $model = User::model()->findByPk($id);
            if(empty($model))
                throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

            $model->delete();
            UserByProject::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
            UserByGroup::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
            BugByUser::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
            UserByCompany::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
            UserByEmailPreference::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));
            SettingsByUser::model()->deleteAll('user_id=:user_id', array(':user_id'=>$id));

   			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
   			if(!isset($_GET['ajax']))
   				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/user'));
   		}
   		else
   			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
   	}
}