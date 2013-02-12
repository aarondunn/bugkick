<?php
$currDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
return array(
    CURLOPT_USERAGENT=>require($currDir . 'cuseragent.php'),
    //CURLOPT_CAINFO=>'/etc/pki/tls/certs/bugkick.ca.crt',
);