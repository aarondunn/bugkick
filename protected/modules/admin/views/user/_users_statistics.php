<div class="form">
    <?php foreach($usersStats->getAttributes() as $attribute => $value) { ?>
    <div class="row stat-item">
        <span class="stat-label">
            <?php echo $usersStats->getAttributeLabel($attribute); ?>:
        </span>
        &nbsp;
        <span class="stat-value"><?php echo $value ?></span>
    </div>
    <div class="clear"></div>
    <?php } ?>
    <div class="row stat-item">
        <?php
        $this->renderPartial('_recent_users_grid', array(
            'model'=>$model,
            'daysCount'=>$daysCount,
        ));
        ?>
    </div>
    <div class="clear"></div>
</div>