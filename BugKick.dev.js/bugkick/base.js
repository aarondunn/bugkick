/*!
 * BugKick core
 *
 * Requires jQuery.
 *
 * @author Evgeniy `f0t0n` Naydenov
 * @author Alexey Kavshirko
 * @copyright BugKick
 */
;(function($, window) {
    var document = window.document,
        baseNameSpace = 'bugkick';
    if(!window._bugKickKey || !window._bugKickPID || !window._widgetStyle) {
        return;
    }
	/**
	 * Provides a namespace for BugKick classes
	 */
	var _ = window[baseNameSpace] = window[baseNameSpace] || {};
    _.jQuery_URL = 'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'
    _.LIB_URL = 'http://bugkick.local/BugKick.dev.js/';
    _.apiKey = window._bugKickKey;
    _.projectID = window._bugKickPID;
    _.widgetStyle = window._widgetStyle;
	/**
	 *
	 * @param namespace {@string}
	 */
	_.namespace = function(namespace) {
		var branch = namespace.split('.'),
			path = _;
		if(branch.length > 0 && branch[0] == baseNameSpace) {
			branch.shift();
		}
		for(var i = 0, node; node = branch[i++];) {
			path[node] = path[node] || {};
			path = path[node];
		}
		return path;
	};

	/*	Namespace definitions	*/
    
	_.string = _.namespace('string');
	_.url = _.namespace('url');
	_.page = _.namespace('page');

	/****************************/

	/**
	* Concatenates string expressions. This is useful
	* since some browsers are very inefficient when it comes to using plus to
	* concat strings. Be careful when using null and undefined here since
	* these will not be included in the result. If you need to represent these
	* be sure to cast the argument to a String first.
	* For example:
	* <pre>buildString('a', 'b', 'c', 'd') -> 'abcd'
	* buildString(null, undefined) -> ''
	* </pre>
	* @param {...*} var_args A list of strings to concatenate. If not a string,
	*     it will be casted to one.
	* @return {string} The concatenation of {@code var_args}.
	*/
	_.string.buildString = function(var_args) {
		return Array.prototype.join.call(arguments, '');
	};

	_.url.REGEX_QUERY_ARRAY_PARAM = /\[\]$/;
	_.url.AMPERSAND = '&';

	_.url.queryStringToObject = function(queryString) {
		var queryObject = {};
		queryString.split(_.url.AMPERSAND).forEach(function(prop) {
			var pair = prop.split('='),
				key = pair.shift(),
				value = pair.shift();
			if(_.url.REGEX_QUERY_ARRAY_PARAM.test(key)) {
				key = key.replace(_.url.REGEX_QUERY_ARRAY_PARAM, '');
			}
			if(queryObject.hasOwnProperty(key)) {
				if(!$.isArray(queryObject[key])) {
					queryObject[key] = [queryObject[key]];
				}
				queryObject[key].push(value);
			} else {
				queryObject[key] = value;
			}
		});
		return queryObject;
	};

	_.page.appendToHead = function(element) {
		document.getElementsByTagName('head')[0].appendChild(element);
	};

	_.page.includeJs = function (src, opt_attr, innerHTML) {
		var script = document.createElement('script');
		script.type = 'text/javascript';
		if(!!src) {
			script.src  = src;
		}
		if(typeof opt_attr === 'object') {
            for(var attr in opt_attr) {
                script[attr] = opt_attr[attr];
            }
		}
		if(typeof innerHTML === 'string') {
			script.innerHTML = innerHTML;
		}
		_.page.appendToHead(script);
	};

	_.page.includeCss = function(href) {
		var link = document.createElement('link');
		link.rel= 'stylesheet';
		link.href = href;
		_.page.appendToHead(link);
	};
    _._url = function (relativePath) {
        return _.string.buildString(_.LIB_URL, relativePath);
    }
    function includeApi() {
        _.page.includeJs(_._url('bugkick/client.js'));
        _.page.includeJs(_._url('bugkick/bugkickui.js'));
    }
    if(typeof $ === 'undefined') {
        _.page.includeJs(_.jQuery_URL, {
            onload: includeApi
        });
    } else {
        includeApi();
    }

})(this.jQuery, this);