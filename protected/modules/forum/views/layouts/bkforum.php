<?php /* @var $this BaseForumController */ ?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="left-menu" class="span2">
            <div class="well sidebar-nav">
                <ul class="nav nav-list">
                    <li class="nav-header">Sidebar</li>
                    <li class="active"><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li class="nav-header">Sidebar</li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li class="nav-header">Sidebar</li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                </ul>
            </div><!--/.well -->
        </div><!--/span-->
        <div id="content" class="span8">
            <div class="hero-unit">
                <div class="container">
                    <?php echo $content; ?>
                </div>
            </div>
        </div><!--/span-->
        <div id="right-menu" class="span2">
            <div class="well sidebar-nav">
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
            <div class="well sidebar-nav">
                <?php
                    $this->widget('ForumSearch');
                ?>
                <?php
                    $this->widget('Forums');
                ?>
            </div><!--/.well -->
        </div><!--/span-->
    </div><!--/row-->

    <div id="modal-wnd" class="modal hide">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Modal header</h3>
        </div>
        <div class="modal-body">
            <p>One fine body…</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" class="btn btn-primary">Save changes</a>
        </div>
    </div>
</div><!--/.fluid-container-->
<?php $this->endContent(); ?>