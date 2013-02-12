<?php
/**
 * AdminController
 *
 * @author f0t0n
 */
class AdminController extends Controller {
    
    public function filters() {
        return array(
            'accessControl',
        );
    }
    
    public function accessRules() {
        return array(
            array('allow',
                'expression'=>function($user, $rule) {
                    return $user->isAdmin;
                },
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}