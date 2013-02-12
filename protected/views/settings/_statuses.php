<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'status-grid',
	'dataProvider'=>$model,
	//'filter'=>$model,
    'summaryText'=>'', //hide Total: 10 result(s)
    'hideHeader'=>true,  //hide Header
	'columns'=>array(
//        'status_id',
        array(
            'name' => 'label',
            'type' => 'html',
            'value' =>
            '(!empty($data->status_color))
            ? "<span class=\"bubble\" style=\"background-color:".$data->status_color."\">".$data->label."</span>"
            : "<span class=\"bubble-name\">".$data->label."</span>"'
        ),
        array(
			'class' => 'CButtonColumn',
			'template' => '{update} {delete}',
            'deleteButtonUrl'=>'CHtml::normalizeUrl(array("status/delete", "id"=>$data->status_id))',
//            'deleteConfirmation'=>false,
            'updateButtonUrl'=>'Yii::app()->createAbsoluteUrl("status/getStatusById", array("id"=>$data->status_id))',
            'updateButtonImageUrl'=>Yii::app()->theme->baseUrl . '/images/icons/edit_icon.png',
            'htmlOptions' => array('style'=>'width:35px')
		)
	),
)); 
?>