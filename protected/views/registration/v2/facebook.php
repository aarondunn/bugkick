<div style="padding: 15px; border: 1px solid #8294BC; box-shadow: 0 0 10px rgba(126,144,186,0.5); width: 680px; margin: 0 auto;" class="round10">
	<div class="form" style="text-align: center; width: 660px; margin: 0 auto;">
		<h2 style="text-align: left; color:#8294BC;"><img alt="Registration with Facebook" src="/themes/bugkick_theme/images/facebook/facebook_48x48.png" style="margin-right: 7px; vertical-align: bottom;" />Registration with Facebook</h2>
		<form name="formRegisterChoice" action="" method="post">
			<!--	Facebook register		-->
			<div id="fb-root"></div>
			<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#appId=<?php echo Yii::app()->params['facebook']['appId'] ?>&xfbml=1"></script>
			<fb:registration
				fields="[
					{'name':'name'},
					{'name':'first_name'},
					{'name':'last_name'},
					{'name':'email'},
					{'name':'password'},
					{'name':'company_name', 'description':'Company Name', 'type':'text'},
					{'name':'company_url', 'description':'Company URL', 'type':'text'}
				]"
				redirect-uri="<?php echo $this->createAbsoluteUrl('/registration/facebook'); ?>"
				width="650">
			</fb:registration>
			<!--	Facebook register End	-->
		</form>
	</div>
</div>