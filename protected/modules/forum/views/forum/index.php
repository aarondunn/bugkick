<?php
/* @var $this ForumController */
/* @var $dataProvider CActiveDataProvider */
$this->breadcrumbs=array(
    Yii::app()->name=>array('/site'),
    'Forums',//=>array('//forum'),
);
$this->menu=array(
	array('label'=>'Create Forum', 'url'=>array('create'),
	    'visible'=>Yii::app()->user->checkAccess('moderator')),
	array('label'=>'Manage Forums', 'url'=>array('admin'),
	    'visible'=>Yii::app()->user->checkAccess('moderator')),
);
?>
<!-- <header><h2><?php echo $this->pageTitle; ?></h2></header> -->

<!-- <header class="nav nav-header"><?php echo Yii::t('main','List of Topics')?></header> -->

<div class="topics-container">
    <?php $this->renderPartial('application.modules.forum.views.topic._list',array(
    	'dataProvider'=>$dataProvider,
    )); ?>
</div>

<?php
    // if(Yii::app()->user->checkAccess('moderator')){
    //     echo CHtml::link(Yii::t('main','New Forum'), '/forum/forum/create',
    //         array('class'=>'btn btn-primary btn-toolbar'));
    // }
?>