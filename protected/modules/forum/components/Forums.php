<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 21.11.12
 * Time: 22:57
 */
Yii::import('zii.widgets.CPortlet');

class Forums extends CPortlet
{
    const FORUMS_PER_PAGE = 10;

    public function init()
    {
       // $this->title=CHtml::encode('Forums:');
        parent::init();
    }

    protected function renderContent()
    {
        $criteria = new CDbCriteria();
        $criteria->order= 'date DESC';
        $dataProvider = new CActiveDataProvider('BKForum', array(
            'pagination'=>array(
                'pageSize'=>self::FORUMS_PER_PAGE,
            ),
        ));

        $this->render('forums/list', array(
            'dataProvider'=>$dataProvider,
        ));
    }
}