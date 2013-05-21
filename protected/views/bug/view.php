<div id="data">
   <?php
 /*  	if(isset($model->comment)){
    	$this->renderPartial('_commentsList', array('model' => Comment::model(), 'comments'=>$model->comment));
    }else{
    	$this->renderPartial('_commentsList', array('model' => Comment::model(), 'comments'=>$model->comment));
    }*/
     ?>
</div>
<?php
$this->breadcrumbs = array(
    'Bugs' => array('index'),
    $model->title,
);
$this->pageTitle = 'Ticket #' . $model->number . ' - ' . $model->title;

//Push last ticket changes to the layout
$this->beginClip('sidebar');

/*
$expr=function($bugChanges, $bug) {
    if (!empty($bugChanges[0])){
         return "'{$bugChanges[0]->date}_{$bug->id}'";
    }
    return "'0_{$bug->id}'";
};

$cacheSettings=array(
	'duration'=>120,
	'varyByExpression'=>$expr($bugChanges, $model),
	'dependency'=>array(
		'class'=>'system.caching.dependencies.CExpressionDependency',
		'expression'=>$expr($bugChanges, $model),
	)
);
if($this->beginCache('sidebar_bug_changes', $cacheSettings)) {
*/

	$this->renderPartial('_bugChanges', array('changesDataProvider'=>$changesDataProvider));

/*
	$this->endCache();
}
*/
$this->endClip();
?>
<div id="ajaxUpdate">
<div class="ticket_wrapper">
    <div class="ticket_content_top">
        <?php 
        echo (!empty($model->status->status_color))
              ? '<span class="status" style="background: ' . $model->status->status_color . '"></span>'
              : '<span class="status not-set"></span>' ;
    ?>
        <h3 class="title"><?php echo '#' . $model->number . ' ' . ActivateLinks::perform($model->title) ?></h3>
        <?php if($model->prev_number != 0): ?>
            <a id="btn_prev" href="<?php echo Yii::app()->createUrl("bug/view", array('id'=>$model->prev_number))?>" title="Previous Ticket"></a>
        <?php endif;?>
        <?php if($model->next_number != 0): ?>
            <a id="btn_next" href="<?php echo Yii::app()->createUrl("bug/view", array('id'=>$model->next_number))?>" title="Next Ticket"></a>
		<?php endif;?>
    </div>
	<div class="ticket_content">
		
        <div class="ticket_info">

            <?php if ($model->is_created_with_api != 1){ //hide owner if created via API ?>
			<span class="creator top-line"><?php echo Yii::t('main', 'Created by:'); ?></span>
			<span class="photo thumb top-line">
                <?php
                    if (!empty($model->owner)){
                        echo CHtml::link(
                            '<img src="'.$model->owner->getImageSrc(31,31).'" class="bug-profile-pic" />',
                            array('user/view', 'id'=>$model->owner->user_id),
                            array('title'=>$model->owner->name .' '. $model->owner->lname)
                        );
                    }
                    else{
                        echo '<span class="tip-deleted" title="Deleted"><img src="'. ImageHelper::thumb( 31, 31, 'images/profile_img/default.jpg', 85 ).'" class="bug-profile-pic" /></span>';
                    }
                ?>
            </span>
           <?php }?>

            <span class="performer top-line"><?php echo Yii::t('main', 'Assigned to'); ?>:</span>
                <?php
