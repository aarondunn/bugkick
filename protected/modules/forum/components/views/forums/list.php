<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 21.11.12
 * Time: 23:05
 */
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'application.modules.forum.components.views.forums._item',
    'summaryText'=>'',
    'itemsTagName'=>'ul',
    'itemsCssClass'=>'nav nav-list',
    'emptyText'=>Yii::t('main','No forums yet.'),
    'pager'=>array(
        'header'=>'',
    ),
    /*'htmlOptions'=>array(
        'class'=>'',
    )*/
));