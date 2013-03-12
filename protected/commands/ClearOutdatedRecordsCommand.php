<?php
/**
 * Clears old DB records
 *
 * Author: Alexey kavshirko@gmail.com
 * Date: 13.03.13
 * Time: 1:02
 */

class ClearOutdatedRecordsCommand extends Command
{
	/**
	 * The maximum age in days for notifications records. <br />
	 * If the age is bigger the record must to be deleted.
	 */
	const MAX_NOTIFICATION_AGE = 30;

	public function actionIndex()
    {
        $this->deleteNotifications();
	}

    protected function deleteNotifications()
    {
        $date = date('Y-m-d H:i:s',strtotime('-'.self::MAX_NOTIFICATION_AGE.' days'));
        Notification::model()->deleteAll('date < :date',array(
            ':date'=>$date,
        ));
    }
}