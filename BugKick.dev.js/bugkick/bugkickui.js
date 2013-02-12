/*!
 * BugKick API widget UI
 *
 * Requires jQuery.
 *
 * @author Evgeniy `f0t0n` Naydenov
 * @author Alexey Kavshirko
 * @copyright BugKick
 */
;(function($, window) {
    
    var _ = window.bugkick;
    
    _.BugKickUI = function() {
        this.client = new _.Client();
        this.widget$ = null;
        this.body$ = $('body');
        this.dialog$ = null;
        this.msgWnd$ = null;
        this.overlay$ = null;
    };
    
    _.BugKickUI.Template = {
        MAIN: _._url('bugkick/templates/main.html')
    };
    
    _.BugKickUI.Message = {
        TICKET_SUBMIT_SUCCESS: 'Your ticket has been submitted. Thank you!'
    };
    
    _.BugKickUI.prototype._getTemplate = function(callback) {
        var bugkickUI = this;
        $.get(_.BugKickUI.Template.MAIN, function(template) {
            callback.call(bugkickUI, template);
        });
    }
    
    _.BugKickUI.prototype.render = function() {
        this._getTemplate(function(template) {
            _.page.includeCss(_._url('bugkick/css/style.css'));
            this.body$.append(template);
            this.widget$ = $('#bugkick-feedback-widget');
            this.dialog$ = $('.modal-window', this.widget$);
            this.msgWnd$ = $('.message-window', this.widget$);
            this.overlay$ = $('.overlay', this.widget$);
            this.initEventHandlers();
        });
    };
    
    _.BugKickUI.prototype.showDialog = function() {
        this.dialog$.show();
        this.overlay$.fadeTo(700, 0.7);
        this.updateDialogPosition();
        $('textarea', this.widget$).focus();
    };
    
    _.BugKickUI.prototype.hideDialog = function() {
        $('.overlay, .modal-window', this.widget$).hide();
    };
    
    _.BugKickUI.prototype.updateDialogPosition = function() {
        var wnd$ = $(window),
            dialogs = [this.dialog$, this.msgWnd$],
            i,
            dlg$;
        for(i = 0; dlg$ = dialogs[i++];) {
            dlg$.css({
                left: _.string.buildString(
                    Math.round(wnd$.width() / 2 - dlg$.width() / 2), 'px'),
                top: _.string.buildString(
                    Math.round(wnd$.height() / 2 - dlg$.height() / 2), 'px')
            });
        }
    };
    
    _.BugKickUI.prototype.initEventHandlers = function() {
        var bugkickUI = this;
        this.widget$.on('click', '.bugkick-feedback-button', function() {
            bugkickUI.showDialog();
        }).on('submit', 'form', function() {
            return bugkickUI.onFormSubmit();
        }).on('reset', 'form', function() {
            bugkickUI.hideDialog();
            $('.error-summary', bugkickUI.widget$).html('').hide();
            return true;
        }).on('click', '.overlay', function() {
            bugkickUI.resetForm();
        }).on('keyup', function(e) {
            if(e.keyCode == 27) {
                bugkickUI.resetForm();
            }
        });
        $(window).on('resize', function() {
            bugkickUI.updateDialogPosition();
        });
    };
    
    _.BugKickUI.prototype.resetForm = function() {
        this.msgWnd$.hide();
        $('form', this.widget$)[0].reset();
    };
    
    _.BugKickUI.prototype.showMessage = function(message) {
        $('.text', this.msgWnd$).html(message);
        this.msgWnd$.show();
    };
    
    _.BugKickUI.prototype.hideMessage = function() {
        this.msgWnd$.hide();
    };
    
    _.BugKickUI.prototype.onFormSubmit = function() {
        var bugkickUI = this;
            ticketEmail = this.widget$.find('form input.bugkick-feedback-email').val(),
            ticketText = this.widget$.find('form textarea').val(),
            ticketType = this.widget$.find('form select').val();
        this.client.createTicket(ticketText, ticketType, ticketEmail,
            function(data) {
                bugkickUI.onCreateTicketSuccess(data);
            },
            function(data) {
                bugkickUI.onCreateTicketError(data);
            }
        );
        return false;
    };
    
    _.BugKickUI.prototype.onCreateTicketSuccess = function(data) {
        // Notify user about success and close the dialog box.
        if(!!data.success) {
            this.dialog$.hide();
            this.showMessage(_.BugKickUI.Message.TICKET_SUBMIT_SUCCESS);
            var bugkickUI = this;
            window.setTimeout(function() {
                bugkickUI.hideMessage();
                bugkickUI.resetForm();
            }, 1500);
        } else if(!!data.error) {
            $('.error-summary', this.widget$).html(data.error).show();
        }
    };
    
    _.BugKickUI.prototype.onCreateTicketError = function(data) {
        this.resetForm();
    };
    
    window.onload = function() {
        var bugkickUI = new _.BugKickUI();
        bugkickUI.render();
    };
    
})(this.jQuery, this);