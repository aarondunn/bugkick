<div id="signup" class="centered-form">
    <div class="head">
        <h2>Sign Up</h2>
    </div>
    <div class="form">
        <?php $this->renderPartial('v2/_form', array(
            'user'=>$user,
            'company'=>$company,
            'subscription'=>$subscription,
            'subscriptions'=>$subscriptions,
        )); ?>
    </div>
</div>
<?php $this->renderPartial('v2/premium-description'); ?>