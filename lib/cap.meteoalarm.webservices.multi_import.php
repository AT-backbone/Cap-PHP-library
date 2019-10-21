<?php
	chdir('../');
	error_reporting(E_ERROR);
	require_once 'class/translate.class.php';
	require_once 'cap.create.class.php';
	require_once 'class/conf.class.php';
	$langs = new Translate();


		$configuration = new Configuration("conf/conf.ini");

		if($configuration->get("installed", "finished") != true){
			$standard_configuration = new Configuration("conf/standard.conf.ini");
			$configuration->conf = $standard_configuration->conf;
			$configuration->setValue("installed", "finished", true);
			$configuration->write_php_ini();
			if($_GET['save'] != 1){
				header('Location: index.php?save=1#conf');
				exit;
			}
		}else{
			// the library is installed
			if(! empty($_GET['lang'])) $configuration->setValue("user", "language", $_GET['lang']);
			$langs->setDefaultLang($configuration->get("user", "language"));
			$langs->load("main");
			date_default_timezone_set($configuration->conf["installed"]["timezone"]);
			if(!file_exists('conf/conf.ini'))
			{
				$error_out.= '['.realpath('conf').'/conf.php] '.$langs->trans('perm_for_conf')."<p>";
			}
		}

	/**
	* encrypt and decrypt function for passwords
	*
	* @return	string
	*/
	function encrypt_decrypt($action, $string, $key = "")
	{
		global $configuration;

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
		$configuration->setValue("webservice", "password", encrypt_decrypt(2, $configuration->conf["webservice"]["password"]));
		$files2 = scandir($configuration->conf["cap"]["output"], 1);

//$count = count($files2);
//echo 'There are ' . $count . ' files';
//exit;
		foreach($files2 as $file)
		{
			if($file != "." && $file != ".." && $file != "ftp_prc" && $file != "skeleton_green" && $file != "COPYING")
			{
				$out = "";
				// lege das output verzeichnis fest
				$_POST[filename] = $file;
					// sezte das passwort

					// setze den timeout
					ini_set("default_socket_timeout", 60000);
					set_time_limit ( 240 );
					require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP

					$filename = $_POST[filename];

//echo date ("F d Y H:i:s.", filectime($filename));
//exit;

					if($_POST['import']==1) $import = true; else $import = false;
					// if($_POST['debug']==1) $debug = true; else $debug = false;
					$debug = false;
					$import = true;

					if ($_POST[filename])
					{
						// Set the WebService URL
						$soapclient = new nusoap_client($configuration->conf["webservice"]["WS_DOL_URL"], '' , $configuration->conf["proxy"]["proxyIP"], $configuration->conf["proxy"]["proxyPort"], $configuration->conf["proxy"]["proxyUserName"], $configuration->conf["proxy"]["proxyUserPass"], 0, 300); // <-- set the Timeout above 300 Sec.
						if ($soapclient)
						{
							$soapclient->soap_defencoding='UTF-8';
							$soapclient->decodeUTF8(false);
						}

						// Call the WebService method and store its result in $result.
						$authentication=array(
						    'dolibarrkey'=> $configuration->conf["webservice"]["securitykey"],
						    'sourceapplication'=> $configuration->conf["webservice"]["WS_METHOD"],
						  	'login'=>  $configuration->conf["webservice"]["login"],
					  		'password'=> $configuration->conf["webservice"]["password"]);

						$tmpfile = $configuration->conf["cap"]["output"].'/'.$_POST[filename];
						$handle = fopen($tmpfile, "r");                  // Open the temp file
						$contents = fread($handle, filesize($tmpfile));  // Read the temp file

//echo $contents;
$tmp_array = array();
array_push($tmp_array, $contents);
/*echo '<pre>';
var_dump($tmp_array);
echo '</pre>';

exit;*/


						fclose($handle);                                 // Close the temp file

					   	//$contents = preg_replace('/[\x00-\x1f]/', ' ', $contents);
						$document=array(
						    'filename'=> $_POST[filename],
						    'mimetype'=> 'text/xml',
						    'content'=> $contents,						//$_FILES["uploadfile"]["tmp_name"]
						    'length'=> filesize($tmpfile),
							'warning_import'=>$import,
							'debug_msg'=>$debug);


//var_dump($document);
//exit;


							$parameters = array('authentication'=>$authentication, 'document'=>$document);

							$result = $soapclient->call($configuration->conf["webservice"]["WS_METHOD"],$parameters,$configuration->conf["webservice"]["ns"],'');
						if ($soapclient->fault) {
						    $out.= '<h2>Fault</h2><pre>';
						    $out.=var_dump($result);
						    $out.= '</pre>';
						} else {
						    // Check for errors
						    $err = $soapclient->getError();
						    if ($err) {
						        // Display the error
						        $out.= '<h2>Error</h2><pre>' . $err . '</pre>';
						    } else {
						        // write file to tmp Directory
						        // file_put_contents('tmp/'.$result["document"]["filename"], $result["document"]["content"]);
						        // Display the result
						    }
						}
						if($result["syntaxcheck"]["capformat_error"]==1) 	$format_error = "YES"; else $format_error = "NO";
						if($result["syntaxcheck"]["capvalue_error"]==1) 	$value_error = "YES"; else $value_error = "NO";
						if($result["syntaxcheck"]["cnt_errors"]>0) 			$error_bool = '<p>CAP format Error:'.$format_error.'<p>CAP value Error:'.$value_error.'<p>Cnt Errors:'.$result["syntaxcheck"]["cnt_errors"].'<p>';

						// Display the request and response
						$out.= '<h2>Response</h2>';
						$out.= '<h3>Message:</h3>';
						$out.= '<pre>'.$result["result"]["result_label"].'</pre>';
						$out.= '<pre>'.$result["result_label"].'</pre>';
						$out.= '<pre>'.$error_bool.$result["syntaxcheck"]["error_log"].$result["syntaxcheck"]["debug_msg"].'</pre>';

					}

				$icon = 'info';
				if($result["syntaxcheck"]["capformat_error"]==1)	$icon = 'alert';
				if($result["syntaxcheck"]["capvalue_error"]==1)		$icon = 'alert';
				if($result["syntaxcheck"]["cnt_errors"]>0)			$icon = 'alert';
				if($result["syntaxcheck"]["warning_import"] < 1) 	$icon = 'alert';
				print '<li data-role="collapsible" data-icon="'.$icon.'" data-iconpos="right" data-inset="false" class="ui-icon-alert '.$icon.'">';
					print '<h2>'.$file.'</h2>';
					print '<a href="index.php?read=1&location='.$file.'#capview" data-ajax="false" target="_blank">'.$file.'</a><br>';
					print $out;
				print '</li>';
			}
		}
		$configuration->setValue("webservice", "password", encrypt_decrypt(1, $configuration->conf["webservice"]["password"]));
	}
?>
