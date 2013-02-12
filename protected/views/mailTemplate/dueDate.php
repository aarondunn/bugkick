<html>
<body>
<div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
    <h3> Hello, <?php echo $name ?> <?php echo $lname ?></h3>
    <p>The following tickets have deadlines that have passed:</p>

<?php
foreach ($tickets as $value) {
    echo '<p><b style="color:#666;">' .
        CHtml::link('Ticket #' . $value->number . ' "' . $value->title . '"',
            Yii::app()->params['siteUrl']
                . '/' . $value->project->project_id . '/' . $value->number,
            array('style' => 'color:#666; font-weight:bold; text-decoration:none;')) .
        ': </b> Due ' . $value->duedate . '</p>';
}
?>
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