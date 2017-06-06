<?php
/**
 *       \file       htdocs/public/webservices/cap_export_client.php
 *       \brief      Client to make a client call to Meteoalarm WebServices "putCap"
 */

$meteoalarm = 1;
if($meteoalarm == 1)
{
	global $out;

	$configuration->setValue("webservice","password",encrypt_decrypt(2, $configuration->conf["webservice"]["password"]));

	ini_set("default_socket_timeout", 60000);
	set_time_limit ( 240 );
	require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP

	$ns=$configuration->conf["webservice"]["ns"];
	$WS_DOL_URL = $ns.'CapAreaInfo.php';

	$filename = $_POST[filename];
	if($_POST['import']==1) $import = true; else $import = false;
	// if($_POST['debug']==1) $debug = true; else $debug = false;
	$debug = true;
	if($import == "") $import = true;

		// Set the WebService URL
		$soapclient = new nusoap_client($WS_DOL_URL, '',$configuration->conf["proxy"]["proxyIP"], $configuration->conf["proxy"]["proxyPort"], $configuration->conf["proxy"]["proxyUserName"], $configuration->conf["proxy"]["proxyUserPass"]); // <-- set the Timeout above 300 Sec.
		$soapclient->setDebugLevel(0);
		if ($soapclient)
		{
			$soapclient->soap_defencoding='UTF-8';
			$soapclient->decodeUTF8(false);
		}

		// Call the WebService method and store its result in $result.
		$authentication=array(
			'dolibarrkey'=>$configuration->conf["webservice"]["securitykey"],
			'sourceapplication'=>'getAreaInfo',
			'login'=> $configuration->conf["webservice"]["login"],
			'password'=> $configuration->conf["webservice"]["password"]
		);

		if(!empty($configuration->conf["identifier"]["ISO"])) $iso = $configuration->conf["identifier"]["ISO"];
		if(!empty($_GET['iso'])) $iso = $_GET['iso'];

		$GenInsInput=array(
    		'iso'=>$iso,
   			'EMMA_ID'=>$_GET["EMMA_ID"]
		);


		$parameters = array('authentication'=>$authentication, 'getAreaInfo'=>$GenInsInput);

		$AreaCodesArray = $soapclient->call('getAreaInfo',$parameters,$ns,'');

		if ($soapclient->fault)
		{
			$out.= '<h2>Fault</h2><pre>';
			$out.=var_dump($result);
			$out.= '</pre>';
		}
		else
		{
			// Check for errors
			$err = $soapclient->getError();

			if ($err)
			{
				// Display the error
				$out.= '<h2>Error</h2><pre>' . $err . '</pre>';
	    	}
	   	 	else
	    	{

	    	}
		}

		$configuration->setValue("webservice", "password", encrypt_decrypt(1, $configuration->conf["webservice"]["password"]));
}
?>
