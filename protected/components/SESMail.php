<?php

/*
 * Class to send emails using Amazon SES
 *
 * */

class SESMail implements Mail {

    /**
	 * @param string $from
	 * @param array|string $to (by default to admin)
	 * @param boolean $body
     * @param boolean $subject
     * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function send($to, $from = '', $subject = '', $body = '', $reply_to=null)
    {

        Yii::import('application.vendors.amazon.sdkClass', true);
        Yii::import('application.vendors.amazon.services.sesClass', true);
        Yii::import('application.vendors.amazon.utilities.utilitiesClass', true);
        Yii::import('application.vendors.amazon.utilities.complextypeClass', true);
        Yii::import('application.vendors.amazon.lib.requestcore.requestcoreClass', true);
        Yii::import('application.vendors.amazon.utilities.requestClass', true);
        Yii::import('application.vendors.amazon.utilities.responseClass', true);
        Yii::import('application.vendors.amazon.utilities.simplexmlClass', true);

		if(empty($from)) {
			$from = Yii::app()->params['adminEmail'];
		}
		$opt=array();
		if(!empty($reply_to)) {
			$opt['ReplyToAddresses']=$reply_to;
		}
        $ses = new AmazonSES;
        return $ses->send_email(
            $from,
            array('ToAddresses' => $to),
            array(
                 'Subject.Data' => $subject,
                 'Body.Html.Data' => $body
            ),
			$opt
        );
	}

}
