<html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        <h3> <?php echo 'New ticket #'.$bug->number.' assigned to you.'?></h3>
       <p><b style="color:#666;">Title:</b> <?php echo $bug->title ?></p>
       <p><b style="color:#666;">Due Date:</b> <?php echo $bug->duedate ?></p>
       <p><b style="color:#666;">Description:</b> <?php echo $bug->description ?></p>
       <br>
       <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
        <table>
            <tr>
                <td>
                    <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                       href='<?php echo Yii::app()->createAbsoluteUrl('bug/view', array('id'=>$bug->number))?>'>
                        View Ticket
                    </a>
                </td>
                <td style="margin:10px; color:#666; font-size:11px;">
                     To view this ticket, visit this link:<br>
                    <?php echo CHtml::link(
                        Yii::app()->createAbsoluteUrl('bug/view', array('id'=>$bug->number)),
                        Yii::app()->createAbsoluteUrl('bug/view', array('id'=>$bug->number))
                        );
                   ?>
                </td>
            </tr>
        </table>
    </div>
    </body>
</html>