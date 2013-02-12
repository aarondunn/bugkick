<?php
/* @var $this PostController */
/* @var $model BKPost */

$this->breadcrumbs=array(
    Yii::app()->name=>array('/site'),
    'Forums'=>array('/forum'),
    CHtml::encode(BKHelper::truncateString($model->topic->forum->title))=>
        array('forum/view/','id'=>$model->topic->forum->id),
    CHtml::encode(BKHelper::truncateString($model->topic->title))=>
            array('topic/view','id'=>$model->topic->id),
    CHtml::encode(BKHelper::truncateString($model->body)),
    Yii::t('main','Update')
);

$this->menu=array(
    array('label'=>'Back to Topic', 'url'=>array('forum/topic/view', 'id'=>$model->topic->id)),
    array('label'=>'Back to Forum', 'url'=>array('forum/view', 'id'=>$model->topic->forum->id)),
    array('label'=>'Back to Forums', 'url'=>array('/forum')),
);
$this->pageTitle = Yii::t('main','Update Post');
?>
<header><h3><?php echo $this->pageTitle; ?></h3></header>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'topic'=>$topic)); ?>