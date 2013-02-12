<?php
Yii::import('zii.widgets.CPortlet');
/**
 * MainTabs
 *
 * @author f0t0n
 */
class MainTabs extends CPortlet {

    public $tabs = array();

    protected function renderContent() {
        $this->render('mainTabs', array());
    }
}