/*!
 * FlashMessage jQuery plug-in
 * 
 * Requires: jQuery
 * @version 1.0 (14-DEC-2011)
 * @author Evgeniy `f0t0n` Naydenov
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
;(function($) {
	var FlashMessageType = {
		PROGRESS: 'progress',
		MESSAGE: 'message',
		ERROR: 'error',
		SUCCESS: 'success',
		INFORMATION: 'information',
		WARNING: 'warning'
	};

	var FlashMessageIcon = {
		SUCCESS: 'f0-fm-icon success',
		INFORMATION: 'information',
		WARNING: 'f0-fm-icon warning',
		ERROR: 'f0-fm-icon error'
	};

	function FlashMessage(message) {
		return this._init(message);
	}

	FlashMessage.prototype._init = function(message) {
		this.messageType = FlashMessageType.MESSAGE;
		this.autoHideInterval = 3500;
		this.msg = message || '';
		this.isError = false;
		this.iconClass = null;
		this.hideTimeout = null;
		this.id = 'f0FlashMessage_'
			+ new Date().getTime().toString(10)
			+ '_'
			+ Math.round(Math.random() * 100000);
		this.containerID = this.id + '_container';
		this.html = '<div id="' + this.containerID + '" class="f0-fm-container">\
			<div id="' + this.id + '" class="f0-fm message">' + this.msg + '</div>\
		</div>';
		this.progressBarCssClass = 'f0-fm-progress';
		this.progressBar ='<div class="' + this.progressBarCssClass + '"></div>';
		this.body = $('body');
		this.body.prepend(this.html);
		this.container = $('#' + this.containerID);
		this.element = $('#' + this.id);
		return this;
	};
	
	FlashMessage.prototype.setIcon = function(flashMessageIcon) {
		if(!flashMessageIcon)
			return this;
		this.removeIcon()
			.element
			.append('<div id="f0-fm-icon_'
				+ this.id + '" class="f0-fm-icon '
				+ flashMessageIcon + '"></div>'
			);
		this.iconClass = flashMessageIcon;
		return this;
	};
	
	FlashMessage.prototype.removeIcon = function() {
		if(this.iconClass !== null) {
			this.element.remove('#f0-fm-icon_' + this.id);
			this.iconClass = null;
		}
		return this;
	};
	
	FlashMessage.prototype.message = function(message) {
		this.messageType = FlashMessageType.MESSAGE;
		this.setIsError(false);
		this._message(message, null).show(true);
		return this;
	};
	
	FlashMessage.prototype.information = function(message) {
		this.messageType = FlashMessageType.INFORMATION;
		this.setIsError(false);
		this._message(message, FlashMessageIcon.INFORMATION).show(true);
		return this;
	};
	
	FlashMessage.prototype.success = function(message) {
		this.messageType = FlashMessageType.SUCCESS;
		this.setIsError(false);
		this._message(message, FlashMessageIcon.SUCCESS).show(true);
		return this;
	};
	
	FlashMessage.prototype.warning = function(message) {
		this.messageType = FlashMessageType.WARNING;
		this.setIsError(false);
		this._message(message, FlashMessageIcon.WARNING).show(true);
		return this;
	};
	
	FlashMessage.prototype.error = function(message) {
		this.messageType = FlashMessageType.ERROR;
		this.setIsError(true);
		this._message(message, FlashMessageIcon.ERROR).show(true);
		return this;
	};
	
	FlashMessage.prototype.progress = function() {
		return this.beginProgress();
	};
	
	FlashMessage.prototype.beginProgress = function() {
		this.messageType = FlashMessageType.PROGRESS;
		this.setIsError(false);
		this._message(this.progressBar, null).show(false);
		this.element.data('isProgress', true);
		return this;
	};
	
	FlashMessage.prototype.endProgress = function() {
		if(this.element.data('isProgress'))
			return this._hide();
		return this;
	};
	
	FlashMessage.prototype._setMessage = function(message) {
		this.msg = message || '';
		return this;
	};
	
	FlashMessage.prototype._message = function(message, icon) {
		this._setMessage(message);
		this.element.html('').prepend(this.msg);
		if(icon)
			this.setIcon(icon);
		else
			this.removeIcon();
		return this;
	};
	
	FlashMessage.prototype._addErrorClass = function () {
		if(!this.element.hasClass('error'))
			this.element.addClass('error');
		return this;
	};
	
	FlashMessage.prototype._removeErrorClass = function() {
		if(this.element.hasClass('error'))
			this.element.removeClass('error');
		return this;
	};
	
	FlashMessage.prototype.setIsError = function(isError) {
		this.isError = isError;
		return this.isError ? this._addErrorClass() : this._removeErrorClass();
	};
	
	FlashMessage.prototype._showCallback = function() {
		var self = this;
		if(this.autoHide)
			self.hideTimeout = window.setTimeout(
				function() {
					self._hide();
				},
				self.autoHideInterval
			);
	};
	
	FlashMessage.prototype.show = function(autoHide) {
		this.autoHide = autoHide;
		var self = this;
		if(self.hideTimeout) {
			window.clearTimeout(self.hideTimeout);
			self.hideTimout = null;
		}
		var height = self.container.height() + 'px';
		if(!self.container.is(':visible')) {
			self.container
				.css('top', '-' + height)
				.show('fast', function() {
					self.container.animate(
						{
							top: '0px'
						},
						'slow',
						'swing',
						function() {
							self._showCallback();
						}
					);
				});
		}
		else
			this._showCallback();
		return this;
	};
	
	FlashMessage.prototype.setAutoHideInterval = function(autoHideInterval) {
		this.autoHideInterval = autoHideInterval || 3500;
		return this;
	};
	
	FlashMessage.prototype._hide = function() {
		var self = this;
		var height = self.container.height() + 'px';
		self.container.animate(
			{
				top: '-' + height
			},
			'slow',
			'swing',
			function() {
				self.container.hide();
			}
		);
		return this;
	};
	
	$.__f0__FlashMessage_instance = null;
	
	$.flashMessage = function(message) {
		var msg = message || '';
		if($.__f0__FlashMessage_instance === null)
			$.__f0__FlashMessage_instance = new FlashMessage(msg);
		return $.__f0__FlashMessage_instance;
	};
})(jQuery);