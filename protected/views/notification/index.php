<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/timeline-style.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.masonry.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/doT.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/timeline-updates.js');
Yii::app()->clientScript->registerCssFile('https://fonts.googleapis.com/css?family=Handlee');
?>

<?php
$this->setPageTitle(Yii::t('main', 'Updates'));
$this->breadcrumbs = array(
    'Updates',
);
?>
<div id="containertop" style="min-height:500px;">
    <div id="container">        
        <div class="timeline_container">
            <div class="timeline">
                <div class="plus"></div>
            </div>
            <div id="removeMe" class="item dateItem">
                <div class="dateText">                  
                </div>    
            </div>
        </div>
    </div>
</div>

<!-- Templates for Updates screen go here, these are loaded by doT.js -->
<script id="notificationFn" type="text/TemplateFn">
    <div class="item">
        <?php
        switch (Yii::app()->params['storageType']) {
            case 's3':
                ?>
                <img src="{{? it.profile_img && it.profile_img !=0 }}https://<?php echo S3Storage::PROFILE_BUCKET . '.' . Storage::get('s3')->endPoint . '/31_31_' ?>{{=it.profile_img}}
                     {{??}}
                     /images/profile_img/default.jpg
                     {{?}}" class="bug-profile-pic" />
                     <?php
                     break;
                 case 'local':
                     ?>
                <div class='bug-profile-pic-wrapper'><img src="/images/profile_img/{{=it.profile_img || "default.jpg" }}" class="bug-profile-pic "/></div>
                <?php
                break;
        }
        ?>
             <div class="infoBox">            
            {{=it.name}}: {{=it.content}}
            <span class="dateTimeLeft">
                {{=it.date}}
            </span>
            <span class="endMessage"></span>  
        </div> 
    </div>
</script>

<script id="dateFn" type="text/TemplateFn">
    <div class="item dateItem">
        <div class="dateText">
            {{=it.date}}    
        </div>    
    </div>
</script>

<script id="todaysDateFn" type="text/TemplateFn">
    <div class="item dateItem">
        <div class="dateText">
            {{=it.date}} <span class="todaysDate">- today</span>
        </div>
    </div>
</script>

<script id="titleFn" type="text/TemplateFn">
    <h2 class="listing-title">Updates</h2>
    <div class="item dummyItem" id="dummyItem">
    </div>
</script>
<!-- end templates -->

<?php
$this->beginWidget(
    'zii.widgets.jui.CJuiDialog',
    array(
        'id'=>'project-form-dialog',
        'options'=>array(
            'title'=>'Edit Project',
            'autoOpen'=>false,
//    			'width'=>565,
            //'height'=>440,
            'modal'=>true,
            'hide'=>'drop',
            'show'=>'drop',
            'buttons'=>array(
                'Save'=>'js:submitProjectForm',
                //'Cancel'=>'js:closeDialog',
            ),
        )
    )
);
$this->endWidget();
?>