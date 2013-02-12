// JavaScript Document

$(document).ready(function(){
	$('#feature-01, #feature-02, #feature-03').accordion({autoheight: false, alwaysOpen: false, active: false});
	
	$("a[rel^='gallery-set']").prettyPhoto({
		animation_speed: 'normal',
		slideshow: false,
		autoplay_slideshow: false,
		opacity: 0.80,
		show_title: true,
		allow_resize: false,
		social_tools: false
	});
	
	$(".gallery-list img").hover(
	function() {$(this).stop().animate({"opacity": "0.2"}, "normal");},
	function() {$(this).stop().animate({"opacity": "1"}, "normal");});
	
	$(".gallery ul li:last-child").css({'margin':'0px 0px 0px 0px'});
	$(".testimonials ul li:last-child").css({'border-right':'none'});

});



