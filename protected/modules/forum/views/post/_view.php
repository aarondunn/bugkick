<?php
/* @var $this PostController */
/* @var $model BKPost */
?>

<div class="topic-comment clearfix" id="post-<?php echo $data->id ?>">
    <div class="avatar">
        <?php
            echo CHtml::link(
                '<img src="'.$data->user->getImage($data->user).'" class="avatar-img" />',
                array('/user/view', 'id'=>$data->user->user_id)
            );
        ?>
    </div>
    <div class="comment-data <?php if ($index%2==0) echo 'odd '; ?>">
        <!--<h4 class="comment-author"><?php //echo CHtml::encode($data->user->repr()); ?></h4>-->
        <h4 class="comment-author"><?php 
        echo CHtml::link(CHtml::encode($data->user->repr()),array('/user/view', 'id'=>$data->user->user_id)); ?></h4>
        <p class="comment-date"><?php echo Yii::app()->dateFormatter->formatDateTime($data->time, 'short', null)." ".strtolower(Yii::app()->dateFormatter->format("h:ma",$data->time)); ?></p>
        <div class="comment-content">
            <?php echo $data->body; ?>
        </div>
        <div class="comment-stuff">
            <!-- <?php echo CHtml::link('#','#post-'.$data->id)?> -->
            <?php
                if(Yii::app()->user->checkAccess('moderator')){
                    echo CHtml::tag('span', array('class'=>'comment-actions'),false,false),
                         CHtml::link(Yii::t('main','Edit'),
                             array('/forum/post/update', 'id'=>$data->id),
                             array('class'=>'')),
                         ' | ',
                         CHtml::ajaxLink(Yii::t('main','Delete'),
                             array('/forum/post/delete', 'id'=>$data->id),
                             array(
                                 'type'=>'post',
                                 'success'=>'function(data,status){
                                     $.fn.yiiListView.update("post-list");
                                 }',
                                 'data'=>array(
                                     'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken,
                                 ),
                             ),
                             array('confirm'=>'Are you sure?', 'id' => 'remove-link-'.uniqid())),
                         CHtml::closeTag('span');
                }
            ?>
        </div>
    </div>
</div>