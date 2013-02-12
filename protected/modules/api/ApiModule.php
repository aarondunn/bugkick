<?php
/**
 * ApiModule
 *
 * @author f0t0n
 */
class ApiModule extends CWebModule {

	public function init() {
		$this->setLayoutPath('themes/'.Yii::app()->theme->name.'/views/layouts');
		$this->setImport(array(
			'application.modules.api.components.*',
			'application.modules.api.models.*',
		));
	}
}