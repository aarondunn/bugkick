<html>
<body>
<div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
    <h3>Hello, <?php echo $user->name ?> <?php echo $user->lname ?></h3>
    <p><?php echo $message; ?></p>
    <br>
    <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
    <table>
        <tr>
            <td>
                <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                   href='<?php echo Yii::app()->createAbsoluteUrl('') ?>'>
                    <?php echo Yii::app()->name; ?>
                </a>
            </td>
            <td style="margin:10px; color:#666; font-size:11px;">
                To go to the site, visit this link:<br>
                <?php echo CHtml::link(Yii::app()->createAbsoluteUrl(''), Yii::app()->createAbsoluteUrl('')); ?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>