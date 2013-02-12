<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 06.12.12
 * Time: 22:47
 */
class NewController extends Controller
{
    const BK_STEP = 'bugkick_current_step';
    public $layout = '//layouts/column1';
    public $defaultAction = 'view';
    public $currentStep=1;

    public function init()
    {
        $this->currentStep = self::getCurrentStep();
        parent::init();
    }

    public function actionView($id)
    {
        switch($id){
            case 1:
                MixPanel::instance()->registerEvent(MixPanel::INTRO_PAGE_VIEW); // MixPanel events tracking
                $this->render('step1');
                break;
            case 2:
                $user=User::current();
                if(empty($user)){
                    Yii::app()->user->setFlash(
                        'error',
                        'Please login first'
                    );
                    $this->redirect(Yii::app()->createUrl('bug/'));
                }
                $skip = Yii::app()->request->getParam('skipStep');
                if($skip && $this->currentStep<3){
                    MixPanel::instance()->registerEvent(MixPanel::SKIP_STEP, array('step' => $this->currentStep)); // MixPanel events tracking
                    $this->currentStep++;
                    NewController::setCurrentStep($this->currentStep);
                }
                $companies = empty($user->company) ? array() : $user->company;
                $viewData['companies'] = array();
                foreach($companies as $company)
                    $viewData['companies'][$company->company_id] =
                        $company->company_name;
                $viewData['formAction'] = $this->createUrl('project/create');
                $viewData['projectForm'] = new ProjectForm();
                $viewData['project'] = new Project;
                $viewData['projectSettings'] = new SettingsByProject;
                Yii::app()->clientScript->registerScriptFile(
//                    Yii::app()->baseUrl . '/js/project/index/common.js'
                    Yii::app()->baseUrl . '/js/project/index/common.min.js'
                );

                if(Yii::app()->request->getParam('completed_step_1')){
                    MixPanel::instance()->registerEvent(MixPanel::COMPLETE_STEP, array('step' => 1)); // MixPanel events tracking
                    Yii::app()->user->setFlash('success', 'Project has been created.');
                }
                elseif(Yii::app()->request->getParam('completed_step_2'))
                    MixPanel::instance()->registerEvent(MixPanel::COMPLETE_STEP, array('step' => 2)); // MixPanel events tracking

                $this->render('step2',$viewData);
                break;
            default:
                throw new CHttpException(404,'The requested page does not exist.');
        }
    }

    public static function getCurrentStep()
    {
        if(Yii::app()->request->cookies->contains(self::BK_STEP)){
            return (int)Yii::app()->request->cookies[self::BK_STEP]->value;
        }
        return 1;
    }

    public static function setCurrentStep($step)
    {
        $cookie = new CHttpCookie(self::BK_STEP, $step);
        $cookie->expire = time()+60*60*24*360; //360days
        Yii::app()->request->cookies[self::BK_STEP] = $cookie;
    }
}
