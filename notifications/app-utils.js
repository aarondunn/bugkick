var config = require('./config/main');
var utils = module.exports = {
	log: function() {
		if(!config.params.DEBUG)
			return;
		var args = ['   [LOG] :: ', new Date().toUTCString(), ' :: '];
		for(var i in arguments)
			args.push(arguments[i]);
		console.log.apply(console, args);
	},
	isEmpty: function(obj) {
		var type= typeof obj;
		if(type != 'object' && type != 'string')
			throw new TypeError('The parameter has to be a string, array or object.');
		// Assume if it has a length property 
		// with a non-zero value (for strings and arrays)
		if (obj.length && obj.length > 0)
			return false;
		for(var key in obj)
			if(Object.prototype.hasOwnProperty.call(obj, key))
				return false;
		return true;
	}
};