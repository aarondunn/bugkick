<?php /* @var $this BaseForumController */ ?>
<ul class="breadcrumb">
    <?php
    $lastIndex = count($this->breadcrumbs) - 1;
    $i = 0;
    foreach($this->breadcrumbs as $crumb => $url) {
        if(is_int($crumb)) {
    ?>
    <li class="active"><?php echo $this->breadcrumbs[$crumb]; ?></li>
        <?php if($i < $lastIndex) { ?> <span class="divider">/</span><?php } ?>
    <?php
        } else {
    ?>
    <li>
        <a href="<?php echo CHtml::normalizeUrl($url); ?>"><?php echo $crumb; ?></a>
            <?php if($i < $lastIndex) { ?> <span class="divider">/</span><?php } ?>
    </li>
    <?php
        }
        $i++;
    }
    ?>
</ul>