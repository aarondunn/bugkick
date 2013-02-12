<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 20.11.12
 * Time: 0:25
 */

Yii::import('zii.widgets.CPortlet');

class BugRightClick extends CPortlet
{
    public function init()
    {
        $this->contentCssClass = '';
        parent::init();
    }

    protected function renderContent()
    {
        Yii::app()->getClientScript()->registerScriptFile(
//			Yii::app()->baseUrl.'/js/__components/bugRightClick/common.js'
			Yii::app()->baseUrl.'/js/__components/bugRightClick/common.min.js'
		);
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->theme->baseUrl.'/css/plug-in/bug-right-click/style.css'
        );
        $this->render('bugRightClick');
    }
}