<?php
Yii::app()->clientScript->registerScript('fixProjectName', "
function fixProjectName(){
	project_id = null;
	$('#b_project_list .b_project_name').each(function(){
		if(project_id != $(this).attr('project_id')){
			project_id = $(this).attr('project_id');
			$(this).removeClass('disabled');
		} else
			$(this).remove();
	});
	$('html, body').animate({ scrollTop: 0 }, 'slow');
};
		
fixProjectName();
", CClientScript::POS_READY);
?>

<div id="summary-tickets">

<?php
$this->widget('zii.widgets.CListView', array(
    'id'=>'b_project_list',
    'dataProvider' => $model,
    'itemView' => '_listAllProjectsItem',
	'tagName' => 'div',
    'enableSorting' => false,
    'enablePagination' => true,
	'itemsCssClass' => 'b_tickets',
    'summaryText' => '',
	'template'=>'{items}{pager}',
    'emptyText' => Yii::t('main', 'You have no tickets.'),
    'pagerCssClass' => 'list-pager',
    'pager' =>array('header'=>''),
    'afterAjaxUpdate'=>'js:function(id, data) {
		fixProjectName();
     }',
));
?>

	<div class="b_closed_tickets">
		<div class="b_label">Tickets Closed per Day:</div>
		<ul class="b_daily_score">
		  <?php foreach($bugChanges as $record) { 
		  	$fScorePerc = ($max_closed == 0) ? 0 : (($record['count'] / $max_closed) * 100);
		  	?>
        	<li>
				<div class="b_graph" style="height:<?php echo $fScorePerc ?>%;"></div>
				<div class="b_score" style="margin-top:<?php echo (53 - 80*$fScorePerc/100) ?>px;"><?php echo $record['count'] ?></div>
				<div class="b_date"><?php  echo $record['date'] ?></div>
			</li>
          <?php } ?>
		</ul>
	</div>
</div>

