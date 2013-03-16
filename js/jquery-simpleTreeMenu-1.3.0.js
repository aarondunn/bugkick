/* SimpleTreeMenu */

(function($) {

    var CHANGE_EVENT = 'simpleTreeMenu.change';
    var ANIMATION_SPEED = 10;
    function raiseChange(data) {
        $(document).trigger(CHANGE_EVENT, data || {});
    }
	var methods = {
		
		init: function() {
	    	return this.each(function() {
	    		var $this = $(this);
				if ($this.hasClass("simpleTreeMenu") === false) {
					$this.hide();
					$(this).addClass("simpleTreeMenu");
					$this.children("li").each(function() {
						methods.buildNode($(this));
					});	
					if (_private.hasLocalStorage() === true) {
						state = localStorage.getItem(_private.localStorageKey.apply(this))
						if (state != null) {
							state = state.split(",");
							if (state.length > 0) {
								methods.deserialize.call(this, state);
							}
						}
					}
					$(this).show();
                    raiseChange();
				}
	    	});		
		},
		
		buildNode: function($li) {
			if ($li.children("ul").length > 0) {
				$li.children("ul").hide();
                raiseChange();
				$li.addClass("Node").click(function(event) {
					var $t = $(this);
					if ($t.hasClass("expanded")) {
						$t.children("ul").slideToggle('fast', function(){
                            $t.removeClass("expanded");
                            applyScrollPane();
                            raiseChange();
                        });

                        //Remember state of filters
                        if(typeof(localStorage) != 'undefined' ) {
                            if ($li.parent().attr('id') == 'statusTree'){
                                localStorage.removeItem('statusTree');
                            }
                            else if ($li.parent().attr('id') == 'userTree'){
                                localStorage.removeItem('userTree');
                            }
                            else if ($li.parent().attr('id') == 'labelTree'){
                                localStorage.removeItem('labelTree');
                            }
                            else if ($li.parent().attr('id') == 'groupTree'){
                                localStorage.removeItem('groupTree');
                            }
                            else if ($li.parent().attr('id') == 'filterTree'){
                                localStorage.removeItem('filterTree');
                            }
                         }

					}
					else {
						$t.addClass("expanded").children("ul").slideToggle('fast', function(){
                            applyScrollPane();
                            raiseChange();
                        });
                        //Remember state of filters
                        if(typeof(localStorage) != 'undefined' ) {
                            if ($li.parent().attr('id') == 'statusTree'){
                                localStorage.setItem('statusTree', '1');
                            }
                            else if ($li.parent().attr('id') == 'userTree'){
                                localStorage.setItem('userTree', '1');
                            }
                            else if ($li.parent().attr('id') == 'labelTree'){
                                localStorage.setItem('labelTree', '1');
                            }
                            else if ($li.parent().attr('id') == 'groupTree'){
                                localStorage.setItem('groupTree', '1');
                            }
                            else if ($li.parent().attr('id') == 'filterTree'){
                                localStorage.setItem('filterTree', '1');
                            }
                         }

					}
					event.stopPropagation();
				});  
                if ($li.children("ul").children("li").length == 0) {
                    $li.addClass("EmptyNode");
                }
				$li.children("ul").children("li").each(function() {
					methods.buildNode($(this));
				});
			} else {
				$li.addClass("Leaf").click(function(event) {
					event.stopPropagation();
				});
				return;
			}		
		},
		
		serialize: function() {
			state = [];
			$('.Node, .Leaf', $(this)).each(function() {
				var s = $(this).hasClass("expanded") ? _private.EXPANDED : _private.COLLAPSED;
				state.push(s);
			});
			if (_private.hasLocalStorage() === true) {
				localStorage.setItem(_private.localStorageKey.apply(this), state.join());
			}
		},

		deserialize: function(state) {
			$('.Node, .Leaf', $(this)).each(function(index) {
				if (state[index] == _private.EXPANDED) {
					$(this).addClass("expanded").children("ul").show();
                    raiseChange();
				}
			});
		},
				
		expandToNode: function($li) {
			if ($li.parent().hasClass("simpleTreeMenu")) {
				if (!$li.hasClass("expanded")) {
					$li.addClass("expanded").children("ul").show();
                    raiseChange();
				}
			}
			$li.parents("li", "ul.simpleTreeMenu").each(function() {
				var $t = $(this);
				if (!$t.hasClass("expanded")) {
					$t.addClass("expanded").children("ul").show();
                    raiseChange();
				}
			});
		},
		
		expandAll: function() {
			$(this).find("li.Node").each(function() {
				$t = $(this);
				if (!$t.hasClass("expanded")) {
					$t.addClass("expanded").children("ul").show();
                    raiseChange();
				}
			});	
		},
		
		closeAll: function() {
			$("ul", $(this)).hide();
			var $li = $("li.Node");
			if ($li.hasClass("expanded")) {
				$li.removeClass("expanded");
			}
            raiseChange();
		}		
		
	};
	
	var _private = {
	
		EXPANDED: "expanded",
		COLLAPSED: "collapsed",
		localStorageKeyPrefix: "jQuery-simpleTreeMenu-treeState-",
			
		hasLocalStorage: function() {
			if (localStorage && localStorage.setItem && localStorage.getItem) {
				return true;
			}
			else {
				return false;
			}
		},
				
		localStorageKey: function() {
			return _private.localStorageKeyPrefix + $(this).attr("id");
		}
		
	};
	
	$.fn.simpleTreeMenu = function(method) {
	    if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
	    } 
	    else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
	    } 
	    else {
			$.error('Method ' +  method + ' does not exist on jQuery.simpleTreeMenu');
	    }
	};
})(jQuery);
