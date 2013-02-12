var insertCodeDlg=null;
$(function() {

    //make links open in new window after CHtmlPurifier.
    $('.description a, .commentMessage a, .commentMessageFull a').attr('target', '_blank');

/*
Collapsing/expanding comments
*/
    var adjustheight = 30; // The height of the content block when it's not expanded

    $('.commentBlock').each(function(index) {
        if($(this).height()>adjustheight){
            $(this).find('.commentMessageFull').css('height', adjustheight).css('overflow', 'hidden');
            $(this).append('<div class="expand-icon"></div>');
        }
    });

    $('.commentMessageFull, .expand-icon').live('click', function(e){
        //enable links
        if ($(e.target).is('a')) return;
	   	var el = $(this);
        if(el.hasClass('expand-icon')){
            el.parents('div:first').find(".commentMessageFull").css('height', 'auto').css('overflow', 'visible');
            el.removeClass('expand-icon').addClass("collapse-icon");
        }
        else{
            el.css('height', 'auto').css('overflow', 'visible');
            el.next('div.expand-icon').removeClass('expand-icon').addClass("collapse-icon");
        }
    });

    $('.collapse-icon').live('click', function(){
        var el = $(this);
        el.parents("div:first").find(".commentMessageFull").css('height', adjustheight).css('overflow', 'hidden');
        el.removeClass("collapse-icon").addClass("expand-icon");
    });
/*
END of Collapsing/expanding comments
*/




	//	Syntax highlighting
	var txtArea = $('#Comment_message');
	var html =
'<div class="form" id="insertCodeForm" style="overflow:hidden;position:relative; text-align:center;">\
	<textarea id="txtAreaCode" class="round6" \
		style="width:410px;min-width:410px;max-width:410px;\
		height:320px;min-height:320px;max-height:320px;"></textarea>\
</div>';
	$('body').append(html);
	insertCodeDlg = $('#insertCodeForm');
	var txtAreaCode = $('#txtAreaCode'),
		codeBtn = $('.codeBtn');
	function closeDlg() {
		insertCodeDlg.dialog('close');
	}
	insertCodeDlg.dialog({
		title: '<img alt="" src="/themes/bugkick_theme/images/icons/code.png" style="vertical-align:middle;" />&nbsp;Insert the code',
		autoOpen: false,
		width: 450,
		height: 450,
		resizable: false,
		modal: true,
		buttons : {
			'OK'		:	function() {
				/*txtArea.val(
					txtArea.val() +
					'\n{{{#!\n\n' + txtAreaCode.val() + '\n\n!#}}}'
				);*/
				var content = txtArea.wysiwyg('getContent');
				var reLt=/</gi, reGt=/>/gi;
				txtArea.wysiwyg(
					'setContent',
					content 
						+ '\n{{{#!\n\n'
						+ txtAreaCode.val()
							.replace(reLt,'&lt;').replace(reGt, '&gt;') 
						+ '\n\n!#}}}'
				);
				closeDlg();
			},
			'Cancel'	:	function() {
				closeDlg();
			}
		}
	});
	//codeBtn.click(function() {
	//	insertCodeDlg.dialog('open');
	//});
	$('#comment-form').submit(function() {
		var re=/{{{#![\s\S]*?!#}}}/gi,
			reLt=/</gi,
			reGt=/>/gi,
			reCloseTag=/\s+(!#}}})/gi;
		matches = [];
		var segment=null;
		var val = txtArea.val();
		while(true){
			segment=re.exec(val);
			if(segment===null)
				break;
			var oldSegment=segment[0];
			var newSegment=oldSegment
				.replace(reLt, '&lt;')
				.replace(reGt, '&gt;')
				.replace(reCloseTag, '$1');
			val=val.replace(oldSegment, newSegment);
		}
		txtArea.val(val);
	});
	//	Syntax highlighting			END
});