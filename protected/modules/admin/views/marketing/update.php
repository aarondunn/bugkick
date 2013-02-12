<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 27.11.12
 * Time: 23:38
 */

$this->menu=array(
	array('label'=>'Back', 'url'=>array('/admin/marketing/')),
);
?>

<div id="container" class="settings">
    <h2 class="title"><?php echo Yii::t('main', 'Marketing Settings'); ?></h2>
    <div class="admin_content">
        <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>