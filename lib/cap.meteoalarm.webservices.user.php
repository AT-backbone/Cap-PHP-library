<?php
/**
 *       \file       htdocs/public/webservices/cap_export_client.php
 *       \brief      Client to make a client call to Meteoalarm WebServices "putCap"
 */
$meteoalarm = 1;
if($meteoalarm == 1)
{
	global $conf, $login_error, $login_error_html;

	$configuration->setValue("webservice", "password", encrypt_decrypt(2, $configuration->conf["webservice"]["password"]));
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

	if ($soapclient)
	{
		$soapclient->soap_defencoding='UTF-8';
		$soapclient->decodeUTF8(false);
	}

	// Call the WebService method and store its result in $result.
	$authentication=array(
		'dolibarrkey'=> $configuration->conf["webservice"]["securitykey"],
		'sourceapplication'=>'getUserInfo',
		'login'=> $configuration->conf["webservice"]["login"],
		'password'=> $configuration->conf["webservice"]["password"]);

	if(!empty($configuration->conf["identifier"]["ISO"])) $iso = $configuration->conf["identifier"]["ISO"];
	if(!empty($_GET['iso'])) $iso = $_GET['iso'];

	$GenInsInput=array();

	$parameters = array('authentication'=>$authentication, 'getUserInfo'=>$GenInsInput);

	$User = $soapclient->call('getUserInfo',$parameters,$ns,'');


	if(empty($User)){
		print_r($soapclient);
		exit;
		if(!empty($soapclient->fault)) $login_error[] = "NuSoap Fault: ".$soapclient->fault;
		if(!empty($soapclient->getError())) $login_error[] = "NuSoap Error: ".$soapclient->getError();
		if(!empty($soapclient->response )) $login_error_html[] = "NuSoap Response: ".$soapclient->response ;
	}elseif(!empty($User["result"]["result_label"])){
		$login_error[] = $User["result"]["result_label"];
	}

	if ($soapclient->fault)
	{
		$out.= '<h2>Fault</h2><pre>';
		$out.= print_r($User, true);
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