

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
    'htmlOptions'=> array('enctype'=>'multipart/form-data'),
    'method'=>'POST',
	'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
	),
)); ?>
<div class="form registration">

	<?php echo $form->errorSummary($user); ?>
    <?php echo $form->errorSummary($company); ?>
    
    <div class="row left">
        <img alt="" class="registation_logo" src="<?php echo ImageHelper::thumb( 42, 42, 'images/profile_img/default.jpg', 75 ); ?>" />

          <?php echo $form->labelEx($company,'Company Logo'); ?>
          <?php echo CHtml::activeFileField($company, 'company_logo'); ?>
          <?php echo $form->error($company,'company_logo'); ?>
    </div>
    
     <div class="row right">
        <img alt="" class="registation_logo" src="<?php echo ImageHelper::thumb( 42, 42, 'images/profile_img/default.jpg', 75 ); ?>" />

                <?php echo $form->labelEx($user,'Profile Image'); ?>
                <?php echo CHtml::activeFileField($user, 'profile_img'); ?>
                <?php echo $form->error($user,'profile_img'); ?>
        </div>
    
    <div class="row left">
		<?php echo $form->labelEx($company,'Company Name'); ?>
		<?php echo $form->textField($company,'company_name',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($company,'company_name'); ?>
	</div>
	
	  <div class="row right">
                <?php echo $form->labelEx($user,'First Name'); ?>
                <?php echo $form->textField($user,'name',array('size'=>60,'maxlength'=>100)); ?>
                <?php echo $form->error($user,'name'); ?>
        </div>


    <div class="row left">
		<?php echo $form->labelEx($company,'Company Url'); ?>
		<?php echo $form->textField($company,'company_url',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($company,'company_url'); ?>
	</div>
  
    <div class="row right">
		<?php echo $form->labelEx($user,'Last Name'); ?>
		<?php echo $form->textField($user,'lname',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($user,'lname'); ?>
	</div>

    <div class="row right">
		<?php echo $form->labelEx($user,'Email Address'); ?>
		<?php echo $form->textField($user,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($user,'email'); ?>
	</div>

    <div class="row right">
		<?php echo $form->labelEx($user,'Password'); ?>
		<?php echo $form->passwordField($user,'password',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($user,'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Register', array (
		                    'class'=>'bkButtonBlueSmall normal',
		                    'style'=>'float:right; box-shadow:none;')
		                ); ?>
	</div>

    <div class="clear"></div>
</div><!-- form -->

    <div class="signup_plans">
        <p>Select which plan you would like to sign up with, you can always upgrade later.</p>
        <ul>
            <li>
                <p class="title"><input type="radio" name="subscription" value="free"
                    <?php echo ($subscription == '' || $subscription == 'free' )? 'checked=""' : ''; ?> > Free</p>
                <p>$0 per month</p>
                <p>Enjoy it free.</p>
                <p>Closed tickets kept for 30 days</p>
                <p>Up to 3 projects, (each can have unlimited tickets, don't worry)</p>
            </li>
            <li>
                <p class="title"><input type="radio" name="subscription" value="pro"
                     <?php echo ($subscription == 'pro')? 'checked=""' : ''; ?> > Bugkick Pro</p>
                <p>$9.00 per month or $98.00 per year</p>
                <p>More features and +20 charisma.</p>
                <p>Closed tickets kept indefinitely</p>
                <p>Website bug submission</p>
                <p>API Access</p>
                <p>GitHub Integration</p>
                <p>Custom logo</p>
                <p>No Ads</p>
            </li>

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
        </ul>
    </div>
    <div class="clear"></div>


<?php $this->endWidget(); ?>

