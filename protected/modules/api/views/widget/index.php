<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>BugKick Widget</title>
        <style type="text/css">
            label{
                font-weight: bold;
                font-size: 12px;
                display:block;
                margin-top:7px;
                color:#444;
            }
            body{
                margin: 0;
                padding: 0;
                width: <?php echo $this->width ?>px;
                height: <?php echo $this->height ?>px;
            }
            textarea{
                width: <?php echo $this->width ?>;
                margin: 5px 0;
            }
        </style>
    </head>
	<body>
        <?php
        if(isset($this->message['error'])) {
            echo $this->message['error'];
        }
        if(isset($this->message['success'])) {
            echo $this->message['success'];
        }
        $form=$this->beginWidget('CActiveForm', array(
            'id'=>'createTicketForm',
            'action'=>$this->createAbsoluteUrl('/api/widget/create'),
        ));
        echo CHtml::hiddenField('projectID', $this->projectID);
        ?>
        <label for="ticketType">Type of ticket:</label>
        <select id="ticketType" name="ticketType">
            <?php foreach($types as $type) { ?>
            <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
            <?php } ?>
        </select>
        <br />
        <label for="ticketText">Text of ticket:</label>
        <textarea id="ticketText" name="ticketText"></textarea>
        <br />
        <input type="submit" name="submit" value="Submit ticket" />
        <?php $this->endWidget(); ?>
	</body>
</html>