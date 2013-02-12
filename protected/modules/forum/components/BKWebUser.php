<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 10.11.12
 * Time: 13:09
 */

class BKWebUser extends WebUser {
    private $_model = null;

    /**
     * Returns user role
     * @return mixed
     */
    function getRole() {
        if($user = $this->getModel()){
            return $user->getForumRole();
        }
    }

    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = BKUser::current();
        }
        return $this->_model;
    }
}