<?php
/*
*  Copyright (c) 2017  Guido Schratzer   <guido.schratzer@backbone.co.at>
*  Copyright (c) 2015  Niklas Spanring   <n.spanring@backbone.co.at>
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/**
*	\file      	index.php
*  \ingroup   	main
*/

/**
* Front end of the Cap-php-library cap 1.3
*/
error_reporting(E_ERROR | E_PARSE);

require_once 'class/cap.form.class.php';
require_once 'lib/cap.convert.class.php';
require_once 'class/translate.class.php';
require_once 'class/conf.class.php';
require_once 'lib/cap.class.php';
require_once 'lib/CapValidatorChecker.class.php';

$langs = new Translate();
$CapProcessor = new CapProcessor();


$configuration = new Configuration("conf/conf.ini");

if($configuration->get("installed", "finished") != true){
	$standard_configuration = new Configuration("conf/standard.conf.ini");
	$configuration->conf = $standard_configuration->conf;
	$configuration->set("installed", "finished", true);
	$configuration->write_php_ini();
	if($_GET['save'] != 1){
		header('Location: index.php?save=1#conf');
		exit;
	}
}else{
	// the library is installed
	if(! empty($_GET['lang'])) $configuration->set("user", "language", $_GET['lang']);
	$langs->setDefaultLang($configuration->get("user", "language"));
	$langs->load("main");
	date_default_timezone_set($configuration->conf["installed"]["timezone"]);
	if(!file_exists('conf/conf.ini'))
	{
		$error_out.= '['.realpath('conf').'/conf.php] '.$langs->trans('perm_for_conf')."<p>";
	}
}

if(! is_dir("output") || ! is_writable("output"))
{
	$error_out.= '[output/] '.$langs->trans('perm_for_conf')."<p>";
}

if(!empty($error_out))
{
	die($error_out);
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

if(!empty($_GET['encrypt']))
{
	$crpt = encrypt_decrypt(1, $_GET['encrypt']);
	print $crpt;
	print '<br>'.encrypt_decrypt(2, $crpt);
	exit;
}

if(!empty($_GET['decrypt']))
{
	$crpt = encrypt_decrypt(2, $_GET['decrypt']);
	print $crpt;
	print '<br>'.encrypt_decrypt(1, $crpt);
	exit;
}

$configuration->set("cap", "save", 1);

$configuration->conf["webservice"]["login"] = "";
$configuration->conf["webservice"]["password"] = "";
session_name(encrypt_decrypt(1, getcwd()));
session_start();

$tryed_login = false;
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

			$Webservicename = parse_url($configuration->conf["webservice"]["WS_DOL_URL"], PHP_URL_HOST);
			setcookie('ServiceHost', $Webservicename, strtotime(' + 3 day'));  /* verfällt in 1 Stunde */
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

			$Webservicename = parse_url($configuration->conf["webservice"]["WS_DOL_URL"], PHP_URL_HOST);
			$_SESSION['ServiceHost'] = $Webservicename;
			$_SESSION['timestamp'] = strtotime('now');
			$_SESSION['Session_login_name'] = $_POST['Session_login_name'][$key];
			$_SESSION['Session_login_pass'] = encrypt_decrypt(1, $_POST['Session_login_pass'][$key]);
		}
		$tryed_login = true;
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
	$configuration->set("webservice", "login", $_COOKIE['Session_login_name']);
	$configuration->set("webservice", "password", $_COOKIE['Session_login_pass']);
}

if($_SESSION['Session_login_name'])
{
	$configuration->set("webservice", "login", $_SESSION['Session_login_name']);
	$configuration->set("webservice", "password", $_SESSION['Session_login_pass']);
}

// build service url
$service_arr = explode('/', $configuration->conf["webservice"]["WS_DOL_URL"]);
end($service_arr);
$key = key($service_arr);

$configuration->set("webservice", "ns", str_replace($service_arr[$key],'',$configuration->conf["webservice"]["WS_DOL_URL"]));
$configuration->set("webservice", "sourceapplication", $configuration->conf["webservice"]["WS_METHOD"]);

