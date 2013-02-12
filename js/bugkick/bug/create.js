/**
 * The common bug/view page logic.
 *
 * Requires jQuery
 */
(function($, window) {
    var _ = bugkick.namespace('bug.create');
    $(function() {
        var document$ = $(window.document),
            storeNewBugKey = 'bug_create_newBugDescription';
        _.clearDescriptionDump = function() {
            store.remove(storeNewBugKey);
        };
        document$.on('keyup', '#createBugDialog #bug-form #BugForm_description',
                function() {
                store.set(storeNewBugKey, this.value);
        }).on('submit', '#createBugDialog #bug-form', function() {
            _.clearDescriptionDump();
        }).on('dialogopen', '#createBugDialog', function() {
            var descriptionText = store.get(storeNewBugKey);
            if(typeof descriptionText === 'string') {
                $(this).find('#bug-form #BugForm_description')
                    .val(descriptionText);
            }
        });
    });
})(jQuery, this);