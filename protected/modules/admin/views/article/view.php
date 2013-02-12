<?php
$this->breadcrumbs=array(
	'Articles'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Articles', 'url'=>array('/admin/article')),
	array('label'=>'Create Article', 'url'=>array('create')),
	array('label'=>'Update Article', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Article', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?','csrf' => true)),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo CHtml::encode($model->title); ?></h2>
    <div class="admin_content">
        <?php echo $model->content; ?>
    </div>
</div>
