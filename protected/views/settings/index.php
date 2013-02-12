<?php
$this->breadcrumbs = array(
    'Settings',
);
?>
<?php $this->renderFlash(); ?>
<?php
Yii::app()->clientScript->registerScript('passwordChange', '
    $("#btn_change_password").click(function(){
        $("#password-form").css("display", "block");
        $("#passwordDialog").dialog("open");
    });

', CClientScript::POS_READY);
?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'passwordDialog',
    'options' => array(
        'title' => 'Change Password',
        'autoOpen' => false,
        'modal' => true,
        'hide'=>'drop',
		'show'=>'drop',
        'buttons' => array(
        //'Cancel'=>'js:function(){ $(this).dialog("close");}',
        //'Save'=>'js:savePassword',
        ),
    ),
));
?>

<div class="form">
    <?php echo $passwordForm; ?>
</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<div class="settings">
    <div class="settings-header">
	   <h2>Settings</h2>
	   <a href="#" id="btn_change_password"><?php echo Yii::t('main', 'Change Password'); ?></a>
    </div>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'user-form',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'action' => $this->createUrl($this->getId() . '/')
            ));
    ?>
	<div class="inner">
		<div class="fl">
			<span class="photo medium">
                <img id="profilepic" alt="" src="<?php echo $userModel->getImageSrc(81, 81); ?>" />
			</span>
            <?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
                  array(
                    'id'=>'btn_upload',
                    'config'=>array(
                           'action'=>$this->createUrl('settings/uploadPhoto', array('type'=>'image')),
                           'allowedExtensions'=>array("jpg","jpeg","gif","png"),
                           'sizeLimit'=>2*1024*1024,// maximum file size in bytes
                          // 'minSizeLimit'=>10*1024*1024,// minimum file size in bytes
                           'onComplete'=>"js:function(id, fileName, responseJSON){ if(responseJSON.filename !='undefined'){  $('#profilepic').attr('src', responseJSON.filename); $('#profilepic').attr('width', '81'); }   }",
            //               'messages'=>array(
            //                                 'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
            //                                 'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
            //                                 'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
            //                                 'emptyError'=>"{file} is empty, please select files again without it.",
            //                                 'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
            //                                ),
            //               'showMessage'=>"js:function(message){ alert(message); }"
                          )
            )); ?>
		</div>
		<div class="fr">
			<ul>
                <li class="email_address">
                    <span class="label"><?php echo $form->labelEx($userModel, 'name'); ?> :</span>
                    <span class="textbox_wrapper">
                        <?php echo $form->textField($userModel, 'name'); ?>
                        <?php echo $form->error($userModel, 'name'); ?>
                    </span>
                </li>
                <li class="email_address">
                    <span class="label"><?php echo $form->labelEx($userModel, 'lname'); ?> :</span>
                    <span class="textbox_wrapper">
                        <?php echo $form->textField($userModel, 'lname'); ?>
                        <?php echo $form->error($userModel, 'lname'); ?>
                    </span>
                </li>
				<li class="email_address">
					<span class="label"><?php echo $form->labelEx($userModel, 'email'); ?> :</span>
					<span class="textbox_wrapper">
                        <?php echo $form->textField($userModel, 'email'); ?>
                        <?php echo $form->error($userModel, 'email'); ?>
					</span>
				</li>
				<li class="use_shortcuts">
					<span class="label"><?php echo Yii::t('main', 'Enable Shortcuts')?>:</span>
                    <span class="checkbox-container">
                        <?php echo CHtml::activeCheckBox($userModel, 'hotkey_preference', array('class'=>'iPhone-checkbox')) ?>
                        <?php echo $form->error($userModel, 'hotkey_preference'); ?>
                    </span>

                   <!--<div id="toggle_wrapper" class="on" onclick="turnOnOff();">
						<span id="toggle_edge"></span>
						<span id="toggle_pos"></span>
					</div>-->

                    <a href="#" onclick="$('#shortcuts').css('display', 'block'); return false;" class="show_shortcuts fr">
                        <?php echo Yii::t('main', 'Show Shortcuts')?>
                    </a>
				</li>

                <li class="use_shortcuts">
                    <span class="label long-title"><?php echo Yii::t('main', 'Enable WYSIWYG editor for comments')?>:</span>
                    <span class="checkbox-container">
                        <?php echo CHtml::activeCheckBox($userModel, 'use_wysiwyg', array('class'=>'iPhone-checkbox')) ?>
                        <?php echo $form->error($userModel, 'use_wysiwyg'); ?>
                    </span>
                </li>

