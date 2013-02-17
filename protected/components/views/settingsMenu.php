<ul id="right_menu">
	<li><?php echo CHtml::link(Yii::t('main','Settings'), CHtml::normalizeUrl(array('settings/')),
                   ($controllerId == 'settings' && $actionId == 'index')? array('class'=>'active') : array()
    ) ?></li>
	<li><?php echo CHtml::link(Yii::t('main','Email Preferences'), CHtml::normalizeUrl(array('settings/email-preferences')),
                   ($controllerId == 'settings' && $actionId == 'emailPreferences')? array('class'=>'active') : array()
    ) ?></li>
	<li><?php echo CHtml::link(Yii::t('main','View Profile'), CHtml::normalizeUrl(array('user/view')),
                   ($controllerId == 'user' && $actionId == 'view')? array('class'=>'active') : array()
    ) ?></li>
	<li><?php echo CHtml::link(Yii::t('main','Edit Labels'), CHtml::normalizeUrl(array('settings/labelListing')),
                   ($controllerId == 'settings' && $actionId == 'labelListing')? array('class'=>'active') : array()
    ) ?></li>
    <li><?php echo CHtml::link(Yii::t('main','Edit Feedback'), CHtml::normalizeUrl(array('settings/editFeedback')),
                   ($controllerId == 'settings' && $actionId == 'editFeedback')? array('class'=>'active') : array()
    ) ?></li>
	<li><?php echo CHtml::link(Yii::t('main','Edit Status'), CHtml::normalizeUrl(array('settings/statusListing')),
                   ($controllerId == 'settings' && $actionId == 'statusListing')? array('class'=>'active') : array()
    ) ?></li>
	<li><?php echo CHtml::link(Yii::t('main','Members'), CHtml::normalizeUrl(array('settings/members')),
                   ($controllerId == 'settings' && $actionId == 'members')? array('class'=>'active') : array()
    ) ?></li>
	<li><?php echo CHtml::link(Yii::t('main','Groups'), CHtml::normalizeUrl(array('settings/groups')),
                   ($controllerId == 'settings' && $actionId == 'groups')? array('class'=>'active') : array()
    ) ?></li>
    <?php
    /*
    <li><?php echo CHtml::link(Yii::t('main','Projects'), CHtml::normalizeUrl(array('settings/projects')),
                   ($controllerId == 'settings' && $actionId == 'projects')? array('class'=>'active') : array()
    ) ?></li>
     */
    ?>
    <li><?php echo CHtml::link(Yii::t('main','Company Settings'), CHtml::normalizeUrl(array('settings/company')),
                       ($controllerId == 'settings' && $actionId == 'company')? array('class'=>'active') : array()
    ) ?></li>
    <?php
    if(empty(User::current()->githubUser)) {
    $company = Company::model()->findByPk(Company::current());
    if(!empty($company) && $company->isGitHubIntegrationAvailable()) {
    ?>
    <li><?php echo CHtml::link(Yii::t('main','Connect to GitHub'),
            CHtml::normalizeUrl(array('github/auth'))
    ); ?></li>
    <?php
        }
    }
    ?>
</ul><!-- #right_menu -->
