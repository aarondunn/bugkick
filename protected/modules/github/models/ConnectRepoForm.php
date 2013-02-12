<?php
/**
 * ConnectRepoForm
 *
 * @author f0t0n
 */
class ConnectRepoForm extends FormModel {

    public $github_repo;
    public $translate_tickets;

    public function rules() {
        return array(
            array('github_repo', 'required'),
            array('github_repo', 'length', 'max'=>255),
            array('translate_tickets', 'boolean', 'trueValue'=>1, 'falseValue'=>0),
            array('translate_tickets', 'in', 'range'=>array(0, 1)),
            array('translate_tickets', 'default', 'value'=>0, 'setOnEmpty'=>true),
        );
    }

    public function attributeLabels() {
        return array(
            'github_repo' => 'GitHub Repository',
            'translate_tickets' => 'Transmit Tickets',
        );
    }
}