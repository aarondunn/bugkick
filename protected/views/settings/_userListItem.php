<div class="userItem round3" id="userItem_<?php echo $data->user_id; ?>">
	<div class="userImage round6"><img alt="" src="<?php echo $data->getImageSrc(31, 31); ?>" class="round6" /></div>
	<div class="userName"><a href="<?php echo $this->createUrl('user/view', array('id'=>$data->user_id)); ?>" 
							 target="_blank"><?php echo $data->name . ' ' . $data->lname; ?></a></div>
	<span class="invis user_id"><?php echo $data->user_id; ?></span>
</div>