<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 01.12.11
 * Time: 6:03
 */
class DuplicateForm extends FormModel {

    public $duplicate_number;
    //public $number;
    public $id;

    public function rules() {
        return array(
            array('id, duplicate_number', 'numerical'),
            array('duplicate_number', 'application.extensions.validators.TicketNumberValidator'),
            array('duplicate_number', 'safe'),
        );
    }

    public function attributeLabels() {
        return array(
            //'number'=>'Number',
            'id'=>'ID',
            'duplicate_number'=>'Duplicate Ticket #',
        );
    }
}
