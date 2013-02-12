<?php
    /**
     * Author: Alexey kavshirko@gmail.com
     * Date: 24.07.12
     * Time: 18:55
     */
    $dataProvider->pagination->route = '/site/articles';
    $this->widget('zii.widgets.CListView', array(
        'id'=>'article_list_view',
    	'dataProvider'=>$dataProvider,
        'ajaxUrl'=>'/site/articles',
    	'itemView'=>'application.components.views.help._articleListViewItem',
        'loadingCssClass'=>false,
        'emptyText'=>'No articles.',
        'summaryText'=>false,
    	'htmlOptions'=>array(
    		'class'=>'articles_popup_list',
    	),
        'pagerCssClass' => 'list-pager',
        'tagName'=>'ul',
//        'template' => '<ul>{items}</ul>',
        'pager'=>array(
            'header'=>false,
        )
    ));
?>