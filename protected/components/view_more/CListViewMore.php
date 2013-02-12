<?php
Yii::import('zii.widgets.CBaseListView');
Yii::import('zii.widgets.CListView');

class CListViewMore extends CListView{

    public function init()
    {
        if($this->itemView===null)
            throw new CException(Yii::t('zii','The property "itemView" cannot be empty.'));
        parent::init();

        if(!isset($this->htmlOptions['class']))
            $this->htmlOptions['class']='list-view';

        if($this->baseScriptUrl===null)
            $this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/listview';

        if($this->cssFile!==false)
        {
            if($this->cssFile===null)
                $this->cssFile=$this->baseScriptUrl.'/styles.css';
            Yii::app()->getClientScript()->registerCssFile($this->cssFile);
        }
        $labelText = 'Show More';
        $this->pager = array(
            'class'          => 'CLinkPager',
            'firstPageLabel' => '',
            'prevPageLabel'  => '',
            'nextPageLabel'  => '<span class="view-more-label">'.$labelText.'</span>',
            'lastPageLabel'  => '',
            'maxButtonCount' => 0,
            'header'         => '',
            'cssFile'        => '',
        );

    }
    public function renderPager()
    {
        if(!$this->enablePagination)
            return;

        $pager=array();
        $class='CListPager';
        if(is_string($this->pager))
            $class=$this->pager;
        else if(is_array($this->pager))
        {
            $pager=$this->pager;
            if(isset($pager['class']))
            {
                $class=$pager['class'];
                unset($pager['class']);
            }
        }
        $pager['pages']=$this->dataProvider->getPagination();

        if($pager['pages']->getPageCount()>1)
        {
            echo '<div class="'.$this->pagerCssClass.'">';
            $this->widget($class,$pager);
            echo '</div>';
        }
        else
            $this->widget($class,$pager);
    }
}