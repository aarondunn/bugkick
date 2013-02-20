<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 23.07.12
 * Time: 23:07
 */
$module = (isset(Yii::app()->getController()->module))? Yii::app()->getController()->module->getName() : '';
$controller = Yii::app()->getController()->getId();
$action = Yii::app()->getController()->getAction()->getId();

if(!Yii::app()->user->isGuest){
    if($module != 'admin'&& (
        ($controller == 'bug' && $action == 'index')
            || ($controller == 'notification')
            || ($controller == 'site' && $action == 'dashboard')
            || ($controller == 'project' && $action == 'people')
    )){
        $this->widget('MainTabs', array(
            'tabs'=>array(
                array(
                    'text'=>'Tickets',
                    'url'=>Yii::app()->createUrl('/bug'),
                    'title'=>'List of the Tickets for this project',
                    'id'=>'tickets-tab'
                ),
                array(
                    'text'=>'Dashboard',
                    'url'=>Yii::app()->createUrl('/site/dashboard'),
                    'title'=>'Your BugKick Dashboard',
                    'id'=>'dashboard-tab'
                ),
                array(
                    'text'=>'Updates',
                    'url'=>Yii::app()->createUrl('/updates'),
                    'title'=>'Timeline of ticket updates',
                    'id'=>'updates-tab'
                ),
                array(
                    'text'=>'People',
                    'url'=>Yii::app()->createUrl('/project/people'),
                    'title'=>'Project People',
                    'id'=>'people-tab'
                ),
                array(
                    'text'=>'Settings',
                    'url'=>Yii::app()->createUrl('project/edit', array('id'=>Project::getCurrent()->project_id)),
                    'title'=>'Customize your BugKick',
                    'id'=>'settings-tab',
                    'class'=>'update',
                ),
                array(
                    'text'=>'Calendar',
                    'url'=>Yii::app()->createUrl('bug',
                        array('show'=>'calendar')),
                    'title'=>'Calendar for this project',
                    'id'=>'calendar-tab'
                ),
                /*array(
                    'text'=>'Flow',
                    'url'=>'#',
                    'title'=>'Flow',
                    'id'=>'flow-tab'
                ),*/
            ),
        ));
//        Yii::app()->clientScript->registerScript('settings-tab', '
//            jQuery("a#settings-tab").live("click",function() {
//                $("#project-form-dialog").dialog("open");
//                return false;
//            });
//        ', CClientScript::POS_END);
/*
        Yii::app()->clientScript->registerScript('settings-tab', '
            jQuery("a#settings-tab").live("click",function() {
                $.post(
                    "'.$this->createUrl('settings/projectSettings').'",
                    { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
                    function(data){
                        jQuery("#projectSettingsForm").html(data);
                        jQuery("#projectSettingsForm .chzn-select").chosen();
                        jQuery("#projectSettingsDialog").dialog("open");
                    },
                    "html"
                );
                return false;
            });
        ', CClientScript::POS_END);
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'=>'projectSettingsDialog',
            'options'=>array(
                'title'=>'Project Settings',
                'autoOpen'=>false,
                'modal'=>true,
                'hide'=>'drop',
                'show'=>'drop',
                'buttons'=>array(
                    //'Cancel'=>'js:function(){ $(this).dialog("close");}',
                    //'Save'=>'js:savePassword',
                ),
            ),
        ));
        echo '<div id="projectSettingsForm"></div>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');

   */

    }
    elseif($module == 'admin'){
        $this->widget('MainTabs', array(
            'tabs'=>array(
                array(
                    'text'=>'Users',
                    'url'=>Yii::app()->createUrl('/admin'),
                    'title'=>'Users',
                    'id'=>'users-tab'
                ),
                array(
                    'text'=>'Articles',
                    'url'=>Yii::app()->createUrl('/admin/article'),
                    'title'=>'Help Articles',
                    'id'=>'articles-tab'
                ),
                array(
                    'text'=>'Marketing',
                    'url'=>Yii::app()->createUrl('/admin/marketing'),
                    'title'=>'Marketing',
                    'id'=>'marketing-tab'
                ),
                array(
                    'text'=>'Coupons',
                    'url'=>Yii::app()->createUrl('/admin/coupon'),
                    'title'=>'Coupons',
                    'id'=>'coupon-tab'
                ),
            ),
        ));
    }
}

