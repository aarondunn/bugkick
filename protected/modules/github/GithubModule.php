<?php
/**
 * GithubModule
 *
 * @author f0t0n
 */
class GithubModule extends CWebModule {

	public function init() {
        $this->defaultController = 'auth';
		$this->setLayoutPath('themes/'.Yii::app()->theme->name.'/views/layouts');
		$this->setImport(array(
			'application.modules.github.components.*',
			'application.modules.github.components.client.*',
			'application.modules.github.models.*',
		));
	}
}