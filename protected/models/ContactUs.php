<?php

/**
 * This is the model class for table "{{filter}}".
 *
 * The followings are the available columns in table '{{filter}}':
 * @property string $filter_id
 * @property string $user_id
 * @property string $name
 * @property string $filter
 */
class ContactUs extends CFormModel {

    public $name;
    public $email;
    public $comment;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('name,email,comment', 'required'),
            array('name', 'length', 'max' => 255),
            array('email', 'length', 'max' => 255),
            array('comment', 'length', 'max' => 255),
            // email has to be a valid email address
            array('email', 'email'),
                // verifyCode needs to be entered correctly
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {

        return array(
            'name' => 'Name',
            'email' => 'E-mail',
            'comment' => 'Comment'
        );
    }

}

