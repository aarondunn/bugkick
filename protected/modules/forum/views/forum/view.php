<?php
/* @var $this ForumController */
/* @var $model BKForum */
$this->breadcrumbs=array(
    Yii::app()->name=>array('/site'),
    'Forums'=>array('index'),
    CHtml::encode(BKHelper::truncateString($model->title)),
);
$this->menu=array(
    array('label'=>'List Forums', 'url'=>array('index')),
   	array(
        'label'=>'Create Forum',
        'url'=>array('create'),
        'visible'=>Yii::app()->user->checkAccess('moderator')
    ),
   	array(
        'label'=>'Update Forum',
        'url'=>array('update', 'id'=>$model->id),
        'visible'=>Yii::app()->user->checkAccess('moderator')
    ),
   	array(
        'label'=>'Delete Forum', 'url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Are you sure you want to delete this item?',
            'csrf' => true
        ),
        'visible'=>Yii::app()->user->checkAccess('moderator')
    ),
   	array(
        'label'=>'Manage Forums',
        'url'=>array('admin'),
   	    'visible'=>Yii::app()->user->checkAccess('moderator')
    ),
    array('label'=>Yii::t('main','Topics'),'itemOptions'=>array('class'=>'nav-header')),
    array(
        'label'=>'Create Topic',
        'url'=>array('topic/create', 'forumID'=>$model->id),
        'visible'=>Yii::app()->user->checkAccess('user')
    ),
);
$this->pageTitle = CHtml::encode(BKHelper::truncateString($model->title));
?>


<div class="forum-header">
    <h3 class="forum-title"><?php echo $this->pageTitle; ?></h3>
    <?php echo CHtml::encode($model->description) ?>
</div>
<div class="topics-container">
    <?php $this->widget('zii.widgets.CListView', array(
'id'=>'topic-list',
    	'dataProvider'=>$topics,
    	'itemView'=>'application.modules.forum.views.topic._view',
        'summaryText'=>'',
        'emptyText'=>Yii::t('main','No topics yet.'),
        'pager'=>array(
            'header'=>'',
        )
    )); ?>
</div>

<?php
    if(Yii::app()->user->checkAccess('user')){
        echo CHtml::link(Yii::t('main','New Topic'), array('/forum/topic/create', 'forumID'=>$model->id),
            array('class'=>'btn btn-primary btn-toolbar', 'id'=>'btn-new-topic'));
    }
?>
