<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 17.01.12
 * Time: 0:16
 */
$provider=$model->gridSearch();
$provider;
$this->widget('zii.widgets.CListView', array(
    'id'=>'projects-list',
    'dataProvider'=>$provider,
    'itemView' => '_listViewItem',
    'ajaxUpdate'=>true,
    'emptyText'=>Yii::t('main', 'No projects yet, please create one to get started.'),
    'enablePagination'=>true,
    'summaryText' => '',
    'pagerCssClass' => 'list-pager',
    'pager' =>$pager,
));
?>
<div class="clear"></div>
