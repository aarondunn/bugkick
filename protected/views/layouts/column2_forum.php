<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 23.11.12
 * Time: 22:35
 */
Yii::app()->clientScript->registerCssFile(
    Yii::app()->theme->baseUrl.'/css/forum.css'
);

$this->beginContent('//layouts/main'); ?>
<div id="main">
    <!-- <div class="main_top"></div> -->
    <div class="main_middle">
        <div class="forum-container clearfix">
            <?php echo $content; ?>
        </div>
    </div>
    <!-- .main_middle -->
    <!-- <div class="main_bottom"></div> -->
</div>

<div id="sidebar">
    <!-- <div class="sidebar_top"></div> -->
    <div class="sidebar_middle">
        <div class="sidebar-nav">
            <?php
                $this->widget('ForumSearch');
            ?>
            <?php
                $this->widget('Forums');
            ?>
        </div><!--/.well -->
        <div class="sidebar-nav">
            <?php
                array_unshift($this->menu, array('label'=>Yii::t('main','Menu')));
                $this->widget('zii.widgets.CMenu', array(
                    'items'=>$this->menu,
                    'activeCssClass'=>'active',
                    'firstItemCssClass'=>'nav-header',
                    'htmlOptions'=>array('class'=>'nav nav-list'),
                ));
            ?>
        </div><!--/.well -->
    </div>
    <!-- .sidebar_middle -->
    <!-- <div class="sidebar_bottom"></div> -->
</div><!-- sidebar -->
<?php $this->endContent(); ?>