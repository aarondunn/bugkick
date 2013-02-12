<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 21.11.12
 * Time: 23:55
 */
Yii::import('zii.widgets.CPortlet');

class ForumSearch extends CPortlet
{
    public function init()
    {
        $this->title=CHtml::encode('Forums');
        $this->titleCssClass = 'nav-header';
        $this->decorationCssClass = 'nav nav-list';
        parent::init();
    }

    protected function renderContent()
    {
        $forumSearchKeyword = Yii::app()->user->getState('forumSearchKeyword');
        $this->render('forum_search/index', array(
            'forumSearchKeyword'=>$forumSearchKeyword,
        ));
    }
}