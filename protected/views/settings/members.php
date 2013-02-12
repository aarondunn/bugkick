<?php
$this->breadcrumbs = array(
    'Members',
);
?>
<div class="settings">
    <div class="members-container">
        <div id="active-members">
            <h2 class="listing-title"><?php echo Yii::t('main', 'Members'); ?></h2>

            <?php $this->renderFlash(); ?>

            <?php if(!empty($userProvider)): ?>
                <?php $this->renderPartial('_users',array(
                    'model' => $userProvider,
                )); ?>
            <?php else: ?>
                <p>No Users</p>
            <?php endif ?>
        </div>

        
        <?php if( $pendingUserProvider->getTotalItemCount() > 0 ): ?>
            <div id="pending-members">
                <h2 class="listing-title"><?php echo Yii::t('main', 'Pending Members'); ?></h2>
                <?php $this->renderPartial('_usersPending',array(
                    'model' => $pendingUserProvider,
                )); ?>
            </div>
        <?php endif ?>

        
        <?php /*if( $deletedUserProvider->getTotalItemCount() > 0 ): ?>
            <div id="deletet-members">
                <h2 class="listing-title <?php if($deletedUserProvider->getTotalItemCount() == 0) echo 'deleted-title'; ?>">
                    <?php echo Yii::t('main', 'Deleted Members'); ?>
                </h2>
                <?php $this->renderPartial('_usersDeleted',array(
                    'model' => $deletedUserProvider,
                )); ?>
            </div>
        <?php endif */ ?>
    </div>

    <?php
    $project = Project::getCurrent();
    if (!empty($project))
        $this->widget('InviteMember');
    ?>

</div>