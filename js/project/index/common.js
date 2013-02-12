$(function() {
    var baseUrl = window.location.protocol + '//' + window.location.hostname;

    $('#projects-list a.update').live('click',editProject);
    $('#projects-grid a.update').live('click',editProject);
    $('#createProjectBtn').live('click',editProject);

//    if ($('div.items > span.empty').text() != "")
//    {
//         $('body').append('<div id="firstProject" style="padding:0 20px 50px;"><div style="clear:both; margin: 30px 0;"><span>Would you like to create your first project?</span></div><div style="margin 0 10px: auto;float:right;"><a id="yes" class="bkButtonBlueSmall normal">Yes</a></div><div style="float:right;margin:0 5px 0 0;"><a id="no" class="bkButtonBlueSmall normal">No</a></div></div>');
//
//         var windowWidth = $(window).width(),
//         xoffset = eval((windowWidth/2)-150);
//
//         $('#no').click(function(){$('#firstProject').dialog('close');});
//         $('#yes').click(function(){$('#firstProject').dialog('close');$('#createProjectBtn').trigger('click');});
//
//         $('#firstProject').dialog({modal: true, dialogClass: "infobox", width: 300, position: [xoffset, 100]});
//         //$('div.infobox > .ui-dialog-titlebar').attr('style', 'height:35px !important;');
//         $('#firstProject').show();
//    }
    
	$('body').append('<div id="manageProjectDlg"></div>');
	var manageProjectDlg=$('#manageProjectDlg').dialog({
		autoOpen:false,
		resizable:false,
		draggable:false,
		modal:true,
		width:850,
		height:450,
		beforeClose:function(event, ui) {
			manageProjectDlg.html('');
		}
	});
	$('.manageProject').live('click', manageProject);
	function manageProject() {
		$.getJSON(
			this.href,
			{
				action:'gantt',
				YII_CSRF_TOKEN:YII_CSRF_TOKEN
			},
			function(data) {
				var html='<div id="overalProjectProgress"></div>\
					<div id="projectGanttDiagram" style="width:90%; height:400px; margin:0 auto;"></div>';
				manageProjectDlg.html(html);
				var projectProgress = 0;
				if(data.tasksCounts.total != 0)
					projectProgress = Math.round(
						data.tasksCounts.completed 
							/ (data.tasksCounts.total / 100)
					);
				manageProjectDlg.find('#overalProjectProgress').fprogressBar({
					progress:projectProgress
				});
				var ganttOptions={
					source:data.source,
					navigate:'scroll',
					scale:'days',
					minScale:'days',
					maxScale:'months',
					holydays:[]
				};
				manageProjectDlg
					.find('#projectGanttDiagram')
					.append('<div class="gantt"></div>')
					.find('.gantt:first')
					.gantt(ganttOptions);
				manageProjectDlg.dialog('option', 'title', data.project.name);
				manageProjectDlg.dialog('open');
			}
		).error(function(jqXHR, textStatus, errorThrown){
			//console.error(jqXHR, textStatus, errorThrown);
		});
		return false;
	}
});
function nearTrim(str, n, delim) {
	if(delim === undefined)
		delim = '\u2026';
	if(n >= str.length)
		return str;
	return str.substr(0, n)
		.replace(/\s+?(\S+)?$/, '').replace(/\s+$/, '') + delim;
}
function submitProjectForm() {
	$('#project-form').ajaxSubmit({
		success: function(data) {
			$('#project-form-dialog').html(data.html);
			$('#project-form .chzn-select').chosen();
			if(data.success) {
                if ($('#projects-list').length != 0){
                    $('#projects-list').yiiListView.update('projects-list');
                }
                if ($('#projects-grid').length != 0){
                    $('#projects-grid').yiiGridView.update('projects-grid');
                }
				$('#project-form-dialog').dialog('close');
                //updating of the top menu
                if ($('#project-item-'+data.projectID).length != 0){
                   $('#project-item-'+data.projectID).html(
                        '<li>'
                            +'<a href="#" onclick="return switchToProject('+data.projectID+');" class="project-url">'
                            +nearTrim(data.projectName, 17)+'</a>'
                        +'</li>'
                     );
                }else{
                    $('.projects-container').prepend(
                        '<li class="project-item-'+data.projectID+'">'
                            +'<a href="#" onclick="return switchToProject('+data.projectID+');" class="project-url">'
                            +nearTrim(data.projectName, 17)+'</a>'
                        +'</li>'
                     );
                }
                if ($('#view-project-link-'+data.projectID).length != 0){
                   $('#view-project-link-'+data.projectID).text(nearTrim(data.projectName, 20));
                }
                //end
                if ($('div.steps').length != 0){
                    window.location = '/project/choose/?menu_project_id='+data.projectID+'&rr=new%2F2%3Fcompleted_step_1=1';
                }
			}
		},
		dataType: 'json'
	});
	return false;
}
function closeDialog() {
	$(this).dialog('close');
	return false;
}
function editProject(targetObj) {
    var windowWidth = $(window).width(),      
    width = 565, //eval(windowWidth*0.5),
    xoffset = eval((windowWidth/2)-(width/2));             

	//bkScreen.block();
	$.get(
		this.href,
		{YII_CSRF_TOKEN:YII_CSRF_TOKEN},
		function(data) {
			if(data.success) {
				dlg = $('#project-form-dialog').html(data.html);
                $("#project-form").css("display", "block"); //show form (we hide it to prevent blinking)

                if (targetObj.currentTarget.attributes['class'].nodeValue == 'update')
                {
                    //dlg.dialog({modal: true, dialogClass: "infobox", width: width, position: [xoffset, 100], title: "Edit Project"});
                    dlg.dialog({
                        modal: true,
                        dialogClass: "infobox",
                        width: width,
                        position: [xoffset, 100],
                        title: "Edit Project"
                    });
                }
                else
                {
                    //dlg.dialog({modal: true, dialogClass: "infobox", width: width, position: [xoffset, 100], title: "New Project"});
                    dlg.dialog({
                        modal: true,
                        dialogClass: "infobox",
                        width: width,
                        position: [xoffset, 100],
                        title: "New Project"
                    });
                    $('#archived-row').hide();
                }
                 
                $('#project-form .chzn-select', dlg).width(eval(width*0.9));
                //$('div.basic-project-text input').width(eval((width*0.8)-125));
                //$('div.basic-project-text textarea').width(eval((width*0.8)-125));                
				$('#project-form .chzn-select', dlg).chosen();
				//bkScreen.release();
				dlg.dialog('open');
			}
            else if(!data.success && data.limit == '1'){
                dlg = $('#update-dialog').html(data.html);
                //bkScreen.release();
                dlg.dialog('open');
            }
			//bkScreen.release();
		$('.ui-dialog-buttonset').attr('style', 'clear:both;');
		},
		'json'
	)
	.error(function(jqXHR, textStatus, errorThrown) {
		//bkScreen.release();
		//console.log(jqXHR, textStatus, errorThrown);
	});
	return false;
}
