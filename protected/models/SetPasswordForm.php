<?php
/**
 * SetPasswordForm
 *
 * @author f0t0n
 */
class SetPasswordForm extends CFormModel {
	
	public $password1;
	public $password2;
    public $name;
    public $lname;

	public function rules() {
		return array(
			array('password1, password2', 'required'),
            array('lname', 'length', 'max' => 100),
            array('name, password1, password2', 'length', 'max' => 100, 'min' => 3, 'allowEmpty'=>false),
			array('password2', 'compare', 'compareAttribute'=>'password1'),
		);
	}
	
	public function attributeLabels() {
		return array(
            'name'=>'First Name',
            'lname'=>'Last Name',
			'password1'=>'Password',
			'password2'=>'Password confirmation',
		);
	}
}