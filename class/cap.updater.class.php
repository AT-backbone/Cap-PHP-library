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

	class CAP_Updater{

		/**
			functions of the class CAP_Updater:
			// Set values to begin calculate
			__construct($cap_array, $awt, $langs)

			// get all langs used in your caps
			getlang($config = false)

			// delete all caps in the export folder
			del_caps_in_output()

			// get visio level warnings and area data from Meteoalarm webservice
			webservice_meteoalarm()

			// set identifier sorted in area
			get_area_identifier()

			// calculate cap which should be Updated or Alerted
			calc_cap_update()

			// calculate cap which should be Cancelled (Updated as green)
			calc_cap_cancel()

			// calculate white areas
			get_white_warnings()

			// produce and save all caps in the export folder
			produce_all_caps()

			// return white type from the areas
			fetch_white_areas()
		 */

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

		// Contains the areas of the country you choose
		var $AreaArray;
		// Contains the areas sortet with the ID from the AREA of the country you choose
		var $AreaIDArray;
		// Contains the warnings sorted to areas
		var $AreaCodesArray;

		// Contains User information from Meteoalarm
		var $User;

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
		function __construct($cap_array, $awt, $data, $langs = "")
		{
			if(!empty($cap_array)) $this->cap_array = $cap_array;
			if(!empty($awt)) $this->awt_arr = $awt;
			if(!empty($data)) $this->data = $data;
			if(!empty($langs)) $this->language = $langs;
			//die(print_r($this));
		}

		/**
	 * Output RFC 3066 Array
	 *
	 * @return	string						Array with RFC 3066 Array
	 */
		function getlang($config = false){
			global $configuration;

			if(is_array($this->language))
			{
				foreach($this->language as $key => $lang_name)
				{
					$out[$lang_name] = $lang_name;
				}
			}

			$out_tmp = $configuration->conf["language"];

			foreach($out_tmp as $key => $lang_name)
			{
				if($configuration->conf["selected_language"][$key] == true) $out[$configuration->conf["language_RFC3066"][$key]] = $out_tmp[$key];
			}

			return $out;
		}

	   /**
		* Delete all Caps in output
		*
		* @return None
		*/
		function del_caps_in_output()
		{
			global $configuration;

			// the dir of the caps
			$files = glob($configuration->conf["cap"]["output"].'/*'); // get all file names
			foreach($files as $file){ // iterate files
				if(is_file($file) && $file != $configuration->conf["cap"]["output"].'/COPYING') unlink($file); // delete file
			}

			return true;
		}

	   /**
		* Delete all Caps in output
		*
		* @return res true = OK, false = no webserice, -2 = can't fetch Area data, -3 = can't fetch VL data
		*/
		function webservice_meteoalarm()
		{
			global $configuration;
			$meteoalarm = 1; // set meteoalarm on (debug value)
			if($meteoalarm == 1) // is meteoalarm service on ?
			{
				if($configuration->conf["webservice"]["service_on"] > 0) // is webservice on ?
				{
					$res = true;
					$_GET['data'] = $this->data;
					require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP if not alredy

					if(file_exists('lib/cap.meteoalarm.webservices.Area.php')) // test if the lib exists
					{
						// Contains the areas of the country you choose
						include 'lib/cap.meteoalarm.webservices.Area.php';  // get data through the meteoalarm lib (Area)
						if(!empty($AreaCodesArray['document']['AreaInfo']))
						{
							$this->AreaArray = $AreaCodesArray['document']['AreaInfo'];
						}
						else
						{
							$res = -2; // Can't fetch Area Data
						}
					}
					else
					{
						print 'file do not exists: lib/cap.meteoalarm.webservices.Area.php';
					}
					//print_r($AreaCodesArray);
					if(file_exists('lib/cap.meteoalarm.webservices.vl.php'))  // test if the lib exists
					{
						// Contains the warnings sorted to areas
						include 'lib/cap.meteoalarm.webservices.vl.php'; // get data through the meteoalarm lib (vl - Visio Level)
						if(!empty($AreaCodesArray['document']['AreaInfo']))
						{
							$this->AreaCodesArray = $AreaCodesArray['document']['AreaInfo'];
						}
						else
						{
							$res = -3;  // Can't fetch VL Data
						}
					}
					else
					{
						print 'file do not exists: lib/cap.meteoalarm.webservices.vl.php';
					}
					//print_r($AreaCodesArray);
					if(file_exists('lib/cap.meteoalarm.webservices.user.php'))  // test if the lib exists
					{
						// Contains the warnings sorted to areas
						include 'lib/cap.meteoalarm.webservices.user.php'; // get data through the meteoalarm lib (vl - Visio Level)
						if(!empty($User['document']['AreaInfo']))
						{
							$this->User = $User['document']['AreaInfo'];
							// $this->User['sender'] = email
							// $this->User['senderName'] = meteo Name
						}
						else
						{
							$res = -3;  // Can't fetch VL Data
						}
					}
					else
					{
						print 'file do not exists: lib/cap.meteoalarm.webservices.vl.php';
					}
					//if($this->debug == true) print_r($AreaCodesArray);
					return $res; // return res !
				}
				return false; // no webservice
			}
			return false; // Meteoalarm is off (can't happen)
		}

	   /**
		* Get Area information (Emma id geocode) and get the identifier of aktive Warnigns ($this->cap_ident)
		*
		* @return true
		*/
		function get_area_identifier()
		{
			// Change AreaArray to Area -> ID <- Array
			foreach($this->AreaArray as $key => $area)
			{
				$this->AreaIDArray[$area['aid']] = $area;
			}
			unset($this->AreaArray);

			// Output Debug values
			//print "d:".$this->debug;
			if($this->debug == true)
			{
				print '<pre>AreaCodesArray(VL): ';
					print_r($this->AreaCodesArray);
				print '</pre>';
			}

			//
			foreach($this->AreaCodesArray as $key => $vl_warn)
			{
				// Add to the Area ID Array the Level and Type info from the VL Warnigns
				$this->AreaIDArray[$vl_warn['aid']][$vl_warn['type']] = $vl_warn['level'];

				// Add identifier information to the cap_ident variable ()
				$this->cap_ident[$vl_warn['type']][$vl_warn['EMMA_ID']]['id']			= $vl_warn['identifier'];
				$this->cap_ident[$vl_warn['type']][$vl_warn['EMMA_ID']]['level']		= intval($vl_warn['level']);
				$this->cap_ident[$vl_warn['type']][$vl_warn['EMMA_ID']]['from']			= date('Y-m-d H:i:s', $this->add_timezone(str_replace('&nbsp;', ' ', $vl_warn['from'])));
				$this->cap_ident[$vl_warn['type']][$vl_warn['EMMA_ID']]['to']			= date('Y-m-d H:i:s', $this->add_timezone(str_replace('&nbsp;', ' ', $vl_warn['to'])));
				$this->cap_ident[$vl_warn['type']][$vl_warn['EMMA_ID']]['sender']		= $vl_warn['sender'];
				$this->cap_ident[$vl_warn['type']][$vl_warn['EMMA_ID']]['timestamp']	= $vl_warn['timestamp'];
				//die(print_r($this->cap_ident));
			}

			// Output Debug values
			if($this->debug == true)
			{
				print '<pre>cap_ident(identifier): ';
					print_r($this->cap_ident);
				print '</pre>';
			}

			return true;
		}

	   /**
		* calculate Updates or Alerts for CAP export
		*
		* @return true
		*/
		function calc_cap_update()
		{
			if($this->debug == true)
			{
				print '<pre>';
					print_r($this->cap_array);
				print '</pre>';
			}
			foreach($this->cap_array as $aid => $warr)
			{
				if($this->debug == true) print '<p>'.$warr->name.'<br>'; // Output Debug values

				foreach($warr as $key => $warning)
				{
					if($warning->level > 0) // is level bigger than 0
					{
						// get identifier
						$ident_level = $this->cap_ident[$warning->type][$warning->eid]['level'];
						$ident = $this->cap_ident[$warning->type][$warning->eid]['id'];
						// set 'aid' also in the $warning string Array()
						$warning->aid = $aid;
						//$this->debug = true;
						if($this->debug == true)
						{
							print '<br>if: '.$ident.'!= ""';
							print '<br>if: '.$ident.' == '.$warning->ident;
							print '<br>if: '.$this->cap_ident[$warning->type][$warning->eid]['level'].' == '.$warning->level;
							print '<br>if: '.$this->cap_ident[$warning->type][$warning->eid]['from'].' == '.str_replace('&nbsp;', ' ', $warning->from);
							print '<br>if: '.$this->cap_ident[$warning->type][$warning->eid]['to'].' == '.str_replace('&nbsp;', ' ', $warning->to);
						}

						// TODO: also check from, to and desc
						// if identifier is not empty and is the same as the proccesed warning and also the lavel is the same: (we dont need a update or alert)
						// or if the level the from and the to value is the same: (we dont need a update or alert)
						if  (
							 (
								$ident != ""
								 &&
								$ident == $warning->ident
								 &&
								$this->cap_ident[$warning->type][$warning->eid]['level'] == $warning->level
								 &&
								$this->cap_ident[$warning->type][$warning->eid]['from'] == str_replace('&nbsp;', ' ', $warning->from)
								 &&
								$this->cap_ident[$warning->type][$warning->eid]['to'] == str_replace('&nbsp;', ' ', $warning->to)
							 )
							  ||
							 (
							 	$ident == $warning->identifier
							 	 &&
								$this->cap_ident[$warning->type][$warning->eid]['level'] == $warning->level
								 &&
								$this->cap_ident[$warning->type][$warning->eid]['from'] == str_replace('&nbsp;', ' ', $warning->from)
								 &&
								$this->cap_ident[$warning->type][$warning->eid]['to'] == str_replace('&nbsp;', ' ', $warning->to)
							 )
							)
						{
							// do not make an warning (warning allready exist)
						}
						elseif($ident != "" && $ident_level > 1 && $warning->level > 1 ) // when the identifier is not empty for this warnings than make a update
						{
							// make an Update
							$warning->sender 		= $this->cap_ident[$warning->type][$warning->eid]['sender'];
							$warning->timestamp 	= $this->cap_ident[$warning->type][$warning->eid]['timestamp'];
							$warning->references 	= $ident; // set the referenc for the Updated
							$this->cap_data['Update'][$warning->type][$warning->level][addslashes($warning->from)][addslashes($warning->to)][addslashes($warning->desc->{'0'}).$warning->eid][] = $warning; // Updates have to be send one by one!
						}
						else // if there is no similar warning so make an Alert
						{
							// make an Alert
							$this->cap_data['Alert'][$warning->type][$warning->level][addslashes($warning->from)][addslashes($warning->to)][addslashes($warning->desc->{'0'})][] = $warning;
						}

						$this->AreaIDArray[$aid][$warning->type] = $warning->level; // set level to type
						$this->cancel_check[$warning->eid][$warning->type][date('Y-m-d', strtotime($warning->from))] = $warning;

					} // is level bigger than 0 exit
				} // foreach warr exit
			} // foreach cap_array exit

			// Output Debug values
			if($this->debug == true)
			{
				print '<pre>cap data: ';
					print_r($this->cap_data);
				print '</pre>';

				print '<pre>cancel data: ';
					print_r($cancel_check);
				print '</pre>';
			}

			return true;
		}

	   /**
		* calculate Cancel for Cap export
		*
		* @return true
		*/
		function calc_cap_cancel()
		{
			// Look for Cancel
			foreach($this->AreaCodesArray as $key => $vl_warning)
			{
				// if the EMMA_ID and TYPE from cancel_check is not set and
				// the date of the Warning is the same
				$timezone_date = date('P');

				$timezone_date_p = $timezone_date[0];
				$timezone_date_h = $timezone_date;

				if  (
						! isset($this->cancel_check[$vl_warning['EMMA_ID']][$vl_warning['type']])
						 &&
						date('Y-m-d', strtotime(str_replace('&nbsp;', ' ',$vl_warning['from']).' '.$timezone_date_p.' '.$timezone_date_h[1].$timezone_date_h[2].' hours')) == date('Y-m-d', strtotime('now + '.$_POST['data'].' days'))
				    )
				{
					// Cancel This warning
					unset($warning); // unset warning for secure
					$warning = $this->cancel_check[$vl_warning['EMMA_ID']][$vl_warning['type']];
					if($vl_warning['level'] > 1) // is the warning on meteoalarm which we want to cancel higher than level 1 (green)
					{
						// Build Update CAP (green)
						$ident = $vl_warning['identifier'];
						$warning->sender 		= $this->cap_ident[$warning->type][$warning->eid]['sender'];
						$warning->timestamp 	= $this->cap_ident[$warning->type][$warning->eid]['timestamp'];
						$warning->references 	= $ident;

						$warning->name = $vl_warning['AreaCaption'];
						$warning->eid = $vl_warning['EMMA_ID'];

						$warning->desc->{'0'} = "cancel warning";
						$warning->type = $vl_warning['type'];
						$warning->level = 1;

						$warning->from = date('Y-m-d').' 00:00:00';
						$warning->to = date('Y-m-d').' 23:59:59';

						$this->cap_data['Update'][$vl_warning['type']][1][addslashes(str_replace('&nbsp;', ' ',$vl_warning['from']))][addslashes(str_replace('&nbsp;', ' ',$vl_warning['to']))]['no warning'][] = $warning;

						// Output Debug values
						if($this->debug == true)
						{
							print '<pre> cap data Cancel / Update Green:';
								print_r($this->cap_data['Update'][$vl_warning['type']][1]);
							print '</pre>';
						}
					}
				}
			}

			unset($this->cancel_check); // free cancel data (it is no longer needed)
			return true;
		}

	   /**
		* calculate Cancel for Cap export
		*
		* @return true if ther are white areas else false
		*/
		function get_white_warnings()
		{
			foreach($this->AreaIDArray as $aid => $data) // check all warnings
			{
				foreach($this->awt_arr as $type => $awt_bool) // check all aktive types
				{
					if($awt_bool == 1 && $data[$type] < 1) // when the type is aktive
					{
						$this->white_data[$aid][$type] = array('aid' => $data['aid'], 'eid' => $data['EMMA_ID'], 'name' => $data['AreaCaption']);
					}
				}
			}

			// Output Debug values
			//$this->debug = 1;
			if($this->debug == true)
			{
				print '<pre> cap data Cancel / Update Green:';
					print_r($this->white_data['Update'][$vl_warning['type']][1]);
					print_r($this->AreaIDArray);
				print '</pre>';
			}

			// area ther any white area / types
			if(count($this->white_data) > 0)
			{
				return true; // ther are white area / types
			}
			else
			{
				return false; // ther are no white area / types
			}
		}

	   /**
		* make all caps from the calculation
		*
		* @return true
		*/
		function produce_all_caps()
		{
			global $configuration;

			$langs_arr = $this->getlang();

			foreach($langs_arr as $key_l => $val_l)
			{
				if(in_array($key,$this->language)) unset($langs_arr[$key]);
			}
			foreach ($langs_arr as $key_l => $val_l)
			{
				$langs_keys[] = $key_l;
			}

			foreach($this->cap_data as $ref => $ref_arr)
			{
				foreach($ref_arr as $type => $type_arr)
				{
					//print '<br>type:'.$type;
					// Neues CAP
					foreach ($type_arr as $level => $level_arr)
					{
						//print '<br>level:'.$level;
						foreach($level_arr as $from => $from_arr)
						{
							//print '<br>from:'.$from;
							foreach($from_arr as $to => $to_arr)
							{
								//print '<br>to:'.$to;
								// Neues CAP
								foreach($to_arr as $text_0 => $data_arr)
								{
									//print '<pre>data_arr data: ';
									//	print_r($data_arr);
									//print '</pre>';

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
										else $timezone_date = '+00:00';

										$timezone_date_p = $timezone_date[0];
										$timezone_date_h = substr($timezone_date, 1);

										$post['identifier']				= $configuration->conf["identifier"]["WMO_OID"].'.'.$configuration->conf["identifier"]["ISO"].'.'.strtotime('now').'.1'.$data_arr[0]->type.$data_arr[0]->level.$data_arr[0]->eid;
										if($ref == "Update")
										{
											$post['identifier']				= $configuration->conf["identifier"]["WMO_OID"].'.'.$configuration->conf["identifier"]["ISO"].'.'.strtotime('now').'.2'.$data_arr[0]->type.$data_arr[0]->level.$data_arr[0]->eid;
											if($this->User['sender'] != "") $data_arr[0]->sender = $this->User['sender'];
											if($data_arr[0]->sender == "") $data_arr[0]->sender = "CapMapImport@meteoalarm.eu";
											$post['references'] 			= $data_arr[0]->sender.','.$data_arr[0]->references.','.date('Y-m-d\TH:i:s\\'.$timezone_date, strtotime(str_replace('&nbsp;', ' ',$data_arr[0]->timestamp)));
										}

										// Template
										if($this->User['sender'] != "")
										$post['sender']					= $this->User['sender'];

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

										if(!empty($data_arr[0]->data) && $_POST['data'] > 1) $eff_date = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_arr[0]->from.' + 1 days'))));
										else $eff_date = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_arr[0]->from.' + '.$_POST['data'].' days'))));

										$post['effective']['date'] = $eff_date;
										$post['effective']['time'] = date('H:i:s', strtotime($data_arr[0]->from));
										$post['effective']['plus'] = $timezone_date_p;
										$post['effective']['UTC'] = $timezone_date_h;

										$post['onset']['date'] = $eff_date;
										$post['onset']['time'] = date('H:i:s', strtotime($data_arr[0]->from));
										$post['onset']['plus'] = $timezone_date_p;
										$post['onset']['UTC'] = $timezone_date_h;

										if(strtotime($data_arr[0]->to) < strtotime($data_arr[0]->from)) $Pdata = $_POST['data'] + 1;
										else $Pdata = $_POST['data'];

										if(!empty($data_arr[0]->data) && $Pdata > 1) $exp_date = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_arr[0]->to.' + 1 days'))));
										else $exp_date = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_arr[0]->to.' + '.$Pdata.' days'))));

										$post['expires']['date'] = $exp_date;
										$post['expires']['time'] = date('H:i:s', strtotime($data_arr[0]->to));
										$post['expires']['plus'] = $timezone_date_p;
										$post['expires']['UTC'] = $timezone_date_h;

										// Template
										if($post['info'][0]['senderName'] != "")
											$post['senderName'] = $post['info'][0]['senderName'];
										elseif($this->User['senderName'] != "")
											$post['senderName']	= $this->User['senderName'];
										else
											$post['senderName'] = 'Cap-PHP-Library Version 1.3';


										foreach($langs_keys as $key => $lang_val)
										{
											if($data_arr[0]->desc->$key != "")
											{
												$post['language'][] = $lang_val;
												$post['headline'][$lang_val] = $this->headline_level[$data_arr[0]->level].' '.$this->event_type[$data_arr[0]->type].' for '.$data_arr[0]->name;
												$post['description'][$lang_val] = $data_arr[0]->desc->$key;
												if($data_arr[0]->inst->$key != "") $post['instruction'][$lang_val] = $data_arr[0]->inst->$key;
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

										if($post['info'][0]['web'] != "")
											$post['web'] 		= $post['info'][0]['web'];

										if($post['info'][0]['contact'] != "")
											$post['contact'] 		= $post['info'][0]['contact'];

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
										$cap->destination = $configuration->conf["cap"]["output"];
										$path = $cap->createFile();
										//print '<pre>';
										//	print_r($post);
										//print '</pre>';
										unset($post);
									}
								}
							}
						}

					}
				}
			}
		}

	   /**
		* return the white areas
		*
		* @return array (aid, eid, name, type)
		*/
		function fetch_white_areas()
		{
			foreach($this->white_data as $aid => $data)
			{
				foreach ($data as $type => $wh_arr) {
					if($wh_arr['aid'] > 0)
					{
						$white_areas[] = array( 'aid' => $wh_arr['aid'],  'eid' => $wh_arr['eid'], 'name' => $wh_arr['name'], 'type' => $type);
					}
				}
			}

			return $white_areas;
		}

		// Add the timezone in hours to a date time
		function add_timezone($timedate)
		{
			$utc = date('P');
			return strtotime(date('Y-m-d H:i:s', strtotime($timedate)));
		}
}
