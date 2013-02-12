<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/payment/pro-account/common.css');
Yii::app()->clientScript->registerScriptFile('https://js.stripe.com/v1/');
Yii::app()->clientScript->registerScriptFile('/js/payment/proAccount/common.js');
Yii::app()->clientScript->registerScript(
        'st_pub_k', 'Stripe.setPublishableKey("' . Yii::app()->params['stripe']['publishableKey'] . '");', CClientScript::POS_END
);
?>
<style>
    #content #main_wide{
        box-shadow:none;
        background:none;
    }
</style> 
<div class="payment">
    <div class="payment-left-column">
        <div class="signup_plans plans_summary">
            <div class="top">
                <div class="title">Bugkick Pro</div>
                <div class="price">
                    <div class="price-month">
                        <span class="price-wrapper">$9.00</span> per month
                    </div> 
                    <div class="or"></div> 
                    <div class="price-year">
                        <span class="price-wrapper">$98.00</span> per year
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="middle">
                <p>More features and +20 charisma.</p>
                <p>Closed tickets kept indefinitely</p>
                <p>Website bug submission</p>
                <p>API Access</p>
                <p>GitHub Integration</p>
                <p>Custom logo</p>
                <p>No Ads</p>
            </div>
            <div class="bottom">

            </div>

            <?php /* ?>
              <li>
              <p class="title"><input type="radio" name="subscription" value="premium"
              <?php echo ($subscription == 'premium')? 'checked=""' : ''; ?> > Premium</p>
              <p>$25 per month</p>
              <p>Nice additional features and feelings of euphoria.</p>
              <p>Closed tickets kept indefinitely</p>
              <p>Website bug submission</p>
              <p>API Access</p>
              <p>No ads</p>
              </li>
              <li>
              <p class="title"><input type="radio" name="subscription" value="ultimate"
              <?php echo ($subscription == 'ultimate')? 'checked=""' : ''; ?> > Ultimate</p>
              <p>$35 per month</p>
              <p>More features and +20 charisma.</p>
              <p>Closed tickets kept indefinitely</p>
              <p>Website bug submission</p>
              <p>API Access</p>
              <p>Custom logo</p>
              <p>GitHub Integration</p>
              </li>
              <?php */ ?>
        </div>
    </div>
    <div class="form payment-right-column">
        <div class="errorSummary payment-errors" style="display:none;"></div>
        <?php
        $form = $this->beginWidget(
            'CActiveForm', array(
                'id' => 'payment-form',
                'method' => 'POST',
                'action' => '',
                'enableAjaxValidation' => false,
            )
        );
        ?>
        <div class="row">
            <?php echo $form->label($model, 'interval', array('style' => 'display:inline-block;')); ?>
            <?php echo $form->dropDownList($model, 'interval', $model->getIntervals(),array('class' => 'chzn-select')); ?>
        </div>
        <div class="row card-number-row">
            <?php
            echo
            $form->label($model, 'cardNumber'),
            $form->textField(
                    $model, 'cardNumber', array(
                'class' => 'card-number',
                'size' => 20,
                'maxlength' => 20,
                'autocomplete' => 'off',
                'placeholder' => $model->getAttributeLabel('cardNumber'),
                'style' => 'width:auto;',
                    )
            );
            ?>
        </div>
        <div class="row card-cvc-row">
            <?php
            echo
            $form->label($model, 'cvc'),
            $form->textField(
                    $model, 'cvc', array(
                'class' => 'card-cvc',
                'size' => 4,
                'maxlength' => 4,
                'autocomplete' => 'off',
                'placeholder' => $model->getAttributeLabel('cvc'),
                'style' => 'width:auto;',
                    )
            );
            ?>
        </div>
        <div class="row">
            <?php
            echo
            CHtml::label(Yii::t('main', 'Expiration (MM/YYYY)'), 'PaymentForm_cardExpiryMonth'),
            $form->dropDownList($model, 'cardExpiryMonth', CHtml::listData($months, 'm', 'm'), array('class' => 'card-expiry-month chzn-select')),
            ' / ',
            $form->dropDownList($model, 'cardExpiryYear', CHtml::listData($years, 'y', 'y'), array('class' => 'card-expiry-year chzn-select')),
            $form->hiddenField($model, 'stripeToken', array('class' => 'stripe-token'));
            ?>
        </div>
        <div class="row">
            <?php
            echo
            CHtml::label(Yii::t('main', 'Coupon (if you have one)'), 'PaymentForm_coupon'),
            $form->textField(
                    $model, 'coupon', array(
                'autocomplete' => 'off',
                'placeholder' => $model->getAttributeLabel('coupon'),
                'style' => 'width: 260px;',
                    )
            );
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('main', 'Continue'), array('class' => 'bkButtonBlueSmall normal submit-button', 'style' => 'box-shadow:none;')); ?>
            <img id="progressImg" alt="In progress" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader-3d-27.gif" style="vertical-align:middle;display:none;" />
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <div class="clear"></div>
    <div class="stripe-powered">
        <a href="http://stripe.com" target="_blank" title="Powered by Stripe"><img alt="Powered by Stripe" src="/themes/bugkick_theme/images/payment/stripe.png" style="margin: 2px" /></a>
        <div style="font-size: 10px; color: #696969">Cancel anytime. Contact us for refunds if you are miserable, we want happy customers.</div>
    </div>
</div>