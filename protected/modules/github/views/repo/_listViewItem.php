<div class="repo-view-item round5">
    <div class="fl-left">
    <?php
    echo CHtml::radioButton('ConnectRepoForm[github_repo]',
        $model->github_repo == $data['full_name'],
        array(
            'id'=>'github-repo-' . $data['id'],
            'value'=>$data['full_name']
        )
    );
    ?>
    <?php
//    echo $form->radioButton($model, 'github_repo', array(
//        'value'=>$data['full_name'],
//        'id'=>'repo-radio-' . $data['id'],
//    ));
    ?>
    </div>
    <div class="fl-left access-indicator<?php if($data['private']) { ?> private<?php } ?>"></div>
    <a title="<?php echo $data['description']; ?>" href="<?php echo $data['html_url'] ?>" target="_blank" class="title"><?php echo $data['full_name']; ?></a>
    <div class="fl-right state-indicator<?php if($model->github_repo == $data['full_name']) { ?> connected<?php } ?>"></div>
    <div class="clear"></div>
</div>