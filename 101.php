<?php

require_once('lib/cap.class.php');

$NewCap = new CapProcessor();
$NewCapAlert = $NewCap->addAlert();

$NewCapAlert->setIdentifier('1.2.3.4');
$NewCapAlert->setSender('Test-Sender');
$NewCapAlert->setSent('2017-03-21T10:43:24+01:00');
$NewCapAlert->setStatus('Actual');
$NewCapAlert->setMsgType('Alert');
$NewCapAlert->setScope('Alert');

// building and saving the Cap
$NewCap->buildCap();
$NewCap->saveCap('output/test.cap.xml');

?>