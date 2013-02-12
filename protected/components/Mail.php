<?php
/**
 *
 * @author Evgeniy `f0t0n` Naydenov
 */
interface Mail {

    public function send($to, $from = '', $subject = '', $body = '',
        $reply_to = null);
}
