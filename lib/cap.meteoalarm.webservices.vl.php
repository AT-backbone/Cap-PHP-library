<?php
/**
 *       \file       htdocs/public/webservices/cap_export_client.php
 *       \brief      Client to make a client call to Meteoalarm WebServices "putCap"
 */

$conf->meteoalarm = 1;
if($conf->meteoalarm == 1)
{
	global $conf;

	$conf->webservice->login = "";
	$conf->webservice->password = "";
	session_name(encrypt_decrypt(1, getcwd()));
	session_start();
	if(!empty($_POST['send-login']) || !empty($_POST['send-logout']))
	{
		if(!is_array($_POST['send-logout']))
		{
			end($_POST['send-login']);
			$key = key($_POST['send-login']);

			if(!empty($_POST['savepass'][$key]))
			{		
				unset($_SESSION['Session_login_name'], $_SESSION['Session_login_pass']);
				unset($_COOKIE['Session_login_name'], $_COOKIE['Session_login_pass']);
				
				session_unset();
				session_start();

				$Webservicename = explode('.', parse_url($conf->webservice->WS_DOL_URL, PHP_URL_HOST));
				setcookie('ServiceHost', $Webservicename[1], strtotime(' + 3 day'));  /* verfällt in 1 Stunde */
				setcookie('timestamp', strtotime('now'), strtotime(' + 3 day'));  /* verfällt in 1 Stunde */
				setcookie("Session_login_name", $_POST['Session_login_name'][$key], strtotime(' + 3 day'));  /* verfällt in 1 Stunde */
				setcookie("Session_login_pass", encrypt_decrypt(1,$_POST['Session_login_pass'][$key]), strtotime(' + 3 day'));  /* verfällt in 1 Stunde */
			}
			else
			{
				unset($_SESSION['Session_login_name'], $_SESSION['Session_login_pass']);
				unset($_COOKIE['Session_login_name'], $_COOKIE['Session_login_pass']);
				
				session_unset();
				session_start();
				
				$Webservicename = explode('.', parse_url($conf->webservice->WS_DOL_URL, PHP_URL_HOST));
				$_SESSION['ServiceHost'] = $Webservicename[1];
				$_SESSION['timestamp'] = strtotime('now');
				$_SESSION['Session_login_name'] = $_POST['Session_login_name'][$key];
				$_SESSION['Session_login_pass'] = encrypt_decrypt(1, $_POST['Session_login_pass'][$key]);
			}
			unset($_POST);
		}
		else
		{
			session_unset();
			unset($_SESSION['Session_login_name'], $_SESSION['Session_login_pass']);
			unset($_COOKIE['Session_login_name'], $_COOKIE['Session_login_pass']);
			setcookie("Session_login_name", '', strtotime(' - 1 day'));  /* verfällt sofort */
			unset($_POST);
		}
	}
	
	if($_COOKIE['Session_login_name'])
	{
		$conf->webservice->login = $_COOKIE['Session_login_name'];
		$conf->webservice->password =  $_COOKIE['Session_login_pass'];
	}
	
	if($_SESSION['Session_login_name'])
	{
		$conf->webservice->login = $_SESSION['Session_login_name'];
		$conf->webservice->password = $_SESSION['Session_login_pass'];
	}
	

	$conf->webservice->password = encrypt_decrypt(2, $conf->webservice->password);
	ini_set("default_socket_timeout", 60000);
	
	$ns='http://www.meteoalarm.eu:8080/functions/webservices/';
	$WS_DOL_URL = $ns.'CapAreaInfo.php';

	$filename = $_POST[filename];
	if($_POST['import']==1) $import = true; else $import = false;
	// if($_POST['debug']==1) $debug = true; else $debug = false;
	$debug = true;
	if($import == "") $import = true;

		// Set the WebService URL
		$soapclient = new nusoap_client($WS_DOL_URL); // <-- set the Timeout above 300 Sec.
		if ($soapclient)
		{
			$soapclient->soap_defencoding='UTF-8';
			$soapclient->decodeUTF8(false);
		}
		
		// Call the WebService method and store its result in $result.
		$authentication=array(
		    'dolibarrkey'=>$conf->webservice->securitykey,
		    'sourceapplication'=>'getAreaInfo',
		  	'login'=> $conf->webservice->login,
	  	  'password'=> $conf->webservice->password);

		if(!empty($conf->identifier->ISO)) $iso = $conf->identifier->ISO;
		if(!empty($_GET['iso'])) $iso = $_GET['iso'];
		
		$GenInsInput=array(
    		'iso'=>$iso,
    		'show_warnings'=> 1,
    		'view_type'=>2,
    		'use_warntable'=>1
    	);
		    	
		
			$parameters = array('authentication'=>$authentication, 'getAreaInfo'=>$GenInsInput);
			
			$AreaCodesArray = $soapclient->call('getAreaInfo',$parameters,$ns,'');
			
			if ($soapclient->fault) 
			{
		    $out.= '<h2>Fault</h2><pre>';
		    $out.=var_dump($result);
		    $out.= '</pre>';
			} else 
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
		$conf->webservice->password = encrypt_decrypt(1, $conf->webservice->password);	
}
?>
