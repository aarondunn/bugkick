<?php

Yii::import('zii.widgets.CPortlet');
 
class BugFilter extends CPortlet
{
    public function init()
    {
       // $this->title=CHtml::encode('Filters:');
        parent::init();
    }
 
    protected function renderContent()
    {
        $filter = self::getFilterState();
        $filterString = '';
        if(is_array($filter)){
            $filterString = '?';
            foreach($filter as $key=>$value){
                foreach($value as $k=>$v){
                    $filterString .= $key . "[$k]=" . $v . '&' ;
                }
            }
        }

        $currentCompanyID =  Company::current();
        if (Company::model()->cache(3600)->findByPk($currentCompanyID)->account_type == Company::TYPE_PAY)
            $proAccount = 1;
        else
            $proAccount = 0;

        //$filterModel = new Filter();
        //$this->render('bugFilter', array('filterText'=>$filterString, 'filterModel'=>$filterModel));
        $this->render('bugFilter', array('filterText'=>$filterString, 'proAccount'=>$proAccount));
    }

    public static function getFilterState()
    {
        $filters = Yii::app()->user->getState('filterState');
        if(!empty($filters))
            return CJSON::decode($filters);
        else
            return null;
        // return Yii::app()->session['ticket-filter'];
    }

    public static function setFilterState(array $filter)
    {
        Yii::app()->user->setState('filterState', CJSON::encode($filter));
       // Yii::app()->session['ticket-filter'] = $filter;
    }

    public static function emptyFilterState()
    {
        Yii::app()->user->setState('filterState', '');
        Yii::app()->user->setState('searchKeyword', '');
       // Yii::app()->session['ticket-filter'] = '';
    }
}