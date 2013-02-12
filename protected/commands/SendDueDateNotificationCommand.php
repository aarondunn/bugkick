<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.11.11
 * Time: 4:34
 */
 class SendDueDateNotificationCommand extends Command {

	public function actionIndex()
    {
       $data = Bug::getOutdatedTickets();
       if (is_array($data)){
           Notificator::sendDueDateNotification($data);
           print 'Notifications have been sent.';
       }
       else
           print 'No Outdated tickets.';
	}
}
