/*!
 * BugKick core
 *
 * Requires jQuery.
 *
 * @author Evgeniy `f0t0n` Naydenov
 */
;(function(window) {
    var baseNameSpace = 'bugkick';
	/**
	 * Provides a namespace for BugKick classes
	 */
	var _ = window[baseNameSpace] = window[baseNameSpace] || {};
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

	_.viewData = _.namespace('viewData');
	_.string = _.namespace('string');
	_.ajax = _.namespace('ajax');
	_.url = _.namespace('url');
	_.page = _.namespace('page');
	_.time = _.namespace('time');

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

	_.ajax.dataCSRF = function(data) {
		var data_ = data || {};
		if(typeof YII_CSRF_TOKEN == 'undefined') {
			return data_;
		}
		return $.extend({}, data_, {YII_CSRF_TOKEN: YII_CSRF_TOKEN});
	};

	_.ajax.updateElement = function(options) {
		var defaultOptions = {
			selector: null,
			url: null,
			data: {},
			callback: function() {},
			onError: function() {}
		},
		opt = $.extend({}, defaultOptions, options);
		$.get(opt.url, opt.data, function(responseData) {
			if(responseData) {
				$(opt.selector).html(responseData);
			}
			opt.callback.apply(this, arguments);
		}).error(opt.onError);
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
			$.each(opt_attr, function(attr, val) {
				script[attr] = val;
			});
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

    //converts utc timestamp to local time
    _.time.toLocal = function(container, format) {
        var utcTimestamps = $(container);
        $.each(utcTimestamps, function() {
            $(this).html(
                moment.unix($(this).attr('utc-timestamp')).format(format)
            );
        });
    }
})(this);