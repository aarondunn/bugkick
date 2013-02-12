<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 23.12.11
 * Time: 19:28
 */

$this->breadcrumbs = array(
    'Company',
);
?>
<div class="settings">

<h2><?php echo Yii::t('main', 'Company Settings'); ?></h2>

<?php $this->renderFlash(); ?>

<div class="company-settings">
    <?php
        if($model->account_type==Company::TYPE_FREE) {
            echo CHtml::link(
                'Upgrade company',
                $this->createUrl('payment/chooseSubscription'),
                array('class'=>'bkButtonBlueSmall medium')
            );
        }
        elseif(!empty($model->stripeCustomer) && !empty($model->stripeCustomer->expires_at)) {
    ?>
	 <h4>
        <?php
            echo Yii::t(
                'main',
                'Your company will automatically downgraded after the end of current paid period at {date}.',
                array('{date}'=>date('Y-m-d', $model->stripeCustomer->expires_at))
            );
        ?>
     </h4>
        <?php
            }
            else {
                echo CHtml::link(
                    'Change subscription',
                    //$this->createUrl('payment/cancel-subscription'),
                    $this->createUrl('payment/chooseSubscription'),
                    array('class'=>'bkButtonGraySmall medium')
                );
            }
        ?>
	 <div class="generate-api-key">
        <?php
            echo  CHtml::link(
                'Get API key and Embed code',
                $this->createUrl('api/key/generate'),
                array('class'=>'bkButtonGraySmall medium')
            );
        ?>
     </div>

     <?php
          $form = $this->beginWidget('CActiveForm', array(
              'id' => 'company-form',
              'enableClientValidation' => true,
              'clientOptions' => array(
                  'validateOnSubmit' => true,
              ),
              'action'=> Yii::app()->createUrl('settings/company', array('id'=>$model->company_id)),
          ));
     ?>

     <?php echo $form->errorSummary($model); ?>

<?php
    if($model->account_type==Company::TYPE_PAY):
?>
     <div class="row-left">
         <?php echo Yii::t('main', 'Choose header logo') ?>:
     </div>
     <div class="row-left">
         <div class="company-logo-top">
               <img id="profilepic" alt="" src="<?php echo $model->getImageSrc(132, 33)?>" />
         </div>
         <?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
             array(
                 'id'=>'btn_upload',
                 'config'=>array(
                     'action' => $this->createUrl('settings/uploadPhoto', array('type' => 'image', 'for' => 'company')),
                     'allowedExtensions' => array("jpg", "jpeg", "gif", "png"),
                     'sizeLimit' => 2 * 1024 * 1024, // maximum file size in bytes
                     'onComplete'=>"js:function(id, fileName, responseJSON){ if(responseJSON.filename !='undefined'){  $('#profilepic').attr('src', responseJSON.filename); $('#profilepic').attr('height', '33'); }   }",
                 )
             ));
         ?>
     </div>
<?php
    endif;
?>

     <div class="clear"></div>

     <div class="row-left">
         <?php echo Yii::t('main', 'Choose header color') ?>:
     </div>
     <div class="row-left">
         <?php
             $this->widget('ext.colorpicker.SActiveColorPicker', array(
             'model' => $model,
             'attribute' => 'company_color',
             'hidden'=>true, // defaults to false - can be set to hide the textarea with the hex
             'options' => array(), // jQuery plugin options
             'htmlOptions' => array(), // html attributes
         ));
          ?>
         <?php echo $form->error($model,'company_color'); ?>
     </div>

     <div class="clear"></div>
     
     <div class="row-left">
         <a href="<?php echo Yii::app()->createUrl("settings/resetCompanyTopBar")?>" onclick="return confirm('Reset header to default?');" >Reset to default colors</a>
     </div>

     <div class="clear"></div>

    <?php
        if($model->account_type == Company::TYPE_PAY) {
    ?>
         <div class="settings-row">
              <div class="row-left">
                   <?php echo $form->labelEx($model, 'show_ads'); ?>:
              </div>
              <div class="row-left">
                   <?php echo CHtml::activeCheckBox($model, 'show_ads', array('class'=>'iPhone-checkbox')) ?>
                   <?php echo $form->error($model, 'show_ads'); ?>
              </div>
         </div>
         <div class="clear"></div>
    <?php
        }
    ?>

     <?php
         echo CHtml::linkButton(
             Yii::t('main', 'Save'),
             array('class'=>'bkButtonBlueSmall normal','style'=>'float:left')
         );
     ?>

     <?php $this->endWidget(); ?>
     <div class="clear"></div>
</div>


</div>
