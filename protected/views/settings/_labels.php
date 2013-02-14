<?php
//applying Chosen to dropdowns
Yii::app()->clientScript->registerScript('re-apply-dropdown-style', "
function reApplyDropdownStyle() {
    $('.filters select').chosen();
}
$('.filters select').chosen();
", CClientScript::POS_READY);

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'label-grid',
    'dataProvider' => $model,
    //'filter'=>$model,
	//'dataProvider'=>$model,
	'filter'=>$labelModel,
    'summaryText'=>'', //hide Total: 10 result(s)
    'hideHeader'=>true,  //hide Header
    'afterAjaxUpdate' => 'reApplyDropdownStyle',
	'columns'=>array(
//        'label_id',
        array(
            'name' => 'name',
            'type' => 'html',
            'filter' => CHtml::listData(Company::getProjects(null,true),'project_id','name'),
            'value' =>'(!empty($data->label_color))
            ? "<span class=\"bubble\" style=\"background-color:".$data->label_color."\">".$data->name."</span>"
            : "<span class=\"bubble-name\">".$data->name."</span>"'
        ),
        array(
			'class' => 'CButtonColumn',
			'template' => '{update} {delete}',
            'deleteButtonUrl'=>'CHtml::normalizeUrl(array("label/delete", "id"=>$data->label_id))',
//            'deleteConfirmation'=>false,
            'updateButtonUrl'=>'Yii::app()->createAbsoluteUrl("label/getLabelById", array("id"=>$data->label_id))',
            'updateButtonImageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/edit_icon.png',
            'htmlOptions' => array('style'=>'width:35px')
		)
	),
)); 
?>