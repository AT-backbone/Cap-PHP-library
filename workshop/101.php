<?php
#### Workshop Example ####
$res=0;
if (! $res && file_exists('../lib/cap.class.php')) $res=@include '../lib/cap.class.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists('../../lib/cap.class.php')) $res=@include '../../lib/cap.class.php';	
if (! $res) die("Failure by include of cap.class.php");

$NewCap = new CapProcessor();
$NewCapAlert = $NewCap->addAlert();

/* #### set Parameter ##### */

/* Alert Block */
$NewCapAlert->setIdentifier('1.2.3.4');
$NewCapAlert->setSender('Test-Sender');
$NewCapAlert->setSent('2017-04-07T10:43:24+01:00');
$NewCapAlert->setStatus('Actual');
$NewCapAlert->setMsgType('Alert');
$NewCapAlert->setScope('Public');

/* Info Block */

$NewCapInfo1= $NewCapAlert->addInfo();
$NewCapInfo1->setLanguage("en-US"); 
$NewCapInfo1->setCategory('Met');
$NewCapInfo1->setEvent('Thunderstorm');
$NewCapInfo1->setResponseType('None');
$NewCapInfo1->setUrgency('Future');
$NewCapInfo1->setSeverity('Minor');
$NewCapInfo1->setCertainty('Likely');
$NewCapInfo1->setHeadline('the headline');
$NewCapInfo1->setDescription('my desc');
$NewCapInfo1->setWeb('https://www.mydomain.com');
$NewCapInfo1->setContact('Firstname Lastname');

$NewCapInfo1->setContact('Firstname Lastname');

$NewCapAlertInfoArea1 = $NewCapInfo1->addArea();
$NewCapAlertInfoArea1->setAreaDesc("TEST LOCATION");
// for meteoalarm import
$NewCapAlertInfoArea1->setGeocode("NUTS3", "AT001");

// building and saving the Cap
$NewCap->buildCap();
$res=$NewCap->saveCap('output/test.cap.xml');
if ($res < 0) echo $NewCap->error;
else print "<a href=".$res.">click to open this cap here</a>";
?>

