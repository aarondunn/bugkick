var cluster = require('cluster');
var http = require('http');
var numCPUs = require('os').cpus().length;
if (cluster.isMaster) {
	console.log('number of CPUs:\t' + numCPUs);
	// Fork workers.
	for (var i = 0; i < numCPUs; i++) {
		cluster.fork();
	}
	cluster.on('death', function(worker) {
		console.log('worker ' + worker.pid + ' died. restart...');
		cluster.fork();
	});
} else {
	// Worker processes have a http server.
	http.Server(function(request, response) {
		var body = 'hello world!';
		response.writeHead(200, {
			'Content-Length': body.length,
			'Content-Type': 'text/plain' 
		});
		response.write(body);
		response.end('');
	}).listen(8000);
	console.log('Server running at http://127.0.0.1:8080/');
}