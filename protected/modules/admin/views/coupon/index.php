<?php
$this->breadcrumbs=array(
	'Coupons'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Coupon', 'url'=>array('create')),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Coupons'); ?></h2>
    <div class="admin_content">
        <?php $this->widget('zii.widgets.grid.CGridView', array(
        	'id'=>'coupon-grid',
        	'dataProvider'=>$model->search(),
        	'filter'=>$model,
        	'columns'=>array(
        		'code',
                array(
                    'name'=>'period',
                    'value'=>'Coupon::getPeriodLabel($data->period)',
                ),
                array(
                    'name'=>'enabled',
                    'value'=>'SiteSettings::itemAlias("onOff",$data->enabled)',
                ),
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