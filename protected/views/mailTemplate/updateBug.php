<html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        <h3> <?php echo Yii::app()->user->name ?> <?php echo Yii::app()->user->lname ?> updated the ticket #<?php echo $model->number ?> "<?php echo $model->title ?>"</h3>
<?php
        foreach($changes as $change){
            if ($change['field'] == 'duplicate_number'){
                if($change['value'] == 0)
                    echo '<p><b style="color:#666;">Duplicate Status:</b> Duplicate status was removed</p>';
                elseif($change['value'] > 0)
                    echo '<p><b style="color:#666;">Duplicate Status:</b> Ticket was set as Duplicate of the Ticket #'.$change['value'].'</p>';
            }
            elseif($change['field']=='archived'){
                if($change['value'] == 0)
                    echo '<p><b style="color:#666;">Ticket is opened</b></p>';
                else
                    echo '<p><b style="color:#666;">Ticket is closed</b></p>';
            }
            elseif($change['field'] == 'label_id' || $change['field'] == 'user_id'){
                $paramChange = '';
                $paramChange = '<p><b style="color:#666;">New '. $change['name'] .':</b> ';
                foreach ($change['value'] as $val){
                   $paramChange .= $val . ', ';
                }
                echo substr($paramChange, 0, -2) . '</p>';
            }
            else{
                echo '<p><b style="color:#666;">New '.$change['name'].':</b> '.$change['value'].'</p>';
            }
        }
?>
       <br>
       <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
        <table>
            <tr>
                <td>
                    <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                       href='<?php echo Yii::app()->createAbsoluteUrl('bug/view', array('id'=>$model->number))?>'>
                        View Ticket
                    </a>
                </td>
                <td style="margin:10px; color:#666; font-size:11px;">
                     To view this ticket, visit this link:<br>
                    <?php echo CHtml::link(
                        Yii::app()->createAbsoluteUrl('bug/view', array('id'=>$model->number)),
                        Yii::app()->createAbsoluteUrl('bug/view', array('id'=>$model->number))
                        );
                   ?>
                </td>
            </tr>
        </table>
    </div>
    </body>
</html>