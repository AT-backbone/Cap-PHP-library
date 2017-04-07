<?php
#### Workshop Example ####
$res=0;
if (! $res && file_exists('../lib/cap.class.php')) $res=@include '../lib/cap.class.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists('../../lib/cap.class.php')) $res=@include '../../lib/cap.class.php';	
if (! $res) die("Failure by include of cap.class.php");

if (isset($_GET["action"]) && $_GET["action"] == "validate")
	require_once('../lib/CapValidatorChecker.class.php');
	

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

$NewCapInfo2= $NewCapAlert->addInfo();
$NewCapInfo2->setLanguage("de-DE"); 
$NewCapInfo2->setCategory('Met');
$NewCapInfo2->setEvent('Thunderstorm');
$NewCapInfo2->setResponseType('None');
$NewCapInfo2->setUrgency('Future');
$NewCapInfo2->setSeverity('Minor');
$NewCapInfo2->setCertainty('Likely');
$NewCapInfo2->setHeadline('the headline');
$NewCapInfo2->setDescription('Meine Beschreibung');
$NewCapInfo2->setWeb('https://www.mydomain.com');
$NewCapInfo2->setContact('Firstname Lastname');


// building and saving the Cap
$NewCap->buildCap();
$res=$NewCap->saveCap('output/test.cap.xml');
if ($res < 0) echo $NewCap->error;
elseif (isset($_GET["action"]) && $_GET["action"] == "validate")
{
	// send File to validator.meteoalarm.eu
	
	
	$CVal = new CapChecker();
		
	$cap_path = $res;
	// $Cvalinfo = $CVal->validate_CAP($cap_path);
	$Cvalinfo = $CVal->validate_CAP($res);
	print $CVal->removeBody($Cvalinfo['html']);
	//print $form->CapView($cap_content, $res, $Cvalinfo->removeBody($Cvalinfo['html'])); // Cap Preview +
}
else print "<a href=".$res.">click to open this cap here</a>";
?>

