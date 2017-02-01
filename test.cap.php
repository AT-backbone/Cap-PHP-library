<?php
require_once 'lib/cap.class.php';

	$cap = new CapProcessor();
	$cap2 = new CapProcessor();
	//$cap->makeTestCAP(false);

	$cap->readCap("output/2.0.0.1.AT.1483100888.162AT007.cap.xml");

	print '<pre>';
		print_r($cap->getCapXmlArray());
	print '<pre>';
	//exit;

	$cap2->readCap($cap->getCapXmlArray()); // "output/2.49.0.3.0.AT.1474628107.116CA-BC.cap"

	print '<pre>';
		print_r($cap2);
	print '<pre>';

	$cap_xml_cont = $cap->buildCap();
	$cap_xml_cont_2 = $cap2->buildCap();

	print '<plaintext>';
	print $cap_xml_cont;
	print $cap_xml_cont_2;
	exit;

	$alert = $cap->getAlert(0);
	$alert->setCode(0);
	$alert2 = $cap->getAlert(1);
	$alert2->setCode(1);

	// add info from second CAP to first cap
	//$cap->alert[0]->info[1] = $cap->alert[1]->info[1];

	// PP
	$cap->alert[0]->info[0]->setEvent('TEST 123'); // change event Procedural

	// OOP
	$alert 	= $cap->getAlert();
	$info 	= $alert->getInfo();
	$area 	= $info->getArea();
	$adesc 	= $area->getAreaDesc();
	$adesc .= ' TEST 123';
	$area->setAreaDesc($adesc);

	// OOP 1 line second Area block
	$alert 	= $cap->getAlert(0)->getInfo(0)->getArea(1)->setAreaDesc('test');

	$cap_xml_cont = $cap->buildCap();
	$cap_xml_dest = $cap->saveCap('changed.test.123456789.123');

	$cap_xml_cont_2 = $cap->buildCap(1);

	print '<plaintext>';
	print $cap_xml_cont;
	print $cap_xml_cont_2;

?>
