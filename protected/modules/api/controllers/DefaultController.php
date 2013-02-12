<?php
/**
 * DefaultController
 *
 * @author f0t0n
 */
class DefaultController extends ApiController {
	
	public function actionIndex() {
		Api::instance()->run();
		Yii::app()->end();
	}
}