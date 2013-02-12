<?php
/* @var $this TopicController */
/* @var $model BKTopic */
?>
<div class="topic clearfix <?php if($index%2) echo 'even'; else echo 'odd'; ?>">
  <h4 class="topic-title">
      <?php echo CHtml::link(CHtml::encode($data->title), array('topic/view', 'id'=>$data->id)); ?>
  </h4>
  <h4 class="topic-description"><?php echo BKHelper::truncateString(CHtml::encode($data->description)); ?></h4>
  <ul class="topic-meta">
  	  <li class="topic-meta-item"><?php echo  ' Created by  ' .  CHtml::encode($data->topicStarter->repr()); ?></li>
      <li class="topic-meta-item"><?php echo Time::timeAgoInWords($data->time); ?></li>
      <li class="topic-meta-item"><?php echo $data->postsCount . ' ' . Yii::t('main', 'posts'); ?></li>
      <?php if(Yii::app()->user->checkAccess("moderator")){ ?>
      <li class="topic-meta-item">
            <?php echo CHtml::ajaxLink(Yii::t('main','Hide'),
                   array('/forum/topic/hide', 'id'=>$data->id),
                   array(
                       'type'=>'post',
					   'beforeSend' => 'function(){
                                        //show loader
                                        $("#prog_' . $data->id . '").css("display", "block");
                                    }',
                       'success'=>'function(data,status){
                   		   $("#prog_' . $data->id . '").css("display", "none");
                           //$.fn.yiiListView.update("topic-list");
                   		   location.reload();
                       }',
                       'data'=>array(
                           'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken,
                       ),
                   ),
                   array('confirm'=>'Are you sure?', 'id' => 'hide-link-'.uniqid()))?>
      </li>
      <?php } ?>
  </ul>
  <div id="prog_<?php echo $data->id; ?>" style="left:50%;margin-top:-42px;position:absolute;display:none;"><img alt="In progress" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader-3d-27.gif" style="vertical-align:middle;" /></div>
</div>