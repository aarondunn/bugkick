<?php
$this->clientScript->registerScript(get_class($this).'#repo-list-view',
<<<JS
    function repoListInitColorTips() {
        $('.repo-view-item a[title]').colorTip({color: 'yellow', timeout:100});
    }
    repoListInitColorTips();
    $(document).on('click', '.repo-view-item', function(e) {
        if($(e.target).hasClass('repo-view-item')) {
            var radio$ = $('input[type="radio"]', $(this)).click();
        }
    });
JS
    ,
    CClientScript::POS_END
);
?>
<div id="connect-github-repo-wrapper">
    <?php if(!empty($project->github_repo)) { ?>
    <h2>
        Project <i>&laquo;<?php echo $project->name; ?>&raquo;</i>
        is currently connected to GitHub repository 
        <?php
        echo CHtml::link(
                $project->github_repo,
                GitHubClient::getGitHubRepoUrl($project->github_repo),
                array(
                    'target'=>'_blank',
                ));
        ?>
    </h2>
    <?php } else { ?>
    <h2>Connect project <i>&laquo;<?php echo $project->name; ?>&raquo;</i> to GitHub repository</h2>
    <?php } ?>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>$formID,
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
    ));
    ?>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_listViewItem',
        'template'=>'{summary}{items}',
        'summaryText'=>'Page <b>#{page}</b>, displaying <b>{start} - {end}</b> of <b>{count}</b> repositories',
        'viewData'=>array(
            'model'=>$model,
            'form'=>$form,
            'formID'=>$formID,
            'project'=>$project,
        ),
        'id'=>'github-repo-list',
    )); ?>
    <div class="row al-right"><?php echo $links; ?></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'translate_tickets'),
            $form->checkBox($model, 'translate_tickets',
                array(
                    'class'=>'translate',
                )
            );
        ?>
    </div>
    <div class="row buttons">
        <?php
        echo CHtml::ajaxSubmitButton('Save', '',
            array(// ajaxOptions
                'success'=>"js:function(data) {
                    $('#connect-github-repo-wrapper').replaceWith(data);
                    repoListInitColorTips();
                    $.flashMessage().success('Saved.');
                }",
                'error'=>'js:function(data) {
                    $.flashMessage().error("Error.");
                 }',
            ),
            array(// htmlOptions
                'onclick'=>'js:(function() {
                    $.flashMessage().progress();
                })();',
                'class'=>'bkButtonBlueSmall normal'
            )
        );
        ?>
    </div>
    <?php $this->endWidget(); ?>
    <?php //VarDumper::dd($repositories); ?>
</div>