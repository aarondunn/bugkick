/*!
 * FProgressBar Plugin
 * version: 0.0.1 (13-DEC-2011)
 * @requires jQuery
 * @author Evgeniy `f0t0n` Naydenov
 *
 * Examples and documentation at: http://bugkick.com
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
;(function($) {
	$.fn.FProgressBars=[];
	$.fn.fprogressBar=function(options) {
		if(!this.data('isFProgressBar'))
			new ProgressBar(this, options).run();
	};
	function ProgressBar($element, options) {
		this._init($element, options);
		return this;
	}
	ProgressBar.prototype._init=function($element, options) {
		this.$element=$element;
		this.options={};
		this._defaults={};
		$.extend(this.options, this._defaults, options);
	};
	ProgressBar.prototype.setProgress=function(progress) {
		this.options.progress=progress;
	};
	ProgressBar.prototype.getHtml=function() {
		return '<div class="FProgress" style="width:'
			+ this.options.progress + '%;"><div class="FText">'
			+ this.options.progress + '%</div></div>';
	};
	ProgressBar.prototype.run=function() {
		this.$element
			.addClass('FProgressBarContainer')
			.html(this.getHtml())
			.data('isFProgressBar', true);
		return this;
	};
})(jQuery);