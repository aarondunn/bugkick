<?php
/**
 * Author: Mingyong
 * Date: 09.12.11
 */
 class DeleteArchivedTicketsCommand extends Command {
	
	/**
	 * delete all tickets and related data after 30 days 
	 * after ticket archiving for FREE company-accounts.
	 */
	public function actionIndex()
    {
		//delete bugs and related tables	
		$bugs = Bug::model()->findAllBySql("SELECT `bkb`.id, `bkb`.number, `bkb`.prev_number, `bkb`.next_number, `bkb`.prev_id, `bkb`.next_id FROM `bk_bug` AS `bkb` JOIN `bk_company` AS `bkc` ON `bkc`.company_id=`bkb`.company_id WHERE isarchive=1 AND DATEDIFF(NOW(), archiving_date) > 30 AND `bkc`.account_type=0");
		
		foreach($bugs as $bug) {
			/**
			 * delete data from bk_bug table
			 * we need to keep the prev_number,next_number,prev_id,next_id linkage
			 */

			//this is first ticket, update prev_number,prev_id of next ticket to zero
			if($bug->prev_number == 0) {
				Bug::model()->updateByPk($bug->next_id, array(
					'prev_number'=>0,
					'prev_id'=>0,
				));
			} else {
				//update prev_number,prev_id of next ticket
				Bug::model()->updateByPk($bug->next_id, array(
					'prev_number'=>$bug->prev_number,
					'prev_id'=>$bug->prev_id,
				));

				//update next_number,next_id of previous ticket
				Bug::model()->updateByPk($bug->prev_id, array(
					'next_number'=>$bug->next_number,
					'next_id'=>$bug->next_id,
				));
			}

			$bug->delete();

			//delete data from bk_bug_by_label table
			BugByLabel::model()->deleteAllByAttributes(array('bug_id'=>$bug->id));

			//delete data from bk_bug_by_user table
			BugByUser::model()->deleteAllByAttributes(array('bug_id'=>$bug->id));

			//delete data from bk_bug_changelog table
			BugChangelog::model()->deleteAllByAttributes(array('bug_id'=>$bug->id));

			//delete data from bk_comment table
			Comment::model()->deleteAllByAttributes(array('bug_id'=>$bug->id));
		}
	}
}
