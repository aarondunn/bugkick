<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 26.12.11
 * Time: 12:52
 */

class EHttpRequest extends CHttpRequest
{
		public $noCsrfValidationRoutes=array();
		
		protected function normalizeRequest() {
			$route=Yii::app()->getUrlManager()->parseUrl($this);
			if($this->enableCsrfValidation
				&& array_search($route, $this->noCsrfValidationRoutes)!==false) {
				$this->enableCsrfValidation=false;
			}
			parent::normalizeRequest();
		}
		
        public function validateCsrfToken($event)
        {
                if($this->getIsPostRequest())
                {
                        $cookies=$this->getCookies();
                        if($cookies->contains($this->csrfTokenName) && isset($_POST[$this->csrfTokenName]) || isset($_GET[$this->csrfTokenName] ))
                        {
                                $tokenFromCookie=$cookies->itemAt($this->csrfTokenName)->value;
                                $tokenFrom=!empty($_POST[$this->csrfTokenName]) ? $_POST[$this->csrfTokenName] : $_GET[$this->csrfTokenName];
                                $valid=$tokenFromCookie===$tokenFrom;
                        }
                        else
                                $valid=false;
                        if(!$valid)
                                throw new CHttpException(400,Yii::t('yii','Lite: The CSRF token could not be verified.'));
                }
        }
}
