<?php
/**
 * 
 *
 * @author f0t0n
 */
class InstantMessage {
	
	//const NODE_SERVER_PORT = 27000;
	protected $nodeServerUrl;
	protected static $_instance=null;
	
	protected function __construct() {
		$this->nodeServerUrl=
			//Yii::app()->createAbsoluteUrl('/').':'.self::NODE_SERVER_PORT;
			Yii::app()->params['siteUrl'].':'
            .Yii::app()->params['node']['notifications']['port'];
	}
	
	/**
	 *
	 * @return InstantMessage A singleton instance of InstantMessage class
	 */
	public static function instance() {
		if(self::$_instance===null)
			self::$_instance=new InstantMessage();
		return self::$_instance;
	}
	
	public function send($userID, $messageType,
            BugBase $ticket=null, $ticketURL=null) {
        $ticketNumber = $ticket->number;
        $this->saveNotification($userID, $messageType, $ticket, $ticketURL );
		if(!Yii::app()->params['node']['notifications']['turned-on'])
			return null;
		$post=array(
			'user_id'=>$userID,
			'message_type'=>$messageType
		);
		if(!empty($ticketNumber))
			$post['ticket_number']=$ticketNumber;
		if(!empty($ticketURL))
			$post['ticket_url']=$ticketURL;
		return $this->sendRequest($post);
	}
	
	public function sendToAll($projectID, $messageType, $message=null) {
		if(!Yii::app()->params['node']['notifications']['turned-on'])
			return null;
		$post=array(
			'project_id'=>$projectID,
			'message_type'=>$messageType,
		);
		if(!empty($message))
			$post['message']=$message;
		return $this->sendRequest($post);
	}
	
	protected function sendRequest($post) {
		$this->checkSslCaCert();
		$response=Yii::app()->CURL->run(
			$this->nodeServerUrl,	// URL of node.js HTTP server
			false,					// Is it GET request
			$post					// Post data
		);
		return is_array($response) 
			? $response					//	got an error from Curl extension method run()
			: CJSON::decode($response); //	got some data from server
	}
	
	protected function checkSslCaCert() {
		if(!empty(Yii::app()->CURL->options['setOptions'][CURLOPT_CAINFO])) {
			$caCert=Yii::app()->CURL->options['setOptions'][CURLOPT_CAINFO];
			if(!is_file($caCert)) {
				unset(Yii::app()->CURL->options['setOptions'][CURLOPT_CAINFO]);
			}
		}
	}

    protected function saveNotification($userID, $messageType, 
            $ticket=null, $ticketURL=null) {
        $ticketNumber = 0;
        $notification = new Notification;
        $notification->user_id = $userID;
        $notification->changer_id = User::current()->id;
        if(!empty($ticket)) {
            $ticketNumber = $ticket->number;
            $notification->bug_id = $ticket->id;
        }
        switch ($messageType) {
            case MessageType::NEW_COMMENT:
                $notification->content = 'New comment created on <a href="'
                					     . $ticketURL . '" target="_blank">Ticket #'
                				       	 . $ticketNumber . '</a>.';
                break;
            case MessageType::NEW_TICKET:
                $notification->content = 'New <a href="' . $ticketURL . '" target="_blank">Ticket #'
                                         . $ticketNumber . '</a> assigned to you';
                break;
            case MessageType::TICKET_CHANGED:
                $notification->content = '<a href="' . $ticketURL . '" target="_blank">Ticket #'
                                         . $ticketNumber . '</a> has been changed';
                break;
            case MessageType::TICKET_DEADLINE_REACHED:
                $notification->content = 'The deadline passed for <a href="'
                                         . $ticketURL . '" target="_blank">Ticket #'
                                         . $ticketNumber . '</a>';
                break;
        }
        $notification->save();
    }

}