// METEOALARM WEBSERVICE ---
$meteoalarm = 1;
if($meteoalarm == 1)
{
	if($configuration->conf["webservice"]["service_on"] > 0)
	{
		if(file_exists('lib/cap.meteoalarm.webservices.Area.php'))
		{
			include 'lib/cap.meteoalarm.webservices.Area.php';
			if($_GET['web_test'] == 1) die(print_r($AreaCodesArray));
			if(!empty($AreaCodesArray['document']['AreaInfo']))
			{
				$AreaCodesArray = $AreaCodesArray['document']['AreaInfo'];
			}
		}
		if(file_exists('lib/cap.meteoalarm.webservices.Parameter.php'))
		{
			include 'lib/cap.meteoalarm.webservices.Parameter.php';
			if($_GET['web_test'] == 2) die(print_r($ParameterArray));
			if(!empty($ParameterArray['document']['AreaInfo']))
			{
				$ParameterArray = $ParameterArray['document']['AreaInfo'];
			}
		}
		if(file_exists('lib/cap.meteoalarm.webservices.user.php'))  // test if the lib exists
		{
			// Contains the warnings sorted to areas
			include 'lib/cap.meteoalarm.webservices.user.php'; // get data through the meteoalarm lib (vl - Visio Level)
			if($_GET['web_test'] == 3) die(print_r($User));
			if(!empty($User['document']['AreaInfo']))
			{
				$User = $User['document']['AreaInfo'];
			}
		}

		if(is_array($AreaCodesArray) && is_array($ParameterArray) && empty($AreaCodesArray['result']) && empty($ParameterArray['result']))
		{
			$configuration->conf["webservice_aktive"] = 1;
		}
		else
		{
			$configuration->conf["webservice_aktive"] = -1;
		}
	}
}

$login_to_webservice_faild = false;
if($tryed_login == true && $configuration->conf["webservice_aktive"] == -1)
{
	unset($_SESSION['Session_login_name'], $_SESSION['Session_login_pass']);
	unset($_COOKIE['Session_login_name'], $_COOKIE['Session_login_pass']);
	$configuration->set("webservice", "login", "");
	$configuration->set("webservice", "password", "");
	if(!is_array($_POST['send-logout']))
	{
		unset($_POST);
	}
	$login_to_webservice_faild = true;
}

