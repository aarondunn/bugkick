/**
 * @author Alexander Farkas
 * v. 1.22
 */


(function($) {
	if(!document.defaultView || !document.defaultView.getComputedStyle){ // IE6-IE8
		var oldCurCSS = $.curCSS;
		$.curCSS = function(elem, name, force){
			if(name === 'background-position'){
				name = 'backgroundPosition';
			}
			if(name !== 'backgroundPosition' || !elem.currentStyle || elem.currentStyle[ name ]){
				return oldCurCSS.apply(this, arguments);
			}
			var style = elem.style;
			if ( !force && style && style[ name ] ){
				return style[ name ];
			}
			return oldCurCSS(elem, 'backgroundPositionX', force) +' '+ oldCurCSS(elem, 'backgroundPositionY', force);
		};
	}

	var oldAnim = $.fn.animate;
	$.fn.animate = function(prop){
		if('background-position' in prop){
			prop.backgroundPosition = prop['background-position'];
			delete prop['background-position'];
		}
		if('backgroundPosition' in prop){
			prop.backgroundPosition = '('+ prop.backgroundPosition;
		}
		return oldAnim.apply(this, arguments);
	};

	function toArray(strg){
		strg = strg.replace(/left|top/g,'0px');
		strg = strg.replace(/right|bottom/g,'100%');
		strg = strg.replace(/([0-9\.]+)(\s|\)|$)/g,"$1px$2");
		var res = strg.match(/(-?[0-9\.]+)(px|\%|em|pt)\s(-?[0-9\.]+)(px|\%|em|pt)/);
		return [parseFloat(res[1],10),res[2],parseFloat(res[3],10),res[4]];
	}

	$.fx.step. backgroundPosition = function(fx) {
		if (!fx.bgPosReady) {
			var start = $.curCSS(fx.elem,'backgroundPosition');
			if(!start){//FF2 no inline-style fallback
				start = '0px 0px';
			}

			start = toArray(start);
			fx.start = [start[0],start[2]];
			var end = toArray(fx.end);
			fx.end = [end[0],end[2]];

			fx.unit = [end[1],end[3]];
			fx.bgPosReady = true;
		}
		//return;
		var nowPosX = [];
		nowPosX[0] = ((fx.end[0] - fx.start[0]) * fx.pos) + fx.start[0] + fx.unit[0];
		nowPosX[1] = ((fx.end[1] - fx.start[1]) * fx.pos) + fx.start[1] + fx.unit[1];
		fx.elem.style.backgroundPosition = nowPosX[0]+' '+nowPosX[1];

	};
})(jQuery);


$(document).ready(function() {
	if ( $("input.search_box").length ) {
		setFocusInOut($("input.search_box"));
	}
	if ( $("ul.items li .header").length ) {				
		$("ul.items li .header").click(function(){
			var parent = $(this).parents("ul li");
			if ( $(parent).hasClass("expanded") ) {
				$(parent).find(".inside").slideToggle('fast', function() {
					$(parent).removeClass("expanded");
				});
			} else {
				$(parent).find(".inside").slideToggle('fast', function() {
					$(parent).addClass("expanded");
				});
			}
		});		
	}
	$(".textbox_wrapper").click(function(){
		var obj = $(this).find("input[type='text']");
		if ( obj.length ) {
			$(obj).focus();
		}
	});
});

setFocusInOut = function(obj) {
	var init_value = $(obj).val();
	var init_color = $(obj).css("color");
	var new_color = "#666666";
	$(obj).focus(function() {
		if ( $(obj).val() == init_value ) {				
			$(obj).val("");
			$(obj).css("color", new_color);
		}
	});
	$(obj).focusout(function() {			
		if ( $(obj).val() == "" ) {				
			$(obj).val(init_value);
			$(obj).css("color", init_color);
		}
		if ( $(obj).val() == init_value ) {				
			$(obj).css("color", init_color);
		}
	});
}

closePopup = function(obj) {
	$("#create_bug_box").hide(0,function(){
		$("#overlay").hide();
	});
}

turnOnOff = function() {
	if ( $("#toggle_wrapper").hasClass("on") ) {
		//alert($("#toggle_wrapper").css("background-position"));
		// from ( 0px, -59px ) to ( -47px, -59px )			
		$("#toggle_wrapper").css("background-position","0px -59px");
		$("#toggle_wrapper").animate({'backgroundPosition':'-47px -59px'},300)
		.add($("#toggle_pos").animate({'left':'-=47px'},300,function(){				
			$("#toggle_wrapper").removeClass("start");
			$("#toggle_wrapper").removeClass("on");
			$("#toggle_wrapper").addClass("off");
			$("#toggle_wrapper").css("background-position","0px -121px");
		}));
	}
	if ( $("#toggle_wrapper").hasClass("off") ) {
		//alert($("#toggle_wrapper").css("background-position"));
		// from ( -47px, -59px ) to ( 0px, -59px )
		$("#toggle_wrapper").css("background-position","-47px -59px");
		$("#toggle_wrapper").animate({'backgroundPosition':'0px -59px'},300)
		.add($("#toggle_pos").animate({'left':'+=47px'},300,function(){
			$("#toggle_wrapper").removeClass("start");
			$("#toggle_wrapper").removeClass("off");
			$("#toggle_wrapper").addClass("on");
			$("#toggle_wrapper").css("background-position","0px -90px");
		}));
	}
}