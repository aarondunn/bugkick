;(function($) {
	$.fn.liveDraggable = function(options) {
		this.live('mousemove', function() {
			$(this).draggable(options);
		});
	};
}(jQuery));
var groupsAfterAjaxUpdate;
$(function() {
	var baseUrl = window.location.protocol + '//' + window.location.hostname,
		groupsBlock=$('#groups'),
		usersBlock=$('#users'),
		groupBlock=$('#group');
		groupsAfterAjaxUpdate=function(id,data) {
			userGroupsDroppable();
		};
	$('body').prepend('<div id="plusOneSphere" class="invis plusOneSphere"></div>');
	var plusOneSphere=$('#plusOneSphere');
	userGroupsDroppable();
	$('.userGroupItem .name').live('click', editGroup);
	$('#group .gBack').live('click', backToGroups);
	$('.userGroupItem .delete').live('click', deleteGroup);
	setAvatarDimensions(usersBlock);
	$('.userItem:not(.ui-draggable), .user:not(.ui-draggable)').liveDraggable({
		zIndex:999,
		helper:'clone',
		cursor:'pointer',
		opacity:0.618
	});
	$('.usersContainer').droppable({
		accept:'.userItem',
		activeClass:'droppableActive',
		drop:function(event, ui) {
			var user_id=ui.draggable.find('span.user_id:first').text();
			ui.draggable.hide().clone()
				.removeAttr('style')
				.removeAttr('id')
				.attr('class', 'user round3')
				.appendTo(this).show();
			addToGroup(groupBlock.data('group_id'), user_id);
		}
	});
	$('#user-list .items').droppable({
		accept:'.user',
		activeClass:'droppableActive',
		drop:function(event, ui) {
			var user_id=ui.draggable.find('span.user_id:first').text();
			ui.draggable.remove();
			$('#userItem_' + user_id, this).show();
			removeFromGroup(groupBlock.data('group_id'), user_id);
		}
	});
	function userGroupsDroppable() {
		$('.userGroupItem:not(.ui-droppable)').droppable({
			accept:'.userItem',
			activeClass:'droppableActive',
			over:function(event,ui) {
				$(this).addClass('groupOver');
			},
			out:function(event,ui) {
				$(this).removeClass('groupOver');
			},
			drop:function(event, ui) {
				var el=$(this);
				var user_id=ui.draggable.find('span.user_id:first').text(),
					group_id=el.find('span.group_id:first').text();
				addToGroup(group_id, user_id, function(data) {
					if(data.success) {
						var blockCnt=$('#usersCount_'+group_id),
							count=parseInt(blockCnt.text(), 10);
						blockCnt.text(count+1);
						var offset=el.offset();
						var left=offset.left-plusOneSphere.width()/2,
							top=offset.top-plusOneSphere.height()/2;
						plusOneSphere.css({
							'left':left,
							'top':top
						})
						.show()
						.animate(
							{
								left:left-plusOneSphere.width(),
								top:top-plusOneSphere.height()
							},
							'slow',
							'linear',
							function() {
								plusOneSphere.hide();
							}
						);
					}
				});
				$(this).removeClass('groupOver');
			}
		});
	}
	function groupAction(group_id, user_id, action, callback) {
		//bkScreen.block();
		$.post(
			'',
			{
				YII_CSRF_TOKEN:YII_CSRF_TOKEN,
				action:action,
				group_id:group_id,
				user_id:user_id
			},
			callback
		).error(function() {
			//bkScreen.release();
		});
	}
	function addToGroup(group_id, user_id, callback) {
		groupAction(group_id, user_id, 'addToGroup', function(data) {
			if(callback!==undefined)
				callback(data);
			//bkScreen.release();
		});
	}
	function removeFromGroup(group_id, user_id) {
		groupAction(group_id, user_id, 'removeFromGroup', function(data) {
			//bkScreen.release();
		});
	}
	function editGroup() {
		var el=$(this);
		var container=el
			.parent('div.nameContainer')
			.parent('div.userGroupItem');
		var group_id=container
			.find('span.group_id:first')
			.text(),
			groupName=el.find('span.groupName:first').text();
		getGroupMembers(group_id, groupName, container.css('background-color'));
	}
	function deleteGroup() {
		if(!confirm('Are you sure?'))
			return false;
		var el=$(this);
		var group_id=el
			.parent('div.userGroupItem')
			.find('span.group_id:first')
			.text();
		//bkScreen.block();
		$.post(
			'',
			{
				YII_CSRF_TOKEN:YII_CSRF_TOKEN,
				'action':'deleteGroup',
				'group_id':group_id
			},
			function(data) {
				//bkScreen.release();
				$('#group-list').yiiListView.update('group-list');
			}
		).error(function(data) {
			//bkScreen.release();
		});
		return true;
	}
	function setAvatarDimensions($container) {
		$container.find('.userImage img').each(function() {
			var img=$(this);
			var cssProp = img.width() > img.height() ? 'width' : 'height';
			img.css(cssProp, 31);
		});
	}
	function backToGroups() {
		//usersBlock.hide();
		$('#usersCount_' + groupBlock.data('group_id'))
			.text(groupBlock.find('div.user').length);
		groupBlock.data('group_id', null).hide();
		groupsBlock.show();
		$('div.userItem').show();
	}
	function getGroupMembers(group_id, groupName, groupColor) {
		$.post(
			'',
			{
				YII_CSRF_TOKEN:YII_CSRF_TOKEN,
				'action':'getGroupMembers',
				'group_id':group_id
			},
			function(data) {
				$('div.userItem').show();
				var html='';
				$.each(data, function(k, v) {
					html+='\
					<div class="user round3">\
						<div class="userImage round6"><img class="round6" alt="" src="' + v.profile_img + '" /></div>\
						<div class="userName"><a href="' + baseUrl + '/user/view/' + v.user_id + '" target="_blank">' + v.name + ' ' + v.lname + '</a></div>\
						<span class="invis user_id">' + v.user_id + '</span>\
					</div>';
					$('#userItem_' + v.user_id).hide();
				});
				groupsBlock.hide();
				//usersBlock.show();
				groupBlock.find('.usersContainer').html(html);
				groupBlock.find('.groupCaption').html(groupName).css('background-color', groupColor);
				setAvatarDimensions(groupBlock);
				groupBlock.data('group_id', group_id).show();
			}
		);
	}
});