if(!file_exists('conf/conf.ini'))
{
	$cap = new CAP_Form();
	print $cap->install();
}
elseif($_GET['conv'] == 1)
{
	if(! empty($_POST['location']) || ! empty($_FILES["uploadfile"]["name"]))
	{
		require_once 'lib/cap.read.class.php';
		// Get TEST Cap
		if(! empty($_FILES["uploadfile"]["name"]))
		{
			$location = $_FILES["uploadfile"]["tmp_name"];
		}
		else
		{

			$location = $configuration->conf["cap"]["output"].'/'.urldecode($_POST['location']);
		}

		$CapProcessor->readCap($location);
		$cap = $CapProcessor->getCapXmlArray();

		// Convert
		$converter = new Convert_CAP_Class();
		$capconvertet = $converter->convert($cap, $_POST['stdconverter'],	$_POST['areaconverter'], $_POST['inputconverter'], $_POST['outputconverter'], $configuration->conf["cap"]["output"]);

		$form = new CAP_Form();
		print $form->CapView($capconvertet, $cap[identifier].'.conv.cap.xml'); // Cap Preview +
	}
	else
	{
		$form = new CAP_Form();
		print $form->ListCap();
	}
}
elseif($_GET['read'] == 1)
{
	require_once 'lib/cap.read.class.php';
	if(! empty($_POST['upload']))
	{
		if(! empty($_FILES["uploadfile"]["name"]))
		{
			$location = $_FILES["uploadfile"]["tmp_name"];
		}

		if(simplexml_load_file($location) != ""){
			$CapProcessor->readCap($location); // read cap
			$CapProcessor->buildCap(); // build cap
			$path = $CapProcessor->saveCap($_FILES["uploadfile"]["name"]); // save cap in a file
			if ($path < 0) header('Location: '.$_SERVER['PHP_SELF'].'?error=createFileErr'.$path.'#read');
			else header('Location: '.$_SERVER['PHP_SELF'].'#read');
		}else{
			header('Location: '.$_SERVER['PHP_SELF'].'?error=fileisnotxml#read');
		}
	}

	if(! empty($_FILES["uploadfile"]["name"]))
	{
		$location = $_FILES["uploadfile"]["tmp_name"];
	}
	else
	{
		if(! empty($_POST['location']))
		{
			$location = $configuration->conf["cap"]["output"].'/'.urldecode($_POST['location']);
		}
		else if(! empty($_GET['location']))
		{
			$location = $configuration->conf["cap"]["output"].'/'.urldecode($_GET['location']);
		}
	}

	//$alert = new alert($location);
	//$cap = $alert->output();

	$CapProcessor->readCap($location);
	//$CapProcessor->makeTestCAP(false);
	$cap = $CapProcessor->getCapXmlArray();
	//die(print_r($cap)); // DEBUG
	if(! empty($cap['msg_format']))
	{
		print $cap['msg_format'];
		exit;
	}

	$form = new CAP_Form($cap);

	print $form->Form();
}
elseif(empty($_POST['action']) && $_GET['webservice'] != 1 && empty($_GET['web_test']))
{
	// Build Cap Creator form
	if(! empty($_GET['delete']))
	{
		unlink($configuration->conf["cap"]["output"].'/'.$_GET['delete']);
		header('Location: '.$_SERVER['PHP_SELF'].'#read');
	}

	if(file_exists('conf/template.cap'))
	{
		require_once 'lib/cap.read.template.class.php';
		$alert = new alert_template('conf/template.cap');
		$cap = $alert->output_template();
		unset($alert);
	}

	$form = new CAP_Form($cap);

	print $form->Form();
}
elseif($_POST['action'] == "create" && $_GET['conf'] != 1 && $_POST['login_sended'] != 1)
{
	$form = new CAP_Form();
	$_POST = $form->MakeIdentifier($_POST);

	if($configuration->conf["webservice_aktive"] == 1)
	{
		if($_POST['sender'] == "") $_POST['sender'] = $User['sender'];
		if($_POST['senderName'] == "") $_POST['senderName'] = $User['senderName'];
	}

		//$cap = new CAP_Class($_POST);

		// Add the new Cap Processor Class
		$CapProcessor->makeCapOfPost($_POST);
		//die(print_r($_POST));

		if(!empty($_GET['cap']))
		{
			// Used for the Cap preview
			$cap_contend = $CapProcessor->buildCap();
			print $cap_contend;
		}
		else
		{
			// Used to build the cap and save it at $cap->destination
			if($_POST['capedit'] == "false" || $_POST['capedit'] == false) {
				$cap_contend = $CapProcessor->buildCap();
				$CapProcessor->destination = $configuration->conf["cap"]["output"];
				if($configuration->conf["cap"]["save"] == 1) $path = $CapProcessor->saveCap();
				if ($path < 0) die($path);

				$configuration->set("identifier", "ID_ID", $configuration->conf["identifier"]["ID_ID"] + 1);

				$form->WriteConf();
				$capname = $CapProcessor->getAlert()->getIdentifier().".xml";
				//print $form->CapView($cap_contend, $capname); // Cap Preview +
			}else{
				$capfile = fopen($configuration->conf["cap"]["output"].'/'.date('Y.m.d.H.i.s').'.edited.xml', "w") or die("Unable to open file! ".$configuration->conf["cap"]["output"].'/'.date('Y.m.d.H.i.s').'.edited.xml');
				fwrite($capfile, $_POST['capeditfield']);
				fclose($capfile);

				chmod($configuration->conf["cap"]["output"].'/'.date('Y.m.d.H.i.s').'.edited.xml', 0660);  // octal; correct value of mode
				chgrp($configuration->conf["cap"]["output"].'/'.date('Y.m.d.H.i.s').'.edited.xml', filegroup($configuration->conf["cap"]["output"]));

				// convert in UTF-8
				$cap_contend = file_get_contents($configuration->conf["cap"]["output"].'/'.date('Y.m.d.H.i.s').'.edited.xml');

				if (preg_match('!!u', $cap_contend))
				{
					// this is utf-8
				}
				else
				{
					$cap_contend = mb_convert_encoding($cap_contend, 'UTF-8', 'OLD-ENCODING');
				}

				file_put_contents($configuration->conf["cap"]["output"].'/'.date('Y.m.d.H.i.s').'.edited.xml');

				$capname = date('Y.m.d.H.i.s').'.edited.xml';
				//print $form->CapView($cap_contend, $capname); // Cap Preview +
			}

		$CVal = new CapChecker();
		if($configuration->conf["webservice_aktive"] == 1) $CVal->profile = "meteoalarm";
		$cap_path = $configuration->conf["cap"]["output"].'/'.$capname;
		$Cvalinfo = $CVal->validate_CAP($cap_path);
		print $form->CapView($cap_contend, $capname, $CVal->removeBody($Cvalinfo['html'])); // Cap Preview +
	}
}
elseif($_GET['webservice'] == 1)
{
	// start webservices
	$form = new CAP_Form();
	print $form->Webservice($_POST[filename]);
}
elseif($_GET['conf'] == "1")
{
	$form = new CAP_Form();
	$form->PostToConf($_POST['conf']);
	$configuration->write_php_ini();

	if($_POST['template_on'] == 'on')
	{
		if(!empty($_POST['Template']))
		{
			if(!file_exists('conf/'.$_POST['Template']))
			{
				if (!copy($configuration->conf["cap"]["output"].'/'.$_POST['Template'], 'conf/template.cap'))
				{
					die('Permission problems detectet pleas fix this: copy ['.$configuration->conf["cap"]["output"].'/'.$_POST['Template'].'] to [conf/template.cap]' );
				}
			}
		}
	}
	else
	{
		if(file_exists('conf/template.cap'))
		{
			unlink('conf/template.cap');
		}
	}

	return true;
}
elseif($_GET['web_test'] == "1")
{

}

?>
