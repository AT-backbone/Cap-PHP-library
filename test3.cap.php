<?php

	require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP

	$soapclient = new nusoap_client($configuration->conf["webservice"]["WS_DOL_URL"], '' , false, false, false, false, 0, 300); // <-- set the Timeout above 300 Sec.
	if ($soapclient)
	{
		$soapclient->soap_defencoding='UTF-8';
		$soapclient->decodeUTF8(false);
	}

	// Call the WebService method and store its result in $result.
	$authentication=array(
		'dolibarrkey'=> "http://www.meteoalarm.eu/mediawiki3/index.php/CAP_actual_securekey",
		'sourceapplication'=> "putCap",
		'login'=> "USER_NAME",
  		'password'=> "USER_PASS"
	);

	
	$document=array(
		'filename'=> "FILE_NAME",
		'mimetype'=> 'text/xml',
		'content'=> $contents,
		'length'=> strlen($tmpfile),
		'warning_import'=> "true",
		'debug_msg'=> "false"
	);

	$parameters = array('authentication'=>$authentication, 'document'=>$document);

	$result = $soapclient->call("http://meteoalarm.eu:8080/functions/webservices/capimport.php", "http://meteoalarm.eu:8080/functions/webservices/",'');

	$out.= '<h3>Response:</h3>';
	$out.= '<pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
	$out.= '<h2>Request</h2>';
	$out.= '<pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';

	print $out;