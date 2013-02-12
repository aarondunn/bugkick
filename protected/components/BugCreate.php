<?php

Yii::import('zii.widgets.CPortlet');
 
class BugCreate extends CPortlet
{
    public function init()
    {
        $this->contentCssClass = '';
        parent::init();
    }
 
    protected function renderContent()
    {
        //$model = new Bug;
		$model=new BugForm();
		$project_id = Project::getCurrent();
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'source';
		$miniColorsDir = Yii::getPathOfAlias('ext.colorpicker.source');
		$baseUrl = Yii::app()->getAssetManager()->publish($miniColorsDir);
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($baseUrl.'/jquery.miniColors.min.js');
		$cs->registerCssFile($baseUrl.'/jquery.miniColors.css');
		$cs->registerScriptFile(
			Yii::app()->baseUrl.'/js/bugCreate/common.0.0.5.min.js'
//			Yii::app()->baseUrl.'/js/bugCreate/common.0.0.5.js'
		);
        $this->render(
			'bugCreate/bugCreate',
			array('model'=>$model, 'project_id'=>$project_id)
		);
    }
}