<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 03.02.12
 * Time: 13:05
 */
$this->breadcrumbs=array(
	'Choose Subscription',
);?>
<div class="settings">
<h2><?php echo Yii::t('main', 'Choose Subscription') ?></h2>
    <?php $this->renderFlash(); ?>
   <div class="membership_wrapper">
        <ul class="membership">
            <li class="ultimate_membership highlighted">
                <h3 class="membership_title">BugKick Pro</h3>
                <p class="membership_fee">$9.00/month or $98.00/year</p>
                <p class="membership_descr">More features and +20 charisma.</p>
                <ul class="membership_ability">
                    <li class="first big">Closed tickets kept indefinitely</li>
                    <li>Website bug submission</li>
                    <li>API Access</li>
                    <li>GitHub Integration</li>
                    <li>Custom logo, No Ads</li>
                </ul>
                <div class="button">
                    <a href="<?php echo $this->createUrl('payment/pro-account', array('subscription'=>'pro')); ?>" class="btn_upgrade">Upgrade</a>
                </div>
            </li>
<?php /* ?>
            <li class="premium_membership">
                <h3 class="membership_title">Premium</h3>
                <p class="membership_fee">$25 per month</p>
                <p class="membership_descr">Nice additional features and feelings of euphoria.</p>
                <ul class="membership_ability">
                    <li class="first">Closed tickets indefinite</li>
                    <li>Website bug submission</li>
                    <li>API Access</li>
                    <li>&nbsp;</li>
                    <li>&nbsp;</li>
                </ul>
                <div class="button">
                    <a href="<?php echo $this->createUrl('payment/pro-account', array('subscription'=>'premium')); ?>" class="btn_upgrade">Upgrade</a>
                </div>
            </li>
            <li class="ultimate_membership highlighted">
                <h3 class="membership_title">Ultimate</h3>
                <p class="membership_fee">$35 per month</p>
                <p class="membership_descr">More features and +20 charisma.</p>
                <ul class="membership_ability">
                    <li class="first">Closed tickets indefinite</li>
                    <li>Website bug submission</li>
                    <li>API Access</li>
                    <li>Custom logo</li>
                    <li>GitHub Integration</li>
                </ul>
                <div class="button">
                    <a href="<?php echo $this->createUrl('payment/pro-account', array('subscription'=>'ultimate')); ?>" class="btn_upgrade">Upgrade</a>
                </div>
            </li>
<?php */ ?>

        </ul>
   </div><!-- .membership_wrapper -->
</div>
