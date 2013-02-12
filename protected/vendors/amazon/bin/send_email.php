<?
include "../sdk.class.php";
include "../services/ses.class.php";

$options = getopt("t:s:m:");
//print_r($options);
$to = $options['t'];
$sub = $options['s'];
$mess = $options['m'];

if (empty($options) || ((!$to) && (!$sub) && (!$mess))) {
  print "<Usage>: -t <email address for \"To\" field> -s <Subject heading> -m <Short message enclosed within quotes>\n";
  exit (1);
 }

$ses = new AmazonSES;

// See the following for a full discussion of the method:
// http://docs.amazonwebservices.com/AWSSDKforPHP/latest/index.html#m=AmazonSES/send_email

$ret = $ses->send_email(
			"test@yahoo.com",
			array('ToAddresses' => $to),
			array( 
			      'Subject.Data' => $sub ,
			      'Body.Text.Data' => $mess . " " . time()
			      )
			);
var_dump($ret);

exit(0);
