<h2>Bcrypt speed test</h2>
<div class="form">
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id'=>$formID,
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    )
));
?>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'password'),
            $form->textField($model, 'password'),
            $form->error($model, 'password');
        ?>
    </div>
    <div class="clear"></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'workFactor'),
            $form->textField($model, 'workFactor'),
            $form->error($model, 'workFactor');
        ?>
    </div>
    <div class="clear"></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'hash'),
            $form->textField($model, 'hash', array(
                'readonly'=>'readonly;'
            )),
            $form->error($model, 'hash');
        ?>
    </div>
    <div class="clear"></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'hashTime'),
            $form->textField($model, 'hashTime', array(
                'readonly'=>'readonly'
            )),
            $form->error($model, 'hashTime');
        ?>
    </div>
    <div class="clear"></div>
    <div class="row">
        <?php
        echo
            $form->labelEx($model, 'checkTime'),
            $form->textField($model, 'checkTime', array(
                'readonly'=>'readonly'
            )),
            $form->error($model, 'checkTime');
        ?>
    </div>
   <div class="clear"></div>
   <div class="row buttons w125">
       <script type="text/javascript">
           function onTestFormSubmitSuccess(d) {
               $('#<?php echo $formID; ?>').parent('div.form').parent('div').html(d);
               $('#loader').css('visibility', 'hidden');
           }
       </script>
       <?php
       echo CHtml::ajaxSubmitButton(
               'Test Hash',
               $this->createUrl('', array('testHash'=>1)),
               array(
                   'success'=>"onTestFormSubmitSuccess",
               ),
               array('class'=>'bkButtonBlueSmall medium submit')
       );
        echo CHtml::ajaxSubmitButton(
            'Test Check',
            $this->createUrl('', array('testHash'=>0)),
            array(
                'success'=>"onTestFormSubmitSuccess",
            ),
            array('class'=>'bkButtonBlueSmall medium submit', 'id'=>'test-check-btn')
        );
       ?>
       <script type="text/javascript">
           <?php if(!empty($model->password) && !empty($model->hash)) { ?>
               $('#test-check-btn').removeAttr('disabled');
           <?php } else { ?>
               $('#test-check-btn').attr('disabled', 'disabled');
           <?php } ?>
               $('.submit').on('click', function() {
                   $('#loader').css('visibility', 'visible');
               });
               $('#EncryptionTestForm_password').focus();
       </script>
   </div>
   <div class="clear"></div>
   <div class="row al-center w125">
       <img src="<?php echo $this->request->baseUrl; ?>/images/ajax-loader.gif" style="visibility: hidden;" id="loader" />
   </div>
   <div class="clear"></div>
<?php $this->endWidget(); ?>
</div>