<?php

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'labelDialog',
    'options' => array(
        'title' => 'New Label',
        'autoOpen' => false,
        'modal' => true,
        'hide' => 'drop',
        'show' => 'drop',
        'width' => 'auto',
        'buttons' => array(
        //'Cancel'=>'js:function(){ $(this).dialog("close");}',
        //'Save'=>'js:savePassword',
        ),
    ),
));
$this->renderPartial(
        'application.views.settings._labelForm', array(
    'labelModel' => $labelModel,
    'formID' => 'label-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'action' => CHtml::normalizeUrl(array('label/create')),
        )
);
$this->endWidget('zii.widgets.jui.CJuiDialog');