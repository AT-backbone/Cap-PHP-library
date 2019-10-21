<?php
	chdir('../');
	error_reporting(E_ERROR);
	require_once 'class/translate.class.php';
	require_once 'cap.create.class.php';
	require_once 'class/conf.class.php';
	require_once 'class/cap.updater.class.php';

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

	$cap_data = array();
	if(!empty($_POST['cap_array'])) $cap_array_tmp = $_POST['cap_array'];
	if(!empty($_GET['cap_array'])) $cap_array_tmp = $_GET['cap_array'];
	if(!empty($_POST['awt'])) $awt_arr_tmp = $_POST['awt'];
	if(!empty($_GET['awt'])) $awt_arr_tmp = $_GET['awt'];
	if(!empty($_POST['data'])) $data = $_POST['data'];
	if(!empty($_GET['data'])) $data = $_GET['data'];
	$cap_array = json_decode($cap_array_tmp);
	$awt_arr = json_decode($awt_arr_tmp);
/*
?>
<script>
var arr = "<?php print_r($cap_array); ?>";
console.log('AWT Array: ' + arr);
</script>
<?php
*/
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

	if(!empty($_COOKIE['Session_login_name']))
	{
		$configuration->setValue("webservice", "login", $_COOKIE['Session_login_name']);
		$configuration->setValue("webservice", "password", $_COOKIE['Session_login_pass']);
	}

	if(!empty($_SESSION['Session_login_name']))
	{
		$configuration->setValue("webservice", "login", $_SESSION['Session_login_name']);
		$configuration->setValue("webservice", "password", $_SESSION['Session_login_pass']);
	}

	$service_arr = explode('/', $configuration->conf["webservice"]["WS_DOL_URL"]);
	end($service_arr);
	$key = key($service_arr);

	$configuration->setValue("webservice", "ns", str_replace($service_arr[$key],'',$configuration->conf["webservice"]["WS_DOL_URL"]));
	$configuration->setValue("webservice", "sourceapplication", $configuration->conf["webservice"]["WS_METHOD"]);

	$utc = date('P');

	$cap_array_2 = array();
	foreach($cap_array as $aid => $cap)
	{
		$i = 0;
		foreach($cap->type as $type => $level)
		{
			if(!empty($cap->exutc)) $cap_array_2[$aid][$i]->exutc = $cap->exutc;
			if(!is_array($cap_array_2[$aid])) $cap_array_2[$aid] = array();
			if(!is_array($cap_array_2[$aid][$i])) $cap_array_2[$aid][$i] = new stdClass();
			$cap_array_2[$aid][$i]->name = $cap->name;

			$cap_array_2[$aid][$i]->type = $type;
			$cap_array_2[$aid][$i]->level = $level;

			$cap_array_2[$aid][$i]->eid = $cap->emma_id;

			if(!empty($cap->date->$type)) $cap_array_2[$aid][$i]->data = $cap->date->$type;
			$cap_array_2[$aid][$i]->from = gmdate('Y-m-d H:i:s', strtotime($cap->date_from->$type.' '.str_replace('&nbsp;', ' ', $cap->from->$type)));
			$cap_array_2[$aid][$i]->to = gmdate('Y-m-d H:i:s', strtotime($cap->date_to->$type.' '.str_replace('&nbsp;', ' ', $cap->to->$type)));

			$cap_array_2[$aid][$i]->desc = $cap->desc->$type;
			$cap_array_2[$aid][$i]->inst = $cap->inst->$type;

			$cap_array_2[$aid][$i]->identifier = $cap->identifier->$type;

			$i++;
		}
	}
	//die(var_dump($cap_array_2));
	if(!empty($_POST['no_del'])) print_r($cap_array_2);

	$capupdater = new CAP_Updater($cap_array_2, $awt_arr, $data);

	//$capupdater->debug = true;

	//print 'getlang:<br>';
	// get all langs used in your caps
	//print '<br>getlang()';
	$capupdater->getlang();

	//print 'del_caps_in_output:<br>';
	// delete all caps in the export folder
	if(empty($_POST['no_del']))
	{
		//print '<br>del_caps_in_output()';
		$capupdater->del_caps_in_output();
	}

	//print 'webservice_meteoalarm:<br>';
	// get visio level warnings and area data from Meteoalarm webservice
	//print '<br>webservice_meteoalarm()';
	$capupdater->webservice_meteoalarm();

	//print 'get_area_identifier:<br>';
	// set identifier sorted in area
	//$capupdater->debug = true;
	//print '<br>get_area_identifier()';
	$capupdater->get_area_identifier();
	//$capupdater->debug = false;

	//print 'calc_cap_update:<br>';
	// calculate cap which should be Updated or Alerted
	//$capupdater->debug = true;
	//print '<br>calc_cap_update()';
	$capupdater->calc_cap_update();
	//$capupdater->debug = false;

	// be shure to sent no cancel CAP for nd from Green warnings!
	if(empty($_POST['no_del']))
	{
		//print 'calc_cap_cancel:<br>';
		// calculate cap which should be Cancelled (Updated as green)
		//print '<br>calc_cap_cancel()';
		$capupdater->calc_cap_cancel();
	}

	//print 'get_white_warnings:<br>';
	// calculate white areas
	//print '<br>get_white_warnings()';
	$capupdater->get_white_warnings();

	//print 'produce_all_caps:<br>';
	// produce and save all caps in the export folder
	//print '<br>produce_all_caps()';
	$capupdater->produce_all_caps();

	//print 'fetch_white_areas:<br>';
	// return white type from the areas
	//print '<br>fetch_white_areas()';
	$white_area = $capupdater->fetch_white_areas();

	//print 'json_encode:<br>';

	echo json_encode($white_area);

	//echo json_encode($files);

?>
