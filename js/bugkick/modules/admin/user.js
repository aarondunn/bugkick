(function($, window) {
    var _ = bugkick.namespace('modules.admin.user'),
        window$ = $(window),
        document$ = $(window.document);
    $(function() {
        var dlg$ = $('#update-user-dialog');
        
        function updateDialog(data) {
            $.flashMessage().endProgress();
            if(!!data.length) {
                dlg$.html(data).dialog('open');
            } else {
                dlg$.dialog('close');
            }
        }
        
        function updateDialogError() {
            $.flashMessage().endProgress();
        }
        
        document$.on('click', '.grid-view a.update', function() {
            $.flashMessage().beginProgress();
            bugkick.ajax.updateElement({
                url: this.href,
                selector: '#update-user-dialog',
                callback: updateDialog,
                onerror: updateDialogError
            });
            return false;
        });
    });
})(jQuery, this);