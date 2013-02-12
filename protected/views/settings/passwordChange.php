<?php
return array(
    'title'=>Yii::t('main', 'Change Password'),
    'action'=>CHtml::normalizeUrl(array('settings/passwordChange')),
    
    'activeForm'=>array(
        'id'=>'password-form',
        'class'=>'CActiveForm',
        'enableAjaxValidation'=>true,
        'htmlOptions'=>array('style'=>'display:none;'),
    ),
 
    'elements'=>array(
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
            'minlength'=>4
        ),
        'password_new'=>array(
            'type'=>'password',
            'maxlength'=>32,
            'minlength'=>4
        ),
        'password_new2'=>array(
            'type'=>'password',
            'maxlength'=>32,
            'minlength'=>4
        ),
    ),

    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Save',
            'class'=>'bkButtonBlueSmall medium',
        ),
    ),
);
?>