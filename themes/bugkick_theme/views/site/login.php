<?php
$this->pageTitle = Yii::app()->name . ' - Login';
/*
  $this->breadcrumbs=array(
  'Login',
  );
 */
?>

<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif; ?>
<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>
<?php if (!Yii::app()->user->hasFlash('block')): ?>
    <div id="login" class="centered-form">
        <div class="head">
            <h2>Login to <?php echo Yii::app()->name ?></h2>
        </div>
        <div class="form">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                    ));
            ?>
            <div class="row">
                <?php echo $form->label($model, 'username'); ?>
                <?php echo $form->textField($model, 'username', array('placeholder' => 'Email address')); ?>
            </div>
            <div class="row">
                <?php echo $form->error($model, 'username'); ?>
            </div>

            <div class="row">
                <?php echo $form->label($model, 'password'); ?>
                <?php echo $form->passwordField($model, 'password', array('placeholder' => 'Password')); ?>
            </div>
            <div class="row">
                <?php echo $form->error($model, 'password'); ?>
            </div>

            <div class="row check">
                <?php echo $form->label($model, 'rememberMe'); ?>
                <?php echo $form->checkBox($model, 'rememberMe'); ?>
                <?php echo $form->error($model, 'rememberMe'); ?>
            </div>

            <div class="row buttons">
                <?php echo CHtml::submitButton('Login >>', array('class' => 'buttonLandingStyle green')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
        <!--<div id="facebook-connect">
             or
             <a href="<?php echo $fbLoginUrl; ?>" title="Connect with Facebook" id="fb-conn">Connect with Facebook</a>
         </div>-->
    </div>
    <div id="additional-actions">
        <p>Not registered? <?php echo CHtml::link('Sign Up', Yii::app()->createUrl('/registration')) ?> now for free!</p>
        <p><a href="#" title="Click to recover your password" class="reset-password-link">Forgot your password</a> ?</p>
    </div>

    <?php
    Yii::app()->clientScript->registerScript('password_reset', '

    jQuery("a.reset-password-link").live("click",function() {
        jQuery("#resetPasswordDialog").dialog("open");
            return false;
    });
    ', CClientScript::POS_END);
    ?>

    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'resetPasswordDialog',
        'options' => array(
            'title' => 'Enter Your Email',
            'autoOpen' => false,
            'modal' => true,
            'hide' => 'drop',
            'show' => 'drop',
            'buttons' => array(),
            'open' => 'js: function(event, ui) {
                     $("#password-reset-form").css("display", "block");
                }',
        ),
    ));
    ?>
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'password-reset-form',
            'method' => 'POST',
            'action' => Yii::app()->createUrl('user/resetPassword'),
            'htmlOptions' => array(
                'style' => 'display:none'
            ),
                ));
        ?>

        <div class="row">
            <input type="text" name="email" placeholder="email">
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Reset'); ?>
        </div>
        <div class="clear"></div>    
        <?php $this->endWidget(); ?>
    </div>

    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<?php endif; ?>