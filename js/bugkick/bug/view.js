/**
 * The common bug/view page logic.
 *
 * Requires jQuery
 */
(function($) {
    var _ = bugkick.namespace('bug.view');
    $(function() {
        var storeBugKey = bugkick.string.buildString(
            'comment_', bugkick.viewData.bug.id);
        var form$ = $('#comment-form'),
            commentArea$ = $('#Comment_message');
        var commentText = store.get(storeBugKey);
        if(typeof commentText === 'string') {
            commentArea$.val(commentText);
        }
        form$.on('submit', function() {
            store.remove(storeBugKey);
        });
        commentArea$.on('keyup', function() {
            store.set(storeBugKey, this.value);
        });
        _.onCommentAreaKeyUp = function() {
            store.set(storeBugKey, commentArea$.wysiwyg('getContent'));
        };
        _.checkEmpty = function() {
            var commentError = $("#Comment_message_em_");
            if(commentArea$.val()==""){
                commentError.text("Message cannot be blank").css("display","block");
            }
            else{
                commentError.text("").css("display","none");
            }
        };
    });
})(jQuery);