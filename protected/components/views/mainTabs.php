<div id="tabed-nav">
    <ul>
        <?php foreach($this->tabs as $tab) { ?>
        <li <?php if(Yii::app()->getRequest()->getUrl() == $tab['url']) { ?>class="current"<?php } ?>>
        <?php
        echo CHtml::link(Yii::t('main', $tab['text']), $tab['url'], array(
            'title'=>$tab['title'],
            'id'=>$tab['id'],
            'class'=>isset($tab['class'])?$tab['class']:'',
        ));
        ?>
        </li>
        <?php } ?>
    </ul>
</div>
<?php
if(Yii::app()->controller->id=='notification' ||
    (Yii::app()->controller->id=='bug' && Yii::app()->request->getParam('show')=='calendar') ||
    (Yii::app()->controller->id=='project' && Yii::app()->controller->action->id=='people')){
        Yii::app()->clientScript->registerCss(
            'tab-margin',
            '#content #tabed-nav{margin-right:10px;}'
        );
}
?>