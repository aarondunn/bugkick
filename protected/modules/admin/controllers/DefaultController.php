<?php
/**
 * DefaultController
 *
 * @author f0t0n
 */
class DefaultController extends AdminController {

	public function actionIndex() {
        $this->render('index');
	}
}