<?php

/**
 * This is the model class for table "bug" excludes archived Ticket using default scope.
 *
 * The followings are the available columns in table 'bug':
 * @property integer $id
 * @property integer $number
 * @property integer $prev_number
 * @property integer $next_number
 * @property integer $prev_id
 * @property integer $next_id
 * @property integer $project_id
 * @property string $created_at
 * @property string $title
 * @property string $description
 * @property integer $status_id
 * @property integer $label_id
 * @property string $duedate
 * @property integer $isarchive
 * @property integer $company_id
 * @property integer $user_id
 * @property integer $notified
 * @property integer $duplicate_number
 * @property integer $priority_order
 * @property integer $is_created_with_api
 * @property string $type
 *
 * @property GithubIssue githubIssue
 *
 */
class Bug extends BugBase {

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function defaultScope() {
        return array(
            'order' => 'id DESC',
            'condition' => "isarchive IS NULL"
        );
    }
    
    public function getLastComment($id){
        $command = Yii::app()->db->createCommand();
        $res=$command->select('message')->from('bk_comment')->where('bug_id=:id', array(':id' => $id))->limit(1)->order('bk_comment.comment_id DESC')->queryRow();
        return $res['message'];
    }

}