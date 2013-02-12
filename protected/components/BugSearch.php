<?php

Yii::import('zii.widgets.CPortlet');
 
class BugSearch extends CPortlet
{
    public function init()
    {    
        $this->setId('bugSearchWidget');
        $this->contentCssClass = 'bugSearchWidgetContent';
        parent::init(); 
    }
 
    protected function renderContent()
    {
		/*$filterText=Yii::app()->request->getParam('filterText', '');
		if(!is_string($filterText))
			$filterText='';*/
        $filterText = Yii::app()->user->getState('searchKeyword');
        $controllerId = Yii::app()->getController()->getId();
        $actionId = Yii::app()->getController()->getAction()->getId();

		$act=null;
        if ( $actionId == 'closed' )
            $act = 'closed';

        if (!($controllerId == 'bug' && ($actionId == 'index' || $actionId == 'closed')))
            $redirectToTheMain = "
            $(\"#bugSearch\").keyup(function(){
                if(navigator.appName == \"Microsoft Internet Explorer\")
                    window.document.execCommand('Stop');
                else
                    window.stop();
            $(\"#keywordSearch\").submit(); }) ";
          //  $redirectToTheMain = "$(\"#bugSearch\").click(function(){location.href='".Yii::app()->createUrl("/bug/")."'; return false})";
          //  $redirectToTheMain = "location.href='http://".$_SERVER['HTTP_HOST']."/bug/index/filterText/'+$(\"#bugSearch\").val()";
        else
            $redirectToTheMain = "";
        
        $this->render('bugSearch', array('filterText'=>$filterText, 'redirectToTheMain'=>$redirectToTheMain, 'act'=>$act));
    }
}