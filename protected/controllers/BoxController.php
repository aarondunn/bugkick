<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 14.01.13
 * Time: 23:21
 */

class BoxController extends Controller
{
    /**
     * @var Box_Rest_Client Box API
     */
    protected $api;

    /**
     * @var string Box API token
     */
    protected $token;
    /**
   	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   	 * using two-column layout. See 'protected/views/layouts/column2.php'.
   	 */
   	public $layout='//layouts/column2';

    public function init()
    {
        Yii::import('application.vendors.box.lib.Box_Rest_Client');
        $api_key = Yii::app()->params['box']['api_key'];
        $this->api = new Box_Rest_Client($api_key);
        $this->token = Yii::app()->session->get('boxAuthToken');
    }

    /*
     * Action to authenticate user in Box
     */
    public function actionAuthenticate()
    {
        if(empty($this->token)){
            Yii::app()->session->add('returnUrl',Yii::app()->request->getUrlReferrer());
            Yii::app()->session->add('boxAuthToken', $this->api->authenticate());
        }
        else{
            $this->api->auth_token = Yii::app()->session->get('boxAuthToken');
            $this->redirect(Yii::app()->request->getUrlReferrer());
        }
    }

    /**
     * Return callback for Box
     */
    public function actionReturn()
    {
        Yii::app()->session->add('boxAuthToken', $this->api->authenticate());
        $returnUrl = Yii::app()->session->get('returnUrl');
        $returnUrl = empty($returnUrl)? '/' : $returnUrl;
        $this->redirect($returnUrl);
    }
}