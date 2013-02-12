<?php
/**
 * LoginController
 *
 * @author f0t0n
 */
class LoginController extends AdminController {

	public function actionIndex() {
		if(Yii::app()->session->get('hasAdminAccess') === true)
			$this->redirect($this->createUrl('/admin'));
		$password = Yii::app()->request->getPost('password');
		if($password === 'bugkick admin') {
			Yii::app()->session->add('hasAdminAccess', true);
			$this->redirect($this->createUrl('/admin'));
		}
		$this->render('index');
	}
}