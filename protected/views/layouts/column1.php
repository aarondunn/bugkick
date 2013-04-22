<?php $this->beginContent('//layouts/main'); ?>
    <div <?php if(!($this->getId() == 'site' && $this->getAction()->getId() == 'login')) echo 'id="main_wide"'?>>
         <?php $this->renderPartial('application.views.site._menu'); ?>
        <div class="wide_content_wraper">
         <?php echo $content; ?>
        </div>    
    </div>
<?php $this->endContent(); ?>