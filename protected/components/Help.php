<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.07.12
 * Time: 18:25
 */
Yii::import('zii.widgets.CPortlet');

class Help extends CPortlet
{
    public function init()
    {
        $this->contentCssClass = '';
        parent::init();
    }

    protected function renderContent()
    {
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile(
			Yii::app()->baseUrl.'/js/__components/help/common.min.js'
			//Yii::app()->baseUrl.'/js/__components/help/common.js'
		);

        $searchQuery = Yii::app()->getRequest()->getParam('helpSearch');

        $dataProvider = Article::getSearch($searchQuery);

        $this->render(
			'help/help',
			array('dataProvider'=>$dataProvider)
		);
    }
}
