var appUtils = require('./app-utils'),
log = appUtils.log,
config = module.exports.config = require('./config/main'),
clients = module.exports.clients = {},
projects = module.exports.projects = {},
db = module.exports.db = null;/*require('./db')
.on('error', function(err) {
	log('Error ' + err);
});*/
//	Setup HTTP server:
var server = require('./server');
server.listen(config.params.IO_PORT);
//	Sockets logic:
var io = require('socket.io').listen(server);
io.configure('production', function(){
	io.enable('browser client etag');
	io.enable('browser client minification');
	io.set('log level', 1);
	io.set(
		'transports',
		[
			'websocket',
			'flashsocket',
			'htmlfile',
			'xhr-polling',
			'jsonp-polling'
		]
	);
});
var notifications = module.exports.notifications = io.of('/notifications');
notifications.on('connection', require('./notifications-on-connection'));