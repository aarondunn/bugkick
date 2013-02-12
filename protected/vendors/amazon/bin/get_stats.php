<?
include "../sdk.class.php";
include "../services/ses.class.php";

$options = getopt("e::sq");
print_r($options);
$endpoint = $options['e'];
if (array_key_exists('s',$options)) $s=1; else $s=0;
if (array_key_exists('q',$options)) $q=1; else $q=0;

if (empty($options) || ((!$s) && (!$q))) {
  print "<Usage>: -e <endpoint> -s -q \n";
  print "\t-e is the endpoint: https://email.us-east-1.amazonaws.com by default\n";
  print "\t-s lists Send Statistics\n";
  print "\t-q lists Send Quota\n"; 
  exit (1);
 }

$ses = new AmazonSES;

if (!$endpoint) $endpoint = "https://email.us-east-1.amazonaws.com";

if ($s) {
  $ret= $ses->get_send_statistics();
  $arr = json_decode(json_encode($ret), true);
  print_r($arr);
  print "************ We need to play with this and parse later ************** \n";
 }

if ($q) {
  $ret = $ses->get_send_quota();
  $arr = json_decode(json_encode($ret), true);
  //  print_r($arr);
  $quota = $arr["body"]["GetSendQuotaResult"];
  print "Max # of emails you are allowed to send in a 24-hour interval: ".$quota["Max24HourSend"]."\n";
  print "Max # of emails you are allowed to send per second: " .$quota["MaxSendRate"]."\n";
  print "# of emails send in the last 24 hours: " .$quota["SentLast24Hours"]."\n";
 }
