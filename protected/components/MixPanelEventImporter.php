<?php
/**
 *
 * Importing users to MixPanel
 *
 * Author: Alexey kavshirko@gmail.com
 * Date: 07.09.12
 * Time: 19:24
 */

class MixPanelEventImporter {

    public $token;
    public $api_key;
    public $host = 'http://api.mixpanel.com/engage';

    public function __construct($token_string,$api_key) {
        $this->token = $token_string;
		$this->api_key = $api_key;
    }

    public function track($userData) {
        $data = Yii::app()->CURL->run($this->host,false,
            array(
                'data'=>base64_encode(json_encode($userData))
            )
        );
        echo ' Success:' . $data . '<br>';
		sleep(.2);
    }
}