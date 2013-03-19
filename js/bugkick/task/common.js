/**
 * Bugkick MicroTasks
 * Author: Alexey Kavshirko kavshirko@gmail.com
 * Date: 18.03.13
 * Time: 23:04
 */
jQuery("a.add-task").live("click",function() {
    $.post(
        $(this).attr("href"),
        { YII_CSRF_TOKEN:YII_CSRF_TOKEN },
          function(data){
            jQuery("#createTaskForm").html(data);
            jQuery("#createTaskDialog").dialog("open");
          },
          "html"
    );
    return false;
});
createTask = function() {
    $.jGrowl.defaults.position='bottom-right';
    $.jGrowl.defaults.life=2000;
    $("#task-form").ajaxSubmit({
        success: function(data) {
            $("#createTaskDialog").dialog("close");
            if ($.fn.yiiListView && !!$('#task-list').length){
                $.fn.yiiListView.update('task-list');
            }
            $.jGrowl(
                "Task is created."
            );
        },
        error: function(data) {
            $.jGrowl(
                "Please check the fields."
            );
        },
        dataType: "json"
    });
    return false;
};

$(".task-item input[type=checkbox]").live("click", function(e) {
    $.jGrowl.defaults.life=2000;
    var checkBox = $(this);
    var taskID = $(this).attr("taskID");
    $.ajax({
        url: '/task/complete',
        type: 'post',
        data: { YII_CSRF_TOKEN:YII_CSRF_TOKEN, taskID:taskID },
        success : function(data) {
                //checking and unchecking tasks instead of refreshing
                // (to keep possibility to uncheck task before page was reloaded)
                var task = checkBox.next('.task-description');
                if (task.hasClass('crossed'))
                    task.removeClass('crossed');
                else
                    task.addClass('crossed');

            $.jGrowl(
                "Saved"
            );
        },
        error : function(data){
            if ($.fn.yiiListView && !!$('#task-list').length){
                $.fn.yiiListView.update('task-list');
            }
            $.jGrowl('Error. Please try again later');
        },
            dataType: "html"
    });
});