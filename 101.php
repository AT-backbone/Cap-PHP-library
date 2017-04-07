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
$NewCapInfo= $NewCapAlert->addInfo();
$NewCapInfo->setLanguage("en-US"); 
$NewCapInfo->setHeadline('headline');
$NewCapInfo->setDescription('mydesc	');

// building and saving the Cap
$NewCap->buildCap();
$res=$NewCap->saveCap('output/test.cap.xml');
if ($res < 0) echo $NewCap->error;
else print "<a href=".$res.">the cap open here</a>";

?>

