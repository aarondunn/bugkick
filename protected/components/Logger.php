<?php

class Logger extends CComponent {

	public function init() {

	}

	public function saveLog($userId=0, $action, $comment='', $success=1) {
        $log = new Log;
        $log->user_id = $userId;
        $log->action_id = $action;
        $log->comment = $comment;
        $log->success = $success;
        $log->save();
	}

}