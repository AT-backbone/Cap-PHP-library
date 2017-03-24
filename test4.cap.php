<?php

	require_once 'lib/cap.webservices.class.php';		// Include SOAP

	$WS_URL = "http://meteoalarm.eu:8080/functions/webservices/capimport.php";
	$ns = "http://meteoalarm.eu:8080/functions/webservices/";
	$WS_METHOD = "putCap";

	$securitykey = "5c2947c0c574e56ac11a4cf8f410d40b"; // http://www.meteoalarm.eu/mediawiki3/index.php/CAP_actual_securekey
	$login= "adminniklas"; // USER_NAME
	$password= "ChaLar007"; // USER_PASS

	$filename= "FILE_NAME";
	$contents= '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
				<alert xmlns="urn:oasis:names:tc:emergency:cap:1.2">
					<identifier>2.0.0.2.AT.170317102808.14</identifier>
					<sender>test</sender>
					<sent>2017-03-17T10:31:04+01:00</sent>
					<status>Actual</status>
					<msgType>Alert</msgType>
					<scope>Public</scope>
					<references>meteoalarm.eu,2.0.0.2.AT.170317095755.10,2017-03-17 10:00:00</references>
					<info>
						<language>en-GB</language>
						<category>Met</category>
						<event>test1123</event>
						<responseType>None</responseType>
						<urgency>Expected</urgency>
						<severity>Severe</severity>
						<certainty>Likely</certainty>
						<effective>2017-03-17T10:28:00+01:00</effective>
						<onset>2017-03-17T10:28:00+01:00</onset>
						<expires>2017-03-17T21:00:00+01:00</expires>
						<headline>test2</headline>
						<description>test3</description>
						<instruction>test4</instruction>
						<parameter>
							<valueName>awareness_type</valueName>
							<value>1; Wind</value>
						</parameter>
						<parameter>
							<valueName>awareness_level</valueName>
							<value>3; orange; Severe</value>
						</parameter>
						<area>
							<areaDesc>test</areaDesc>
							<geocode>
								<valueName>NUTS2</valueName>
								<value>AT32</value>
							</geocode>
						</area>
					</info>
				</alert>
				';
	$import= "false";
	$debug= "true";

	$MWS = new MeteoalarmWebservice($WS_URL);
	$MWS->setParameterArray($securitykey, $WS_METHOD, $login, $password, $filename, $contents, $import, $debug);
	$out = $MWS->sendSoapCall($WS_METHOD, $ns);

	print $out;