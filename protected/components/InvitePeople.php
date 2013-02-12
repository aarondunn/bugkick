<?php

Yii::import('zii.widgets.CPortlet');
 
class InvitePeople extends CPortlet
{
    const BK_INVITES_LIMIT = 'invitesLimit';
    protected $invitesLeft = 0;
    protected $settings = null;

    public function init()
    {
        $this->settings = SiteSettings::getBugkickSettings();
        if($this->settings->invites_limit==true){
            if(Yii::app()->request->cookies->contains(self::BK_INVITES_LIMIT)){
                $this->invitesLeft = (int)Yii::app()->request->cookies[self::BK_INVITES_LIMIT]->value;
            }
            else{
                self::setBkUserCookie();
                $this->invitesLeft = $this->settings->invites_count;
            }
        }
        $this->contentCssClass = 'inviteMemberWidgetContent';
        parent::init(); 
    }
 
    protected function renderContent()
    {
        if($this->settings->invites_limit==false
            || ($this->settings->invites_limit==true
                && $this->invitesLeft>0)){
            $model = new InvitePeopleForm;
            $this->render('invitePeople', array('model'=>$model));
        }
        elseif($this->settings->invites_limit==true
            && $this->invitesLeft<1){
           // echo CHtml::tag('p',array('style'=>'margin:10px 22px','class'=>'invites-text'),Yii::t('main','You have no invites left.'));
        }
    }

    public static function setBkUserCookie($invitesLeft = null) {
        $settings = SiteSettings::getBugkickSettings();
        $invitesLeft = (int) ($invitesLeft===null)? $settings->invites_count : $invitesLeft;
        $cookie = new CHttpCookie(self::BK_INVITES_LIMIT,
            $invitesLeft);
        $cookie->expire = time()+60*60*24*360; //360days
        Yii::app()->request->cookies[self::BK_INVITES_LIMIT] = $cookie;
    }

    public static function decreaseInvites()
    {
        $settings = SiteSettings::getBugkickSettings();
        if(Yii::app()->request->cookies->contains(self::BK_INVITES_LIMIT)){
            $invitesLeft = (int) Yii::app()->request->cookies[self::BK_INVITES_LIMIT]->value;
            $invitesLeft--;
            if($invitesLeft>=0){
                self::setBkUserCookie($invitesLeft);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            self::setBkUserCookie($settings->invites_count-1);
            return true;
        }
    }
}