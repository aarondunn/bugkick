<li class="task-item access_del">
    <input type="checkbox" class="check-task" taskID="<?php echo $data->id; ?>"
        <?php if($data->status==Task::STATUS_COMPLETED) echo 'checked="checked"' ?>/>
    <span class="task-description <?php if($data->status==Task::STATUS_COMPLETED) echo 'crossed' ?>">
        <?php echo CHtml::encode($data->description); ?>
    </span>
    <div class="comment_delete">
        <a onclick="return confirm('Are you sure you want to delete this micro task?')" href="/task/delete/<?php echo $data->id; ?>">
            <img src="/themes/bugkick_theme/images/icons/i_delete.png" title="Delete micro task">
        </a>
    </div>
</li>