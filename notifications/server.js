var notifications = null,
	clients = null,
	projects = null,
	appUtils = require('./app-utils'),
	log = appUtils.log,
	qs = require('querystring'),
	app = require('./app'),
	db = app.db,
	fs = require('fs'),
	server = module.exports = require('https').createServer({
		key: fs.readFileSync(app.config.params.SSL.key),
		cert: fs.readFileSync(app.config.params.SSL.cert)
	})
.on('connect', function(req, res) {
	res.writeHead(200);
	res.end();
})
.on('request', function(req, res) {
	if(req.method == 'POST') {
		handlePost(req, res);
		return;
	}
	res.writeHead(200);
	res.end();
});
//	HTTP POST request handler:
function handlePost(req, res) {
	var clients = app.clients,
		projects = app.projects,
		notifications = app.notifications,
		reqBody = '';
	req.on('data', function (data) {
		reqBody += data;
	});
	req.on('end', function () {
		var post = qs.parse(reqBody);
		var responseData = {success: false};
		//	handle request and notify the target socket
		if(post.user_id && clients
			&& Object.prototype.hasOwnProperty.call(post, 'user_id')) {
			//	User is on-line
			if(clients[post.user_id]) {
				for(var socket_id in clients[post.user_id]) {
					var socket = notifications.sockets[socket_id];
					if(!socket.disconnected)
						socket.emit('notification', [post]);
				}
				responseData.success = true;
			}
		}
		else if(post.project_id && projects && projects[post.project_id]) {
			for(socket_id in projects[post.project_id]) {
				socket = notifications.sockets[socket_id];
				if(!socket.disconnected)
					socket.emit('notification', [post]);
			}
				
		}
		var body = JSON.stringify(responseData);
		res.writeHead(200, {
			'content-type': 'text/json; charset=utf-8',
			'content-length': body.length,
			'connection': 'close',
			'transfer-encoding': 'chunked'
		});
		res.end(body, 'utf-8');
	});
}