<?php
/**
 * KeyController
 *
 * @author f0t0n
 */
class KeyController extends ApiController {
    
	public function actionGenerate()
    {
        $company = Company::model()->findByPk( Company::current() );
        if(empty($company)) {
            $this->redirect(array('/site/login'));
        }
        if ($company->account_type == Company::TYPE_PAY){

            if(User::current()->isCompanyAdmin(Company::current())) {
                $refreshAPI = (int) $this->request->getParam('refresh');
                if (empty($company->api_key) ){
                    $company->refreshApiKey()->save();
                }
                elseif($refreshAPI == 1){
                    Yii::app()->user->setFlash( 'success', Yii::t('main', 'You API key was refreshed.') );
                    $company->refreshApiKey()->save();
                }
                MixPanel::instance()->registerEvent(MixPanel::API_CODE_PAGE_VIEW); // MixPanel events tracking
                $this->render('generate', array( 'company' => $company ));
            }
            else{
                Yii::app()->user->setFlash( 'error', Yii::t('main', 'You need admin permissions to use this feature.') );
                $this->redirect(Yii::app()->createUrl('settings/company'));
            }
        }
        else{
            Yii::app()->user->setFlash( 'error', Yii::t('main', 'You need Pro-account to use this feature.') );
            $this->redirect(Yii::app()->createUrl('settings/company'));
        }

	}
}