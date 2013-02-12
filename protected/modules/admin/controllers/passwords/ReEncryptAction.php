<?php
/**
 * ReEncryptAction
 *
 * @author f0t0n
 */
class ReEncryptAction extends CAction {

	protected $viewData = array();
	public function run() {
		if(!empty($_FILES['file']))
			$this->reEncryptPasswords($_FILES['file']);
		$this->controller->render('re-encrypt', $this->viewData);
	}
	
	protected function reEncryptPasswords($file) {
		if(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'csv')
			return;
		$dir = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR;
		$filename = $file['tmp_name'];
		$destination = $dir . 'reEncryptPasswords_' . time();
		move_uploaded_file($filename, $destination);
		$handle = fopen($destination, 'r');
		while(true) {
			$ln = fgetcsv($handle);
			if(empty($ln))
				break;
			$email = $ln[0];
			$password = $ln[1];
			$user = User::model()->find('email=:email', array(':email'=>$email));
			if(!empty($user)) {
				$user->password = Hash::sha256($password . $user->salt());
				$user->save();
			}
		}
		unlink($destination);
		Yii::app()->user->setFlash(
			'success', 'The passwords has been re-encrypted successfully.');
	}
}