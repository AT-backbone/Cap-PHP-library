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

	require_once 'lib/cap.create.class.php';

	class CAP_Engine{

		var $version = '1.3'; // Class of CAP-PHP-library 1.3
		var $login_id = 0;

		var $debug = false;
	   /**
		* Standard Values for Meteoalarm Caps 1.2
		*/
		// the severity tag
		var $severity = array(
			1 => 'Minor',
			2 => 'Moderate',
			3 => 'Severe',
			4 => 'Extreme'
		);

		// the event tag
		var $event_type = array(
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

		// the headline tag
		var $headline_level = array(
			1 => 'Green',
			2 => 'Yellow',
			3 => 'Orange',
			4 => 'Red'
		);

		// the awareness level parameter tag
		var $awareness_level = array(
			1 => '1; green; Minor',
			2 => '2; yellow; Moderate',
			3 => '3; orange; Severe',
			4 => '4; red; Extreme'
		);

		// the awareness type parameter tag
		var $awareness_type = array(
			1 => "Wind",
			2 => "Fire",
			3 => "test1",
			4 => "test2",
			5 => "test3",
			6 => "Tornado"
		);

		// Contains the areas of the country you choose
		var $AreaArray;
		// Contains the areas sortet with the ID from the AREA of the country you choose
		var $AreaIDArray;
		// Contains the warnings sorted to areas
		var $AreaCodesArray;

		// Contains the identifiers of the akitve warnings
		// struc: array(awt_type => array( EMMA_ID  => array( identifier, level, from, to, sender, timestamp) ) ) 
		var $cap_ident;

		// Contains
		// [Update/Alert][type][level][from][to][text_0.eid][NUMBER] = content of cap_array[aid];
		var $cap_data = array();

		// Contains the Cap you WANT to send and are Aktive
		// struc: array(AreaID => array( name, eid, level_1, NUMBER => array( eid, level, type, text_{number from lang}, inst_{number from lang}, from, to, ident)))
		var $cap_array;

		// Contains the Types you CAN send
		// struc: array(type_id => bool) 1 = true / 0 = false
		var $awt_arr;

		// Contains all languages used for your caps
		var $language;

		// Contains all warnings from you sortet to emma_id => type => from
		var $cancel_check;

		// Contains all white areas / all not warnd types of every area
		var $white_data;

	   /**
		* initialize Class with Data
		*
		* @return	None
		*/
		function __construct()
		{

		}

		/**
		 * Output RFC 3066 Array
		 *     
		 * @return	string						Array with RFC 3066 Array
		 */
		function create($config = false)
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


			if($data_arr[0]->type > 0 && $data_arr[0]->level > 0 && $data_arr[0]->eid != "")
			{
				if($data_arr[0]->exutc != "") $timezone_date = $data_arr[0]->exutc;
				else $timezone_date = date('P');

				$timezone_date_p = $timezone_date[0];
				$timezone_date_h = substr($timezone_date, 1);

				$post['identifier']				= $conf->identifier->WMO_OID.'.'.$conf->identifier->ISO.'.'.strtotime('now').'.1'.$data_arr[0]->type.$data_arr[0]->level.$data_arr[0]->eid;
				if($ref == "Update") 
				{
					$post['identifier']				= $conf->identifier->WMO_OID.'.'.$conf->identifier->ISO.'.'.strtotime('now').'.2'.$data_arr[0]->type.$data_arr[0]->level.$data_arr[0]->eid;
					if($data_arr[0]->sender == "") $data_arr[0]->sender = "CapMapImport@meteoalarm.eu";
					$post['references'] 			= $data_arr[0]->sender.','.$data_arr[0]->references.','.date('Y-m-d\TH:i:s\\'.$timezone_date, strtotime(str_replace('&nbsp;', ' ',$data_arr[0]->timestamp)));
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
					$post['event'][$lang_val]	= $this->severity[$data_arr[0]->level].' '.$this->event_type[$data_arr[0]->type].' warning';
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

				$post['severity'] 				= $this->severity[$data_arr[0]->level];
				
				// Template
				if($post['info'][0]['certainty'] != "") 
					$post['certainty'] 			= $post['info'][0]['certainty'];
				else
					$post['certainty'] 			= 'Likely';

				if($post['info'][0]['audience'] != "") 
					$post['audience'] = $post['info'][0]['audience'];

				$post['effective']['date'] = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_arr[0]->from.' + '.$_POST['data'].' days'))));
				$post['effective']['time'] = date('H:i:s', strtotime($data_arr[0]->from));
				$post['effective']['plus'] = $timezone_date_p;
				$post['effective']['UTC'] = $timezone_date_h;

				$post['onset']['date'] = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_arr[0]->from.' + '.$_POST['data'].' days'))));
				$post['onset']['time'] = date('H:i:s', strtotime($data_arr[0]->from));
				$post['onset']['plus'] = $timezone_date_p;
				$post['onset']['UTC'] = $timezone_date_h;

				if(strtotime($data_arr[0]->to) < strtotime('now')) $data_arr[0]->to = date('Y-m-d H:i:s',strtotime($data_arr[0]->to.' + 1 days'));
				$post['expires']['date'] = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_arr[0]->to.' + '.$_POST['data'].' days'))));
				$post['expires']['time'] = date('H:i:s', strtotime($data_arr[0]->to));
				$post['expires']['plus'] = $timezone_date_p;
				$post['expires']['UTC'] = $timezone_date_h;

				// Template
				if($post['info'][0]['senderName'] != "") 
					$post['senderName'] = $post['info'][0]['senderName'];
				else
					$post['senderName'] = 'ZAMG Zentralanstalt für Meteorologie und Geodynamik';

				foreach($langs_keys as $key => $lang_val)
				{
					if($data_arr[0]->{'text_'.$key} != "")	
					{
						$post['language'][] = $lang_val;
						$post['headline'][$lang_val] = $this->headline_level[$data_arr[0]->level].' '.$this->event_type[$data_arr[0]->type].' for '.$data_arr[0]->name;
						$post['description'][$lang_val] = $data_arr[0]->{'text_'.$key};
						if($data_arr[0]->inst_0 != "") $post['instruction'][$lang_val] = $data_arr[0]->{'inst_'.$key};
					}
					else
					{
						if($data_arr[0]->level == 1)
						{
							$post['language'][] = $lang_val;
							$post['headline'][$lang_val] = $this->headline_level[$data_arr[0]->level].' '.$this->event_type[$data_arr[0]->type].' for '.$data_arr[0]->name;
							$post['description'][$lang_val] = $this->headline_level[$data_arr[0]->level].' '.$this->event_type[$data_arr[0]->type].' for '.$data_arr[0]->name;
						}
					}
				}


				$post['areaDesc'] = $data_arr[0]->name;

				$post['parameter']['valueName'][0] = 'awareness_level';
				$post['parameter']['value'][0] =  $this->awareness_level[$data_arr[0]->level];

				$post['parameter']['valueName'][1] = 'awareness_type';
				$post['parameter']['value'][1] = $this->awareness_type[$data_arr[0]->type];

				foreach($data_arr as $key => $data)
				{
					$post['geocode']['value'][] = $data->eid.'<|>emma_id';
				}
				
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