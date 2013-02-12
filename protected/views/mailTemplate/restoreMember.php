<html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        <h3> Hi, <?php echo $user->name; ?> <?php echo $user->lname; ?>, your account on <?php echo Yii::app()->name ?> was restored.</h3>
      <p>
      Please, <?php echo CHtml::link('login', Yii::app()->createAbsoluteUrl('site/login')) ?> using your email and password,<br>
        or use Forgot Password form to reset your password.
      </p>
       <br>
       <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
        <table>
            <tr>
                <td>
                    <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                       href='<?php echo Yii::app()->createAbsoluteUrl('site/login') ?>'>
                        Login
                    </a>
                </td>
                <td style="margin:10px; color:#666; font-size:11px;">
                    To go to the site, visit this link:<br>
                    <?php echo CHtml::link(Yii::app()->params['siteUrl'],
                                           Yii::app()->params['siteUrl']); ?>
                </td>
            </tr>
        </table>
    </div>
    </body>
</html>