<?php 
/*
 *  Copyright (c) 2015  Guido Schratzer   <guido.schratzer@backbone.co.at>
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
	require_once 'lib/cap.create.class.php';
	require_once 'lib/cap.write.class.php';
	require_once 'lib/cap.convert.class.php';
	require_once 'class/translate.class.php';
	
	$langs = new Translate();
	
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
		$secret_iv = ($conf->webservice->securitykey ? $conf->webservice->securitykey : 'WebTagServices#hash');
	
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

	if(file_exists('conf/conf.php'))
	{
		include 'conf/conf.php';
		if(! empty($_GET['lang'])) $conf->user->lang = $_GET['lang'];
		$langs->setDefaultLang($conf->user->lang);		
		$langs->load("main");	
	}
	else
	{
		chmod('conf', 755);
		$capfile = fopen('conf/conf.php', "w");
		fwrite($capfile, "
		<?php
		$"."conf->lang['en-GB']                                   = 'english';
		$"."conf->lang['ca']                                      = 'català';
		$"."conf->lang['cs']                                      = 'ceština';
		$"."conf->lang['da-DK']                                   = 'dansk';
		$"."conf->lang['de-DE']                                   = 'deutsch';
		$"."conf->lang['es-ES']                                   = 'español';
		$"."conf->lang['et']                                      = 'eesti';
		$"."conf->lang['eu']                                      = 'euskera';
		$"."conf->lang['fr-FR']                                   = 'français';
		$"."conf->lang['gl']                                      = 'galego';
		$"."conf->lang['hr-HR']                                   = 'hrvatski';
		$"."conf->lang['is']                                      = 'íslenska';
		$"."conf->lang['it-IT']                                   = 'italiano';
		$"."conf->lang['lt']                                      = 'lietuviu';
		$"."conf->lang['lv']                                      = 'latviešu';
		$"."conf->lang['hu-HU']                                   = 'magyar';
		$"."conf->lang['mt']                                      = 'malti';
		$"."conf->lang['nl-NL']                                   = 'nederlands';
		$"."conf->lang['no']                                      = 'norsk';
		$"."conf->lang['pl']                                      = 'polski';
		$"."conf->lang['pt-PT']                                   = 'português';
		$"."conf->lang['ro']                                      = 'româna';
		$"."conf->lang['sr']                                      = 'српски';
		$"."conf->lang['sl']                                      = 'slovenšcina';
		$"."conf->lang['sk']                                      = 'slovencina';
		$"."conf->lang['fi-FI']                                   = 'suomi';
		$"."conf->lang['sv-SE']                                   = 'svenska';
		$"."conf->lang['el-GR']                                   = 'Ελληνικά';
		$"."conf->lang['bg']                                      = 'bulgarian';
		$"."conf->lang['mk']                                      = 'македонски';
		$"."conf->lang['name']                                    = '';
		
		$"."conf->select->lang['en-GB']                           = 1;
		$"."conf->select->lang['ca']                              = 0;
		$"."conf->select->lang['cs']                              = 0;
		$"."conf->select->lang['da-DK']                           = 0;
		$"."conf->select->lang['de-DE']                           = 1;
		$"."conf->select->lang['es-ES']                           = 0;
		$"."conf->select->lang['et']                              = 0;
		$"."conf->select->lang['eu']                              = 0;
		$"."conf->select->lang['fr-FR']                           = 0;
		$"."conf->select->lang['gl']                              = 0;
		$"."conf->select->lang['hr-HR']                           = 0;
		$"."conf->select->lang['is']                              = 0;
		$"."conf->select->lang['it-IT']                           = 0;
		$"."conf->select->lang['lt']                              = 0;
		$"."conf->select->lang['lv']                              = 0;
		$"."conf->select->lang['hu-HU']                           = 0;
		$"."conf->select->lang['mt']                              = 0;
		$"."conf->select->lang['nl-NL']                           = 0;
		$"."conf->select->lang['no']                              = 0;
		$"."conf->select->lang['pl']                              = 0;
		$"."conf->select->lang['pt-PT']                           = 0;
		$"."conf->select->lang['ro']                              = 0;
		$"."conf->select->lang['sr']                              = 0;
		$"."conf->select->lang['sl']                              = 0;
		$"."conf->select->lang['sk']                              = 0;
		$"."conf->select->lang['fi-FI']                           = 0;
		$"."conf->select->lang['sv-SE']                           = 0;
		$"."conf->select->lang['el-GR']                           = 0;
		$"."conf->select->lang['bg']                              = 0;
		$"."conf->select->lang['mk']                              = 0;
		$"."conf->trans['en_US']                                  = 'english';
		$"."conf->trans['de_DE']                                  = 'deutsch';
		$"."conf->trans['fr_FR']                                  = 'français';
		$"."conf->trans['es_ES']                                  = 'Español';
		?>
		");
		fclose($capfile);
		$conf->user->lang = 'en_US';
		$langs->setDefaultLang($conf->user->lang);		
		$langs->load("main");	
		
		// index.php#conf
		if(file_exists('conf/conf.php'))
		{
			header('Location: index.php#conf');
			exit;
		}
		else
		{
			$error_out.= '['.realpath('conf').'/conf.php] '.$langs->trans('perm_for_conf')."<p>";
			//die('Permision problems detectet pleas fix this: Can\'t create conf.php file in folder conf/<br>Please give this folder conf/ the group apache and the mod rwxrwxr-x');
		}
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

	if(! is_dir("output") || ! is_writable("output"))
	{
		$error_out.= '[output/] '.$langs->trans('perm_for_conf')."<p>";
		//((die('Permision problems detectet pleas fix this: Can\'t create the folder ("'.$post['cap']['output'].'") please create the folder manualy (rights 0774, group apache) or give the folder of the index.php the group apache! ');
	}

	if(!empty($error_out))
	{
		die($error_out);
	}

	$conf->cap->output="output";
	$conf->cap->save = 1;

	$conf->webservice->login = "";
	$conf->webservice->password = "";
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

				$Webservicename = parse_url($conf->webservice->WS_DOL_URL, PHP_URL_HOST);
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
				
				$Webservicename = parse_url($conf->webservice->WS_DOL_URL, PHP_URL_HOST);
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
		$conf->webservice->login = $_COOKIE['Session_login_name'];
		$conf->webservice->password =  $_COOKIE['Session_login_pass'];
	}
	
	if($_SESSION['Session_login_name'])
	{
		$conf->webservice->login = $_SESSION['Session_login_name'];
		$conf->webservice->password = $_SESSION['Session_login_pass'];
	}
	
	// build service url
	$service_arr = explode('/', $conf->webservice->WS_DOL_URL);
	end($service_arr);
	$key = key($service_arr);

	$conf->webservice->ns = str_replace($service_arr[$key],'',$conf->webservice->WS_DOL_URL);
	$conf->webservice->sourceapplication = $conf->webservice->WS_METHOD;

	// METEOALARM WEBSERVICE ---
	$conf->meteoalarm = 1;
	if($conf->meteoalarm == 1)
	{			
		if($conf->webservice->on > 0)
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
				 $conf->webservice_aktive = 1;
			}
			else
			{
				$conf->webservice_aktive = -1;
				//unset($_SESSION['Session_login_name'], $_SESSION['Session_login_pass']);
				//unset($_COOKIE['Session_login_name'], $_COOKIE['Session_login_pass']);
				//unset($conf->webservice->login);
				//unset($conf->webservice->password);
			}
		}
	}

	$login_to_webservice_faild = false;
	if($tryed_login == true && $conf->webservice_aktive == -1)
	{
		unset($_SESSION['Session_login_name'], $_SESSION['Session_login_pass']);
		unset($_COOKIE['Session_login_name'], $_COOKIE['Session_login_pass']);
		unset($conf->webservice->login);
		unset($conf->webservice->password);
		if(!is_array($_POST['send-logout']))
		{
			unset($_POST);
		}
		$login_to_webservice_faild = true;
	}

	if(!file_exists('conf/conf.php'))
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
				$location = $conf->cap->output.'/'.urldecode($_POST['location']);
			}
			
			$alert = new alert($location);
			$cap = $alert->output();
			
			// Convert
			$converter = new Convert_CAP_Class();		
			$capconvertet = $converter->convert($cap, $_POST['stdconverter'],	$_POST['areaconverter'], $_POST['inputconverter'], $_POST['outputconverter'], $conf->cap->output);

			$form = new CAP_Form();
			print $form->CapView($capconvertet, $cap[identifier].'.conv'); // Cap Preview +
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
			
			$alert = new alert($location);
			$cap = $alert->output();
			
			$cap_m = new CAP_Class($_POST);
			$cap_m->buildCap_from_read($cap);
			
			$cap_m->identifier = $_FILES["uploadfile"]["name"];
			$cap_m->destination = $conf->cap->output;
			$path = $cap_m->createFile();
			
			header('Location: '.$_SERVER['PHP_SELF'].'#read');
		}
		
		if(! empty($_FILES["uploadfile"]["name"]))
		{
			$location = $_FILES["uploadfile"]["tmp_name"];
		}
		else
		{
			if(! empty($_POST['location']))
			{
				$location = $conf->cap->output.'/'.urldecode($_POST['location']);
			}
			else if(! empty($_GET['location']))
			{
				$location = $conf->cap->output.'/'.urldecode($_GET['location']);
			}
		}
		
		$alert = new alert($location);
		$cap = $alert->output();
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
			unlink($conf->cap->output.'/'.$_GET['delete']);		
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
		
		if($conf->webservice_aktive == 1)
		{
			if($_POST['sender'] == "") $_POST['sender'] = $User['sender'];
			if($_POST['senderName'] == "") $_POST['senderName'] = $User['senderName'];
		}

		$cap = new CAP_Class($_POST);
		
		if(!empty($_GET['cap']))
		{
			// Used for the Cap preview
			$cap->buildCap();
			print $cap->cap;
		}
		else
		{
			// Used to build the cap and save it at $cap->destination
			$cap->buildCap();
			$cap->destination = $conf->cap->output;
			if($conf->cap->save == 1)	$path = $cap->createFile();
			
			$conf->identifier->ID_ID++;
			$form->WriteConf();
			
			print $form->CapView($cap->cap, $_POST[identifier]); // Cap Preview +
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
		$form->WriteConf();
		
		if($_POST['template_on'] == 'on')
		{
			if(!empty($_POST['Template']))
			{
				if(!file_exists('conf/'.$_POST['Template']))
				{
					if (!copy($conf->cap->output.'/'.$_POST['Template'], 'conf/template.cap')) 
					{
						die('Permision problems detectet pleas fix this: copy ['.$conf->cap->output.'/'.$_POST['Template'].'] to [conf/template.cap]' );
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
