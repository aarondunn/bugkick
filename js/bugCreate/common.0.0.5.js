$(function() {

    var newLabelBtn=$('.createBug_newLabelBtn'),
           newLabelContainer = newLabelBtn.parent().parent().parent().next('.newLabelContainer'),
   		formPlaceHolder = newLabelContainer.find('div.form-placeholder:first'),
   		newLabelAjaxUrl = newLabelContainer.find('span.ajaxUrl:first').text();
   		//labelsDropdown = $('#Bug_label_id');
   		//labelsDropdown = $('.label-select');

	function toggleBtn(btn) {
		var src=btn.attr('src'),
			reCollapse=/(bullet-toggle-)(minus)(-icon\d+\.png)$/,
			reExpand=/(bullet-toggle-)(plus)(-icon\d+\.png)$/;
		if(reCollapse.test(src))
			src=src.replace(reCollapse, '$1plus$3');
		else
			src=src.replace(reExpand, '$1minus$3');
		btn.attr('src', src);
	}
	$('#createBugDialog, #updateBugDialog').bind('dialogbeforeclose', function(event, ui) {
		if(newLabelContainer.is(':visible')) {
			newLabelContainer.hide();
			toggleBtn(newLabelBtn);
		}
	});
	function showLoader($form) {
		$form.find('a.label-form-submit-btn:first').hide();
		$form.find('img.imgAjaxLoading:first').show();
	}
	function hideLoader($form) {
		$form.find('img.imgAjaxLoading:first').hide();
		$form.find('a.label-form-submit-btn:first').show();
	}
	$('.createBug_newLabelBtn').live('click', function() {

        var newLabelBtn=$(this),
            newLabelContainer = newLabelBtn.parent().parent().parent().next('.newLabelContainer'),
       		formPlaceHolder = newLabelContainer.find('div.form-placeholder:first'),
       		labelsDropdown = $('.label-select');

		if(newLabelContainer.is(':visible')) {
			newLabelContainer.hide();
			toggleBtn(newLabelBtn);
			return false;
		}
		$.get(
			newLabelAjaxUrl,
			function(data) {
				if(data.html===undefined)
					return;
				formPlaceHolder.html(data.html);

                formPlaceHolder.find('input.color_picker').miniColors([]);
                jQuery(".chzn-select").chosen();

				var ajaxFormOptions = {
					beforeSubmit: function(arr, $form, options) {
						showLoader($form);
					},
					success: function(data) {
						if(data.success) {
							labelsDropdown.prepend(
								'<option value="'+data.label.label_id+'">'
								+ data.label.name
								+ '</option>'
							);
							labelsDropdown.val(data.label.label_id);
                            labelsDropdown.trigger("liszt:updated");
							newLabelContainer.hide();
							toggleBtn(newLabelBtn);
						}
						formPlaceHolder.html(data.html);
						initAjaxForm();
						hideLoader(formPlaceHolder.find('form:first'));
					},
					error: function(data) {
						hideLoader(formPlaceHolder.find('form:first'));
						//console.log(data);
					}
				};
				initAjaxForm();
				function initAjaxForm() {
					newLabelContainer.find('form:first').ajaxForm(ajaxFormOptions);
				}
				newLabelContainer.show();
				toggleBtn(newLabelBtn);
			}
		);
		return false;
	});
});
function nearTrim(str, n, delim) {
	if(delim === undefined)
		delim = '\u2026';
	if(n >= str.length)
		return str;
	return str.substr(0, n)
		.replace(/\s+?(\S+)?$/, '').replace(/\s+$/, '') + delim;
}
function showBugTitle($textarea, $target) {
	var $container = $target.parent('div:first'), text = $textarea.val();
	if(text.length == 0)
		$container.hide();
	else {
		var targetText = nearTrim(text, 60);
		var newLineIndex = targetText.indexOf('\n\n');
		if(newLineIndex > 0)
			$target.text(targetText.substr(0, newLineIndex))
		else
			$target.text(targetText);
		$container.show();
	}
}

function createTicket() {
    $.jGrowl.defaults.position='bottom-right';
    $.jGrowl.defaults.life=6000;

    $("#bug-form").ajaxSubmit({
        beforeSubmit:function(data) {
            bugkick.bug.create.clearDescriptionDump();
            $("#createBugDialog").dialog("close");
        },
        success: function(data) {

            if ($.fn.yiiListView && !!$('#bug-list').length){
                var searchKeywords = $('input#bugSearch').serialize();
                $.fn.yiiListView.update('bug-list', {data: searchKeywords});
            }
            $.jGrowl(
                "Ticket is created. <a href='/ticket/"+data.ticketNumber+"'>View</a> "
            );
            if(typeof data.redirect != 'undefined'){
                window.location = data.redirect;
            }
            /*setTimeout(function() {
                $.flashMessage().message(" Ticket is created. <a href='/ticket/"+data.ticketNumber+"'>View</a> ")
                // $.flashMessage().success("Ticket is created");
             }, 800);*/
        },
        error: function(data) {
            $.jGrowl(
                "Please check the fields."
            );
//                 $.flashMessage().message("Please check the fields");
        },
        dataType: "json"
    });
    return false;
}
