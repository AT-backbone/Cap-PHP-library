<?php
/*
 *  Copyright (c) 2016  Guido Schratzer   <schratzerg@backbone.co.at>
 *  Copyright (c) 2016  Niklas Spanring   <n.spanring@backbone.co.at>
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
 *	\file      	/class/cap.updater.class.php
 *  \ingroup   	core
 *	\brief     Create Caps that can be used as Update for meteoalarm or other webservices
 */

/**
 *	Create Caps that can be used to Updated Warnings from Meteoalarm or other webservices
 */
	// plugin/canada/cap_engine
	chdir('../'); // get to root of Cap PHP library
	error_reporting(E_ERROR | E_PARSE);
	require_once 'class/translate.class.php';
	require_once 'lib/cap.create.class.php';
	require_once 'class/cap.updater.class.php';
	require_once 'class/plugin.install.class.php';

	$plugin = new Plugin();
	$plugin->fetch($_POST['use_plugin']);

	$langs = new Translate();

	if(file_exists('conf/conf.php'))
	{
		include 'conf/conf.php';
		if(! empty($_GET['lang'])) $conf->user->lang = $_GET['lang'];
		$langs->setDefaultLang($conf->user->lang);		
		$langs->load("main");	


		// $conf->cap->output the dir of the caps
		$files = glob($conf->cap->output.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file) && $file != $conf->cap->output.'/COPYING') unlink($file); // delete file
		}
	}

	$langs_arr = getlang();	
							
	foreach($langs_arr as $key_l => $val_l)
	{
		if(in_array($key,$language)) unset($langs_arr[$key]);
	}
	foreach ($langs_arr as $key_l => $val_l) 
	{
		$langs_keys[] = $key_l;
	}

	$cap_array = json_decode($_POST['cap_array'], true);
	if(is_array($cap_array))
	foreach($cap_array as $id => $data)
	{
		print "\n".'id: '.$id."\n";
		if(is_array($data['type']))
		foreach($data['type'] as $type => $level)
		{
			print 'type: '.$type."\n";
			print 'level: '.$level."\n";
			if(is_array($langs_keys))
			foreach($langs_keys as $key => $lang_val)
			{
				print $lang_val.': '.$data['desc'][$type][$key]."\n";
				if($type > 0 && $level > 0 && $id != "")
				{

					if(file_exists('conf/template.cap'))
					{
						require_once 'lib/cap.read.template.class.php';
						$alert = new alert_template('conf/template.cap');
						$post = $alert->output_template();
						//print '<pre>';
						//	print_r($post);
						//print '</pre>';
						unset($alert);
					}

					if($data_arr[0]->exutc != "") $timezone_date = $data_arr[0]->exutc;
					else $timezone_date = date('P');

					$timezone_date_p = $timezone_date[0];
					$timezone_date_h = substr($timezone_date, 1);

					$post['identifier']				= $conf->identifier->WMO_OID.'.'.$conf->identifier->ISO.'.'.strtotime('now').'.1'.$type.$level.$id;
					if($ref == "Update") 
					{
						$post['identifier']				= $conf->identifier->WMO_OID.'.'.$conf->identifier->ISO.'.'.strtotime('now').'.2'.$type.$level.$id;
						if($sender == "") $sender = "CapMapImport@meteoalarm.eu";
						if($references) $post['references'] 			= $sender.','.$references.','.date('Y-m-d\TH:i:s\\'.$timezone_date, strtotime(str_replace('&nbsp;', ' ',$timestamp)));
					}

					// Template
					if($post['sender'] == "") 
					$post['sender']					= 'admin@meteoalarm.eu';

					$post['sent'] 					= array();
					$post['sent']['date'] 			= date('Y-m-d', strtotime('now + '.$_POST['data'].' days'));
					$post['sent']['time'] 			= date('H:i:s');
					$post['sent']['plus'] 			= $timezone_date_p;
					$post['sent']['UTC']  			= $timezone_date_h;

					// Template
					if($post['status'] != "") 
						$post['status'] 			= $post['status'];
					else
						$post['status'] 			= 'Actual';

					if($ref == "Update")
					{
						$post['msgType'] 			= 'Update'; // Or Update / Cancel
					}
					else
					{
						$post['msgType'] 			= 'Alert'; // Or Update / Cancel
					}

					// Template
					if($post['scope'] != "") 
						$post['scope'] 				= $post['scope'];
					else
						$post['scope'] 				= 'Public';

					foreach($langs_keys as $key => $lang_val)
					{
						$post['event'][$lang_val]	= $plugin->AWL[$level]['level'].' '.$plugin->AWT[$type]['hazard_type_DESC'].' warning';
					}

					// Template
					if($post['info'][0]['category'] != "") 
						$post['category'] 			= $post['info'][0]['category'];
					else
						$post['category'] 			= 'Met';		

					// Template
					if($post['info'][0]['responseType'] != "") 
						$post['responseType'] 		= $post['info'][0]['responseType'][0];
					else
						$post['responseType']		= 'Monitor';

					// Template
					if($post['info'][0]['urgency'] != "") 
						$post['urgency'] 			= $post['info'][0]['urgency'];
					else
						$post['urgency'] 			= 'Immediate';

					$post['severity'] 				= $plugin->AWL[$level]['level'];
					
					// Template
					if($post['info'][0]['certainty'] != "") 
						$post['certainty'] 			= $post['info'][0]['certainty'];
					else
						$post['certainty'] 			= 'Likely';

					if($post['info'][0]['audience'] != "") 
						$post['audience'] = $post['info'][0]['audience'];

					$post['effective']['date'] = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data['from'][$type].' + '.$_POST['data'].' days'))));
					$post['effective']['time'] = date('H:i:s', strtotime($data['from'][$type]));
					$post['effective']['plus'] = $timezone_date_p;
					$post['effective']['UTC'] = $timezone_date_h;

					$post['onset']['date'] = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data['from'][$type].' + '.$_POST['data'].' days'))));
					$post['onset']['time'] = date('H:i:s', strtotime($data['from'][$type]));
					$post['onset']['plus'] = $timezone_date_p;
					$post['onset']['UTC'] = $timezone_date_h;

					if(strtotime($data_arr[0]->to) < strtotime('now')) $data['to'][$type] = date('Y-m-d H:i:s',strtotime($data['to'][$type].' + 1 days'));
					$post['expires']['date'] = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data['to'][$type].' + '.$_POST['data'].' days'))));
					$post['expires']['time'] = date('H:i:s', strtotime($data['to'][$type]));
					$post['expires']['plus'] = $timezone_date_p;
					$post['expires']['UTC'] = $timezone_date_h;

					// Template
					if($post['info'][0]['senderName'] != "") 
						$post['senderName'] = $post['info'][0]['senderName'];
					else
						$post['senderName'] = 'ZAMG Zentralanstalt für Meteorologie und Geodynamik';

					foreach($langs_keys as $key => $lang_val)
					{
						if($data['desc'][$type][$key] != "")	
						{
							$post['language'][] = $lang_val;
							$post['headline'][$lang_val] = $plugin->AWL[$level]['name'].' '.$plugin->AWT[$type]['hazard_type_DESC'].' for '.$name;
							$post['description'][$lang_val] =$data['desc'][$type][$key];
							if($data['inst'][$type][$key] != "") $post['instruction'][$lang_val] = $data['inst'][$type][$key];
						}
						else
						{
							if($level == 1)
							{
								$post['language'][] = $lang_val;
								$post['headline'][$lang_val] = $plugin->AWL[$level]['name'].' '.$plugin->AWT[$type]['hazard_type_DESC'].' for '.$name;
								$post['description'][$lang_val] = $plugin->AWL[$level]['name'].' '.$plugin->AWT[$type]['hazard_type_DESC'].' for '.$name;
							}
						}
					}


					$post['areaDesc'] = $name;

					$post['parameter']['valueName'][0] = 'awareness_level';
					$post['parameter']['value'][0] = $plugin->AWL[$level]['level'];

					$post['parameter']['valueName'][1] = 'awareness_type';
					$post['parameter']['value'][1] = $plugin->AWT[$type]['hazard_type_DESC'];

					$post['geocode']['value'][] = $id.'<|>id';
					
					$cap = new CAP_Class($post);
					$cap->buildCap();
					$cap->destination = $conf->cap->output;
					$path = $cap->createFile();
					//print '<pre>';
					//	print_r($post);
					//print '</pre>';
					unset($post);
				}
			}
		}
	}


	/**
	 * Output RFC 3066 Array
	 *     
	 * @return	string						Array with RFC 3066 Array
	 */
	function getlang($config = false)
	{
		global $conf;
		
		if(is_array($language))
		{
			foreach($language as $key => $lang_name)
			{
				$out[$lang_name] = $lang_name;
			}
		}

		$out_tmp = $conf->lang;
		//conf->lang['en-GB']			= 'english'; // Key and name of lang
		//conf->select->lang['en-GB']	= 1; // key and bool if lang is aktive
		foreach($out_tmp as $key => $lang_name)
		{
			if($conf->select->lang[$key] == true) $out[$key] = $out_tmp[$key];
		}
		
		return $out;
	}
?>