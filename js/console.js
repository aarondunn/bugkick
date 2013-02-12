/*!
 * The wrapper for window.console
 * @version: 1.0 (14-DEC-2011)
 * @author Evgeniy `f0t0n` Naydenov
 * @see http://getfirebug.com/wiki/index.php/Console_API
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
;(function() {
	var debug = true;
	var __console = window.console;
	window.console = {};
	[
		'log',
		'error',
		'warn',
		'info',
		'clear',
		'count',
		'debug',
		'trace',
		'exception',
		'dir',
		'dirxml',
		'assert',
		'time',
		'timeEnd',
		'profile',
		'profileEnd',
		'group',
		'groupEnd',
		'memoryProfile',
		'memoryProfileEnd',
		'table',
		'timeStamp',
		'trace'
	].forEach(function(method) {
		window.console[method] = function() {
			return (debug && __console && __console[method])
				? __console[method].apply(__console, arguments)
				: null;
		}
	});
})();
//	The wrappers for most popular window.console methods:
function log() {
	return window.console.log.apply(window.console, arguments);
}
function info() {
	return window.console.info.apply(window.console, arguments);
}
function error() {
	return window.console.error.apply(window.console, arguments);
}
function warn() {
	return window.console.warn.apply(window.console, arguments);
}