<?php
return array(
	'free'=>null,
    //bugkick_pro - $9.00/month, bugkick_pro_yearly - $98.00/year
	'pro_month'=>array(
		'id'=>'bugkick_pro',		// id of month plan, created within Stripe.com account
        'projects_available'=>100500,
        'is_github_integration_available'=>true,
	),
	'pro_year'=>array(
		'id'=>'bugkick_pro_yearly',	// id of year plan, created within Stripe.com account
        'projects_available'=>100500,
        'is_github_integration_available'=>true,
	),
	//bugkick_yearly_ultimate - $350 a year bugkick_yearly_premium - $250 a year bugkick_monthly_ultimate - $35 a month bugkick_monthly_premium - $25 a month
/*    'premium_month'=>array(
		'id'=>'bugkick_monthly_premium',
        'projects_available'=>5,
        'is_github_integration_available'=>false,
	),
	'premium_year'=>array(
		'id'=>'bugkick_yearly_premium',
        'projects_available'=>5,
        'is_github_integration_available'=>false,
	),
	'ultimate_month'=>array(
		'id'=>'bugkick_monthly_ultimate',
        'projects_available'=>100500,
        'is_github_integration_available'=>true,
	),
	'ultimate_year'=>array(
		'id'=>'bugkick_yearly_ultimate',
        'projects_available'=>100500,
        'is_github_integration_available'=>true,
	),*/
);