 <html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        <h3>Hello, <?php echo $user->name ?> <?php echo $user->lname ?>.</h3>
        <p>
            Your payment is outdated. Your account will be
            automatically downgraded to FREE plan on
            <?php echo date('Y-m-d G:i', $model->next_payment_time + ( Yii::app()->params['stripe']['interval1'] + Yii::app()->params['stripe']['interval2'] + Yii::app()->params['stripe']['interval3'] ) );?>.
        </p>
       <p><b style="color:#666;">Last Payment:</b> <?php echo date('Y-m-d G:i', $model->last_payment_time) ?></p>
       <p><b style="color:#666;">Due Date:</b> <?php echo date('Y-m-d G:i', $model->next_payment_time + ( Yii::app()->params['stripe']['interval1'] + Yii::app()->params['stripe']['interval2'] + Yii::app()->params['stripe']['interval3'] ) ) ?></p>
       <br>
       <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
        <table>
            <tr>
                <td>
                    <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                       href='<?php echo Yii::app()->params['siteUrl'] ?>'>
                        <?php echo Yii::app()->name; ?>
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