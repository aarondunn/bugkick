$(function() {
	var userGroupDialog=null;
	$('#createGroupBtn, table.items a.update, .userGroupItem a.update').live('click', function() {
		if(userGroupDialog === null) {
			$('body').append('<div id="userGroupDialog" style="display:none;"></div>');
			userGroupDialog = $('#userGroupDialog').dialog({
				autoOpen: false,
				modal: true,
				hide: 'drop',
				show: 'drop',
//				height: 495,
				width: 250,
				resizable: false,
				buttons: {
					'Save': function() {
						submitForm();
					}/*,
					'Cancel': function() {
						$(this).dialog('close');
					}*/
				}
			});
		}
		//bkScreen.block();
		$.get(this.href, {YII_CSRF_TOKEN:YII_CSRF_TOKEN}, function(data) {
			initForm(data);
			//bkScreen.release();
			userGroupDialog.dialog('open');
		})
		.error(function(data) {
			//bkScreen.release();
		});
		return false;
	});
	function submitForm() {
		var form=userGroupDialog.find('form');
		$.ajax({
			url: form.attr('action'),
			data: $.extend({YII_CSRF_TOKEN:YII_CSRF_TOKEN}, form.serializeObject()),
			type: 'POST',
			beforeSend: function() {
				//bkScreen.block();
			},
			success: function(data) {
				initForm(data);
				if($('#updateGrid').text() > 0) {
					//$('#group-grid').yiiGridView.update('group-grid');
					$('#group-list').yiiListView.update('group-list');
					userGroupDialog.dialog('close');
				}
				//bkScreen.release();
			},
			error: function(data) {
				//bkScreen.release();
			}
		});
		return false;
	}
	function initForm(data) {
		userGroupDialog.html(data);
		userGroupDialog.dialog('option', 'title', $('#groupsEditFormTitle').text());
		var form=userGroupDialog.find('form');
		form.find('select.chzn-select').chosen();
		form.find('a.submitBtn:first').click(submitForm);
		initColorPicker();
	}
	function initColorPicker() {
		$('.miniColorsWidget').miniColors([]);
	}
});