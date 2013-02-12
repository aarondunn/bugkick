/*!
 * BugKick API client.
 *
 * Requires jQuery.
 *
 * @author Evgeniy `f0t0n` Naydenov
 * @author Alexey Kavshirko
 * @copyright BugKick
 */
(function(e,t){var n=t.bugkick;n.Client=function(){};n.Client.API_URL="https://bugkick.com/api";n.Client.TicketType={BUG:"Bug",FEATURE_REQUEST:"Feature request",SUGGESTION:"Suggestion"};n.Client.Method={CREATE_TICKET:"createTicket"};n.Client.RequestData={apiKey:n.apiKey,projectID:n.projectID};n.Client.prototype.createTicket=function(e,t,r,i,s){this.makeCall({ticketEmail:r,ticketText:e,ticketType:t,method:n.Client.Method.CREATE_TICKET},i||function(){},s||function(){})};n.Client.prototype.makeCall=function(t,r,i){e.post(n.Client.API_URL,{apiCall:e.extend({},t,n.Client.RequestData)}).success(r||function(){}).error(i||function(){})}})(this.jQuery,this)