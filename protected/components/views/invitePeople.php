<div class="form invite-members" style="margin: 5px">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'invite-people-form',
        'action' =>Yii::app()->createUrl('user/invitePeople'),
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
    ));
    ?>
    <div class="row">
        <span class="invites-text">Invite a friend, give them 1 year of pro free!</span>
        <?php echo $form->textField($model, 'email', array('style'=>'width: 175px', 'placeholder'=>'Email'));  ?>
        <a href="#" title="Hide" class="cancel-search" style="left:-9px;top:9px;float: right;position: relative">Hide</a>
        <?php echo $form->error($model, 'email'); ?>
        <div class="clear"></div>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('main', 'Invite'), array (
                'class'=>'bkButtonBlueSmall normal',
                'style'=>'width:70px',
        ));
        ?>
        <?php if($this->settings->invites_limit==true):?>
        <div style="float: right; margin: 10px" class="invites-text">
            <span class="invites-left"><?php echo $this->invitesLeft; ?></span> invites left
        </div>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<?php Yii::app()->clientScript->registerScript('hide-invites',
    "if (typeof(localStorage) != 'undefined' && localStorage.getItem('inviteMembersHidden') == 1){
        $('.invite-members').hide();
    }
    $('a.cancel-search').on('click',function(){
        if(typeof(localStorage) != 'undefined' ) {
            $('.invite-members').hide();
            localStorage.setItem('inviteMembersHidden', '1');
        }
    });",
    ClientScript::POS_READY);
?>