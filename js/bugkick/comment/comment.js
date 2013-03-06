(function($, window, document) {
    $(document).on('click', '#postCommentBtn', function() {
        $(this).closest('form').submit();
        return false;
    });
    $(document).on('click', '#postCommentCloseBtn', function() {
        var commentForm = $(this).closest('form');
        commentForm.append(
            $('<input/>')
                .attr('type', 'hidden')
                .attr('name', 'comment-and-close')
                .val(1)
        );
        commentForm.submit();
        return false;
    });
})(this.jQuery, this, this.document);

function commentdelete()
{
    var r=confirm("Do you want to delete comment !");
    if (r==true)
      {  
          return true;
      }
      else
        {
            return false;
        }
}