<!--				<li class="default_assignee">
					<span class="label"><?php /*echo $form->labelEx($userSettings, 'defaultAssignee'); */?>:</span>
					<span class="selectbox">
                        <?php /*echo CHtml::activeDropDownList(
                            $userSettings,
                            'defaultAssignee',
                            CHtml::listData(Company::getUsers(), 'user_id', 'name'),
                            array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
                        ) */?>
                        <?php /*echo $form->error($userSettings, 'defaultAssignee'); */?>
					</span>
				</li>-->
<!--				<li class="default_company">
					<span class="label"><?php /*echo $form->labelEx($userSettings, 'defaultCompany'); */?>:</span>
					<span class="selectbox">
                        <?php /*echo CHtml::activeDropDownList(
                                            $userSettings,
                                            'defaultCompany',
                                            CHtml::listData($userModel->company, 'company_id', 'company_name'),
                                            array('prompt'=>'Please select company', 'class'=>'chzn-select selectbox',)
                                        ) */?>
                        <?php /*echo $form->error($userModel, 'defaultCompany'); */?>
					</span>
				</li>-->
<!--				<li class="default_status">
					<span class="label"><?php /*echo $form->labelEx($userSettings, 'defaultStatus'); */?>:</span>
					<span class="selectbox">
                        <?php /*echo CHtml::activeDropDownList(
                                            $userSettings,
                                            'defaultStatus',
                                            CHtml::listData(Company::getStatuses(), 'status_id', 'label'),
                                            array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
                                        ) */?>
                        <?php /*echo $form->error($userModel, 'defaultStatus'); */?>
					</span>
				</li>
				<li class="default_label">
					<span class="label"><?php /*echo $form->labelEx($userSettings, 'defaultLabel'); */?>:</span>
					<span class="selectbox">
                        <?php /*echo CHtml::activeDropDownList(
                                            $userSettings,
                                            'defaultLabel',
                                            CHtml::listData(Company::getLabels(), 'label_id', 'name'),
                                            array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox',)
                                        ) */?>
                        <?php /*echo $form->error($userSettings, 'defaultLabel'); */?>
					</span>
				</li>-->
				<li class="tickets_per_page">
					<span class="label"><?php echo $form->labelEx($userModel, 'tickets_per_page'); ?>:</span>
					<span class="textbox_wrapper thin">
                        <?php echo $form->textField($userModel, 'tickets_per_page'); ?>
                        <?php echo $form->error($userModel, 'tickets_per_page'); ?>
					</span>
				</li>
				<li class="ticket_update_return">
					<span class="label"><?php echo $form->labelEx($userModel, 'After updating a ticket'); ?>:</span>
					<span class="selectbox">
                        <?php echo CHtml::activeDropDownList(
                            $userModel,
                            'ticket_update_return',
                            User::getTicketUpdRtnOptions(),
                            array('prompt'=>'&nbsp;', 'class'=>'chzn-select selectbox')); ?>
                        <?php echo $form->error($userModel, 'ticket_update_return'); ?>
					</span>
				</li>
				<li class="choose_theme">
                    <?php echo $form->hiddenField($userModel, 'look_and_feel'); ?>
					<span class="label"><?php echo $form->labelEx($userModel, 'look_and_feel'); ?>:</span>
                    <div id="laf-choice">
                        <div class="laf-sample round5 default-style" title="Default" name="body__default.css"></div>
                <?php
                   $i=1;
                   foreach($lafSet as $laf) {
                ?>
                        <div class="laf-sample"
                                title="<?php echo $laf->name; ?>"
                                name="<?php echo $laf->css_file; ?>"
                                style="background-image: url('/images/body_backgrounds/<?php echo $laf->img_preview; ?>')">
                        </div>
                <?php if(++$i%3==0) { ?>
                        <div class="clear"></div>
                <?php } ?>
                <?php
                   }
                ?>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
				</li>
				<li class="save">
                    <?php
                        echo CHtml::ajaxLink(
                            'Save',
                            $this->createUrl($this->getId() . '/'),
                            array(
                                'type'=>'POST',
                                //'update'=>'#output',
                                'beforeSend'=>'js:function() {$.flashMessage().beginProgress();}',
                                'success'=>'js:function(data) {$.flashMessage().message(data);}',
                            ),
                            array(
                                'class'=>'bkButtonBlueSmall normal',
                                'style'=>'float:right'
                            )
                        );
                    ?>
                    <?php echo CHtml::link(
                        Yii::t('main','Export Tickets to XLS'),
                        'settings/exportTickets',
                        array(
                            'target'=>'_blank',
                            'class'=>'bkButtonBlueSmall normal fr',
                            'style'=>'margin-right:10px')
                        );
                    ?>
				</li>
			</ul>
		</div>
		  <div class="clear"></div>
        <div id="output"></div>
	</div><!-- .inner -->

    <?php $this->endWidget(); ?>

</div><!-- .settings -->
