<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'bug-grid',
    'dataProvider' => $model,
    //'filter' => $model,
    //'ajaxUrl' => CHtml::normalizeUrl(array('bug/search')),
    'enableSorting' => false,
    'enablePagination'=>true,
    'summaryText'=>'', //hide Total: 10 result(s)
    'hideHeader'=>true,  //hide Header
    //'pager'=>array('class'=>$pages),
	'pager'=>array(
        'class'=>'ext.yiinfinite-scroll.YiinfiniteScroller',
        'contentSelector'=>'#bug-grid table tbody',
		'itemSelector'=>'#bug-grid table tbody tr',
		'loadingImg'=>Yii::app()->theme->baseUrl.'/images/ajax-loader-bar-gray.gif',
		'loadingText'=>Yii::t('main','Loading...'),
		'donetext'=>Yii::t('main','There is no more tickets'),
		'pages'=>$pages
    ),
    'rowCssClassExpression'=>'Bug::getDueDateRemainAlias($data->id)',
    'columns' => array(
        array(
            'name' => 'status',
            'type' => 'html',
            'htmlOptions' => array('class'=>'status-border'),
            'value' => '(!empty($data->status->status_color))? "<div style=\"border-left:1px solid ".$data->status->status_color." !important;\"></div>" : ""'
        ),
/* Uncomment below to use priority stars
        array(
            'name' => 'priority',
            'type' => 'raw',
            'htmlOptions' => array('class'=>'medium-padding-td'),
            'value' => '
            ($data->priority == 1)
            ? CHtml::ajaxLink(
                       "<div class=\'priority-star-yellow\'></div>",
                       Yii::app()->createUrl("bug/setPriority",array("id" =>$data->id)),
                       array(
                           "type" => "POST",
                           "data"=>array("id"=>$data->id),
                            "success" => "js:function(){
                                    if($(\"#priority_".$data->id." div\").hasClass(\"priority-star-yellow\")){
                                        $(\"#priority_".$data->id." div\").addClass(\"priority-star-empty\");
                                        $(\"#priority_".$data->id." div\").removeClass(\"priority-star-yellow\");
                                    }else{
                                        $(\"#priority_".$data->id." div\").addClass(\"priority-star-yellow\");
                                        $(\"#priority_".$data->id." div\").removeClass(\"priority-star-empty\");
                                    }
                            }"
                            ),
                       array("id"=>"priority_".$data->id)
                      )
            : CHtml::ajaxLink(
                       "<div class=\'priority-star-empty\'></div>",
                       Yii::app()->createUrl("bug/setPriority",array("id" =>$data->id)),
                       array(
                           "type" => "POST",
                           "data"=>array("id"=>$data->id),
                            "success" => "js:function(){
                                    if($(\"#priority_".$data->id." div\").hasClass(\"priority-star-yellow\")){
                                        $(\"#priority_".$data->id." div\").addClass(\"priority-star-empty\");
                                        $(\"#priority_".$data->id." div\").removeClass(\"priority-star-yellow\");
                                    }else{
                                        $(\"#priority_".$data->id." div\").addClass(\"priority-star-yellow\");
                                        $(\"#priority_".$data->id." div\").removeClass(\"priority-star-empty\");
                                    }
                            }"
                            ),
                       array("id"=>"priority_".$data->id)
                      )
            ',
        ),
 */
        array(
            'name' => 'title',
            'type' => 'html',
            'value' => '
            ($data->label != null)
            ? CHtml::link($data->title, array("bug/view", "id"=>$data->number)) .  "<span class=\"bubble\" style=\"background-color: ". $data->label->label_color ."\">" . $data->label->name . "</span> "
            : CHtml::link($data->title, array("bug/view", "id"=>$data->number));
            ',
            'htmlOptions'=>array('style'=>'font-size: 14px;')
        ),
        array(
            'name' => 'due date',
            'type' => 'html',
            'htmlOptions' => array('class'=>'small-padding-td'),
            'value' => '($data->duedate != "0000-00-00")? "<span class=\"due-date-bubble\">due ".Helper::formatDateShort($data->duedate)."</span>" :    ""',
        ),
        array(
            'name' => 'member',
            'type' => 'html',
            'htmlOptions' => array('class'=>'small-padding-td'),
            'value' => '(!empty($data->user->id))?  CHtml::link("<div class=\"bug-profile-pic-container\"><img src=\"".$data->user->getImageSrc(25,25)."\" class=\"bug-profile-pic\"></div>", array("user/view", "id"=>$data->user_id), array("title"=>$data->user->name ." ".$data->user->lname)       )  : "" ;'
        ),
        array(
            'name' => 'comments',
            'type' => 'html',
            'htmlOptions' => array('class'=>'small-padding-td'),
            'value' => '($data->commentCount > 0)? "<span class=\"comment-count-bubble\">".$data->commentCount."</span>" :    ""',
        ),
//        array(
//            'class' => 'CButtonColumn',
//            'deleteConfirmation'=>false,
//            'htmlOptions' => array('style'=>'width:60px;','class'=>'actions-column'),
//            'updateButtonUrl'=>'Yii::app()->createAbsoluteUrl("bug/getBugById", array("id"=>$data->id))',
//            'template'=>'{update} {delete} {closed}',
//            'buttons'=>array
//            (
//                'closed' => array
//                (
//                    'label'=>'Closed',
//                    'imageUrl'=>Yii::app()->theme->baseUrl .'/images/icons/archive.png',
//                    'url'=>'Yii::app()->createUrl("bug/setarchived", array("id"=>$data->id))',
//                ),
//            ),
//        ),
       ),
));


?>