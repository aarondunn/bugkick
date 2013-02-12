<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.10.12
 * Time: 20:29
 */
class InvitePeopleForm extends CFormModel
{
	public $email;

	public function rules()
	{
		return array(
			array('email', 'required'),
            array('email', 'email'),
            array('email', 'unique', 'className'=>'User', 'message'=>'This user is already a member'),
            array('email', 'safe')
		);
	}

    public function attributeLabels() {
 		return array(
 			'email'=>'Email',
 		);
 	}
}