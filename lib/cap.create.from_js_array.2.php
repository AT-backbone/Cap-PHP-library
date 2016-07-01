<?php
	chdir('../');
	error_reporting(E_ERROR | E_PARSE);
	require_once 'class/translate.class.php';
	require_once 'cap.create.class.php';
	require_once 'class/cap.updater.class.php';

	$langs = new Translate();

	if(file_exists('conf/conf.php'))
	{
		include 'conf/conf.php';
		if(! empty($_GET['lang'])) $conf->user->lang = $_GET['lang'];
		$langs->setDefaultLang($conf->user->lang);		
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

	$cap_data = array();
	if(!empty($_POST['cap_array'])) $cap_array_tmp = $_POST['cap_array'];
	if(!empty($_GET['cap_array'])) $cap_array_tmp = $_GET['cap_array'];
	if(!empty($_POST['awt'])) $awt_arr_tmp = $_POST['awt'];
	if(!empty($_GET['awt'])) $awt_arr_tmp = $_GET['awt'];
	$cap_array = json_decode($cap_array_tmp);
	$awt_arr = json_decode($awt_arr_tmp);

	//print '<br>Start:<br>';

	// $conf->webservice
	// $conf->lang
	// $conf->select->lang
	// $conf->cap->output
	// $conf->meteoalarm
	// $conf->webservice->on
	// $conf->identifier->WMO_OID
	// $conf->identifier->ISO
	// $conf->cap->output
	$capupdater = new CAP_Updater($cap_array, $awt_arr);

	$capupdater->debug = false;

	//print 'getlang:<br>';
	// get all langs used in your caps
	$capupdater->getlang();

	//print 'del_caps_in_output:<br>';
	// delete all caps in the export folder
	$capupdater->del_caps_in_output();

	//print 'webservice_meteoalarm:<br>';
	// get visio level warnings and area data from Meteoalarm webservice
	$capupdater->webservice_meteoalarm();
	
	//print 'get_area_identifier:<br>';
	// set identifier sorted in area
	$capupdater->get_area_identifier();

	//print 'calc_cap_update:<br>';
	// calculate cap which should be Updated or Alerted
	//$capupdater->debug = true;
	$capupdater->calc_cap_update();
	//$capupdater->debug = false;
	
	//print 'calc_cap_cancel:<br>';
	// calculate cap which should be Cancelled (Updated as green)
	$capupdater->calc_cap_cancel();

	//print 'get_white_warnings:<br>';
	// calculate white areas
	$capupdater->get_white_warnings();
	
	//print 'produce_all_caps:<br>';
	// produce and save all caps in the export folder
	$capupdater->produce_all_caps();
	
	//print 'fetch_white_areas:<br>';
	// return white type from the areas
	$white_area = $capupdater->fetch_white_areas();

	//print 'json_encode:<br>';

	echo json_encode($white_area);
	//$files = glob('../'.$conf->cap->output.'/*'); // get all file names
	//echo json_encode($files);

?>