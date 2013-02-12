<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.11.12
 * Time: 0:32
 */

class MarketingController extends AdminController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = SiteSettings::getBugkickSettings();

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
   	 * Updates a particular model.
   	 * If update is successful, the browser will be redirected to the 'view' page.
   	 * @param integer $id the ID of the model to be updated
   	 */
   	public function actionUpdate()
   	{
   		$model = SiteSettings::getBugkickSettings();
        $form = new MarketingForm;
        $form->setAttributes($model->getAttributes());

   		// Uncomment the following line if AJAX validation is needed
   		$this->performAjaxValidation($form);

   		if(isset($_POST['MarketingForm']))
   		{
   			$model->attributes=$_POST['MarketingForm'];
   			if($model->save()){
                   Yii::app()->user->setFlash('success','Saved!');
                   $this->redirect(array('/admin/marketing/'));
            }
   		}
   		$this->render('update',array(
   			'model'=>$form,
   		));
   	}

    /**
   	 * Performs the AJAX validation.
   	 * @param CModel the model to be validated
   	 */
   	public function performAjaxValidation($model)
   	{
   		if(isset($_POST['ajax']) && $_POST['ajax']==='site-settings-form')
   		{
   			echo CActiveForm::validate($model);
   			Yii::app()->end();
   		}
   	}
}