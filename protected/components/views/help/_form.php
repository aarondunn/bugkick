<div class="form ui-dialog-titlebar">
<?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'help-form',
            'action'=> CHtml::normalizeUrl(array('/site/articles')),
            'enableAjaxValidation' => false
        ));
?>
    <div class="form-container">
        <div class="row search">
            <?php echo CHtml::link(' ','#',array('class'=>'help-back-link'))?>
            <label>Search: </label>
            <?php echo CHtml::textField('helpSearch', '', array('id'=>'help-search','placeholder'=>'Search...')) ?>
            <a href="#" title="Cancel Searching" id="cancel-search">Cancel Searching</a>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><!--form -->