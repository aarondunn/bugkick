<?php
/* @var $this TopicController */
/* @var $model BKTopic */
/* @var $forum BKForum */

$this->breadcrumbs=array(
    Yii::app()->name=>array('/site'),
    'Forums'=>array('forum/index'),
    CHtml::encode(BKHelper::truncateString($forum->title))=>array('forum/view','id'=>$forum->id),
    'Create',
);

$this->menu=array(
	array('label'=>'Back to Forum', 'url'=>array('forum/view','id'=>$forum->id)),
	array('label'=>'Manage Topics', 'url'=>array('admin'),
	    'visible'=>Yii::app()->user->checkAccess('moderator')),
);
$this->pageTitle = Yii::t('main','Create Topic');
?>

<header><h3><?php echo $this->pageTitle; ?></h3></header>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'forum'=>$forum)); ?>