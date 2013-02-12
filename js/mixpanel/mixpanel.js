//Click events for MixPanel tracking
var NEW_TICKET_BUTTON = '#createBug';
var NEW_PROJECT_BUTTON = '#createProjectBtn';
var PROJECT_SETTINGS_BUTTON = '#settings-tab';
var EXPORT_TICKETS_BUTTON = '#export_tickets';
var HELP_BUTTON = '#show-help';
var INVITE_MEMBER_BUTTON = '#inviteMember';
var NEW_STATUS_BUTTON = '#newStatus';
var NEW_LABEL_BUTTON = '#newlabel';
var NEW_GROUP_BUTTON = '#createGroupBtn';
var SHOW_ADVANCED_BUTTON = '#showAdvancedOptions';
var EDIT_TICKET_BUTTON = '.update-bug-link';
var CLOSE_TICKET_BUTTON = '#archived-link';
var DELETE_TICKET_BUTTON = '#delete_bug_link';
var DUPLICATE_TICKET_BUTTON = '.duplicate-link';

var NEW_TICKET_CLICK = "New Ticket Button Click";
var NEW_PROJECT_CLICK = "New Project Button Click";
var PROJECT_SETTINGS_CLICK = "Project Settings Button Click";
var EXPORT_TICKETS_CLICK = "Export Tickets Button Click";
var HELP_CLICK = "Help Button Click";
var INVITE_MEMBER_CLICK = "Invite New Member Button Click";
var NEW_STATUS_CLICK = 'New Status Button Click';
var NEW_LABEL_CLICK = 'New Label Button Click';
var NEW_GROUP_CLICK = 'New Group Button Click';
var SHOW_ADVANCED_CLICK = 'Show Advanced Comment Options Button Click';
var EDIT_TICKET_CLICK = 'Edit Ticket Button Click';
var CLOSE_TICKET_CLICK = 'Close Ticket Button Click';
var DELETE_TICKET_CLICK = 'Delete Ticket Button Click';
var DUPLICATE_TICKET_CLICK = 'Duplicate Ticket Button Click';

var TICKETS_ORDER_CHANGED_BY_DRAG_N_DROP = "Tickets Order Changed by Drag-n-Drop";
var FILTER_APPLIED_BY_DRAG_N_DROP = "Filter Applied by Drag-n-Drop";

$(document).ready(function(){
    function trackEvent(eventName, params, callback){
        mixpanel.track(eventName, params, callback);
    }
    $(NEW_TICKET_BUTTON).click(function(){
        trackEvent(NEW_TICKET_CLICK);
    });
    $(NEW_PROJECT_BUTTON).click(function(){
        trackEvent(NEW_PROJECT_CLICK);
    });
    $(PROJECT_SETTINGS_BUTTON).click(function(){
        trackEvent(PROJECT_SETTINGS_CLICK);
    });
    $(EXPORT_TICKETS_BUTTON).click(function(event){
        event.preventDefault();
        window.location.href = $(EXPORT_TICKETS_BUTTON).attr("href");
        trackEvent(EXPORT_TICKETS_CLICK);
    });
    $(HELP_BUTTON).click(function(){
        trackEvent(HELP_CLICK);
    });
    $(INVITE_MEMBER_BUTTON).click(function(){
        trackEvent(INVITE_MEMBER_CLICK);
    });
    $(NEW_STATUS_BUTTON).click(function(){
        trackEvent(NEW_STATUS_CLICK);
    });
    $(NEW_LABEL_BUTTON).click(function(){
        trackEvent(NEW_LABEL_CLICK);
    });
    $(NEW_GROUP_BUTTON).click(function(){
        trackEvent(NEW_GROUP_CLICK);
    });
    $(SHOW_ADVANCED_BUTTON).click(function(){
        trackEvent(SHOW_ADVANCED_CLICK);
    });

    $(EDIT_TICKET_BUTTON).click(function(){
        trackEvent(EDIT_TICKET_CLICK);
    });
    $(CLOSE_TICKET_BUTTON).click(function(event){
        event.preventDefault();
        window.location.href = $(CLOSE_TICKET_BUTTON).attr("href");
        trackEvent(CLOSE_TICKET_CLICK);
    });
    $(DELETE_TICKET_BUTTON).click(function(){
        trackEvent(DELETE_TICKET_CLICK);
    });
    $(DUPLICATE_TICKET_BUTTON).click(function(){
        trackEvent(DUPLICATE_TICKET_CLICK);
    });
});