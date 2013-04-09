<div class="dashboard-container">
    <div class="dash-item">
        <div class="dash-title"><?php echo Yii::t('main', 'Tickets: Open / Closed'); ?></div>
        <div><?php echo $bugCount ?><span class="gray-dash-number">/<?php echo $archivedBugCount ?></span></div>
    </div>
    <div class="dash-item">
        <div class="dash-title"><?php echo Yii::t('main', 'Tickets per Person:'); ?></div>
        <div><?php echo $bugsPerUser ?></div>
    </div>
    <div class="clr"></div>
    <div  class="dash-item">
        <div class="dash-title"><?php echo Yii::t('main', 'Open Tickets Today:'); ?></div>
        <div><?php echo $openBugsToday ?></div>
    </div>
    <div class="dash-item">
        <div class="dash-title"><?php echo Yii::t('main', 'New Tickets Today:'); ?></div>
        <div><?php echo $newBugsToday ?></div>
    </div>
</div>
<div class="clr"></div>

<!--[if IE]><script src="<?php echo Yii::app()->baseUrl;?>/js/dygraphs/excanvas.min.js"></script><![endif]-->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/dygraphs/dygraph-combined.js'); ?>

<div id="graph"></div>
    <p style=""><b><?php echo Yii::t('main', 'Display'); ?>: </b>
        <input type=checkbox id=0 onClick="change(this)" checked>
        <label for="0"> <?php echo Yii::t('main', 'Closed Tickets'); ?></label>
        <input type=checkbox id=1 onClick="change(this)" checked>
        <label for="1"> <?php echo Yii::t('main', 'New Tickets'); ?></label>
    </p>

<?php

     $graphValues = '';
     foreach($recentBugs as $key=>$value) {
         $graphValues .= '"' . $key . ',' . $value['archived'] . ',' .  $value['open'] . '\n"+'  ;
      }
      $graphValues = substr($graphValues, 0, strlen($graphValues)-1);

Yii::app()->clientScript->registerScript('graph', '
      chart = new Dygraph(document.getElementById("graph"),
                          "Date,Closed,New\n" +
                          '.$graphValues.'
                          ,
                          {
                            width: 500,
                            height: 300,
                              avoidMinZero: true,
                              drawPoints: true,
                              showRangeSelector: true,
                            //  stepPlot: true,
                            visibility: [true, true]
                          });
      function change(el) {
        chart.setVisibility(el.id, el.checked);
      }
', CClientScript::POS_END);
?>

<div style="margin-top: 20px;"><?php echo Yii::t('main', 'Total Tickets'); ?>: <b><?php echo $bugCount ?></b></div>

<div class="reportTable">
<table>
    <tr>
        <th colspan="2"><?php echo Yii::t('main', 'Users'); ?></th>
    </tr>
    <?php if(!empty($users)): ?>
        <?php foreach($users as $user): ?>
        <tr>
            <td width="18%"><div class="reportNumber"><?php echo $user->bugCount; ?></div></td>
            <td><?php echo $user->name; ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
</div>

<div class="reportTable">
<table>
    <tr>
        <th colspan="2"><?php echo Yii::t('main', 'Status'); ?></th>
    </tr>
    <?php if(!empty($statuses)): ?>
        <?php foreach($statuses as $status): ?>
        <tr>
            <td width="18%"><div class="reportNumber"><?php echo $status->bugCount; ?></div></td>
            <td><?php echo $status->label; ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
</div>

<div class="reportTable">
<table>
    <tr>
        <th colspan="2"><?php echo Yii::t('main', 'Label'); ?></th>
    </tr>
    <?php if(!empty($labels)): ?>
        <?php foreach($labels as $label): ?>
        <tr>
            <td width="18%"><div class="reportNumber"><?php echo $label->bugCount; ?></div></td>
            <td><?php echo $label->name; ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
</div>

<div class="clr"></div>

<?php
    $project = Project::getCurrent();
    if (!empty($project))
        $this->widget('InviteMember');
?>

<?php
$this->beginWidget(
    'zii.widgets.jui.CJuiDialog',
    array(
        'id'=>'project-form-dialog',
        'options'=>array(
            'title'=>'Edit Project',
            'autoOpen'=>false,
//    			'width'=>565,
            //'height'=>440,
            'modal'=>true,
            'hide'=>'drop',
            'show'=>'drop',
            'buttons'=>array(
                'Save'=>'js:submitProjectForm',
                //'Cancel'=>'js:closeDialog',
            ),
        )
    )
);
$this->endWidget();
?>