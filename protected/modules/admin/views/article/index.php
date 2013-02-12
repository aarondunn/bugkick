<?php
$this->breadcrumbs=array(
	'Articles'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Article', 'url'=>array('create')),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Articles'); ?></h2>
    <div class="admin_content">
        <?php $this->widget('zii.widgets.grid.CGridView', array(
        	'id'=>'article-grid',
        	'dataProvider'=>$model->search(),
        	'filter'=>$model,
        	'columns'=>array(
        		'id',
        		'title',
        		array(
        			'class'=>'CButtonColumn',
        		),
        	),
            'pagerCssClass' => 'list-pager',
            'pager'=>array(
                'header'=>false,
            )
        )); ?>
    </div>
</div>