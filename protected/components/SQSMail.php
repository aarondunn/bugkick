<?php

Yii::import('application.vendors.amazon.sdkClass', true);
Yii::import('application.vendors.amazon.services.sqsClass', true);
Yii::import('application.vendors.amazon.utilities.utilitiesClass', true);
Yii::import('application.vendors.amazon.utilities.complextypeClass', true);
Yii::import('application.vendors.amazon.lib.requestcore.requestcoreClass', true);
Yii::import('application.vendors.amazon.utilities.requestClass', true);
Yii::import('application.vendors.amazon.utilities.responseClass', true);
Yii::import('application.vendors.amazon.utilities.simplexmlClass', true);

/**
 * SQSMessage
 *
 * @author Evgeniy `f0t0n` Naydenov
 */
class SQSMail implements Mail {

    const QUEUE_NAME = 'bugkick_mail_queue';

    /**
     *
     * @var AmazonSQS
     */
    protected $sqs;

    /**
     *
     * @var string
     */
    protected $queueUrl;

    public function __construct() {
        $this->initSqs();
        $this->initQueueUrl();
    }
    
    protected function initSqs() {
        $this->sqs = new AmazonSQS();
    }

    protected function initQueueUrl() {
        $this->queueUrl = Yii::app()->cache->get('sqs_queue_url');
        if(!$this->queueUrl) {
            $res = $this->sqs->create_queue(self::QUEUE_NAME);
            if(!$res->isOK()) {
                throw new Exception('Can\'t create SQS queue');
            }
            $this->queueUrl =
                (string)$res->body->CreateQueueResult->QueueUrl[0];
            Yii::app()->cache->set('sqs_queue_url', $this->queueUrl);
        }
    }

    public function send($to, $from = '', $subject = '', $body = '', $reply_to=null) {
        return $this->sqs->send_message($this->queueUrl, CJSON::encode(array(
            'to' => $to,
            'from' => $from,
            'subject' => $subject,
            'body' => $body,
            'reply_to' => $reply_to,
        )));
    }

    public function retrieve($numberOfMessages=10) {
        $res = $this->sqs->receive_message($this->queueUrl, array(
            'MaxNumberOfMessages'=>$numberOfMessages,
        ));

        if($res->isOK()) {
            $messages = array();
            foreach($res->body->ReceiveMessageResult->Message as $msg) {
                $messages[(string)$msg->ReceiptHandle[0]] =
                    (string)$msg->Body[0];
            }
            return $messages;
        }
        return null;
    }

    public function delete($receiptHandle) {
        return $this->sqs->delete_message($this->queueUrl, $receiptHandle);
    }
}