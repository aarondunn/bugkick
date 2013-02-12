<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 23.12.11
 * Time: 17:13
 */

class SiteHeader extends  CWidget
{
    public function init()
    {
    }

    public function run()
    {
        $company = Company::model()->findByPk( Company::current() );

        if (!empty($company)){
            if(!empty($company->company_color))
                Yii::app()->clientScript->registerCss('header', ' #header{ background: ' .$company->company_color. '; }');

            if(!empty($company->company_top_logo))
                $companyLogoSrc = $company->getImageSrc( 132, 33);
        }

        if(!isset($companyLogoSrc))
            $companyLogoSrc = Yii::app()->baseUrl . '/images/logo.png';

        $this->render('siteHeader', array('companyLogoSrc'=>$companyLogoSrc));
    }
}