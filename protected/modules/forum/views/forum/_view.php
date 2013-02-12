<?php
/* @var $this ForumController */
/* @var $model BKForum */
?>

<dl class="view">
    <dt>
        <?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id' => $data->id)); ?>
        <span class="fr">
            <?php echo $data->topicsCount; ?>
            <?php echo Yii::t('main', 'topics')?>
        </span>
    </dt>
    <dd><em><?php echo CHtml::encode($data->description); ?></em></dd>
</dl>
