<li class="task-item">
    <input type="checkbox" class="check-task" taskID="<?php echo $data->id; ?>"
        <?php if($data->status==Task::STATUS_COMPLETED) echo 'checked="checked"' ?>/>
    <span class="task-description <?php if($data->status==Task::STATUS_COMPLETED) echo 'crossed' ?>">
        <?php echo CHtml::encode($data->description); ?>
    </span>
</li>