<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 10.11.12
 * Time: 14:20
 */

class BKPhpAuthManager extends CPhpAuthManager{
    public function init(){
        if($this->authFile===null){
            $this->authFile=Yii::getPathOfAlias('application.config.auth').'.php';
        }

        parent::init();

        // Guests have 'guest' role by default.
        if(!Yii::app()->user->isGuest){
            // Attaching role defined in DB to userID
            $this->assign(Yii::app()->user->role, Yii::app()->user->id);
        }
    }
}