<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 23.12.11
 * Time: 18:32
 */

class CompanySettingsForm extends FormModel {

	public $company_color;
    public $show_ads;

	public function rules() {
		return array(
			array('company_color', 'length', 'max'=>7),
			array('company_color, show_ads', 'safe')
		);
	}

	public function attributeLabels() {
		return array(
			'company_color'=>Yii::t('main','Company color'),
			'show_ads'=>Yii::t('main','Show Advertisements'),
		);
	}
}
