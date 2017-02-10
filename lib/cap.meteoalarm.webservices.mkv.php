<?php
	chdir('../');
	error_reporting(E_ERROR);
	require_once 'class/translate.class.php';
	require_once 'cap.create.class.php';

	$langs = new Translate();

	if(file_exists('conf/conf.php'))
	{
		include 'conf/conf.php';
		if(! empty($_GET['lang'])) $configuration->conf["user"]["language"] = $_GET['lang'];
		$langs->setDefaultLang($configuration->conf["user"]["language"]);
		$langs->load("main");
	}

	/**
	* encrypt and decrypt function for passwords
	*
	* @return	string
	*/
	function encrypt_decrypt($action, $string, $key = "")
	{
		global $conf;

		$output = false;

		$encrypt_method = "AES-256-CBC";
		$secret_key = ($key?$key:'NjZvdDZtQ3ZSdVVUMXFMdnBnWGt2Zz09');
		$secret_iv = ($configuration->conf["webservice"]["securitykey"] ? $configuration->conf["webservice"]["securitykey"] : 'WebTagServices#hash');

		// hash
		$key = hash('sha256', $secret_key);

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		if( $action == 1 ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 2 ){
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}

	$meteoalarm = 1;
	if($meteoalarm == 1)
	{
		global $conf;

		$configuration->conf["webservice"]["login"] = "";
		$configuration->conf["webservice"]["password"] = "";
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

					$Webservicename = explode('.', parse_url($configuration->conf["webservice"]["WS_DOL_URL"], PHP_URL_HOST));
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

					$Webservicename = explode('.', parse_url($configuration->conf["webservice"]["WS_DOL_URL"], PHP_URL_HOST));
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

			$configuration->conf["webservice"]["login"] = $_COOKIE['Session_login_name'];
			$configuration->conf["webservice"]["password"] =  $_COOKIE['Session_login_pass'];
		}

		if($_SESSION['Session_login_name'])
		{
			$configuration->conf["webservice"]["login"] = $_SESSION['Session_login_name'];
			$configuration->conf["webservice"]["password"] = $_SESSION['Session_login_pass'];
		}

		global $out;
		$configuration->set("webservice", "password", encrypt_decrypt(2, $configuration->conf["webservice"]["password"]));
		ini_set("default_socket_timeout", 60000);
		set_time_limit ( 240 );
		require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP

		$ns=$configuration->conf["webservice"]["ns"];
		$WS_DOL_URL = $ns.'SvgAreaInfo.php';

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
			    'dolibarrkey'=>$configuration->conf["webservice"]["securitykey"],
			    'sourceapplication'=>'getSvgInfo',
			  	'login'=> $configuration->conf["webservice"]["login"],
		  	  'password'=> $configuration->conf["webservice"]["password"]);

			if(!empty($configuration->conf["identifier"]["ISO"])) $iso = $configuration->conf["identifier"]["ISO"];
			if(!empty($_GET['iso'])) $iso = $_GET['iso'];

			$GenInsInput=array(
				'iso'=>$iso,
				'mkv' => 1,
				'EMMA_ID'=>$_GET["EMMA_ID"]
			);


				$parameters = array('authentication'=>$authentication, 'getSvgInfo'=>$GenInsInput);

				$pid = $soapclient->call('getSvgInfo',$parameters,$ns,'');

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


			$configuration->set("webservice", "password", encrypt_decrypt(1, $configuration->conf["webservice"]["password"]));

			print $pid['document']['SvgInfo'];
	}

?>
