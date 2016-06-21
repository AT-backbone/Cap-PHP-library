<?php
	error_reporting(E_ERROR);
	require_once '../class/translate.class.php';
	require_once 'cap.create.class.php';

	$langs = new Translate();

	if(file_exists('../conf/conf.php'))
	{
		include '../conf/conf.php';
		if(! empty($_GET['lang'])) $conf->user->lang = $_GET['lang'];
		$langs->setDefaultLang($conf->user->lang);		
		$langs->load("main");	
	}

	$language = array();
	/**
	 * Output RFC 3066 Array
	 *     
	 * @return	string						Array with RFC 3066 Array
	 */
	function getlang($config = false){
		global $conf, $language;
		
		if(is_array($language))
		{
			foreach($language as $key => $lang_name)
			{
				$out[$lang_name] = $lang_name;
			}
		}

		$out_tmp = $conf->lang;

		foreach($out_tmp as $key => $lang_name)
		{
			if($conf->select->lang[$key] == true) $out[$key] = $out_tmp[$key];
		}
		
		return $out;
	}

	$severity = array(
		1 => 'Minor',
		2 => 'Moderate',
		3 => 'Severe',
		4 => 'Extreme'
	);

	$event_type = array(
		1 => 'Wind',
		2 => 'snow-ice',
		3 => 'Thunderstorm',
		4 => 'Fog',
		5 => 'high-temperature',
		6 => 'low-temperature',
		7 => 'coastalevent',
		8 => 'forest-fire',
		9 => 'avalanches',
		10 => 'Rain',
		12 => 'flooding',
		13 => 'rain-flood'
	);

	$headline_level = array(
		1 => 'Green',
		2 => 'Yellow',
		3 => 'Orange',
		4 => 'Red'
	);

	$awareness_level = array(
		1 => '1; green; Minor',
		2 => '2; yellow; Moderate',
		3 => '3; orange; Severe',
		4 => '4; red; Extreme'
	);

	$awareness_type = array(
		1 => '1; Wind',
		2 => '2; snow-ice',
		3 => '3; Thunderstorm',
		4 => '4; Fog',
		5 => '5; high-temperature',
		6 => '6; low-temperature',
		7 => '7; coastalevent',
		8 => '8; forest-fire',
		9 => '9; avalanches',
		10 => '10; Rain',
		12 => '12; flooding',
		13 => '13; rain-flood'
	);

	// Delete all Caps in output
	$files = glob('../'.$conf->cap->output.'/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file)) unlink($file); // delete file
	}

	$cap_data = array();
	$cap_array = json_decode($_POST['cap_array']);

	//print_r($cap_array);
	foreach($cap_array as $aid => $warr)
	{
		//print $warr->name;
		//if(count($warr[0]) > 0)
		foreach($warr as $key => $warrning)
		{
			if($warrning->level > 0)
			{
				//print $warrning->level; //print $warrning->type;
				$cap_data[$warrning->type][$warrning->level][$aid] = $warrning;
			}
			else
			{
				$white_data[$aid] = $warrning;
			}
		}
	}
	//print_r($cap_array);
	$langs_arr = getlang();	
							
	foreach($langs_arr as $key_l => $val_l)
	{
		if(in_array($key,$language)) unset($langs_arr[$key]);
	}
	foreach ($langs_arr as $key_l => $val_l) 
	{
		$langs_keys[] = $key_l;
	}

	$capid = 0;
	foreach($cap_data as $type => $level_arr)
	{
		// Neues CAP
		foreach ($level_arr as $level => $area_arr)
		{
			// Neues CAP
			foreach($area_arr as $aid => $data)
			{
				if($data->type > 0 && $data->level > 0 && $aid > 0)
				{
					$post['identifier']				= $conf->identifier->WMO_OID.'.'.$conf->identifier->ISO.'.'.strtotime('now').'.'.$data->type.$data->level.$aid;
					$post['sender']					= 'admin@meteoalarm.eu';
					$post['status']['date'] 		= date('Y-m-d');
					$post['status']['time'] 		= date('H:i:s');
					$post['status']['plus'] 		= '+';
					$post['status']['UTC']  		= '01:00';
					$post['status'] 				= 'Actual';
					$post['msgType'] 				= 'Alert'; // Or Update / Cancel
					$post['scope'] 					= 'Public';
					$post['event'][$langs_keys[0]]	= $severity[$data->level].' '.$event_type[$data->type].' warning';
					$post['event'][$langs_keys[1]]	= $severity[$data->level].' '.$event_type[$data->type].' warning';
					$post['category'] 				= 'Met';				
					$post['responseType']			= 'Monitor';
					$post['urgency'] 				= 'Immediate';
					$post['severity'] 				= $severity[$data->level];
					$post['certainty'] 				= 'Likely';
					
					$post['effective']['date'] = date("Y-m-d");
					$post['effective']['time'] = date('H:i:s', strtotime($data->from));
					$post['effective']['plus'] = '+';
					$post['effective']['UTC'] = '01:00';
					$post['onset']['date'] = date("Y-m-d");
					$post['onset']['time'] = date('H:i:s', strtotime($data->from));
					$post['onset']['plus'] = '+';
					$post['onset']['UTC'] = '01:00';
					$post['expires']['date'] = date("Y-m-d");
					$post['expires']['time'] = date('H:i:s', strtotime($data->to));
					$post['expires']['plus'] = '+';
					$post['expires']['UTC'] = '01:00';

					$post['senderName'] = 'ZAMG Zentralanstalt für Meteorologie und Geodynamik';

					if($data->text_0 != "")	
					{
						$post['language'][] = $langs_keys[0];
						$post['headline'][$langs_keys[0]] = $headline_level[$data->level].' '.$event_type[$data->type].' for '.$data->name;
						$post['description'][$langs_keys[0]] = $data->text_0;
						if($data->inst_0 != "") $post['instruction'][$langs_keys[0]] = $data->inst_0;
					}
					if($data->text_1 != "")
					{
						$post['language'][] = $langs_keys[1];
						$post['headline'][$langs_keys[1]] = $headline_level[$data->level].' '.$event_type[$data->type].' for '.$data->name;
						$post['description'][$langs_keys[1]] = $data->text_1;
						if($data->inst_1 != "") $post['instruction'][$langs_keys[1]] = $data->inst_1;
					}

					$post['areaDesc'] = $data->name;

					$post['parameter']['valueName'][0] = 'awareness_level';
					$post['parameter']['value'][0] =  $awareness_level[$data->level];

					$post['parameter']['valueName'][1] = 'awareness_type';
					$post['parameter']['value'][1] = $awareness_type[$data->type];

					$post['geocode']['value'][] = $data->eid.'<|>emma_id';

					$cap = new CAP_Class($post);
					$cap->buildCap();
					$cap->destination = '../'.$conf->cap->output;
					$path = $cap->createFile();
					unset($post);
				}
			}

		}
	}

	unset($data);
	foreach($white_data as $aid => $data)
	{
		$white_area[] = array( 'aid' => $aid, 'name' => $data->name);
	}

	echo json_encode($white_area);
	//$files = glob('../'.$conf->cap->output.'/*'); // get all file names
	//echo json_encode($files);

?>