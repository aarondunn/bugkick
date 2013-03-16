$(document).ready(function() {
	var baseUrl = window.location.protocol + '//' + window.location.hostname;
	//Tree-menu
	$(".tree").simpleTreeMenu();
	//var get =  "'.$filterText.'";
	var request = unescape(get).substring(1, get.length);
	var paramArray = request.split("&");
	// console.log(request,paramArray);

	for(var i=0; i<paramArray.length ; i++) {
        var paramIndex = paramArray[i].match(/=(\d+)/i);
        //var paramName = paramArray[i].match(/\w+/i);
        var paramName = paramArray[i].match(/[\w-]+/i);
        //console.log(paramName);
        if(paramIndex != null) {
            // console.log(paramName);
            var param = paramName[0] + "_" + paramIndex[1];
            //console.log(param);

            /*Filters*/
            if (paramName == "user" || paramName == "status" || paramName == "label" || paramName == "group" || paramName == "filter" ){
                //expand active menu items
                $("#"+paramName+"Tree").simpleTreeMenu("expandToNode", $("#target-"+paramName));

                $("#"+param).css("color", "#3bd392");
                input = $("#"+param).next(":input");
                input.val(paramIndex[1]);
                var input_negative = input.next(":input");
                input_negative.val("");
            }

            /*Negative Filters*/
            if (paramName == "user-negative" || paramName == "status-negative" || paramName == "label-negative" || paramName == "group-negative" || paramName == "filter-negative" ){
                paramName = paramName[0].substring(0, paramName[0].length - 9);
                param = paramName + "_" + paramIndex[1];
                //expand active menu items
                $("#"+paramName+"Tree").simpleTreeMenu("expandToNode", $("#target-"+paramName));

                $("#"+param).css("color", "grey");
                input = $("#"+param).next(":input");
                input.val("");
                var input_negative = input.next(":input");
                input_negative.val(paramIndex[1]);
            }
        }
    }

    var DELAY = 180 /*time for double click*/, clicks = 0, timer = null;

	$(".operations a[class!='filter-edit-link']").click(function(){

        var link = $(this);
        clicks++;  //count clicks

        if(clicks === 1) {
            //single click (usual filter)

            timer = setTimeout(function() {

                var status_id = link.attr("id");
                var id, input, input_negative;

                if (status_id == 'saveFilter')
                  return false;

                id = status_id.match(/\d+/i)[0];
                input = link.next(":input");
                input_negative = input.next(":input");

                //turnoff old saved filters
                 if (link.attr("class") == 'saved-filters-link'){
                     if(input.val() == "") {
                         $(".operations a").css("color", "");
                         $(".operations a").next(":input").val("");
                         $(".operations a").next(":input.negative").val("");
                     }
                 }
                 else{
                     $('.saved-filters-link').css("color", "");
                     $('.saved-filters-input').val("");
                     $('.saved-filters-input-negative').val("");
                 }

                if(input.val() == "") {
                    link.css("color", "#3bd392");
                    input.val(id);
                    input_negative.val("");
                }
                else {
                    link.css("color", "");
                    input.val("");
                    input_negative.val("");
                }

                sendFilterData();

                clicks = 0;   //after action performed, reset counter
            }, DELAY);

        }
        else {
            //double click (negative filter)

            var status_id = link.attr("id");
            var id, input, input_negative;

            if (status_id == 'saveFilter')
              return false;

            id = status_id.match(/\d+/i)[0];
            input = link.next(":input");
            input_negative = input.next(":input");

            //turnoff old saved filters
            if (link.attr("class") == 'saved-filters-link'){
                if(input_negative.val() == "") {
                    $(".operations a").css("color", "");
                    $(".operations a").next(":input").val("");
                    $('.saved-filters-input-negative').val("");
                }
            }
            else{
                $('.saved-filters-link').css("color", "");
                $('.saved-filters-input').val("");
                $('.saved-filters-input-negative').val("");
            }

            if(input_negative.val() == "") {
                link.css("color", "grey");
                input_negative.val(id);
                input.val("");
            }
            else {
                link.css("color", "");
                input_negative.val("");
                input.val("");
            }

            sendFilterData();

            clearTimeout(timer);    //prevent single-click action
            clicks = 0;             //after action performed, reset counter
        }

        return false;
    }).live("dblclick", function(e){
        e.preventDefault();  //cancel system double-click event
    });

    function sendFilterData()
    {
        //getting new data...
        $("#filterForm").ajaxSubmit({
            url     : baseUrl + act, //+ "/bug/" + act,
            beforeSubmit:function(data) {
               // $.flashMessage().beginProgress();
            },
            success: function(data) {
                $("#bug-list").html(data);
                renderTicketUsersAndLabels();
                addTooltip();
                destroySortable();
                setupSortable();
               // $.flashMessage().message('Ready');
            },
            error: function(data) {
               // $.flashMessage().message('An Error has occurred');
            },
            dataType: "html"
        });
    }


	function _makeDroppable(selector, targetIdName, requestUrl, errorCallback) {
		$(selector).droppable({
			greedy: true,
			tolerance : "pointer",
			hoverClass: "drophover",
			accept:'div.ticket-item',
			drop : function(event, ui) {
				var elemID = [];
                if(ui.draggable.attr('id') == 'bugkick-multidrag-container') {
                    $('.ticket-item', ui.draggable).each(function() {
                        elemID.push($(this).attr('ticketID'));
                    });
                } else {
                    elemID.push(ui.draggable.attr("ticketID"));
                }
				var targetID = $(this).attr(targetIdName),
                    postData = {
                        elemID:elemID,
                        YII_CSRF_TOKEN:YII_CSRF_TOKEN
                    };
				postData[targetIdName]=targetID;
				$.ajax({
					url: requestUrl,
					type: "post",
					data: postData,
					success: function(request, status, error) {
						//refreshing tickets list
                        var searchKeywords = $('input#bugSearch').serialize();
                        $.fn.yiiListView.update('bug-list', {data: searchKeywords});
                        if(targetIdName == 'labelID'){
                            updateLabels();
                        }
					},
					error: errorCallback
				});

                $('#bug-list div.items').data('disallowSort', true);

                //$('#bug-list div.items').sortable('cancel');
                //destroySortable();
                if(typeof mixpanel !="undefined"){
                    //Mixpanel tracking
                    mixpanel.track(FILTER_APPLIED_BY_DRAG_N_DROP);
                }
                _setupMenuHover();
			}
		});
	}

    function _setupMenuHover(){
        $('li.Node').droppable('destroy'); //removing old droppable to fix 'over' bug
        $('li.Node').droppable({
            greedy: false,
            tolerance : "pointer",
            accept:'div.ticket-item',
            over: function(event, ui) {
                $('li.Node.expanded').removeClass('expanded').find('ul').hide();
                if ($(this).parent().attr('id') !='filterTree')
                    $(this).addClass('expanded').find('ul').show();
            }
        });
    }

	function getErrorCallback(msg) {
		return function(request, status, error){
			alert(msg);
		};
	}
	var filterItems=[
		{
			selector:'div.label-container',
			targetIdName:'labelID',
			requestUrl:baseUrl + '/bug/DndAddLabel',
			errorCallback:getErrorCallback('We are unable to set the label at this time.  Please try again in a few minutes.')
		},
		{
			selector:'li.user',
			targetIdName:'userID',
			requestUrl:baseUrl + '/bug/DndAddUser',
			errorCallback:getErrorCallback('We are unable to set the assignee at this time.  Please try again in a few minutes.')
        },
		{
			selector:'li.status',
			targetIdName:'statusID',
			requestUrl:baseUrl + '/bug/DndAddStatus',
			errorCallback:getErrorCallback('We are unable to change the status at this time.  Please try again in a few minutes.')
		}
	];
	for(i=0; i < filterItems.length; i++)
		_makeDroppable(
			filterItems[i].selector,
			filterItems[i].targetIdName,
			filterItems[i].requestUrl,
			filterItems[i].errorCallback
		);
    _setupMenuHover();
	//Expand filter menus when tickets dragged over
	/*$('span.menu-title').droppable({
		greedy: true,
		tolerance: 'pointer',
		over: function(event, ui) {
			var el=$(this);
			el.simpleTreeMenu('expandToNode', el.next('ul'));
		}
	});*/

    //Remember state of filters
    if(typeof(localStorage) != 'undefined' ) {
        if (localStorage.getItem('statusTree') == 1){
            $("#statusTree").simpleTreeMenu("expandToNode", $("#target-status"));
        }
        if (localStorage.getItem('labelTree') == 1){
            $("#labelTree").simpleTreeMenu("expandToNode", $("#target-label"));
        }
        if (localStorage.getItem('userTree') == 1){
            $("#userTree").simpleTreeMenu("expandToNode", $("#target-user"));
        }
        if (localStorage.getItem('groupTree') == 1){
            $("#groupTree").simpleTreeMenu("expandToNode", $("#target-group"));
        }
        if (localStorage.getItem('filterTree') == 1){
            $("#filterTree").simpleTreeMenu("expandToNode", $("#target-filter"));
        }
    }

   $('.delete-filter').click(function(){
       var id = $(this).attr("id");
       if (id >0){
           if (confirm('Delete this filter?')){
               window.location = baseUrl + '/user/deleteFilter/id/'+id;
           }
       }
       return false;
   });

    //Setup Resizable filters
    sideBarMiddle.css({'height' : getFilterHeight()});
    applyScrollPane();
    initResizable();
});
/**
 * Resizable filters
 * @author: Alexey Kavshirko
 */
