<?php $this->beginContent('//layouts/main'); ?>
    <div <?php if(!($this->getId() == 'site' && $this->getAction()->getId() == 'login')) echo 'id="main_wide"'?>>
         <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>