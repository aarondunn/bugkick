var emailNotify,
	emailPreferences,
	emailPreferencesContainer,
	emailNotifyOnChange=function(elem, value) {
		if(elem.is(':checked')){
            emailPreferences.prop('checked',true).iphoneStyle('refresh');
            emailPreferencesContainer.slideDown('slow');
        }
		else {
			//emailPreferences.removeAttr('checked').iphoneStyle('refresh');
			emailPreferencesContainer.slideUp('slow');
		}
	},
	emailPreferencesOnChange=function(elem, value) {
		if(elem.is(':checked'))
			emailNotify.attr('checked', 'checked').iphoneStyle('refresh');
	};
$(function() {
	emailPreferencesContainer=$('#emailPreferences');
	emailPreferences=$('input.cbEmailPref').iphoneStyle({
		//onChange:emailPreferencesOnChange,
		resizeContainer:false,
		resizeHandle:false
	}),
	emailNotify=$('#EmailPreferenceForm_email_notify').iphoneStyle({
		onChange:emailNotifyOnChange,
		resizeContainer:false,
		resizeHandle:false
	});
});