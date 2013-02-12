<li> 
	<?php echo Chtml::link(
	    Helper::neatTrim($data->title,18),
	    array(
	        '/site/getArticle',
	        'id'=>$data->id,
	    ),
	    array(
	        'class'=>'article-link',
		));
	?>
</li>