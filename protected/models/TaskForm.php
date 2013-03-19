<?php
/**
 * Author: Alexey Kavshirko kavshirko@gmail.com
 * Date: 18.03.13
 * Time: 23:42
 */
class TaskForm extends FormModel
{
    public $description;

    public function rules() {
        return array(
            array('description', 'length', 'max'=>500),
            array('description', 'safe'),
        );
    }

    public function attributeLabels() {
        return array(
            'description'=>'Description',
        );
    }
}