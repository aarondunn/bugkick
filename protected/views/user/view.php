<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
    $model->name . ' ' . $model->lname,
);
/*
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->user_id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
*/
$model->defaultCompany;
$this->loadModel(Yii::app()->user->id)->defaultCompany;
?>
<div class="settings">
    <h2 class="title">
        <?php echo Yii::t('main', 'Profile'); ?>
    </h2>
    <div class="profile-content">
        <div class="profile-photo">
            <img width="81" alt="<?php echo $model->name . ' ' . $model->lname; ?>" src="<?php echo $model->getImageSrc(81,81,100); ?>" />
        </div>
        <div class="profile-description">
          <div id="name">
            <div id="first-name">
              <?php echo Yii::t('main', 'First Name') ?>:
              <span class="desc"><?php echo $model->name  ?></span>
            </div>
            <div id="last-name">
              <?php echo Yii::t('main', 'Last Name') ?>:
              <span class="desc"><?php echo $model->lname; ?></span>
            </div>
          </div>
          <div>
            <?php echo Yii::t('main', 'Email') ?>:
            <span class="desc"><?php echo $model->email ?></span>
          </div>
          <div>
            <?php echo Yii::t('main', 'Created') ?>:
            <span class="desc"><?php echo Helper::formatDateLongWithTime($model->created_at) ?></span>
          </div>        
        </div>
        <?php if($model->defaultCompany == $this->loadModel(Yii::app()->user->id)->defaultCompany):?>
        <div class="user-activity">
            <h2><?php echo Yii::t('main', 'Last Activity') ?></h2>

            <h3><?php echo Yii::t('main', 'Tickets') ?>:</h3>
            <div>
                <?php
                   if (!empty($activity['tickets'])){
                       $out = '<div class="activity-list"><ul>';
                       foreach($activity['tickets'] as $key=>$value){
                           $out .= '<li>
                             <div class="activity-date">' . Helper::formatDate12($value['created_at']) . '</div>
                             <div class="ticket-data">
                               <div class="item-name">'.  Yii::t('main', 'Ticket') .': <a href="/'.$value->project->project_id.'/'.$value->number.'">' . strip_tags($value['title']) . '</a></div>
                               <div class="activity-desc">' . Helper::neatTrim($value['description'], 120) . '</div>
                             </div>
                           </li>';
                       }
                       $out .= '</ul></div>';
                   }
                   else{
                       $out = Yii::t('main', 'No Tickets');
                   }
                   echo  $out;
                ?>
            </div>

            <h3><?php echo Yii::t('main', 'Comments') ?>:</h3>
            <div>
                <?php
                   if (!empty($activity['comments'])){
                       $out = '<div class="activity-list"><ul>';
                       foreach($activity['comments'] as $key=>$value){
                           $out .= '<li>
                             <div class="activity-date">' . Helper::formatDate12($value['created']) . '</div>
                             <div class="ticket-data">
                               <div class="item-name">'.  Yii::t('main', 'Comment') .': ' . strip_tags(Helper::neatTrim($value['message'], 100)) . '</div>
                               <div class="item-name">'.  Yii::t('main', 'On Ticket') .': <a href="/'.$value['project_id'].'/'.$value['number'].'">' . strip_tags($value['title']) . '</a></div>
                             </div>
                           </li>';
                       }
                       $out .= '</ul></div>';
                   }
                   else{
                       $out = Yii::t('main', 'No Comments');
                   }
                   echo  $out;
                ?>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>
