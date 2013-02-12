(function() {
	$.jGrowl.defaults.position='bottom-right';
    $.jGrowl.defaults.life=6000;
	var notifications = null,
	NotificationHandler = {
		//	New ticket
		'0': function(data) {
			$.jGrowl(
				'New <a href="' + data.ticket_url + '" target="_blank">Ticket #'
					+ data.ticket_number + '</a> assigned to you'
			);
		},
		//	Ticket changed
		'1': function(data) {
			$.jGrowl(
				'<a href="' + data.ticket_url + '" target="_blank">Ticket #' 
				+ data.ticket_number + '</a> has been changed'
			);
		},
		//	Ticket deadline reached
		'2': function(data) {
			$.jGrowl(
				'The deadline passed for <a href="' 
					+ data.ticket_url + '" target="_blank">Ticket #' 
					+ data.ticket_number + '</a>'
			);
		},
		//	Tickets order changed
		'3': function(data) {
			$('#bug-list').yiiListView.update('bug-list');
		},
		//	New comment on ticket
		'4': function(data) {
			$.jGrowl(
				'New comment created on <a href="' 
					+ data.ticket_url + '" target="_blank">Ticket #' 
					+ data.ticket_number + '</a>.'
			);
		}
	};
	function initSocket() {
		//var baseUrl = window.location.protocol + '//' + window.location.host + ':27000';
		var baseUrl = 'https:' + '//' + window.location.host + ':' + (window.notificationsPort || '27000');
		var userData=JSON.parse($('#user_data').text());
		var user_id = parseInt(userData.user_id, 10);
		var project_id = parseInt(userData.project_id, 10);
		notifications = io.connect(baseUrl + '/notifications', {secure: true});
		notifications.on('connect', function() {
			if(!isNaN(user_id)) {
				notifications.on('setUserDataResponse', function(data) {
					// The user is added to collection if data.success === true
					// console.log(data);
				});
				notifications.emit('setUserData', {
					'user_id': user_id,
					'project_id': project_id
				});
			}
		});
		notifications.on('notification', function(data) {
			data.forEach(function(dataItem) {
				if(dataItem.message_type
						&& NotificationHandler[dataItem.message_type]) {
					NotificationHandler[dataItem.message_type](dataItem);
				}
			});
		});
	}
	$(window).load(function () {
		if(typeof io != 'undefined') {
			initSocket();
		}
	});
})();