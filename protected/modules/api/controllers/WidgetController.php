<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 31.03.12
 * Time: 13:35
 */
class WidgetController extends ApiController {

    public $layout=false;

    protected $width = 250;
    protected $height = 250;
    protected $message = array();
    protected $projectID;

	public function actionIndex()
    {
        $this->projectID = Yii::app()->request->getParam('projectID');
        $api = Api::instance();
        if ($api->checkProjectID($this->projectID)){

            $width = (int) Yii::app()->request->getParam('width');
            $height = (int) Yii::app()->request->getParam('height');

            $this->width = ($width>0)? $width : $this->width;
            $this->height = ($height>0)? $height : $this->height;
            $types = $api->getAllowedTicketTypes();

            $this->render('index', array( 'types' => $types ));
        }
        else{
            echo Yii::t('main', 'Invalid request. Please check your Project ID.');
        }
        Yii::app()->end();
	}

    public function actionCreate()
    {
        $this->projectID = Yii::app()->request->getParam('projectID');

        $api = Api::instance();
        $project = $api->checkProjectID($this->projectID);

        if ($project){
            $ticketText = Yii::app()->request->getParam('ticketText');
            $ticketType = Yii::app()->request->getParam('ticketType');
            $types = $api->getAllowedTicketTypes();

            if (!empty($ticketText) && !empty($ticketType) && in_array($ticketType, $types)){

                $data[Api::API_CALL_POST_KEY]=array(
                    'ticketText'=>$ticketText,
                    'ticketType'=>$ticketType,
                    'method'=>'createTicket',
                    'apiKey'=>$project->company->api_key,
                    'projectID'=>$this->projectID,
                );
                $_POST = $data;

                ob_start();
                $api->run();
                $responseStr = ob_get_contents();
                ob_end_clean();

                $responseObj=json_decode($responseStr);

                if(empty($responseObj->success)){
                    $this->message = array('error'=>$responseObj->error);
                }
                else{
                    echo Yii::t('main', 'Ticket was created.');
                    Yii::app()->end();
                }
            }
            else{
                $this->message = array('error'=>'Please check the fields.');
            }

            $width = (int) Yii::app()->request->getParam('width');
            $height = (int) Yii::app()->request->getParam('height');

            $this->width = ($width>0)? $width : $this->width;
            $this->height = ($height>0)? $height : $this->height;

            $this->render('index', array( 'types' => $types ));
        }
        else{
            throw new CHttpException(400,'Invalid request.');
        }
        Yii::app()->end();
    }
}