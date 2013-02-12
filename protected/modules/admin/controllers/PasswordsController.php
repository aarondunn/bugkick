<?php
/**
 * PasswordsController
 *
 * @author f0t0n
 */
class PasswordsController extends AdminController {
	
	public function actions() {
		$prefix = 'application.modules.admin.controllers.passwords.';
		return array(
			//'re-encrypt'=>$prefix.'ReEncryptAction',
		);
	}
}