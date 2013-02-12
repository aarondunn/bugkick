<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 05.11.11
 * Time: 17:22
 */
$this->widget('application.components.view_more.CListViewMore', array(
    'id'=>'bug-changes-list',
    'dataProvider' => $changesDataProvider,
    'itemView' => '_bugChangesItem',
    'enablePagination' => true,
    'summaryText' => '',
    'emptyText' => Yii::t('main', 'No changes.'),
    'pagerCssClass' => 'view-more-pager',
    'loadingCssClass'=>false,
));

