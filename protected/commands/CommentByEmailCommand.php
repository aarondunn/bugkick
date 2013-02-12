<?php
class CommentByEmailException extends CException {
	
}
/**
 * CommentByEmailCommand
 *
 * @author f0t0n
 */
class CommentByEmailCommand extends Command {
	
	/**
	 *
	 * @var User
	 */
	protected $user;
	/**
	 *
	 * @var Bug
	 */
	protected $bug;
	/**
	 *
	 * @var string
	 */
	protected $commentMessage;
	
	public function actionIndex($email) {
		$this->parseEmail($email);
		//list($from, $to, $body_at) = $this->parseEmail($email);
		return;
		try {
			$this->processCall($from, $to, $body_at);
		} catch(CommentByEmailException $ex) {
			$this->logError($ex->getMessage());
		}
	}
	
	protected function parseEmail($email) {
		file_put_contents('email_example.txt', file_get_contents($email));
	}
	
	protected function processCall($from, $to, $body_at) {
		$this->initUser($from);
		$bugID=$this->getBugID($to);
		$this->initBug($bugID);
		$this->initCommentMessage($body_at);
		$this->addComment();
	}
	
	protected function initUser($email) {
		$this->user=User::model()->find('email=:email', array(':email'=>$email));
		if(empty($this->user)) {
			throw new CommentByEmailException("User with email '$email' doesn't exists.");
		}
	}
	
	protected function initBug($id) {
		$this->bug=Bug::model()->resetScope()->findByPk($id);
		if(empty($this->bug)) {
			throw new CommentByEmailException("Bug with ID '$id' doesn't exists.");
		}
	}
	
	protected function getBugID($emailAddress) {
		$pattern='/^notifications-(\d+)@bugkick\.com$/';
		$subject=trim($emailAddress);
		if(preg_match($pattern, $subject, $matches)===1) {
			return $matches[1];
		} else {
			throw new CommentByEmailException(
				"Can't extract ID of ticket from e-mail address '$emailAddress'."
			);
		}
	}
	
	protected function initCommentMessage($path) {
		$this->commentMessage=file_get_contents($path);
		if($this->commentMessage===false) {
			throw new CommentByEmailException("Can't read the comment from file '$path'");
		}
		$this->purifyCommentMessage();
		$this->processCommentMessage();
	}
	
	protected function addComment() {
		$comment=new Comment();
		$comment->bug_id=$this->bug->id;
		$comment->user_id=$this->user->user_id;
		$comment->message=$this->commentMessage;
		if($comment->validate() && $comment->save()) {
			Notificator::newComment($comment, array($this->user->user_id));
		} else {
			$errors=CJSON::encode($comment->getErrors());
			throw new CommentByEmailException("Can't save the comment in the database. Comment model errors: '$errors'.");
		}
	}
	
	protected function processCommentMessage() {
		$this->commentMessage=preg_replace(
			'/({{{#!)([^\s]*)\s+((.|\s)+?)\s*(!#}}})/',
			'<pre class="language-$2">$3</pre>',
			$this->commentMessage
		);
		$this->commentMessage=preg_replace(
			'/(<pre class=")(language-)(">)/',
			'$1highlight$3',
			$this->commentMessage
		);
	}
	
	protected function purifyCommentMessage() {
		$purifier=$this->createPurifier();
		$this->commentMessage=$purifier->purify($this->commentMessage);
	}
	
	/**
	 *
	 * @return CHtmlPurifier 
	 */
	protected function createPurifier() {
		$purifier=new CHtmlPurifier();
		$purifier->options=array(
			'HTML.AllowedElements'=>
				'p,div,span,ul,ol,li,a,hr,br,h1,h2,h3,h4,h5,h6,b,i,u,strike,big,small',
			'HTML.AllowedAttributes'=>
				'style,class,width,size,href, align',
		);
		return $purifier;
	}
	
	protected function logError($msg) {
		Yii::log(
			$msg,
			CLogger::LEVEL_ERROR,
			'application.commands.CommentByEmailCommand'
		);
	}
}