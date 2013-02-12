/*!
 * The ImageSet jQuery plugin
 * that provides the ability to view the collection of images in popup.
 *
 * Requires jQuery, colorTip
 * @author Evgeniy `f0t0n` Naydenov
 */

(function($, window, document) {
    var defaultOptions = {
        rowSize: 2,         // 2 items in a row
        imageWidth: 31,     // image width is 31px by default
        imageHeight: 31,     // image height is 31px by default
        colorTipOptions: {
			color: 'black',
			timeout: 100,
            getTipContent: function(el$) {
                var maxWidth = defaultOptions.imageWidth * defaultOptions.rowSize + 12,
                content$ = $('<div style="text-align: left; max-width:'
                    + maxWidth +'px"></div>'),
                images$ = $('img', el$),
                w = Math.floor(el$.width() / 2),
                imgCnt = 0;
                images$.each(function() {
                    var img$ = $(this),
                    imgItem$ = img$.parent('a').clone();
                    imgItem$.find('img').css({
                        'float': 'left',
                        'margin': '3px'
                    });
                    content$.append(imgItem$);
                    if(++imgCnt > 4) {
                        img$.parent('a').hide();
                    } else {
                        var css = {
                            'width': w,
                            'height': w
                        };
                        img$.parent('a')./*css({
                            'width': w,
                            'height': w
                        }).*/replaceWith(
                            $('<div></div>').css(css).html(
                                img$.clone().css(css)
                            )
                        );
                    }
                });
                return $('<div></div>').append(content$).html();
            }
		}
    },
    methods = {
        init: function(options) {
            var settings = $.extend({}, defaultOptions, options);
            this.css({
                display: 'inline-block'
            });
            return this.colorTip(settings.colorTipOptions);
//            return this.each(function() {
//            });
        }
    };

    $.fn.imageSet = function(method) {
        // Method calling logic
        if(methods[method]) {
            return methods[method].apply(this,
                Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            return $.error(bugkick.string.buildString(
                'Method ',  method, ' does not exist on jQuery.imageSet'));
        }
        return this;
    };
})(this.jQuery, this, this.document);