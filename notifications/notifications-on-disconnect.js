var appUtils = require('./app-utils'),
log = appUtils.log,
isEmpty = appUtils.isEmpty,
app = require('./app'),
clients = app.clients,
projects = app.projects,
db = app.db,
onDisconnect = module.exports = function(socket) {
	socket.get('userData', function(err, data) {
		if(err || !data)
			return;
		if(data.user_id && clients[data.user_id]) {
			delete clients[data.user_id][socket.id];
			if(isEmpty(clients[data.user_id]))
				delete clients[data.user_id];
		}
		if(data.project_id && projects[data.project_id]) {
			delete projects[data.project_id][socket.id];
			if(isEmpty(projects[data.project_id]))
				delete projects[data.project_id];
		}
	});
};