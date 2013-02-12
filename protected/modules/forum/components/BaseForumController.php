<?php
/**
 * BaseForumController
 *
 * @property string $assetsUrl
 *
 * @author f0t0n
 */
class BaseForumController extends Controller {

    public function init() {
        //$this->layout = 'bkforum';
        $this->layout = '//../views/layouts/column2_forum';
        parent::init();
    }

    public function getAssetsUrl() {
        return $this->getModule()->getAssetsUrl();
    }
}