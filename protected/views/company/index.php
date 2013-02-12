<?php
$this->breadcrumbs=array(
	'Companys',
);

$this->menu=array(
	//array('label'=>'Create Company', 'url'=>array('create')),
	//array('label'=>'Manage Company', 'url'=>array('admin')),
);
?>

<h2>Please select company:</h2>

<?php echo CHtml::form('', 'post') ?>

<?php
    $user = User::model()->findByPk(Yii::app()->user->id);
	
    echo CHtml::dropDownList(
            'company',
            'company_id',
            CHtml::listData($user->company , 'company_id', 'company_name')
    );
?>

<?php echo CHtml::submitButton('Go'); ?>

<?php echo CHtml::endForm(); ?>

