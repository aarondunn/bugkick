<html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        <h3> To reset your password on <?php echo Yii::app()->name; ?>, please click the link below. </h3>
        <p>Or just ignore this email if you don't want reset it.</p>
        <br>
        <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
        <table>
            <tr>
                <td>
                    <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                       href='<?php echo Yii::app()->createAbsoluteUrl('user/resetPassword', array('token' => $user->resetToken))?>'>
                        Reset Password
                    </a>
                </td>
                <td style="margin:10px; color:#666; font-size:11px;">
                     To reset your password, visit this link:<br>
                    <?php echo CHtml::link(
                        Yii::app()->createAbsoluteUrl('user/resetPassword', array('token' => $user->resetToken)),
                        Yii::app()->createAbsoluteUrl('user/resetPassword', array('token' => $user->resetToken))
                        );
                   ?>
                </td>
            </tr>
        </table>
    </div>
    </body>
</html>