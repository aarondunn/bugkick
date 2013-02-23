<?php
/**
 * mixpanel.com events tracker
 * Author: Alexey kavshirko@gmail.com
 * Date: 28.08.12
 * Time: 11:59
 */

class MixPanel {
    //List of available events. "Click" events are stored here: scripts/mixpanel.js
    const LOGIN_PAGE_VIEW = 'Page View - Login';
    const SIGN_UP_PAGE_VIEW = 'Page View - Sign Up';
    const TICKETS_LIST_PAGE_VIEW = 'Page View - Tickets Dashboard';
    const TICKET_PAGE_VIEW = 'Page View - Ticket';
    const PROJECTS_PAGE_VIEW = 'Page View - All Projects';
    const DASHBOARD_PAGE_VIEW = 'Page View - Dashboard';
    const UPDATES_PAGE_VIEW = 'Page View - Updates';
    const CALENDAR_PAGE_VIEW = 'Page View - Calendar';
    const CLOSED_TICKETS_PAGE_VIEW = 'Page View - Closed Tickets';
    const PROFILE_PAGE_VIEW = 'Page View - Profile';
    const SETTINGS_PAGE_VIEW = 'Page View - Settings';
    const EMAIL_PREFERENCES_PAGE_VIEW = 'Page View - Email Preferences';
    const LABELS_PAGE_VIEW = 'Page View - Edit Labels';
    const STATUS_PAGE_VIEW = 'Page View - Edit Status';
    const FEEDBACK_SETTINGS_PAGE_VIEW = 'Page View - Edit Feedback';
    const MEMBERS_PAGE_VIEW = 'Page View - Members';
    const GROUPS_PAGE_VIEW = 'Page View - Groups';
    const COMPANY_SETTINGS_PAGE_VIEW = 'Page View - Company Settings';
    const API_CODE_PAGE_VIEW = 'Page View - API Code';
    const HOME_PAGE_VIEW = 'Page View - Homepage';
    const SIGN_UP = 'Sign Up'; // params: 'type'=>'free', 'type'=>'pay'
    const MARKETING_INVITE = 'Marketing Invite';
    const INTRO_PAGE_VIEW = 'Page View - Intro Page';
    const COMPLETE_STEP = 'User Completed Intro Step'; //params: 'step'=>1 , etc
    const SKIP_STEP = 'User Skipped Intro Step'; //params: 'step'=>1 , etc

    private static $_instance = null;
    private $_enabled = false;

    /**
     * @return MixPanel singleton
     */
    public static function instance()
    {
        if(empty(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function __construct()
    {
        if(isset(Yii::app()->params['mixpanel']['enabled'])
            && Yii::app()->params['mixpanel']['enabled'] === true){
            $this->_enabled = true;
        }
        else{
            $this->_enabled = false;
        }
    }

    /**
     * Registers tracking code with "click" events
     */
    public function registerTracking()
    {
        if($this->_enabled){
            Yii::app()->clientScript->registerScript('mixpanel',
                '(function(c,a){window.mixpanel=a;var b,d,h,e;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src=("https:"===c.location.protocol?"https:":"http:")+\'//cdn.mxpnl.com/libs/mixpanel-2.1.min.js\';d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d);a._i=[];a.init=function(b,c,f){function d(a,b){var c=b.split(".");2==c.length&&(a=a[c[0]],b=c[1]);a[b]=function(){a.push([b].concat(Array.prototype.slice.call(arguments,0)))}}var g=a;"undefined"!==typeof f?
                g=a[f]=[]:f="mixpanel";g.people=g.people||[];h="disable track track_pageview track_links track_forms register register_once unregister identify name_tag set_config people.identify people.set people.increment".split(" ");for(e=0;e<h.length;e++)d(g,h[e]);a._i.push([b,c,f])};a.__SV=1.1})(document,window.mixpanel||[]);
                mixpanel.init("463782a5946f29d2daaa7b9cb5482f43");',
                CClientScript::POS_HEAD);

            Yii::app()->clientScript->registerScriptFile('/js/mixpanel/mixpanel.min.js');
            //Yii::app()->clientScript->registerScriptFile('/js/mixpanel/mixpanel.js');
        }
    }

    /**
     * Raises event
     * @param string $eventName
     * @param array $params
     */
    public function registerEvent($eventName, $params = null)
    {
        if ($this->_enabled && !empty($eventName)){
            $params = empty($params)? '' : ','. CJSON::encode($params);
            Yii::app()->clientScript->registerScript('mixpanel',
                'mixpanel.track(\''.$eventName.'\''.$params.');',
                CClientScript::POS_END);
        }
    }

    /**
     * Registers user
     * @param User $user
     */
    public function registerUser(User $user)
    {
        if ($this->_enabled){
            //Identify user
            Yii::app()->clientScript->registerScript('mixpanel',
                'mixpanel.people.set({
                    "User Name": "'.$user->name.' '.$user->lname.'",
                    "Pro Status": "'.$user->pro_status.'",
                    "$email": "'.$user->email.'",
                    "$created": "'.$user->created_at.'",
                    "$last_login": new Date(),
                    });
                mixpanel.people.identify("'.$user->email.'");',
                CClientScript::POS_END);
        }
    }

    public function importUsers()
    {
        date_default_timezone_set("America/New_York"); //set for the timezone your sign up data is using.

        $usersCount = User::model()->count();
        $time = date('Y-m-d H:i:s');

        $metrics = new MixPanelEventImporter(Yii::app()->params['mixpanel']['token'],
            Yii::app()->params['mixpanel']['api_key']);

        for ($i=0; $i<=$usersCount; $i+=50){
            $users = User::model()->findAll(
                array(
                    'limit'=>50,
                    'offset'=>$i,
                    'order'=>'user_id'
                )
            );

            echo $i. ' pack <br>';

            $userData = array();
            foreach($users as $user){
                $props = array();
                $props['$distinct_id'] = $user->email; //distinct_id should be your identifier
                $props['$token'] = Yii::app()->params['mixpanel']['token'];
                $props['$set']['User Name'] = $user->name . ' ' . $user->lname;
                $props['$set']['Pro Status'] = $user->pro_status;
                $props['$set']['$email'] = $user->email;
                $props['$set']['$created'] = $user->created_at;
                $props['$set']['$last_login'] = $time;
                $props['$set']['$country_code'] = 'US';
                //$props['time'] = $time;
                echo "\n Sending SET event for ".$props['$distinct_id']." at ".$time."<br>";
                $userData[]=$props;
            }
            if(!empty($userData)){
                $metrics->track($userData);
            }
        }
    }
}