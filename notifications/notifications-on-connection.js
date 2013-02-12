var appUtils = require('./app-utils'),
log = appUtils.log,
app = require('./app'),
clients = app.clients,
projects = app.projects,
db = app.db,
notifications = app.notifications,
onConnection = module.exports = function(socket) {
	socket.on('setUserData', function(data) {
		if(data.user_id && !isNaN(data.user_id) 
			&& data.user_id > 0 && data.project_id !== undefined) {
			socket.set('userData', data, function() {
				if(data.project_id) {
					if(!projects[data.project_id])
						projects[data.project_id] = {};
					projects[data.project_id][socket.id]=socket.id;
				}
				if(!clients[data.user_id])
					clients[data.user_id] = {};
				// Save socket.id in the dictionary to be able
				// find sockets by user_id 
				// and send the message to specific socket
				// E.g.
				//	notifications.sockets[socket_id].emit('someEvent', data);
				clients[data.user_id][socket.id] = socket.id;
				socket.emit('setUserDataResponse', {
					success: true
				});
			});
		}
		else {
			socket.emit('setUserDataResponse', {
				success: false
			});
		}
	});
	socket.on('disconnect', function() {
		require('./notifications-on-disconnect')(socket);
	});
};