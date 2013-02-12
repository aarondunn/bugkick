<?php

Yii::import('zii.widgets.CPortlet');
 
class SettingsMenu extends CPortlet
{
    public function init()
    {
        //$this->title=CHtml::encode('Settings:');
        parent::init();
    }
    protected function renderContent()
    {

        $controllerId = Yii::app()->getController()->getId();
        $actionId = Yii::app()->getController()->getAction()->getId();

        $this->render('settingsMenu',
                       array('controllerId'=>$controllerId,
                             'actionId'=>$actionId)
                       );
    }
}