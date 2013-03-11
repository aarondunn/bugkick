$(function() {
	$(".b_options .b_position ul li").click(function(){
		$(".b_options .b_position ul").find("li.active").first().removeClass("active");
		$(this).addClass("active");
		$("#hf_position").val($(this).attr("value"));
	});
	
	$(".b_options .b_style ul li").click(function(){
		$(".b_options .b_style ul").find("li.active").first().removeClass("active");
		$(this).addClass("active");
		$("#hf_style").val($(this).attr("value"));
	});
	
	$(".b_options .b_color ul li").click(function(){
		$(".b_options .b_color ul li").find("div.active").first().removeClass("active");
		$(this).find("div").first().addClass("active");
		$("#hf_color").val($(this).attr("value"));
		
		$(".b_panel ul.b_styles").find("li.active").first().removeClass("active");
		$sColorName = $(this).attr('class').substr(2);
		$(".b_panel ul.b_styles").find("li.b_" + $sColorName).first().addClass("active");
	});
    var currentStyle = '322';
    $('.settings a#feedback_code').on('click',function() {
        var selectedStyle = $('#hf_position').val()
            + $('#hf_style').val()
            + $('#hf_color').val();
        $('.apikey-textarea').val($('.apikey-textarea').val().replace(currentStyle,selectedStyle));
        currentStyle = selectedStyle;
        return false;
    });
});