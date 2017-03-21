<?php
	require_once 'lib/cap.class.php';
	$NewCap = new CapProcessor();

	$NewCapAlert = $NewCap->addAlert();

	$NewCapAlert->setIdentifier("2.49.0.3.0.AT.150112080000.52550478");
	$NewCapAlert->setSender("cap.php.library.at");
	$NewCapAlert->setSent("now");
	$NewCapAlert->setStatus("Actual");
	$NewCapAlert->setMsgType("Alert");
	$NewCapAlert->setScope("Public");

	$NewCapAlertInfo = $NewCapAlert->addInfo();

	$NewCapAlertInfo->setLanguage("en-GB");

	$NewCapAlertInfo->setCategory("Met");

	$NewCapAlertInfo->setEvent("TEST GENERATED MESSAGE");

	$NewCapAlertInfo->setUrgency("Immediate");
	$NewCapAlertInfo->setSeverity("Minor");
	$NewCapAlertInfo->setCertainty("Observed");

	$NewCapAlertInfo->setEffective("now");
	$NewCapAlertInfo->setOnset("now");
	$NewCapAlertInfo->setExpires("now + 1 day");

	$NewCapAlertInfo->setSenderName("CAP PHP LIBRARY");
	$NewCapAlertInfo->setHeadline("GENERATED TEST MESSAGE FROM CAP CLASS!");
	$NewCapAlertInfo->setDescription("THIS IS A GENERATED TEST MESSAGE FROM THE CAP CLASS!");

	$NewCapAlertInfo->setParameter("awareness_level", "1; green; Minor");
	$NewCapAlertInfo->setParameter("awareness_type", "1; Wind");

	$NewCapAlertInfoArea = $NewCapAlertInfo->addArea();

	$NewCapAlertInfoArea->setAreaDesc("TEST LOCATION");
	$NewCapAlertInfoArea->setGeocode("NUTS3", "AT001");

	print '<plaintext>';
	print $NewCap->buildCap();
?>