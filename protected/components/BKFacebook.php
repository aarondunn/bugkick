<?php
Yii::import('application.vendors.facebook.src.facebook', true);
/**
 * BugKick overriden version of Facebook class
 * BKFacebook
 *
 * @author f0t0n
 */
class BKFacebook extends Facebook {
	
	public function getUser() {
		if(empty($this->user) && $this->user !== null)
			$this->user = null;
		return parent::getUser();
	}
}