<?php
$beginCache=function($controller, $cacheId, $duration, $expression) {
	return $controller->beginCache(
		$cacheId,
		array(
			'duration'=>$duration,
			'varyByExpression'=>$expression,
			'varyByRoute'=>false,
			'dependency'=>array(
				'class'=>'system.caching.dependencies.CExpressionDependency',
				'expression'=>$expression,
			)
		)
	);
};
?>
<div class="ticket-item-wrapper">
<?php 
        echo (!empty($data->status->status_color))
              ? '<span class="status" style="background: ' . $data->status->status_color . '"></span>'
              : '<span class="status not-set"></span>' ;
    ?>
<div id="<?php echo $data->id ?>" class="ticket-item <?php echo Bug::getDueDateRemainAlias($data); ?> unchecked"
        data-ticket-id="<?php echo $data->id; ?>"
        ticketID="<?php echo $data->id ?>"
        position="<?php echo $data->priority_order ?>"
        user_set="<?php echo (empty($data->user_set))? null : implode(CJSON::decode($data->user_set), ',') ?>"
        label_set="<?php echo (empty($data->label_set))? null : implode(CJSON::decode($data->label_set), ',') ?>">
    
    <div class="opacity05 checkbox unchecked" data-ticket-id="<?php echo $data->id; ?>"></div>
    <span class="title">
        <?php
      		echo CHtml::link('<span class="ticket-number">#' . $data->number . '</span> ' . $data->title, array('bug/view', 'id'=>$data->number ), array('title'=>substr($data->description, 0, 160)));
            if ($data->is_created_with_api == 1){ ?>
                <span class="bubble" style="background-color:#008000;"><?php echo Yii::t('main', 'USER SUBMITTED'); ?></span>
        <?php
            }
        ?>
    </span>
<?php
if($beginCache($this, 'ticket_comment_count', 3600, "'{$data->commentCount}'")) {
	if($data->commentCount>0) {
?>
	<a href="<?php echo $this->createUrl('bug/view', array('id'=>$data->number, '#'=>'comments'))?>">
       <div class="comments" title="<?php //$sl=strlen(strip_tags($data->getLastComment($data->id))); echo substr(strip_tags($data->getLastComment($data->id)),0,160).(($sl>160)? '...':''); ?>" >
           <span class="comment-count-number"><?php echo $data->commentCount; ?></span> 
           <span class="comment-count-icon" ></span>            
        </div>
    </a>
<?php
	}else {
?>
	<span class="no-comments"></span>
<?php
	}
	$this->endCache();
}
?>
	<?php
	if($beginCache($this, 'ticket_due_date_str', 3600, "'{$data->duedate}'")) {
		echo  ($data->duedate != '0000-00-00')? '<span class="clock" title="due '.Helper::formatDateShort($data->duedate).'"></span>' : '';
		$this->endCache(); 
	}
	?>
</div>
</div>    