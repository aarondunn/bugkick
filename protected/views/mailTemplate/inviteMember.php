<html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        Hello,<br>

        <h3><?php echo User::current()->getUserName()?> has invited you to join their project on Bugkick.com.</h3>

        Bugkick is a simple online task management app, helping people organize work and collaborate online.<p>

        To get started, please click the following link to <?php echo CHtml::link('Confirm', $acceptUrl); ?> your account.<p>

        When you are done,  click "People" in the filters list (on the right side) and then your name to see tasks assigned to you.<p>

        Best,<br>
        Bugkick.com<br>

       <p style='border-bottom: 1px solid silver;'>&nbsp;</p>
        <table>
            <tr>
                <td>
                    <a style="display: block; text-decoration: none; color: white; background-color: #1A74B0; margin: 10px; padding: 10px;"
                       href='<?php echo $acceptUrl; ?>'>
                        Confirm
                    </a>
                </td>
                <td style="margin:10px; color:#666; font-size:11px;">
                     To Confirm your account, visit this link:<br>
                    <?php echo CHtml::link($acceptUrl, $acceptUrl); ?>
                </td>
            </tr>
        </table>
    </div>
    </body>
</html>