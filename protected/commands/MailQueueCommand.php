<?php
/**
 * MailQueueCommand
 *
 * @author Evgeniy `f0t0n` Naydenov
 */
class MailQueueCommand extends Command {

    public function actionIndex($numberOfMessages = 10) {
        $sqsMail = new SQSMail();
        $sesMail = new SESMail();
        $messages = $sqsMail->retrieve($numberOfMessages);
        if(empty($messages))
            return;

        echo count($messages) . ' new messages.';

        foreach($messages as $receiptHandle => $msg) {
            $mailArgs = CJSON::decode($msg, false);
            $res = $sesMail->send($mailArgs->to, $mailArgs->from,
                $mailArgs->subject, $mailArgs->body, $mailArgs->reply_to);
            if($res->isOK())
                $sqsMail->delete($receiptHandle);
        }
    }
}