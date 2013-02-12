var baseUrl = window.location.protocol + '//' + window.location.hostname;
$(function() {
	var lafHiddenField = $('#User_look_and_feel'),
		styleTag = $('#style_look_and_feel');
	if(styleTag.length == 0)
		styleTag = null;
	var samples = $('#laf-choice div.laf-sample');
	samples.click(function() {
		var sample = $(this),
			cssFile = sample.attr('name'),
			lafName = sample.attr('title'),
			href = baseUrl + '/css/body/' + cssFile;
		lafHiddenField.val(lafName);
		if(styleTag !== null && styleTag.attr('href') == href)
			return;
		setLookAndFeel();
		function setLookAndFeel() {
			$.post('',
				{
					'User' : {
						'look_and_feel' : lafName
					},
                    'YII_CSRF_TOKEN':YII_CSRF_TOKEN
				},
				function(data) {
					if(data != 'Saved')
						return;
					if(styleTag === null) {
						$('html head link:last').after(
							'<link rel="stylesheet" type="text/css" id="style_look_and_feel" href="' + href + '" />'
						);
						styleTag = $('#style_look_and_feel');
					}
					else
						styleTag.attr('href', href);
				}
			);
		}
	});
});