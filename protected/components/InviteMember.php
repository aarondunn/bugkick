<?php

Yii::import('zii.widgets.CPortlet');
 
class InviteMember extends CPortlet
{
    public function init()
    {
        $this->contentCssClass = 'inviteMemberWidgetContent';
        parent::init();
    }
 
    protected function renderContent()
    {
        $model = new User;
        $this->render('inviteMember', array('model'=>$model));
    }
}