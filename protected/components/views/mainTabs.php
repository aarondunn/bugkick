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