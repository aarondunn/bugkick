<?php
/**
 * WebUser
 * 
 * @property boolean $isAdmin
 * @property boolean $isGuest
 * 
 * @author f0t0n
 */
class WebUser extends CWebUser {

    /**
     *
     * @var User
     */
    protected $userModel = null;
    
    public function getUserModel() {
        if($this->isGuest) {
            return null;
        }
        return empty($this->userModel)
            ? $this->userModel = User::model()->findByPk($this->id)
            : $this->userModel;
    }
    
    public function getIsAdmin() {
        $model = $this->getUserModel();
        return !empty($model) && $model->is_global_admin == 1;
    }
}
