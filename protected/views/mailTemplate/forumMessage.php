<html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        <h3> <?php echo Yii::app()->user->name ?> <?php echo Yii::app()->user->lname ?> added a new comment on Bugkick Forum.</h3>
        <p><b style="color:#666;">Comment:</b><?php echo CHtml::encode($post->body)?></p>
       <br>
       <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
        <table>
            <tr>
                <td>
                    <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                       href='<?php echo Yii::app()->createAbsoluteUrl('/forum/topic/view', array('id'=>$post->topic->id))?>'>
                        View Topic
                    </a>
                </td>
                <td style="margin:10px; color:#666; font-size:11px;">
                     To view this comment, visit this link:<br>
                    <?php echo CHtml::link(
                        Yii::app()->createAbsoluteUrl('/forum/topic/view', array('id'=>$post->topic->id)),
                        Yii::app()->createAbsoluteUrl('/forum/topic/view', array('id'=>$post->topic->id))
                    );
                   ?>
                </td>
            </tr>
        </table>
    </div>
    </body>
</html>