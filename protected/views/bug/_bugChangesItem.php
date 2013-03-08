<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 15.11.12
 * Time: 0:32
 */
?>
<ul class="ticket_changes">
       <li class="photo thumb">
           <?php
               if(!empty($data->user)) {
                   echo CHtml::link(
                       '<img src="'.$data->user->getImageSrc(31,31).'" class="bug-profile-pic" />',
                       array('user/view', 'id'=>$data->user_id)
                   );
               }
               else{
                   echo '<img src="'. ImageHelper::thumb( 31, 31, 'images/profile_img/default.jpg', 85 ).'" class="bug-profile-pic" title="Deleted" />';
               }
           ?>
       </li>
       <li class="info">
           <?php if (!empty($data->user)){ ?>
            <a class="name" href="<?php echo Yii::app()->createUrl("user/view", array('id'=>$data->user_id))?>">
              <?php echo CHtml::encode($data->user->name); ?>
            </a>
            <?php
                }
                elseif($data->user_id == 0){
                    echo '<strong>API User</strong>';
                }
                else {
                    echo '<strong>Deleted</strong>';
                }
            ?>
            <?php echo $data->change; ?>
       </li>
       <div class="clear"></div>
</ul><!-- .ticket_changes -->