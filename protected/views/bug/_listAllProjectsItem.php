<div class="b_project_name disabled" project_id="<?php echo $data->project->project_id ?>"><div><?php echo $data->project->name ?></div><hr></div>
<div>
	<a href="<?php echo '/'.$data->project->project_id.'/'.$data->number ?>">
		<div class="t_status"<?php echo (!empty($data->status->status_color))
		              			   ? ' style="background: ' . $data->status->status_color . '"'
		              			   : '' ;
							 ?>></div>
		<div class="t_number">#<?php echo $data->number ?></div>
		<div class="t_text"><?php
			$iTextLBeg = 60;
			$iTextLEnd = 90;
			$iTStrLen  = strlen($data->title);
			$iDStrLen  = strlen($data->description);
			$sTitle = str_replace("&#133;", "", $data->title);
			$sTitle = ( $iTStrLen <= $iTextLBeg ) ? trim($sTitle) : trim(substr($sTitle, 0, $iTextLBeg));
			$sTitle .= ( (substr($sTitle, strlen($sTitle) - 3) == '...') || (trim($data->title) == trim($data->description) ) ) ? '' : '...';
			$sDescr = '';
			if( trim($data->title) != trim($data->description) ){
				if( $iDStrLen <= $iTextLEnd )
					$sDescr = trim($data->description);
				else
					$sDescr = trim(substr($data->description, $iDStrLen - $iTextLEnd));
			}
			echo $sTitle . '<span>' . $sDescr . '</span>';
		?></div>
	</a>
</div>