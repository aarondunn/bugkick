if(/#?_=_/.test(window.location.hash))window.location.hash='';//FB fail fix
var bkScreen = null;
/**
 * SerializeObject jQuery mini plug-in
 * that erializes form fields to object.
 */
(function($) {
	$.fn.serializeObject = function() {
		var o = {};
		var a = this.serializeArray();
		$.each(a, function() {
			if(o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
	};
})(jQuery);
$(function() {
    $('body').append(bugkick.string.buildString('<input type="text" ',
        'id="focusme" ',
        'style="position: absolute; left: -1000px; top: -1000px; width: 10px;" ',
        '/>'));
    var focusMe$ = $('#focusme');
    $(document).on('dialogclose', function() {
        focusMe$.focus().blur();
    });
	//bkScreen = new BKScreen({blockerID: 'ajaxLoading'});
	$('.chzn-select').chosen();
	$('.iPhone-checkbox').iphoneStyle();
    //For Main Menu
/*	$("li.settings-link").hover(
        function(){
            $("div.setting-container").css('display', 'block');
            $("#view-settings-link").css('backgroundColor', '#1E71AB');
        },
        function(){
            $("div.setting-container").css('display', 'none');
            $("#view-settings-link").css('backgroundColor', '');
        }
    );
    $("li.company-link").hover(
        function(){
            $("div.company-container").css('display', 'block');
            $("#view-company-link").css('backgroundColor', '#1E71AB');
        },
        function(){
            $("div.company-container").css('display', 'none');
            $("#view-company-link").css('backgroundColor', '');
        }
    );*/

    //For showing hiding icons in the ticket list
//    $("table.items tr").hover(
//        function(){
//            $(this).find('td.actions-column a').css('visibility','visible');
//        },
//        function(){
//            $(this).find('td.actions-column a').css('visibility','hidden');
//        }
//    );
    
});

function switchToProject(id) {
	document.getElementById('menu_project_id').value=id;
	document.forms['choseProjectForm'].submit();
	return false;
}

/*
*
* DnD for tickets
*
* */
function destroySortable(){
   // $('#bug-list div.items').sortable('cancel');
  $('#bug-list div.items').sortable('destroy');
}

function expandFilterItems() {
    $('ul.simpleTreeMenu').each(function() {
        $(this).find('li.Node').each(function() {
            var el=$(this);
            if(el.hasClass('expanded'))
                el.data('collapseOnStop', false);
            else {
                el.addClass('expanded').find('ul').show();
                el.data('collapseOnStop', true);
            }
        });
    });
}
function restoreExpandedFilterItems() {
    $('ul.simpleTreeMenu').each(function() {
        $(this).find('li.Node.expanded').each(function() {
            var el=$(this);
            if(el.data('collapseOnStop'))
                el.removeClass('expanded').find('ul').hide();
        });
    });
}
function collapseFilterItems() {
    $('ul.simpleTreeMenu').each(function() {
        $(this).find('li.Node').each(function() {
            var el=$(this);
            if(el.hasClass('expanded')) {
                el.removeClass('expanded').find('ul').hide();
                el.data('expandOnStop', true);
            } else {
                el.data('expandOnStop', false);
            }
        });
    });
}
function restoreCollapsedFilterItems() {
    $('ul.simpleTreeMenu').each(function() {
        $(this).find('li.Node').each(function() {
            var el=$(this);
            if(el.data('expandOnStop')) {
                el.addClass('expanded').find('ul').show();
            }
        });
    });
}
window.FilterEventHandlers = {
//    onDragStart: expandFilterItems,
//    onDragStop: restoreExpandedFilterItems
    onDragStart: collapseFilterItems,
    onDragStop: restoreCollapsedFilterItems
};
function setupSortable(){
        $('#bug-list div.items').sortable({
                        change:  function (event, ui) {
                            flag = true;
                            var separator = 0;
                            var placeholder = 0;
                            $('.items div').each(function(i){
                                if($(this).hasClass('separator')){
                                    separator = i;
                                }
                                if($(this).hasClass('ui-sortable-placeholder')){ 
                                    placeholder = i;
                                }
                            });
                            if(separator == (placeholder-4) || separator == (placeholder+1)){
                                    $('.separator').css('display','none');
                                    $('.separator').css('visibility','hidden');
                                }
                                else{
                                    $('.separator').css('display','block');
                                    $('.separator').css('visibility','visible');
   
                                }
                            
                        },
			start: function(){
				window.FilterEventHandlers.onDragStart;
				$('.ui-sortable-placeholder').after($('.ui-sortable-placeholder').clone());
				$('.ui-sortable-placeholder').each(function(i) {
					if(i==0){
						$(this).removeClass('ticket-item moreTwoDays unchecked ui-sortable-placeholder');
                                                $(this).attr('class','separator');
                                                $(this).css('height','1px');
                                                $(this).css('display','none');
                                                $(this).css('margin-bottom','10px');
                                                $(this).css('width','98%');
                                                $(this).css('background-color','#2A70BF');
                                                $(this).css('visibility','visible');
                                                $(this).css('z-index','12');
                                                //alert('12');
                                                
					}
					if(i==1){
						$(this).css('visibility','visible');
						$(this).css('border-style','dashed');
						$(this).css('border-width','2px');
						$(this).css('border-color','#F1F1F1');
                                                $(this).css('z-index','999');
					}
				});
				
			},
			stop: function(){
				window.FilterEventHandlers.onDragStop;
				$('.ui-sortable-placeholder').remove();
			},
			opacity: 0.618,
            refreshPositions: true,
			cursor: 'pointer',
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'div.ticket-item',
            update : function(event, ui) {
                $('#bug-list div.items').sortable( {disabled : true} );

                var new_positions = $(this).sortable('toArray'),
                new_index;
                $.each(new_positions, function(i, val){
                    if (val == ui.item.attr('id'))
                    {
                        new_index = i;
                    }
                });

                var projectid = JSON.parse($('#user_data').text()).project_id;
                $.getJSON('/bug/GetTicketOrder/'+projectid, function(data){

                    var displayedTicketIds = [],
                    displayedTicketPositions = [];

                    //Loop through tickets from database and work out which ones are displayed on the page
                    $.each(data, function(i, val){
                        var ticket = $('#bug-list').find('div[ticketid='+val.id+']');
                        if (ticket.length)
                        {
                            displayedTicketIds.push(val.id);
                            displayedTicketPositions.push(val.priority_order);
                        }
                    });

                    //Here we pull out the old postion value and index of our moved element
                    var old_index = $.inArray(ui.item.attr('ticketid'), displayedTicketIds);

                    displayedTicketIds.splice(old_index, 1);

                    //Insert it at the correct place in the list
                    displayedTicketIds.splice(new_index, 0, ui.item.attr('ticketid'));

                    //Now we have a list of id's in the correct order, next we need to assign the position values correctly.
                    var new_ordered_list = [],
                    updatedTicket = {};

                    $.each(displayedTicketIds, function(i, val){
                        if (new_index == i)
                        {
                            updatedTicket = {"id":val, "position":displayedTicketPositions[i]};
                        }
                        new_ordered_list.push({"id":val, "position":displayedTicketPositions[i]});
                    });

                    changeTicketList = [];

                    if (old_index < new_index)
                    {
                        $.each(new_ordered_list, function(i,val){
                            if ((i>=old_index) && (i<=new_index))
                            {
                                changeTicketList.push(val);
                            }
                        });
                    }
                    else
                    {
                        $.each(new_ordered_list, function(i,val){
                            if ((i<=old_index) && (i>=new_index))
                            {
                                changeTicketList.push(val);
                            }
                        });
                    }

                    dndUrl = '/bug/UpdateTicketOrder';
                    //Now we have them in the correct order we can post new position to db
                    $.ajax({
                        'url': dndUrl,
                        'type': 'post',
                        'data': {'newpositions': JSON.stringify(changeTicketList), 'YII_CSRF_TOKEN':YII_CSRF_TOKEN},
                        'success' : function(request, status, error) {
                            $.each(new_ordered_list, function(i, val){
                                $('#'+val.id+'').attr("position", val.position);
                            });

                            var tickets = $('#bug-list div.items').children();

                            tickets.sort(function (a, b) {
                                var contentA =parseInt( $(a).attr('position'));
                                var contentB =parseInt( $(b).attr('position'));
                                return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
                            });

                            $('#bug-list div.items').empty().html(tickets);
                            $('#bug-list div.items').sortable('refresh');
                            $('#bug-list div.items').sortable( {disabled : false} );
                        },
                        'error': function(request, status, error){
                            alert('We are unable to set the priority order at this time.  Please try again in a few minutes.');
                        }
                    });
                });

                if(typeof mixpanel !="undefined"){
                    //Mixpanel tracking
                    mixpanel.track(TICKETS_ORDER_CHANGED_BY_DRAG_N_DROP);
                }
            }
        });
        $('#bug-list div.items').disableSelection();
}
$(function() {
    setupSortable();
    $("#showCalendar").click(function(){
            $.ajax({
                'url': '/site/calendar',
                'type': 'post',
                'data': {'YII_CSRF_TOKEN':YII_CSRF_TOKEN},
                'success' : function(data) {
                    $(".main_middle").html(data);
                },
                'error': function(data){
                    alert('We are unable to load calendar. Please try again later.');
                },
                    dataType: "html"
            });
           return false;
    });
});

function addTooltip() {
    $("#bug-list span.clock[title]").colorTip({color:"yellow", timeout:100});
	$("#bug-list a[title]").colorTip({color:"yellow", timeout:100, delay:750});
 //   $("#bug-list span.title a[title]").colorTip({color:"yellow", timeout:100});
    $('#bug-list div.imageset').imageSet();
  //  $(".ticket-item .comments").colorTip({color:"yellow", timeout:100});
}
/* END of DnD for tickets */

//function for strings truncating
String.prototype.trunc = function(n){
  return this.substr(0,n-1)+(this.length>n?'&hellip;':'');
};

//function renders user's icon
function renderUser($user, $userID) {
    return '<a href="user/'+$userID+'" title="'+$user.name+'"><img src="'+$user.profile_img+'" class="bug-profile-pic"></a>'
}

//function renders ticket labels and users
function renderTicketUsersAndLabels() {
    //get ticket users and labels
    var ticketMetaData = JSON.parse($('#ticketMetaData').html());

    $('div.items div.ticket-item').each(function() {
        var $ticket = $(this);

        if($ticket.attr('label_set').length >0){
            var $ticketLabelIDs = $ticket.attr('label_set').split(',');
        }
        if($ticket.attr('user_set').length >0){
            var $ticketUserIDs = $ticket.attr('user_set').split(',');
        }

        //generate labels
        if($ticketLabelIDs != null){
            $i = 0;
            jQuery.each($ticketLabelIDs, function() {
                if(typeof ticketMetaData.labels[this] != 'undefined' && $i < maxLabels ){
                    var $label =  ticketMetaData.labels[this];
                    var $labelHTML = '<span class="bubble" style="background-color:'+$label.label_color+'">'+
                    $label.name.trunc(12)+'</span>';
                    $ticket.find('span.title').append($labelHTML);
                    $i++;
                }
            });
        }

        //generate users
        if($ticketUserIDs != null){
            var $usersHTML = '<span class="photo thumb">';
            var $totalUsers = $ticketUserIDs.length;
            if($totalUsers < 3) {
                //case when we have less than 3 user
                jQuery.each($ticketUserIDs, function() {
                    if(typeof ticketMetaData.users[this] != 'undefined'){
                        var $user = ticketMetaData.users[this];
                        $usersHTML += renderUser($user, this);
                    }
                });
            } else {
                var $i = 0;
                jQuery.each($ticketUserIDs, function() {
                    if(typeof ticketMetaData.users[this] != 'undefined'){
                        $user = ticketMetaData.users[this];
                        if($i == 0){
                            $usersHTML += renderUser($user, this);
                            $usersHTML += '<div class="imageset round5">';
                        }
                        else{
                            $usersHTML += renderUser($user, this);
                        }
                        $i++;
                    }
                });
                $usersHTML += '</div>';
            }
            $usersHTML +='</span>';

            if($ticket.find('span.clock').length>0){
                //render users after clock
                $ticket.find('span.clock').after($usersHTML);
            }
            else{
                //render users at the end of a ticket
                $ticket.append($usersHTML);
            }
        }
    });
}

$(document).ready(function() {
    //close dialogs when clicked out of modal box
    $('.ui-widget-overlay').live("click", function() {
        $(".ui-dialog-content").dialog("close");
    });
});