//                    $users = $model->user;
                    if (is_array($users) && !empty($users)){
                        foreach($users as $user){
                            if (!empty($user)){
                                echo '<span class="photo thumb top-line">';
                                echo CHtml::link(
                                    '<img src="'.$user->getImageSrc(31,31).'" class="bug-profile-pic" />',
                                    array('user/view', 'id'=>$user->user_id),
                                    array('title'=>$user->name .' '. $user->lname)
                                );
                                echo '</span>';
                            }
                        }
                    }
                ?>

            <?php if ($model->is_created_with_api == 1){ ?>
                <span class="performer top-line">
                    <?php echo Yii::t('main', 'Type'); ?>:
                    <?php
                       if(!empty($model->type)){
                          echo  $model->type;
                       }
                    ?>
                </span>
            <?php }?>

            <span class="performer top-line"><?php echo Yii::t('main', 'Labels'); ?>:</span>
            <span class="top-line">
                <?php
                    if($model->label != null)
                        foreach($model->label as $label) {
                ?>
                    <span class="bubble bubble-ticket" style="background-color:<?php //echo $label->label_color; ?>"><?php echo $label->name; ?></span>
                <?php
                        }
                    if ($model->is_created_with_api == 1){ ?>
                    <span class="bubble bubble-ticket" ><?php echo Yii::t('main', 'USER SUBMITTED'); ?></span>
                <?php
                    }
                ?>
            </span>
            <span class="status-label top-line">
                <?php echo Yii::t('main', 'Status'); ?>:
                <?php
                   if ($model->isarchive == 1){
                        echo 'Closed';
                   }
                   elseif(!empty($model->status)){
                        echo ($model->status->status_color != null)? '<span class="bubble bubble-ticket" >'. $model->status->label . '</span>' : $model->status->label;
                   }
                ?>
            </span>
			<ul class="options">
				<li class="comment" title="Edit">
                    <a href="<?php echo Yii::app()->createAbsoluteUrl('bug/getBugById/',array('id'=>$model->id))?>" class="update-bug-link"><?php echo Yii::t('main', 'Edit'); ?></a>
				</li>
				<li class="<?php echo ($model->isarchive == 1? 'open' : 'print');?>"
                        title="<?php echo Yii::t('main', ($model->isarchive == 1? 'Open' : 'Close'));?>">
                    <a href="<?php echo Yii::app()->createUrl("bug/setarchived", array('id'=>$model->id))?>"  id="archived-link" ticket-id="<?php echo $model->id?>"  ><?php echo Yii::t('main', ($model->isarchive == 1? 'Open' : 'Close'));?></a>
				</li>
				<li class="delete" title="Delete">
                    <?php echo CHtml::ajaxLink(
                        Yii::t('main', 'Delete Ticket'),
                        Yii::app()->createUrl('bug/delete',array('id' =>$model->id, 'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken)),
                        array(
                            "type" => "POST",
                            "success" => "function(){ window.location = '".Yii::app()->createUrl('bug/index')."' }"
                        ),
                        array(
                            'id'=>'delete_bug_link',
                            'confirm' => 'Are you sure you want to delete this item?'
                        )
                    ); ?>
				</li>
				<li class="duplicate" title="Duplicate">
                    <a href="<?php echo Yii::app()->createAbsoluteUrl('bug/getDuplicateFormByBugId/',array('id'=>$model->id))?>" class="duplicate-link" ></a>
				</li>
			</ul>
            <div class="clear"></div>
		</div><!-- .ticket_info -->
		<p class="description">
            <?php echo ActivateLinks::perform(nl2br($model->description))?>
            <?php if(!empty($model->githubIssue)) { ?>
            <br />
            <div class="al-right">
                <?php
                $themeBaseUrl = Yii::app()->theme->baseUrl;
                echo CHtml::link(
<<<LINKTEXT
<img src="{$themeBaseUrl}/images/icons/github.png" width="16" style="vertical-align: bottom;" />&nbsp;
This issue at GitHub
LINKTEXT
                    ,
                    'https://github.com/' . $model->project->github_repo
                        . '/issues/' . $model->githubIssue->number,
                    array('target'=>'_blank', 'style'=>'text-decoration: none;')
                );
                ?>
            </div>
            <?php } ?>

            <?php if ($model->is_created_with_api == 1){ ?>
                <div class="api-ticket-email">
                    <?php
                       if(!empty($model->api_user_email)){
                          echo Yii::t('main', 'Submitted by: '),
                              ActivateLinks::perform(CHtml::encode($model->api_user_email));
                       }
                    ?>
                </div>
            <?php }?>
		</p>
        <?php if(!empty($model->attachments) && is_array($model->attachments)):?>
        <div class="attached-files">
            <?php foreach($model->attachments as $file):?>
            <div>
                <?php echo CHtml::link(
                    $file->name,
                    $file->getFileUrl(),
                    array('target'=>'_blank')
                ) . ' &nbsp ' . Helper::getReadableFileSize($file->size); ?>
            </div>
            <?php endforeach;?>
        </div>
        <?php endif;?>

        <?php
            $token = Yii::app()->session->get('boxAuthToken');
            if(empty($token)){
                echo CHtml::link(
                    Yii::t('main', 'Login to Box.com to attach files'),
                    $this->createUrl('/box/authenticate'),
                    array('style'=>'float:right; clear: both; padding-right:20px;'));
            }
            else{
                Yii::import("xupload.models.XUploadForm");
                $modelForm = new XUploadForm;
                $this->widget('xupload.XUpload', array(
                    'url' => Yii::app()->createUrl('bug/upload',array('ticket_id'=>$model->id)),
                    'model' => $modelForm,
                    'attribute' => 'file',
                    'multiple' => true,
                    'previewImages'=>false,
                    'autoUpload'=>true,
                ));
            }
        ?>
        <div class="clear"></div>

        <?php echo $this->renderPartial('application.views.task._task_list', array('tasks' => $tasks, 'ticket'=>$model));?>

    </div>
<!--    <div class="ticket_content_bottom"></div>-->
    <!-- .ticket_content -->
	<div class="ticket_reply">
<!--		<h3 class="header" id="comments"><?php echo Yii::t('main', 'Replies and Issue History'); ?></h3>-->
        <!-- List of comments     -->
            <ul class="message">
		<?php
		$expression="'{$model->id}_".count($model->comment)."'";
		$beginCache=$this->beginCache(
			'commentList_cache',
			array(
				'duration'=>60,
				'varyByExpression'=>$expression,
				'dependency'=>array(
					'class'=>'system.caching.dependencies.CExpressionDependency',
					'expression'=>$expression,
				)
			)
		);
		if($beginCache) {
			echo $this->renderPartial('_commentsList', array('model' => Comment::model(), 'comments'=>$model->comment));
			$this->endCache();
		}
		?>
            </ul><!-- .message -->
        <ul class="post_comment">
            <li class="photo thumb leave-comment-part comment-thumb">
                  <img src="<?php echo User::current()->getImageSrc(31,31) ?>" class="bug-profile-pic" />
            </li>
            <li class="leave-comment-part">
                <span class="name"><?php echo User::current()->name . ' ' . User::current()->lname ?></span>
                <span class="date utc-timestamp-date" utc-timestamp="<?php echo time();?>"></span>
                  <!--Comment form-->
                  <?php echo $this->renderPartial('_comment', array('model' => Comment::model(), 'bug'=>$model, 'useWysiwyg'=>User::current()->use_wysiwyg)); ?>
              </li>
              <div class="clear"></div>
      	</ul>
<!--        <div class="bottom"></div>-->
	</div>
</div><!-- .ticket_wrapper -->
</div>

<?php
Yii::app()->clientScript->registerScript(
	'tips',
'$(".photo a[title]").colorTip({color:"yellow", timeout:100});
$("li.comment[title]").colorTip({color:"yellow", timeout:100});
$("li.print[title]").colorTip({color:"yellow", timeout:100});
$("li.delete[title]").colorTip({color:"yellow", timeout:100});
$("li.duplicate[title]").colorTip({color:"yellow", timeout:100});
$("span.tip-deleted[title]").colorTip({color:"yellow", timeout:100});',
    CClientScript::POS_READY
);
?>

<!--Updating Ticket-->
<?php
    Yii::app()->clientScript->registerScript('bug_update', '
    jQuery("a.update-bug-link").live("click",function() {
        $.post(
            $(this).attr("href"),
            { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
              function(data){
                jQuery("#bugUpdateForm").html(data);
                jQuery("#updateBugDialog").dialog("open");
                jQuery(".chzn-select").chosen();
              },
              "html"
        );
        return false;
    });
    ', CClientScript::POS_END);
?>
<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'updateBugDialog',
        'options'=>array(
            'title'=>'Edit Ticket',
            'autoOpen'=>false,
            'modal'=>true,
            'hide'=>'drop',
            'show'=>'drop',
            'width'=>500,
            'buttons'=>array(
    //            'Cancel'=>'js:function(){ $(this).dialog("close");}',
                'Save'=>'js:function(){ $("#bug-update-form").submit();}'
            ),
            'open'=>'js: function(event, ui) {
                 //setting tabindex for save button
                 $("button").attr("tabindex","5");
            }',
            'beforeClose'=> 'js: function(event, ui) {
                 //hack for datapicker
                 $(\'#BugForm_title\').focus();
            }'
        ),
    ));
?>
    <div id="bugUpdateForm"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!-- End Updating Ticket-->

<!-- Duplicate Ticket-->
    <?php
        Yii::app()->clientScript->registerScript('bug_duplicate', '
        jQuery("a.duplicate-link").live("click",function() {
            $.post(
                $(this).attr("href"),
                { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
                  function(data){
                    jQuery("#bugDuplicateForm").html(data);
                    jQuery("#duplicateBugDialog").dialog("open");
                  },
                  "html"
            );
            return false;
        });
        ', CClientScript::POS_END);
    ?>
    <?php
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'=>'duplicateBugDialog',
            'options'=>array(
                'title'=>'Duplicate Ticket',
                'autoOpen'=>false,
                'modal'=>true,
                'hide'=>'drop',
                'show'=>'drop',
                'buttons'=>array(
                    //'Cancel'=>'js:function(){ $(this).dialog("close");}',
                    'Save'=>'js:function(){ $("#bug-duplicate-form").submit();}',
                ),
            ),
        ));
    ?>
        <div id="bugDuplicateForm"></div>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!--End Duplicate Ticket-->

<?php $this->widget('BugRightClick'); ?>