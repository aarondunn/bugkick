<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 22.11.12
 * Time: 22:04
 */
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'application.modules.forum.views.topic._view',
    'summaryText'=>'',
    'emptyText'=>Yii::t('main','No topics yet.'),
    'pager'=>array(
        'header'=>'',
    )
));
