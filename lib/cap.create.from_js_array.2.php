<?php
	chdir('../');
	error_reporting(E_ERROR);
	require_once 'class/translate.class.php';
	require_once 'cap.create.class.php';
	require_once 'cap.updater.class.php';

	$langs = new Translate();

	if(file_exists('conf/conf.php'))
	{
		include 'conf/conf.php';
		if(! empty($_GET['lang'])) $conf->user->lang = $_GET['lang'];
		$langs->setDefaultLang($conf->user->lang);		
		$langs->load("main");	
	}


	$cap_data = array();
	if(!empty($_POST['cap_array'])) $cap_array_tmp = $_POST['cap_array'];
	if(!empty($_GET['cap_array'])) $cap_array_tmp = $_GET['cap_array'];
	if(!empty($_POST['awt'])) $awt_arr_tmp = $_POST['awt'];
	if(!empty($_GET['awt'])) $awt_arr_tmp = $_GET['awt'];
	$cap_array = json_decode($cap_array_tmp);
	$awt_arr = json_decode($awt_arr_tmp);

	$capupdater = new CAP_Updater();

		// Set values to begin calculate
	$capupdater->__construct($cap_array, $awt);

	// get all langs used in your caps
	$capupdater->getlang();

	// delete all caps in the export folder
	$capupdater->del_caps_in_output();

	// get visio level warnings and area data from Meteoalarm webservice
	$capupdater->webservice_meteoalarm();
	
	// set identifier sorted in area
	$capupdater->get_area_identifier();

	// calculate cap which should be Updated or Alerted
	$capupdater->calc_cap_update();
	
	// calculate cap which should be Cancelled (Updated as green)
	$capupdater->calc_cap_cancel();

	// calculate white areas
	$capupdater->get_white_warnings();
		
	// produce and save all caps in the export folder
	$capupdater->produce_all_caps();
	
	// return white type from the areas
	$white_area = $capupdater->fetch_white_areas();

	echo json_encode($white_area);
	//$files = glob('../'.$conf->cap->output.'/*'); // get all file names
	//echo json_encode($files);

?>