var sideBarMiddle = $('#sidebar .sidebar_middle:first');
var maxFilterHeight = $(window).height() - 160;
var defaultFilterHeight = 220;
function applyScrollPane(){
    sideBarMiddle.jScrollPane({hideFocus: true,animateScroll: true});
}
function setFilterHeight(){
    var height = sideBarMiddle.height();
    if(typeof(localStorage) != 'undefined' ) {
        localStorage.setItem('filterHeight', height);
    }
}
function getFilterHeight(){
    if(typeof(localStorage) != 'undefined'
        && localStorage.getItem('filterHeight') > 0
        && localStorage.getItem('filterHeight') <= maxFilterHeight) {
            return localStorage.getItem('filterHeight');
    }
    return defaultFilterHeight;
}
function initResizable(){
    sideBarMiddle.resizable({
        minHeight: defaultFilterHeight,
        maxHeight: maxFilterHeight,
        autoHide: true,
        handles: "s",
        stop: function(event, ui) {
            applyScrollPane();
            setFilterHeight();
        }
    });
}
//END of Resizable filters

//Updates number of tickets by label
function updateLabels(){
    $.ajax({
        'url': '/label/updateCount',
        'type': 'post',
        'data': { 'YII_CSRF_TOKEN':YII_CSRF_TOKEN },
        'success' : function(request, status, error) {
            $.each(request.labels, function(index, value) {
                $('#label_' + index).find('.label-count').html('('+value+')');
            });
        },
        'error': function(request, status, error){
            alert('We are unable to update labels at this time.  Please try again in a few minutes.');
        },
        dataType: "json"
    });
}