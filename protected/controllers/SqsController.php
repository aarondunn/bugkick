<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SqsController
 *
 * @author Evgeniy `f0t0n` Naydenov 
 */
class SqsController extends Controller {


    /**
     *
     * @var SQSMail
     */
    protected $sqsMail;
    protected $from = 'notifications@bugkick.com';

    public function setUp() {
        $this->sqsMail = new SQSMail();
    }

    public function sendMessage() {
        $r = $this->sqsMail->send('jimmy@gmail.com',
            $this->from, 'Test msg #1', 'Body of "Test msg #1"');
        $r = $this->sqsMail->send('billy@gmail.com',
            $this->from, 'Test msg #1', 'Body of "Test msg #1"');
    }

    public function retrieveMessage() {
        $messages = $this->sqsMail->retrieve();
        foreach($messages as $receiptHandle => $msg) {
            VarDumper::dd($receiptHandle);
            VarDumper::dd($msg);
            $this->sqsMail->delete($receiptHandle);
        }
    }

    public function _actionSendMessage() {
        $this->setUp();
        $this->sendMessage();
    }

    public function _actionRetrieveMessage() {
        $this->setUp();
        $this->retrieveMessage();
    }

    public function _actionIndex() {
        $this->setUp();
        $this->sendMessage();
        $this->retrieveMessage();
    }
}

