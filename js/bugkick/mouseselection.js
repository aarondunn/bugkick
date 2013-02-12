/**
 * The MouseSelection class
 * that provides the ability to select area on page using mouse.
 *
 * Requires jQuery
 * @author Evgeniy `f0t0n` Naydenov
 */

(function($, window, document) {
    var _ = window.bugkick,
        window$ = $(window),
        document$ = $(window.document),
        body$;

    _.MouseSelection = function(targetID) {
        this.targetID = targetID;
        this.targetSelector = bugkick.string.buildString('#', this.targetID);
        this.selection$ = $(_.MouseSelection.Template.SELECTION);
        this.isReady = false;
        this.isActive = false;
        this.startX = 0;
        this.startY = 0;
        this.run();
    };

    _.MouseSelection.defaultCss = {
        width: 0,
        height: 0
    };

    _.MouseSelection.EventNamePrefix = 'bugkick.bug.list.MouseSelection.';

    _.MouseSelection.getEventName = function(eventName) {
        return bugkick.string.buildString(_.MouseSelection.EventNamePrefix,
            eventName);
    };

    _.MouseSelection.EventType = {
        READY: _.MouseSelection.getEventName('ready'),
        ACTIVATE: _.MouseSelection.getEventName('activate'),
        DEACTIVATE: _.MouseSelection.getEventName('deactivate'),
        SELECTION_CHANGE: _.MouseSelection.getEventName('selectionChange')
    };

    _.MouseSelection.Template = {
        SELECTION: bugkick.string.buildString(
            '<div id="bug-list-mouse-selection" ',
            'class="bug-list-mouse-selection opacity03"></div>'
        )
    };

    _.MouseSelection.prototype.run = function() {
        this.initEventHandlers();
    };

    _.MouseSelection.prototype.setSelectable = function(isSelectable) {
        /*
        if(isSelectable) {
            body$.removeAttr('unselectable').removeClass('unselectable');
        } else {
            body$.attr('unselectable', 'on').addClass('unselectable');
        }
        */
    };

    _.MouseSelection.prototype.setIsActive = function(isActive) {
        this.isActive = !!isActive;
        var event = null;
        if(this.isActive) {
            this.setSelectable(false);
            event = _.MouseSelection.EventType.ACTIVATE;
        } else {
            this.setSelectable(true);
            event = _.MouseSelection.EventType.DEACTIVATE;
        }
        document$.trigger(event);
    };
    _.MouseSelection.prototype.getIsActive = function() {
        return this.isActive;
    };

    _.MouseSelection.prototype.validateEventTarget = function(e) {
        return e.target.id == this.targetID;
    };

    _.MouseSelection.prototype.setStartPoint = function(x, y) {
        this.startX = x;
        this.startY = y;
    };

    _.MouseSelection.prototype.resetStartPoint = function() {
        this.setStartPoint(0, 0);
    };

    _.MouseSelection.prototype.initStartPoint = function(e) {
        this.setStartPoint(e.pageX, e.pageY);
    };

    _.MouseSelection.prototype.reset = function(e) {
        this.selection$.hide();
        this.resetStartPoint();
        this.setIsActive(false);
    };

    _.MouseSelection.prototype.intersectsWith = function(el$) {
        if(!this.getIsActive()) {
            return false;
        }
        rect1 = this.getRect(el$);
        rect2 = this.getRect(this.selection$);
        return rect1.x1 < rect2.x2
            && rect1.x2 > rect2.x1
            && rect1.y1 < rect2.y2
            && rect1.y2 > rect2.y1;
    };

    _.MouseSelection.prototype.getRect = function(el$) {
        var offset = el$.offset();
        return {
            x1: offset.left,
            y1: offset.top,
            x2: offset.left + el$.width(),
            y2: offset.top + el$.height()
        };
    }

    _.MouseSelection.prototype.setLayout = function(left, top, width, height) {
        var layout = {
            left: left,
            top: top,
            width: width,
            height: height
        };
        this.selection$.css(layout);
        document$.trigger(_.MouseSelection.EventType.SELECTION_CHANGE, {
            layout: layout});
    };

    _.MouseSelection.prototype.handleMove = function(e) {
        var x = e.pageX,
            y = e.pageY,
            left = 0,
            top = 0,
            w = 0,
            h = 0,
            docW = document$.width(),
            docH = document$.height();
        if(x > docW - 5) {
            x = docW - 5;
        }
        if(y > docH - 5) {
            y = docH - 5;
        }
        if(x > this.startX && y > this.startY) {
            left = this.startX;
            top = this.startY;
            w = x - this.startX;
            h = y - this.startY;
        } else if(x < this.startX && y < this.startY) {
            left = x;
            top = y;
            w = this.startX - x;
            h = this.startY - y;
        } else if(x > this.startX && y < this.startY) {
            left = this.startX;
            top = y;
            w = x - this.startX;
            h = this.startY - y;
        } else if(x < this.startX && y > this.startY) {
            left = x;
            top = this.startY;
            w = this.startX - x;
            h = y - this.startY;
        }
        this.setLayout(left, top, w, h);
    };

    _.MouseSelection.prototype.initEventHandlers = function() {
        var mouseSelection = this;
        document$.on('mousedown', mouseSelection.targetSelector, function(e) {
            if(!mouseSelection.validateEventTarget(e)) {
                return;
            }
            mouseSelection.initStartPoint(e);
            mouseSelection.selection$
                .css($.extend({
                    left: mouseSelection.startX,
                    top: mouseSelection.startY
                }, _.MouseSelection.defaultCss))
                .show();
            mouseSelection.setIsActive(true);
        }).on('mouseup', function() {
            if(mouseSelection.getIsActive()) {
                mouseSelection.reset();
            }
        }).on('mousemove', function(e) {
            if(mouseSelection.getIsActive()) {
                mouseSelection.handleMove(e);
            }
        }).on(_.MouseSelection.EventType.READY, function() {
            mouseSelection.isReady = true;
        });
        $(function() {
            body$ = $('body').append(mouseSelection.selection$);
            document$.trigger(_.MouseSelection.EventType.READY);
        });
    };
})(this.jQuery, this, this.document);