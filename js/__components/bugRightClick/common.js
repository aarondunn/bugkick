$(document).ready(function () {
    /*
     * Right context menu for View ticket page
     */
    if($('div.ticket_content').length>0){
        $('div.ticket_content').live('contextmenu', function (e) {

            if(e.target.nodeName == 'A') //when right click on link - we show usual context menu
                return true;

            var $cmenu = $('div.vmenu');
            $('<div class="overlay"></div>').css({left:'0px', top:'0px', position:'absolute', width:'100%', height:'100%', zIndex:'100' }).click(function () {
                $(this).remove();
                $cmenu.hide();
            }).mouseover(function () {
                $(this).remove();
                $cmenu.hide();
                return false;
            }).appendTo(document.body);
            $('div.vmenu').css({ left:e.pageX, top:e.pageY, zIndex:'101' }).show();
            return false;
        });

        $('.vmenu .btn-edit').live('click',function(){
            hideMenu();
            $('.update-bug-link').trigger('click');
        });
        $('.vmenu .btn-close').live('click',function(){
            hideMenu();
            $('#archived-link').trigger('click');
        });
        $('.vmenu .btn-duplicate').live('click',function(){
            hideMenu();
            $('.duplicate-link').trigger('click');
        });
        $('.vmenu .btn-delete').live('click',function(){
            hideMenu();
            $('#delete_bug_link').trigger('click');
        });
    }
    /*
     * Right context menu for View ticket page
     */
    else if($('#bug-list').length>0){
        $.jGrowl.defaults.position='bottom-right';
        $.jGrowl.defaults.life=6000;
        var ticketID;
        $('div.ticket-item').live('contextmenu', function (e) {
            ticketID =  $(this)[0].id;
            var $cmenu = $('div.vmenu');
            $('<div class="overlay"></div>').css({left:'0px', top:'0px', position:'fixed', width:'100%', height:'100%', zIndex:'100' }).click(function () {
                $(this).remove();
                $cmenu.hide();
            }).mouseover(function () {
                $(this).remove();
                $cmenu.hide();
                return false;
            }).appendTo(document.body);
            $('div.vmenu').css({ left:e.pageX, top:e.pageY, zIndex:'101' }).show();
            return false;
        });

        $('.vmenu .btn-edit').live('click',function(){
            hideMenu();
            $.post(
                '/bug/getBugById/'+ticketID,
                { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
                  function(data){
                    jQuery("#bugUpdateForm").html(data);
                    jQuery("#updateBugDialog").dialog("open");
                    jQuery(".chzn-select").chosen();
                  },
                  "html"
            );
            return false;
        });
        $('.vmenu .btn-close').live('click',function(){
            hideMenu();
            $.post(
                '/bug/setArchived/'+ticketID,
                { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
                function(){
                    if ($.fn.yiiListView && !!$('#bug-list').length){
                        var searchKeywords = $('input#bugSearch').serialize();
                        $.fn.yiiListView.update('bug-list', {data: searchKeywords});
                        $.jGrowl(
                            "Ticket is closed."
                        );
                    }
                },
                "html"
            );
        });
        $('.vmenu .btn-duplicate').live('click',function(){
            hideMenu();
            $.post(
                '/bug/getDuplicateFormByBugId/'+ticketID,
                { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
                function(data){
                    jQuery("#bugDuplicateForm").html(data);
                    jQuery("#duplicateBugDialog").dialog("open");
                },
                "html"
            );
            return false;
        });
        $('.vmenu .btn-delete').live('click',function(){
            hideMenu();
            if(confirm('Are you sure you want to delete this item?')){
                $.post(
                    '/bug/delete/'+ticketID,
                    { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
                    function(){
                        if ($.fn.yiiListView && !!$('#bug-list').length){
                            var searchKeywords = $('input#bugSearch').serialize();
                            $.fn.yiiListView.update('bug-list', {data: searchKeywords});
                            $.jGrowl(
                                "Ticket is deleted."
                            );
                        }
                    },
                    "html"
                );
            }
            return false;
        });
    }
/*
    $('.vmenu .first_li').live('click', function () {
        if ($(this).children().size() == 1) {
            alert($(this).children().text());
            $('.vmenu').hide();
            $('.overlay').hide();
        }
    });
    $('.vmenu .inner_li span').live('click', function () {
        alert($(this).text());
        $('.vmenu').hide();
        $('.overlay').hide();
    });
*/
    function hideMenu(){
        $('.vmenu').hide();
        $('.overlay').hide();
    }
    $(".first_li , .sec_li, .inner_li span").hover(function () {
        $(this).css({backgroundColor:'#E0EDFE', cursor:'pointer'});
        if ($(this).children().size() > 0)
            $(this).find('.inner_li').show();
        $(this).css({cursor:'default'});
    },
    function () {
        $(this).css('background-color', '#fff');
        $(this).find('.inner_li').hide();
    });
});

$(document).ready(function () {
    /*
     * Right context menu for ticket's comments
     */
	if($('ul.message .body-comment').length>0){
		var iCommentID = null;
		$('ul.message .body-comment').hover(
		  function () {
			  iCommentID = $(this).attr('access_del');
			  if (typeof iCommentID === 'undefined' || iCommentID === false) {
				  iCommentID = null;
			  }
		  },
		  function () {
		  }
		);
		
        $.jGrowl.defaults.position='bottom-right';
        $.jGrowl.defaults.life=6000;
        $('ul.message .body-comment.access_del').live('contextmenu', function (e) {
            var $cmenu = $('div.vmenu_dl');
            $('<div class="overlay"></div>').css({left:'0px', top:'0px', position:'fixed', width:'100%', height:'100%', zIndex:'100' }).click(function () {
                $(this).remove();
                $cmenu.hide();
            }).mouseover(function () {
                $(this).remove();
                $cmenu.hide();
                return false;
            }).appendTo(document.body);
            $('div.vmenu_dl').css({ left:e.pageX, top:e.pageY, zIndex:'101' }).show();
            return false;
        });

        $('.vmenu_dl .btn-delete').live('click',function(){
        	if(confirm('Are you sure you want to delete this comment?')){
            	jQuery.ajax({
            		"type": 'POST',
            		"url" : '/bug/DeleteComment/' + iCommentID,
            		"data": {
            					 YII_CSRF_TOKEN : YII_CSRF_TOKEN
            				},
            		"cache": false,
            		"success": function(data){
            			obj = jQuery.parseJSON(data);
            			if(obj.status == '200'){
            				$('[access_del="'+iCommentID+'"]').hide(1500);
            				return true;
            			} else{
            				alert(obj.status);
            			};
            		}
            	});
            }
            return false;
        });
        
    }
});