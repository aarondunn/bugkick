<?php
$this->clientScript->registerScriptFile('js/plug-in/mailcheck/mailcheck.min.js', ClientScript::POS_END);
Yii::app()->clientScript->registerScript('mailcheck', "
$('.mailcheck').on('blur', function() {
    $(this).mailcheck({
        suggested: function(element, suggestion) {
            $('.suggestion').html('Maybe \"<span class=\"suggested\">'+suggestion.full+'</span>\"?');
            $('.suggestion').show();
        },
        empty: function(element) {
          $('.suggestion').hide();
        }
    });
});
$('.suggested').live('click', function(){
    $('.mailcheck').val($(this).text());
    $('.mailcheck').blur();
});
", CClientScript::POS_READY);
?>

<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    'htmlOptions'=> array('enctype'=>'multipart/form-data'),
    'method'=>'POST',
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
));
?>
<div class="row">
    <div class="profile-pic">
        <div class="profile-pic-preview">
            <img src="<?php echo ImageHelper::thumb( 42, 42, 'images/profile_img/default.jpg', 75 ); ?>" alt="Profile Logo" />
            <div class="profile-pic-shadow"></div>
        </div>
    </div>
    <div class="description">
        <label for="profile-pic-upload">Profile Logo</label>
    </div>
    <div id="photo-upload">
        <?php //echo $form->labelEx($user,'Profile Image'); ?>
        <?php echo CHtml::activeFileField($user, 'profile_img'); ?>
        <?php echo $form->error($user,'profile_img'); ?>
    </div>
</div>
<div class="row">
    <div class="row left">
        <?php echo $form->labelEx($user,'First Name'); ?>
        <?php echo $form->textField($user,'name',array('maxlength'=>100)); ?>
        <?php echo $form->error($user,'name'); ?>
    </div>
    <div class="row right">
        <?php echo $form->labelEx($user,'Last Name'); ?>
		<?php echo $form->textField($user,'lname',array('maxlength'=>255)); ?>
		<?php echo $form->error($user,'lname'); ?>
    </div>
</div>
<div class="row">
    <?php echo $form->labelEx($user,'Email Address'); ?>
    <?php echo $form->textField($user,'email',array('maxlength'=>100, 'class'=>'mailcheck')); ?>
    <?php echo $form->error($user,'email'); ?>
    <div class="suggestion flash-success" style="display: none;"></div>
</div>
<div class="row">
    <?php echo $form->labelEx($user,'Password'); ?>
    <?php echo $form->passwordField($user,'password',array('size'=>60,'maxlength'=>100)); ?>
    <?php echo $form->error($user,'password'); ?>
</div>
<div class="row" id="plan-selection">
    <div class="description">
        <label for="plan">Choose a Plan:</label>
    </div>
    <?php
    echo CHtml::dropDownList('subscription', $subscription, $subscriptions,
        array(
            'id'=>'plan',
            'class'=>'chzn-select',
        )
    );
    ?>
</div>
<?php /*
<div class="row">
    <div class="description">
        <label for="Company_coupon_id">Have a Coupon?</label>
    </div>
    <?php //echo $form->labelEx($company,'coupon_id'); ?>
    <?php echo $form->textField($company,'coupon_id',array('maxlength'=>255)); ?>
    <?php echo $form->error($company,'coupon_id'); ?>
</div>
 */ ?>
<div class="row buttons">
    <input type="submit" class="buttonLandingStyle green" value="Register" />
</div>
<?php $this->endWidget(); ?>