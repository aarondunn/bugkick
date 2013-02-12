<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.11.12
 * Time: 22:29
 */

class MarketingForm extends FormModel
{
    public $invites_module;
    public $invites_count;
    public $invites_limit;

    public function rules()
    {
        return array(
            array('invites_module, invites_count, invites_limit', 'numerical', 'integerOnly'=>true),
            array('invites_count', 'length', 'max'=>10),
        );
    }

    public function attributeLabels()
    {
        return array(
            'invites_module' => 'Invites Module',
            'invites_count' => 'Invites Count',
            'invites_limit' => 'Invites Limit',
        );
    }
}