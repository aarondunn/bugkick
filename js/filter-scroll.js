/*!
 * Code to apply the pretty scrollbar to BugKick filter pane
 *
 * Authors:
 *       Boyan Yordanov,
 *       Evgeniy `f0t0n` Naydenov
 *
 * Date: 2012-07-01
 */
(function($, window, document) {
    var window$ = $(window),
        TREE_CHANGE_TIMER_ID = 'treeChangeTimer',
        sidebar$ = null,
        main$ = null,
        sideBarMiddle = null,
        timers = {},
        scrollPaneOptions = {
            hideFocus: true,
            verticalDragMaxHeight: 150,
            animateScroll: true
        };

    function setTimerOnce(func, delay, timerId) {
        if(!!timers[timerId]) {
            window.clearTimeout(timers[timerId]);
            delete timers[timerId];
        }
        timers[timerId] = window.setTimeout(func, delay);
    }


    function getScrollPaneActiveRects() {
        var offset = sidebar$.offset(),
        w = sidebar$.width(),
        h = sidebar$.height(),
        x = offset.left,
        y = offset.top,
        rectHeight = 100;
        return {
            top: {
                x: x,
                y: y - rectHeight,
                w: w,
                h: rectHeight
            },
            bottom: {
                x: x,
                y: y + h,
                w: w,
                h: rectHeight
            }
        };
    }

    var scrollTopInterval = false,
        scrollBottomInterval = false;

    function scrollThePane(e) {
        var rects = getScrollPaneActiveRects(),
        point = {x:e.pageX, y: e.pageY};
        var api = sideBarMiddle.data('jsp');
        if(inRect(point, rects.top) && !scrollTopInterval) {
            clearScrollIntervals();
            scrollTopInterval = setInterval(function() {
                api.scrollByY(-10, false);
            }, 50);
        } else if(inRect(point, rects.bottom) && !scrollBottomInterval) {
            clearScrollIntervals();
            scrollBottomInterval = setInterval(function() {
                api.scrollByY(10, false);
            }, 50);
        } else {
            clearScrollIntervals();
        }
    }

    function clearScrollIntervals() {
        if(scrollTopInterval) {
            clearInterval(scrollTopInterval);
            scrollTopInterval = false;
        }
        if(scrollBottomInterval) {
            clearInterval(scrollBottomInterval);
            scrollBottomInterval = false;
        }
    }

    function inRect(point, rect) {
        return point.x >= rect.x
            && point.x <= rect.x + rect.w
            && point.y >= rect.y
            && point.y <= rect.y + rect.h;
    }
    
    function applyJScrollPane() {
        //var delta = 148;
        //delta = $('#header').height() + $('#footer-wrapper').height() + 15;
        var delta = $(document).height() - main$.height() - 54 //15,
        height = window$.height(),
        portletHeight = sideBarMiddle.find('.portlet:first').height();
        if(window$.scrollTop() > main$.offset().top) {
            delta -= $('#header').height();
        }
        height -= delta;
        if(height > portletHeight) {
            height = portletHeight + 30;
        }
        sideBarMiddle
            .css({height: height})
            .jScrollPane(scrollPaneOptions);
    }
/*
                                                        <applyJScrollPaneOld>
    function applyJScrollPaneOld() {
        sideBarMiddle.css({'height':'auto'}); // Used to resize the sidebar acording to the elements' state
        var windowSize = window$.height(); // Current window size. Used with some math to estimete how to evaluate the logical expression
        var toSetToSideBar = windowSize - 100; // The size, which will be applied to sidebar_middle, which is different on some ocasions

        // Acording to the position property, decides if the header is present or not
        // If the header is present, sets different values for the window size and the new sidebar size
        var sidebarPosition = $('#sidebar').css('position');
        if( sidebarPosition != 'fixed' && sidebarPosition!= 'absolute') {
            //console.log('Position Detection: ', 'entered');
            toSetToSideBar = windowSize - 140;
            windowSize -= 160;
        }

        //console.log('Sidebar Height Change: ', $('.sidebar_middle').height());
        //console.log('Current Window Size: ', windowSize);

        // Decides if jScrollPane should to be applied or not
        if(sideBarMiddle.height() > windowSize) {
            sideBarMiddle.css({
                'height': toSetToSideBar
            });
            sideBarMiddle.jScrollPane({hideFocus: true});
        } else {
            // Destroys the jScrollPane if it has been applied, but now is not necessary
            var api = sideBarMiddle.data('jsp');
            console.log(api);
            if(api) {
                api.destroy();
            }
            // Initialize the sidebar height after jScrollPane is destroyed
            // to wrap around its content
            sideBarMiddle.css({'height': 'auto'});
        }
    }
                                                        </applyJScrollPaneOld>
*/
    // on document ready:
    $(function() {
        if(!$('#filterForm').length) {
            return;
        }
        /**
         * Reference to element to which jScrollPane is applied.
         * Used for jScrollPane api functions.
         */
        sidebar$ = $('#sidebar');
        main$ = $('#main').css('min-height', 400);
        sideBarMiddle =  $('.sidebar_middle:first', sidebar$);
        $(document).on('simpleTreeMenu.change', function(e) {
            //setTimerOnce(applyJScrollPane, 500, TREE_CHANGE_TIMER_ID);
            applyJScrollPane();
        });
        window$.on('resize', function() {
            applyJScrollPane();
        });
        /*
        $('#sidebar .tree li').on('click', function() {
            applyJScrollPane();
        });
        window$.on('resize', function() {
            applyJScrollPane();
        });*/
        
        //Doesn't work as expected
        $(document).on('scroll', function(e) {
            if(e.target == document) {
                applyJScrollPane();
            }
        })
        .on('drag sort',
            '#bug-list, #bugkick-multidrag-container', scrollThePane);
        var mainH = main$.height();
        window.setInterval(function() {
            var h = main$.height();
            if(h != mainH) {
                applyJScrollPane();
                mainH = h;
            }
        }, 250);
    });
})(this.jQuery, this, this.document);