<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 22.11.11
 * Time: 5:02
 */
 Yii::app()->clientScript->registerScript('sortable-tickets', 'var dndUrl="' . $this->createUrl('//bug/DndPrioritySort') . '"; var maxLabels='.Yii::app()->params['label_number_shown'], CClientScript::POS_END);
?>

<?php
    $ticketsData = Bug::getTicketsUserAndLabelSets($model->getData());
    $dataString = '<script type="application/json" id="ticketMetaData">'. CJSON::encode($ticketsData).'</script>';
    
     Yii::app()->request->getPathInfo();
?>

<?php
if(Yii::app()->request->getPathInfo()== 'bug/closed'){
$this->widget('zii.widgets.CListView', array(
    'id'=>'bug-list',
    'dataProvider' => $model,
    'itemView' => '_listViewItem',
    'enableSorting' => false,
    'enablePagination' => true,
    'summaryText' => '',    
    'emptyText' => Yii::t('main', 'You have no close tickets.'),
    'pagerCssClass' => 'list-pager',
    'pager' =>array('header'=>''),
    'afterAjaxUpdate'=>'js:function(id, data) {
        renderTicketUsersAndLabels();
        addTooltip();
        destroySortable();
        setupSortable();
        if(!!bugkick.bug.list) {
            var listView$ = $("#bug-list");
            $.each(bugkick.bug.list.CheckedItems, function(k, v) {
                var item$ = $("#" + k, listView$);
                if(!!item$.length) {
                    item$.find("div.checkbox").click();
                } else {
                    delete bugkick.bug.list.CheckedItems[k];
                }
            });
        }
     }',
    'template'=>'{items}{pager}'.$dataString,
    //'pager'=>array('class'=>$pages),

/*  Uncomment for infinite scroll
    'pager' => array(
        'class' => 'ext.yiinfinite-scroll.YiinfiniteScroller',
        'contentSelector' => 'div.items',
        'itemSelector' => 'div.ticket-item-view',
        'loadingImg' => Yii::app()->theme->baseUrl . '/images/ajax-loader-bar-gray.gif',
        'loadingText' => Yii::t('main', 'Loading...'),
        'donetext' => Yii::t('main', 'There is no more tickets'),
        'pages' => $pages
    ),
*/
));}else{
?>

<?php 
$this->widget('zii.widgets.CListView', array(
    'id'=>'bug-list',
    'dataProvider' => $model,
    'itemView' => '_listViewItem',
    'enableSorting' => false,
    'enablePagination' => true,
    'summaryText' => '',    
    'emptyText' => Yii::t('main', 'You have no open tickets.'),
    'pagerCssClass' => 'list-pager',
    'pager' =>array('header'=>''),
    'afterAjaxUpdate'=>'js:function(id, data) {
        renderTicketUsersAndLabels();
        addTooltip();
        destroySortable();
        setupSortable();
        if(!!bugkick.bug.list) {
            var listView$ = $("#bug-list");
            $.each(bugkick.bug.list.CheckedItems, function(k, v) {
                var item$ = $("#" + k, listView$);
                if(!!item$.length) {
                    item$.find("div.checkbox").click();
                } else {
                    delete bugkick.bug.list.CheckedItems[k];
                }
            });
        }
     }',
    'template'=>'{items}{pager}'.$dataString,
    //'pager'=>array('class'=>$pages),

/*  Uncomment for infinite scroll
    'pager' => array(
        'class' => 'ext.yiinfinite-scroll.YiinfiniteScroller',
        'contentSelector' => 'div.items',
        'itemSelector' => 'div.ticket-item-view',
        'loadingImg' => Yii::app()->theme->baseUrl . '/images/ajax-loader-bar-gray.gif',
        'loadingText' => Yii::t('main', 'Loading...'),
        'donetext' => Yii::t('main', 'There is no more tickets'),
        'pages' => $pages
    ),
*/
));}

if ($currentView != 'closed' && !empty($textForSearch) && !is_array($this->request->getParam('filterText'))){
?>
<div class="search-archived">
    <a href="<?php echo $this->createUrl('/bug/closed/filterText/' . CHtml::encode($textForSearch)); ?>"><?php echo Yii::t('main', 'Show results for closed tickets'); ?></a>
</div>
<?php
}
?>
