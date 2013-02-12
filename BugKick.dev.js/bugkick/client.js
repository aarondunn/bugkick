/*!
 * BugKick API client.
 *
 * Requires jQuery.
 *
 * @author Evgeniy `f0t0n` Naydenov
 * @author Alexey Kavshirko
 * @copyright BugKick
 */
;(function($, window) {
    
    var _ = window.bugkick;
    
    _.Client = function() {
        
    };
    
    _.Client.API_URL = 'http://bugkick.local/api';
    _.Client.TicketType = {
        BUG: 'Bug',
        FEATURE_REQUEST: 'Feature request',
        SUGGESTION: 'Suggestion'
    };
    _.Client.Method = {
        CREATE_TICKET: 'createTicket'
    };
    _.Client.RequestData = {
        'apiKey': _.apiKey,
        'projectID': _.projectID
    };
    
    _.Client.prototype.createTicket = function(ticketText, ticketType, ticketEmail,
        onSuccess, onError) {
        // send the POST HTTP request with ticketText, ticketType and method.
        this.makeCall({
            ticketEmail: ticketEmail,
            ticketText: ticketText,
            ticketType: ticketType,
            method: _.Client.Method.CREATE_TICKET
        }, onSuccess || function(){}, onError || function() {});
    };
    
    _.Client.prototype.makeCall = function(data, onSuccess, onError) {
        $.post(_.Client.API_URL, {
            apiCall: $.extend({}, data, _.Client.RequestData)
        })
        .success(onSuccess || function() {})
        .error(onError || function() {});
    };
    
})(this.jQuery, this);