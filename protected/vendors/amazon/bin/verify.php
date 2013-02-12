<?
include "../sdk.class.php";
include "../services/ses.class.php";

$options = getopt("e::v:ld:");
//print_r($options);
$endpoint = $options['e'];
$v_email = $options['v'];
$del_email = $options['d'];

if (array_key_exists('l',$options)) $list=1; else $list=0;
if (array_key_exists('e',$options)) $endpoint=1; else $endpoint=0;

if (empty($options) || ((!$v_email) && (!$del_email) && (!$list))) {
  print "<Usage>: -e <endpoint> -v <email address to verify> -l -d <verified email address to delete>\n";
  print "\t-e is the endpoint: https://email.us-east-1.amazonaws.com by default\n";
  print "\t-l lists verified email addresses \n";
  print "Either one of -l, -v, or -d must be mentioned\n";
  exit (1);
 }

$ses = new AmazonSES;

if (!$endpoint) $endpoint = "https://email.us-east-1.amazonaws.com";

if ($list) {
  $ret = $ses->list_verified_email_addresses();
  //  print_r($ret);
  $arr = json_decode(json_encode($ret), true);
  //  print_r($arr);
  $listemail = $arr["body"]["ListVerifiedEmailAddressesResult"]["VerifiedEmailAddresses"]["member"];
  //  print_r($listemail);
  print "Verified email addresses are:\n";
  if (is_array($listemail))
    foreach ($listemail as $v)  print "\t$v\n";
  else print "\t$listemail\n";
 }

if ($v_email) {
  $ret = $ses->verify_email_address($v_email);
  print_r($ret);
 }

if ($del_email) {
  $ret = $ses->delete_verified_email_address($del_email);
  var_dump($ret);
 }

exit(0);
