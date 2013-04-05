<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 23.12.11
 * Time: 17:18
 */

echo CHtml::link('<img src="'. $companyLogoSrc .  '" />',
    Yii::app()->getController()->getDefaultPage(), array('id'=>'logo'));