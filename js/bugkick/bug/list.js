/**
 * The logic that provides the ability to drag and drop multiple ticket items.
 *
 * Requires jQuery
 * @author Evgeniy `f0t0n` Naydenov
 */
(function($, window, document) {
    var _ = bugkick.namespace('bug.list'),
        window$ = $(window),
        document$ = $(document),
        mouseSelection = new bugkick.MouseSelection('main-wrapper'),
        DRAG_CONTAINER_ID = 'bugkick-multidrag-container',
        dragContainer$ = null;
    _.CheckedItems = {};
    _.Selector = {
        CHECKBOX: '#bug-list .items .ticket-item .checkbox',
        CHECKBOX_CHECKED: '#bug-list .items .ticket-item .checkbox.checked',
        CHECKBOX_UNCHECKED: '#bug-list .items .ticket-item .checkbox.unchecked',
        TICKET_ITEM: '#bug-list .items .ticket-item',
        TICKET_ITEM_UNCHECKED: '#bug-list .items .ticket-item.unchecked',
        TICKET_ITEM_CHECKED: '#bug-list .items .ticket-item.checked'
    },
    _.Template = {
        DRAG_CONTAINER: '<div id="content"><div id="main"></div></div>'
    },
    _.DraggableOptions = {
        start: function(event, ui) {
            if(!!window.FilterEventHandlers.onDragStart) {
                window.FilterEventHandlers.onDragStart();
            }
        },
        stop: function(event, ui) {
            if(!!window.FilterEventHandlers.onDragStop) {
                window.FilterEventHandlers.onDragStop(event, ui);
            }
            $(this).hide().html('');
        },
        refreshPositions: true,
        zIndex: 3000,
        cursor: 'move',
        revert: true,
        revertDuration: 0
    };

    function getCb$(el) {
        return $('.checkbox', $(el));
    }

    function isChecked(el$) {
        return el$.hasClass('checked');
    }

    function isCheckBox(el) {
        return !!el && $(el).hasClass('checkbox');
    }
    
    function dragStart(e) {
        if(isCheckBox(e.target)) {
            return;
        }
        var el$ = $(this),
            offset = el$.offset(),
            ticketList$ = $('#bug-list').clone(),
            itemWidth = $(_.Selector.TICKET_ITEM + ':first').width();
            ticketList$
                .find('.items .ticket-item.unchecked, .summary, .list-pager, .keys, .colorTip')
                .remove();
        el$.trigger('mouseup');
        dragContainer$.html(_.Template.DRAG_CONTAINER)
            .find('#main').html(ticketList$);
        dragContainer$.css({
            left: offset.left,
            top: offset.top,
            width: itemWidth
        }).show().trigger(e);
    }
    
    document$.on('mouseenter', _.Selector.TICKET_ITEM, function() {
        getCb$(this).removeClass('opacity05');
    }).on('mouseleave', _.Selector.TICKET_ITEM, function() {
        var cb$ = getCb$(this);
        if(cb$.hasClass('unchecked')) {
            cb$.addClass('opacity05');
        }
    }).on('click', _.Selector.CHECKBOX, function(e) {
        var el$ = $(this),
            ticketItem$ = el$.parent('.ticket-item'),
            ticketID = ticketItem$.data('ticketId');
        $.each([el$, ticketItem$], function(i, el$) {
            el$.toggleClass('checked unchecked').removeClass('opacity05');
        });
        if(isChecked(ticketItem$)) {
            ticketItem$.on('mousedown', dragStart);
            _.CheckedItems[ticketID] = ticketID;
        } else {
            ticketItem$.off('mousedown', dragStart);
            el$.hide();
            delete _.CheckedItems[ticketID];
        }
    }).on(bugkick.MouseSelection.EventType.ACTIVATE, function() {
        $(_.Selector.CHECKBOX_CHECKED).click().mouseout().hide();
    }).on(bugkick.MouseSelection.EventType.SELECTION_CHANGE, function(e, data) {
        $(_.Selector.TICKET_ITEM).each(function() {
            var el$ = $(this),
                cb$ = getCb$(this);
            if(mouseSelection.intersectsWith(el$)) {
                if(!isChecked(el$)) {
                    cb$.show().click();
                }
            } else {
                if(isChecked(el$))
                    cb$.hide().click();
            }
        });
    });
    $(function() {
        dragContainer$ = $(bugkick.string.buildString(
            '<div id="', DRAG_CONTAINER_ID, '" class="opacity03 round5 ticket-item"></div>'
        ));
        $('body').append(dragContainer$);
        dragContainer$
            .draggable(_.DraggableOptions)
            .on('mouseup', function() {
            $(this).hide();
        });
        $('#bug-list div.imageset').imageSet();
    });
})(this.jQuery, this, this.document);