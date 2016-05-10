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
 *	\file      	cap.convert.class.php
 *  \ingroup   	build
 *	\brief      File of class with CAP 1.2 builder
 *	\standards  from http://docs.oasis-open.org/emergency/cap/v1.2/CAP-v1.2-os.html
 *
 */
	
	require_once 'cap.write.class.php'; // for the XML / CAP view
	require_once 'log.lib.php';

	class Convert_CAP_Class{
		var $output = "CAP"; // CAP / XML
		var $cap = "";
		var $destination = "output";
		var $debug = "";
		var $_log = "";
		
		// alert
		var $identifier			= ""; 			// WMO Organisation ID green -> Your Country ISO Code -> Your File Date/Time "YYMMDDHHMMSS" -> your warning ID (CHAR max Len: 20 / special characters are not allowed only a-Z 0-9 and "_") -> 2.49.0.3.0.AT.150112080000.52550478
		var $sender 				= ""; 			// link to Homepage Guaranteed by assigner to be unique globally
		var $sent 					= ""; 			// <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min> Offset to UTC (e.g. CET: +01:00; CEST: +02:00) -> 2014-07-15T06:03:02+01:00
		var $status 				= ""; 			// Actual / Test / Exercise / System / Test / Draft
		var $msgType 				= "";	 			// Alert / Update / Cancel / Ack / Error
		var $references 		= array();	// web / identifier / sent [from the older Cap (only fore Update and Cancel)] -> http://www.zamg.ac.at/warnsys/public/aus_all.html,2.49.0.3.0.AT.150115080000.52550477,2015-01-08T10:05:02+01:00
		var $scope 					= "";				// Public / Restricted / Private
		                            	
			// info                	
			var $language 		= array(); 	// language-COUNTRY 	Format RFC 3066 Specification: de-DE -> German
			var $category 		= array();	// Geo / Met / Safety / Security / Rescue / Fire / Health / Env / Transport / Infra / CBRNE / Other
			var $event 				= array();	// The text denoting the type of the subject event of the alert message
			var $responseType = array();	// Shelter / Evacuate / Prepare / Execute / Avoid / Monitor / Assess / AllClear / None
			var $urgency 			= array();	// Immediate / Expected / Future / Past
			var $severity 		= array();	// Extreme / Severe / Moderate / Minor / Unknown
			var $certainty 		= array();	// Observed / Likely / Possible/ Unlikely / Unknown
			var $audience 		= array();	// The text describing the intended audience of the alert message 
			var $eventCode 		= array();	// <eventCode>  <valueName>valueName</valueName>  <value>value</value></eventCode>
			var $effective 		= array();	// The effective time 																														/ Form: <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min> Offset to UTC -> 2015-01-08T10:05:02+01:00
			var $onset 				= array();	// The expected time of the beginning of the subject event of the alert message  	/ Form: <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min> Offset to UTC -> 2015-01-08T10:05:02+01:00
			var $expires 			= array();	// The expiry time of the information of the alert message 												/ Form: <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min> Offset to UTC -> 2015-01-08T15:00:13+01:00
			var $senderName 	= array();	// The text naming the originator of the alert message  (The human-readable name of the agency or authority issuing this alert.) -> ZAMG Österreich
			var $headline 		= array();	// The text headline of the alert message 
			var $description 	= array();	// The text describing the subject event of the alert message 
			var $instruction 	= array();	// The text describing the recommended action to be taken by recipients of the alert message 
			var $web 					= array();	// The identifier of the hyperlink associating additional information with the alert message 
			var $contact 			= array();	// The text describing the contact for follow-up and confirmation of the alert message 
			var $parameter 		= array(); 	// A system-specific additional parameter associated with the alert message (as example meteoalarm.eu using it as specific warnings identifier) <parameter>  <valueName>valueName</valueName>  <value>value</value></parameter>
			
			// area
			var $area					= array();	// The container for all component parts of the area sub-element of the info sub-element of the alert message 
				var $areaDesc 	= array();	// A text description of the affected area. -> Niederösterreich
				var $polygon 		= array();	// The paired values of points defining a polygon that delineates the affected area of the alert message 
				var $circle 		= array();	// The paired values of a point and radius delineating the affected area of the alert message
				var $geocode 		= array(); 	// <geocode><valueName>valueName</valueName>  <value>value</value></geocode> -> valueName: NUTS2 value: AT12
				
		/**
     * initialize Class with Data
     *
     * @param   string	$post			Array with Type/Tag of CAP 1.2
     * @return	None
     */
		function __construct($post = "")
		{
			if(is_array($post))
			{
				$this->output 			= $post['output'];
				$this->identifier 	= $post['identifier'];
				$this->sender				= $post['sender'];
				$this->sent					= $post['sent'];
				$this->status				= $post['status'];
				$this->msgType			= $post['msgType'];
				$this->references		= $post['references'];
				$this->scope				= $post['scope'];
				$this->language			= $post['language'];
				$this->category			= $post['category'];
				$this->event				= $post['event'];
				$this->responseType	= $post['responseType'];
				$this->urgency			= $post['urgency'];
				$this->severity			= $post['severity'];
				$this->certainty		= $post['certainty'];
				$this->audience			= $post['audience'];
				$this->eventCode		= $post['eventCode'];
				$this->effective		= $post['effective'];
				$this->onset				= $post['onset'];
				$this->expires			= $post['expires'];
				$this->senderName		= $post['senderName'];
				$this->headline			= $post['headline'];
				$this->description	= $post['description'];
				$this->instruction	= $post['instruction'];
				$this->web					= $post['web'];
				$this->contact			= $post['contact'];
				$this->parameter		= $post['parameter'];

				$this->areaDesc			= $post['areaDesc'];
				$this->polygon			= $post['polygon'];
				$this->circle				= $post['circle'];
				$this->geocode			= $post['geocode'];

			}
		}
		
		/*********************************************************************************************************************************
		 *********************************************************************************************************************************
     *  						  						  						  				NEW Converter
     *********************************************************************************************************************************
     *********************************************************************************************************************************/
    var $conv = "";
    var $tmpconv = "";
    var $actions = "";
    var $input_actions	= "";
		var $output_actions	= "";
		var $move_action = "";

		function convert($cap, $std_c, $area_c, $input, $output, $cap_output_path)
		{
			error_reporting(E_ERROR);
			global $conf;
			if($cap_output_path) $this->destination = $cap_output_path;
			else								 $this->destination = $conf->cap->output;
			
			$this->_log.= cap_syslog('_______________________________________________________________________________________________________________', LOG_INFO, 'CAP_Converter');
			$this->_log.= cap_syslog('Start Converting !', LOG_INFO, 'CAP_Converter'); // LOG_EMERG LOG_ALER, LOG_CRIT LOG_ERR LOG_WARNING LOG_NOTICE LOG_INFO LOG_DEBUG
			$this->_log.= cap_syslog('', LOG_INFO, 'CAP_Converter');
			$this->_log.= cap_syslog(array('Input File: ', 'conv_'.$input.'.conf.php'), LOG_INFO, 'CAP_Converter');
			$this->_log.= cap_syslog(array('Output File: ', 'conv_'.$output.'.conf.php'), LOG_INFO, 'CAP_Converter');
			$this->_log.= cap_syslog('', LOG_INFO, 'CAP_Converter');
			
			if(!file_exists('./convert/conv_'.$input.'.conf.php'))
			{
				$this->_log.= cap_syslog('Could not found: ./convert/conv_'.$input.'.conf.php', LOG_ERR, 'CAP_Converter');
				return 'Could not found: ./convert/conv_'.$input.'.conf.php';
			}			
			if(!file_exists('./convert/conv_'.$output.'.conf.php'))
			{
				$this->_log.= cap_syslog('Could not found: ./convert/conv_'.$output.'.conf.php', LOG_ERR, 'CAP_Converter');
				return 'Could not found: ./convert/conv_'.$output.'.conf.php';
			}
			
			// Input 
			include './convert/conv_'.$output.'.conf.php';
			$this->conv = $conv;
			$this->tmpconv = $this->conv;
			unset($this->conv, $conv);
			
			include './convert/conv_'.$input.'.conf.php';
			
			$this->conv = $conv;
				
			$this->_log.= cap_syslog(array('start:','convert_input()'), LOG_INFO, 'CAP_Converter');
					
			$icap = $this->convert_input($cap);
			
			$this->_log.= cap_syslog(array('end:','convert_input()'), LOG_INFO, 'CAP_Converter');
			$this->_log.= cap_syslog('', LOG_INFO, 'CAP_Converter');
			// Output
			$this->tmpconv = $this->conv;
			unset($this->conv, $conv);
			include './convert/conv_'.$output.'.conf.php';
			
			$this->conv = $conv;
			$this->input_actions = $this->actions;
			unset($this->actions);
							
			$this->_log.= cap_syslog(array('start:','convert_output()'), LOG_INFO, 'CAP_Converter');
							
			$ocap = $this->convert_output($icap);
			
			$this->_log.= cap_syslog(array('end:','convert_output()'), LOG_INFO, 'CAP_Converter');
			$this->_log.= cap_syslog('', LOG_INFO, 'CAP_Converter');
			
			$this->_log.= cap_syslog(array('start:','Create File'), LOG_INFO, 'CAP_Converter');
			
			$this->_log.= cap_syslog(array('build:','Build the Cap to xml content'), LOG_INFO, 'CAP_Converter');
			$this->buildCap($ocap);
			$this->_log.= cap_syslog(array('create:','save Cap'), LOG_INFO, 'CAP_Converter');
			$path = $this->createFile($ocap);
			$this->_log.= cap_syslog(array('end:','Create File'), LOG_INFO, 'CAP_Converter');
			
			$this->_log.= cap_syslog(array('file:',$path), LOG_INFO, 'CAP_Converter');
			$this->_log.= cap_syslog('_______________________________________________________________________________________________________________', LOG_INFO, 'CAP_Converter');
			
			return $this->cap;	
		}
		
		function convert_input($cap)
		{
			foreach($this->conv->info as $key => $tagname)
			{
				if($key != "ValueName")
				{
					foreach($tagname as $key2 => $tag)
					{
						if($this->conv->info['ValueName'][$key2] == "-1")
						{
							$stag = $this->get_structur($tag);
							$scap = $this->get_cap_structur($tag, $cap);
							if($stag == "") 
							{
								$this->_log.= cap_syslog(array('translate:','in <alert>','<'.$tag.'>',$cap[$tag].' -> '.$this->translate_input($cap[$tag])), LOG_INFO, 'CAP_Converter');
								$cap[$tag] =  $this->translate_input($cap[$tag]);
							}
							elseif($stag == "info")
							{
								foreach($cap['info'] as $key => $tmp)
								{
									$this->_log.= cap_syslog(array('translate:','in <info>','<'.$tag.'>',$cap['info'][$key][$tag].' -> '.$this->translate_input($cap['info'][$key][$tag])), LOG_INFO, 'CAP_Converter');
									$cap['info'][$key][$tag] =  $this->translate_input($cap['info'][$key][$tag]);
								}
							}
							elseif($stag == "area")
							{
								foreach($cap['info'] as $key => $tmp)
								{
									foreach($cap['info'][$key]['area'] as $key2 => $tmp)
									{
										$this->_log.= cap_syslog(array('translate:','in <area>','<'.$tag.'>',$cap['info'][$key]['area'][$key2][$tag].' -> '.$this->translate_input($cap['info'][$key]['area'][$key2][$tag])), LOG_INFO, 'CAP_Converter');
										$cap['info'][$key]['area'][$key2][$tag] =  $this->translate_input($cap['info'][$key]['area'][$key2][$tag]);
									}
								}
							}
							
							unset($stag, $scap);
							$this->get_action($tag);
						}
						else
						{
							$tagnameValueval = $this->conv->info['ValueName'][$key2];
							
							$stag = $this->get_structur($tag);
							$scap = $this->get_cap_structur($tag, $cap);
							$this->_log.= cap_syslog(array('get structur:','tag:'.$tag,'stag:'.$stag), LOG_INFO, 'CAP_Converter');
							if($stag == "") 
							{
								foreach($cap[$tag] as $capkey => $capsearch) // 
								{
									if($tagnameValueval == $capsearch['valueName'])
									{						
										$this->_log.= cap_syslog(array('translate:','in <alert>','<'.$tag.'><'.$capsearch['valueName'].'>',$capsearch['value'].' -> '.$this->translate_input($capsearch['value'])), LOG_INFO, 'CAP_Converter');
										
										$cap[$tag][$capkey]['valueName'] = $this->get_action($capsearch['valueName'], $tag);
										
										$cap[$tag][$capkey]['value'] = $this->translate_input($capsearch['value']);
									}
								}		
							}
							elseif($stag == "info") 
							{
								foreach($cap['info'] as $key => $tmp)
								{
									foreach($cap['info'][$key][$tag] as $capkey => $capsearch) // 
									{
										if($tagnameValueval == $capsearch['valueName'])
										{							
											$this->_log.= cap_syslog(array('translate:','in <info>','<'.$tag.'><'.$capsearch['valueName'].'>',$capsearch['value'].' -> '.$this->translate_input($capsearch['value'])), LOG_INFO, 'CAP_Converter');
											
											$cap['info'][$key][$tag][$capkey]['valueName'] = $this->get_action($capsearch['valueName'], $tag);
											
											$cap['info'][$key][$tag][$capkey]['value'] = $this->translate_input($capsearch['value']);
										}
									}		
								}
							}
							elseif($stag == "area") 
							{
								foreach($cap['info'] as $key => $tmp)
								{
									foreach($cap['info'][$key]['area'] as $key2 => $tmp)
									{
										foreach($cap['info'][$key]['area'][$key2][$tag] as $capkey => $capsearch) // 
										{
											if($tagnameValueval == $capsearch['valueName'])
											{						
												$this->_log.= cap_syslog(array('translate:','in <info>','<'.$tag.'><'.$capsearch['valueName'].'>',$capsearch['value'].' -> '.$this->translate_input($capsearch['value'])), LOG_INFO, 'CAP_Converter');
												
												$cap['info'][$key]['area'][$key2][$tag][$capkey]['valueName'] = $this->get_action($capsearch['valueName'], $tag);
												
												$cap['info'][$key]['area'][$key2][$tag][$capkey]['value'] = $this->translate_input($capsearch['value']);
											}
										}		
									}
								}
							}
							unset($stag, $scap);
						}
					}
				}
			}

			return $cap;
		}
			
		function convert_output($cap)
		{
			// move
			$cap = $this->move_input($cap);
			
			$this->_log.= cap_syslog('', LOG_INFO, 'CAP_Converter');
			foreach($this->conv->move as $key_m => $val_arr)
			{
				end($val_arr);
				$key = key($val_arr);
				$val = $val_arr[$key];
				$action = $this->move_action[$val];		
				$cap = $this->move_output($cap, $key_m, $val, $action);
			}
			$this->_log.= cap_syslog('', LOG_INFO, 'CAP_Converter');
			
			// Copy paste event
			$copy_arr = $this->copy_input($cap);
			$cap = $this->insert_output($cap, $copy_arr);
			
			return $cap;
		}
		
		function move_input($cap)
		{
			foreach($this->input_actions['move'] as $movetag => $move)
			{
				if(!is_array($this->input_actions['move'][$movetag]))
				{
					// Whenn it is not ValueName
					
					$stag = $this->get_structur($movetag);
					if($stag == "") 
					{
						$this->_log.= cap_syslog(array('move input:','tag: <'.$movetag.'>','code: '.$this->input_actions['move'][$movetag],'value: '.$cap[$movetag].' -> '.$this->translate_output($cap[$movetag])), LOG_INFO, 'CAP_Converter');
						$this->move_action[$this->input_actions['move'][$movetag]][] = $this->translate_output($cap[$movetag]);
						unset($cap[$movetag]);
					}
					elseif($stag == "info")
					{
						foreach($cap['info'] as $key => $tmp)
						{							
							$this->_log.= cap_syslog(array('move input:','tag: <'.$movetag.'>','code: '.$this->input_actions['move'][$movetag],'value: '.$cap['info'][$key][$movetag].' -> '.$this->translate_output($cap['info'][$key][$movetag])), LOG_INFO, 'CAP_Converter');
							$this->move_action[$this->input_actions['move'][$movetag]][] = $this->translate_output($cap['info'][$key][$movetag]);
							unset($cap['info'][$key][$movetag]);
						}
					}
					elseif($stag == "area")
					{
						foreach($cap['info'] as $key => $tmp)
						{
							foreach($cap['info'][$key]['area'] as $key2 => $tmp)
							{
								$this->_log.= cap_syslog(array('move input:','tag: <'.$movetag.'>','code: '.$this->input_actions['move'][$movetag],'value: '.$cap['info'][$key]['area'][$key2][$movetag].' -> '.$this->translate_output($cap['info'][$key]['area'][$key2][$movetag])), LOG_INFO, 'CAP_Converter');
								$this->move_action[$this->input_actions['move'][$movetag]][] = $this->translate_output($cap['info'][$key]['area'][$key2][$movetag]);
								unset($cap['info'][$key]['area'][$key2][$movetag]);
							}
						}
					}
				}
				else
				{
					foreach($this->input_actions['move'][$movetag] as $movetagvalkey => $movetagval)
					{
						$stag = $this->get_structur($movetag);
						if($stag == "") 
						{
							foreach($cap[$movetag] as $capkey => $intag)
							{
								if($cap[$movetag][$capkey]['valueName'] == $movetagval)
								{
									$this->_log.= cap_syslog(array('move input:','tag: <'.$movetag.'>','code: '.$movetagval,'value: '.$cap[$movetag][$capkey]['value'].' -> '.$this->translate_output($cap[$movetag][$capkey]['value'])), LOG_INFO, 'CAP_Converter');
									$this->move_action[$movetagval][] = $this->translate_output($cap[$movetag][$capkey]['value']);
									unset($cap[$movetag][$capkey]);
								}
							}
						}
						elseif($stag == "info")
						{
							foreach($cap['info'] as $key => $tmp)
							{
								foreach($cap['info'][$key][$movetag] as $capkey => $intag)
								{
									if($cap['info'][$key][$movetag][$capkey]['valueName'] == $movetagval)
									{
										$this->_log.= cap_syslog(array('move input:','tag: <'.$movetag.'>','code: '.$movetagval,'value: '.$cap['info'][$key][$movetag][$capkey]['value'].' -> '.$this->translate_output($cap['info'][$key][$movetag][$capkey]['value'])), LOG_INFO, 'CAP_Converter');
										$this->move_action[$movetagval][] = $this->translate_output($cap['info'][$key][$movetag][$capkey]['value']);
										unset($cap['info'][$key][$movetag][$capkey]);
									}
								}
							}
						}
						elseif($stag == "area")
						{
							foreach($cap['info'] as $key => $tmp)
							{
								foreach($cap['info'][$key]['area'] as $key2 => $tmp)
								{
									foreach($cap['info'][$key]['area'][$key2][$movetag] as $capkey => $intag)
									{
										if($cap['info'][$key]['area'][$key2][$movetag][$capkey]['valueName'] == $movetagval)
										{
											$this->_log.= cap_syslog(array('move input:','tag: <'.$movetag.'>','code: '.$movetagval,'value: '.$cap['info'][$key]['area'][$key2][$movetag][$capkey]['value'].' -> '.$this->translate_output($cap['info'][$key]['area'][$key2][$movetag][$capkey]['value'])), LOG_INFO, 'CAP_Converter');
											$this->move_action[$movetagval][] = $this->translate_output($cap['info'][$key]['area'][$key2][$movetag][$capkey]['value']);
											unset($cap['info'][$key]['area'][$key2][$movetag][$capkey]);
										}
									}
								}
							}
						}
					}
				}
			}
						
			return $cap;
		}
		
		function move_output($cap, $tag, $action_key, $action)
		{
			$stag = $this->get_tag($tag);
			if($stag == $tag)
			{ // wenn z.b: der tag web übersetzt wird
				if($this->get_structur($stag) == "")
				{
					$this->_log.= cap_syslog(array('move output:','tag: <'.$stag.'>','code: '.$action_key,'value: '.$action), LOG_INFO, 'CAP_Converter');
					$cap[$stag] = $action;
				}
				elseif($this->get_structur($stag) == "info")
				{
					foreach($cap['info'] as $key => $tmp)
					{
						$this->_log.= cap_syslog(array('move output:','tag: <'.$stag.'>','code: '.$action_key,'value: '.$action[$key]), LOG_INFO, 'CAP_Converter');
						$cap['info'][$key][$stag] = $action[$key];
					}
				}
				elseif($this->get_structur($stag) == "area")
				{
					foreach($cap['info'] as $key => $tmp)
					{
						foreach($cap['info'][$key]['area'] as $key2 => $tmp)
						{
							$this->_log.= cap_syslog(array('move output:','tag: <'.$stag.'>','code: '.$action_key,'value: '.$action[$key2]), LOG_INFO, 'CAP_Converter');
							$cap['info'][$key]['area'][$key2][$stag] = $action[$key2];
						}
					}
				}
			}
			else
			{ // wenn z.b: der tag parameter übersetzt wird
				if($this->get_structur($stag) == "")
				{
					$deeptag['valueName'] = $tag;
					$deeptag['value'] = $action;
					$this->_log.= cap_syslog(array('move output:','tag: <'.$stag.'>','code: '.$action_key,'value: '.$action), LOG_INFO, 'CAP_Converter');
					$cap[$stag][]  = $deeptag;
				}
				elseif($this->get_structur($stag) == "info")
				{
					foreach($cap['info'] as $key => $tmp)
					{
						$deeptag['valueName'] = $tag;
						$deeptag['value'] = $action[$key];
						$this->_log.= cap_syslog(array('move output:','tag: <'.$stag.'>','code: '.$action_key,'value: '.$action[$key]), LOG_INFO, 'CAP_Converter');
						$cap['info'][$key][$stag][]  = $deeptag;
					}
				}
				elseif($this->get_structur($stag) == "area")
				{
					foreach($cap['info'] as $key => $tmp)
					{
						foreach($cap['info'][$key]['area'] as $key2 => $tmp)
						{
							$deeptag['valueName'] = $tag;
							$deeptag['value'] = $action[$key2];
							$this->_log.= cap_syslog(array('move output:','tag: <'.$stag.'>','code: '.$action_key,'value: '.$action[$key2]), LOG_INFO, 'CAP_Converter');
							$cap['info'][$key]['area'][$key2][$stag][]  = $deeptag;
						}
					}
				}
			}
			
			return $cap;
		}
		
		/*
		ToDo: Note: fileicht sollte man doch die funktion copy_paste nennen und alles in einen machen?
		*/
		function copy_input($cap)
		{
			// Kopiert eine copy action und lagert sie aus
			foreach($this->input_actions['copy'] as $copytag => $copy)
			{
				$stag = $this->get_structur($copytag);
				if($stag == "")
				{
					$this->_log.= cap_syslog(array('copy:','tag: <'.$copytag.'>','value: '.$cap[$copytag]), LOG_INFO, 'CAP_Converter');
					$copy_arr[$copy] = $cap[$copytag];
				}
				elseif($stag == "info")
				{
					foreach($cap['info'] as $key => $tmp)
					{
						$this->_log.= cap_syslog(array('copy:','tag: <'.$copytag.'>','value: '.$cap['info'][$key][$copytag]), LOG_INFO, 'CAP_Converter');
						$copy_arr[$copy] = $cap['info'][$key][$copytag];
					}
				}
				elseif($stag == "area")
				{
					foreach($cap['info'] as $key => $tmp)
					{
						foreach($cap['info'][$key]['area'] as $key2 => $tmp)
						{
							$this->_log.= cap_syslog(array('copy:','tag: <'.$copytag.'>','value: '.$cap['info'][$key]['area'][$key2][$copytag]), LOG_INFO, 'CAP_Converter');
							$copy_arr[$copy] = $cap['info'][$key]['area'][$key2][$copytag];
						}
					}
				}
			}
			
			return $copy_arr;
		}
		
		function insert_output($cap, $copy_arr)
		{
			// Fügt eine aus der copy ablagerung stammenden eintrag ein
			foreach($this->conv->insert as $inserttag => $insert)
			{
				foreach($this->conv->insert[$inserttag] as $key => $insert_code)
				{
					$stag = $this->get_structur($inserttag);
					if($stag == "")
					{
						$this->_log.= cap_syslog(array('insert:','tag: <'.$inserttag.'>','value: '.$copy_arr[$insert_code]), LOG_INFO, 'CAP_Converter');
						$cap[$inserttag]  = $this->translate_output($copy_arr[$insert_code]);
					}
					elseif($stag == "info")
					{
						foreach($cap['info'] as $key => $tmp)
						{
							$this->_log.= cap_syslog(array('insert:','tag: <'.$inserttag.'>','value: '.$copy_arr[$insert_code]), LOG_INFO, 'CAP_Converter');
							$cap['info'][$key][$inserttag] = $this->translate_output($copy_arr[$insert_code]);
						}
					}
					elseif($stag == "area")
					{
						foreach($cap['info'] as $key => $tmp)
						{
							foreach($cap['info'][$key]['area'] as $key2 => $tmp)
							{
								$this->_log.= cap_syslog(array('insert:','tag: <'.$inserttag.'>','value: '.$copy_arr[$insert_code]), LOG_INFO, 'CAP_Converter');
								$cap['info'][$key]['area'][$key2][$inserttag] = $this->translate_output($copy_arr[$insert_code]);
							}
						}
					}
				}
			}
			
			return $cap;
		}
		
		function get_tag($val)
		{
			foreach($this->conv->info['tag'] as $key => $tag)
			{
				if($tag == $val)
				{
					//cap_syslog(array('get_tag:','tag: <'.$tag.'>'), LOG_INFO, 'CAP_Converter');
					return $tag;
				}
				elseif($this->conv->info['ValueName'][$key]  == $val)
				{
					//cap_syslog(array('get_tag:','tag: <'.$tag.'>'), LOG_INFO, 'CAP_Converter');
					return $tag;
				}
			}
			 
		}
		
		function get_action($val, $tag = "")
		{
			if(is_array($this->conv->move[$val]) || ! empty($this->conv->move[$val]))
			{
				end($this->conv->move[$val]); 
				$key = key($this->conv->move[$val]);
				if($tag)
				{
					$this->actions['move'][$tag][] = $this->conv->move[$val][$key];
				}
				else
				{
					$this->actions['move'][$val] = $this->conv->move[$val][$key];
				}
				//cap_syslog(array('get_action:','move','<'.$val.'>'), LOG_INFO, 'CAP_Converter');
				return $this->conv->move[$val][$key];
			}
			elseif(is_array($this->conv->copy[$val]))
			{
				end($this->conv->copy[$val]); 
				$key = key($this->conv->copy[$val]);
				if($tag)
				{
					$this->actions['copy'][$tag][] = $this->conv->copy[$val][$key];
				}
				else
				{
					$this->actions['copy'][$val] = $this->conv->copy[$val][$key];
				}
				//cap_syslog(array('get_action:','copy','<'.$val.'>'), LOG_INFO, 'CAP_Converter');
				return $this->conv->copy[$val][$key];
			}
			elseif(is_array($this->conv->insert[$val]))
			{
				end($this->conv->insert[$val]); 
				$key = key($this->conv->insert[$val]);
				if($tag)
				{
					$this->actions['insert'][$tag][] = $this->conv->insert[$val][$key];
				}
				else
				{
					$this->actions['insert'][$val] = $this->conv->insert[$val][$key];
				}
				//cap_syslog(array('get_action:','insert','<'.$val.'>'), LOG_INFO, 'CAP_Converter');
				return $this->conv->insert[$val][$key];
			}
		}
	
		function translate_input($val)
		{
			if(is_array($val)) return $val;
			end($this->conv->translate[$val]); 
			$key = key($this->conv->translate[$val]);
			if($this->conv->translate[$val][$key])
			{
				return $this->conv->translate[$val][$key];
			}
			else
			{
				$this->_log.= cap_syslog(array('warning: ','Can\'t find translation',$val), LOG_WARNING, 'CAP_Converter');			
				return $val;
			}
		}
	
		function translate_output($val)
		{
			if(is_array($val)) return $val;
			foreach($this->conv->translate as $transkey => $trans)
			{
				foreach($trans as $transvaluekey => $transvalue)
				{
					if($transvalue == $val)
					{
						return $transkey;
					}
				}
			}
			$this->_log.= cap_syslog(array('warning: ','Can\'t find translation',$val), LOG_WARNING, 'CAP_Converter');		
			return $val; // wenn keine übersetzung
		}
		
		function get_structur($val)
		{
			foreach($this->conv->structure['tag'] as $testerkey => $tester)
			{
				if($val == $tester) return "";
			}
			foreach($this->conv->structure['tag']['info'] as $testerkey => $tester)
			{
				if($val == $tester) return "info";
			}
			foreach($this->conv->structure['tag']['info']['area'] as $testerkey => $tester)
			{
				if($val == $tester) return "area";
			}
			foreach($this->tmpconv->structure['tag'] as $testerkey => $tester)
			{
				if($val == $tester) return "";
			}
			foreach($this->tmpconv->structure['tag']['info'] as $testerkey => $tester)
			{
				if($val == $tester) return "info";
			}
			foreach($this->tmpconv->structure['tag']['info']['area'] as $testerkey => $tester)
			{
				if($val == $tester) return "area";
			}
			
		}
		
		function get_cap_structur($val, $cap)
		{
			foreach($this->conv->structure['tag'] as $testerkey => $tester)
			{
				if($val == $tester) return $cap;
			}
			foreach($this->conv->structure['tag']['info'] as $testerkey => $tester)
			{
				if($val == $tester) return $cap['info'];
			}
			foreach($this->conv->structure['tag']['info']['area'] as $testerkey => $tester)
			{
				if($val == $tester) return $cap['info'][0]['area'];
			}
			foreach($this->tmpconv->structure['tag'] as $testerkey => $tester)
			{
				if($val == $tester) return $cap;
			}
			foreach($this->tmpconv->structure['tag']['info'] as $testerkey => $tester)
			{
				if($val == $tester) return $cap['info'];
			}
			foreach($this->tmpconv->structure['tag']['info']['area'] as $testerkey => $tester)
			{
				if($val == $tester) return $cap['info'][0]['area'];
			}
		}

	 /**
     * Put CAP 1.2 content in $this->cap
     *
     * @return	None
     */
		function buildCap($cap)
		{
			$xml = new xml(/*ver*/'1.0',/*encoding*/'utf-8',array('standalone'=>'yes'));
			$xml->tag_open('alert',array('xmlns' => 'urn:oasis:names:tc:emergency:cap:1.2'));
			
					
				$xml->tag_simple('identifier', $cap['identifier']);
				$xml->tag_simple('sender', $cap['sender']);
				
				
				$xml->tag_simple('sent', $cap['sent']);				
				
				$xml->tag_simple('status', $cap['status']);
				$xml->tag_simple('msgType', $cap['msgType']);
				$xml->tag_simple('references', $cap['references']);
				$xml->tag_simple('scope', $cap['scope']);
				
				$xml->tag_simple('source', $cap['source']);
				$xml->tag_simple('restriction', $cap['restriction']);
				$xml->tag_simple('addresses', $cap['addresses']);
				$xml->tag_simple('code', $cap['code']);
				$xml->tag_simple('note', $cap['note']);
				$xml->tag_simple('incidents', $cap['incidents']);
				
				foreach($cap['info'] as $info)
				{
					$xml->tag_open('info');
						
						
						$xml->tag_simple('language', $info['language']);							
						$xml->tag_simple('category', $info['category']);						
						$xml->tag_simple('event', $info['event']);
						
						$xml->tag_simple('responseType', $info['responseType']);
						$xml->tag_simple('urgency', $info['urgency']);
						$xml->tag_simple('severity', $info['severity']);
						$xml->tag_simple('certainty', $info['certainty']);
						$xml->tag_simple('audience', $info['audience']);
						
						if(! empty($info['eventCode'][0]['valueName']))
						foreach($info['eventCode'] as $key => $eventCode)
						{
							$xml->tag_open('eventCode');							
								$xml->tag_simple('valueName', ($eventCode['valueName']));
								$xml->tag_simple('value', ($eventCode['value']));							
							$xml->tag_close('eventCode');
						}
						
						$xml->tag_simple('effective', $info['effective']);
						$xml->tag_simple('onset', $info['onset']);
						$xml->tag_simple('expires', $info['expires']);
						
						$xml->tag_simple('senderName', $info['senderName']);
						
						$xml->tag_simple('headline', $info['headline']);
						$xml->tag_simple('description', $info['description']);
						$xml->tag_simple('instruction', $info['instruction']);
						
						$xml->tag_simple('web', $info['web']);
						$xml->tag_simple('contact', $info['contact']);
						
						if(! empty($info['parameter'][0]['valueName']))
						foreach($info['parameter'] as $key => $parameter)
						{
							$xml->tag_open('parameter');						
								$xml->tag_simple('valueName', ($parameter['valueName']));
								$xml->tag_simple('value', ($parameter['value']));							
							$xml->tag_close('parameter');						
						} // foreach parameter
						
						// look if area zone is used
						foreach($info['area'] as $key => $area)
						{
							$xml->tag_open('area');
						
								$xml->tag_simple('areaDesc', $area['areaDesc']);
								$xml->tag_simple('polygon', $area['polygon']);
								$xml->tag_simple('circle', $area['circle']);
							
								if(! empty($area['geocode'][0]['valueName']))
								foreach($area['geocode'] as $key => $geocode)
								{
									$xml->tag_open('geocode');						
										$xml->tag_simple('valueName', ($geocode['valueName']));
										$xml->tag_simple('value', ($geocode['value']));							
									$xml->tag_close('geocode');
								} // foreach geocode
							
							$xml->tag_close('area');
						}
												
					$xml->tag_close('info');	
				}// Foreach info lang
					
			$xml->tag_close('alert');

			$this->cap = $xml->output();
		}		
			
		/**
     * Create File
     *
     * @return	path of the New CAP 1.2
     */
		function createFile($cap)
		{			
			$capfile = fopen($this->destination.'/'.$cap['identifier'].'.conv.cap', "w") or die("Unable to open file! ".$this->destination.'/'.$cap['identifier'].'.conv.cap');
			fwrite($capfile, $this->cap);
			fclose($capfile);
			
			// convert in UTF-8
			$data = file_get_contents($this->destination.'/'.$cap['identifier'].'.conv.cap');
			
			if (preg_match('!!u', $data))
			{
			   // this is utf-8
			}
			else 
			{
			   $data = mb_convert_encoding($data, 'UTF-8', 'OLD-ENCODING');
			}

			file_put_contents($this->destination.'/'.$cap['identifier'].'.conv.cap', $data);
			
			return $this->destination.'/'.$cap['identifier'].'.conv.cap';
		}
		
		/*********************************************************************************************************************************
		 *********************************************************************************************************************************
     *  						  						  						  				NEW Converter ENDE
     *********************************************************************************************************************************
     *********************************************************************************************************************************/
		
		function old_convert($cap, $std_c, $area_c, $input, $output, $cap_output_path)
		{
			require_once 'lib/cap.create.class.php';
			
			if(!file_exists('./convert/std_'.$std_c.'.conf.php'))
			{
				return 'Could not found: ./convert/std_'.$std_c.'.conf.php';
			}
			if(!file_exists('./convert/area_'.$area_c.'.conf.php'))
			{
				return 'Could not found: ./convert/std_'.$area_c.'.conf.php';
			}			
			if(!file_exists('./convert/conv_'.$input.'.conf.php'))
			{
				return 'Could not found: ./convert/std_'.$input.'.conf.php';
			}
			if(!file_exists('./convert/conv_'.$output.'.conf.php'))
			{
				return 'Could not found: ./convert/std_'.$output.'.conf.php';
			}
			
			/**
			 * Write Cap in $this
			 */
			
			$this->output 			= $cap['output'];
			$this->identifier 	= $cap['identifier'];
			$this->sender				= $cap['sender'];
			$this->sent					= $cap['sent'];
			$this->status				= $cap['status'];
			$this->msgType			= $cap['msgType'];
			$this->references		= $cap['references'];
			$this->scope				= $cap['scope'];
			
			$this->source					= $cap['source'];
			$this->restriction		= $cap['restriction'];
			$this->addresses			= $cap['addresses'];
			$this->code						= $cap['code'];
			$this->note						= $cap['note'];
			$this->incidents			= $cap['incidents'];
			
			$this->language			= $cap['info'][0]['language'];
			$this->category			= $cap['info'][0]['category'];
			$this->event				= $cap['info'][0]['event'];
			$this->responseType	= $cap['info'][0]['responseType'][0];
			$this->urgency			= $cap['info'][0]['urgency'];
			$this->severity			= $cap['info'][0]['severity'];
			$this->certainty		= $cap['info'][0]['certainty'];
			$this->audience			= $cap['info'][0]['audience'];
			$this->eventCode		= $cap['info'][0]['eventCode'];
			$this->effective		= $cap['info'][0]['effective'];
			$this->onset				= $cap['info'][0]['onset'];
			$this->expires			= $cap['info'][0]['expires'];
			$this->senderName		= $cap['info'][0]['senderName'];
			$this->headline			= $cap['info'][0]['headline'];
			$this->description	= $cap['info'][0]['description'];
			$this->instruction	= $cap['info'][0]['instruction'];
			$this->web					= $cap['info'][0]['web'];
			$this->contact			= $cap['info'][0]['contact'];
			$this->parameter		= $cap['info'][0]['parameter'];

			$this->areaDesc			= $cap['info'][0]['area'][0]['areaDesc'];
			$this->polygon			= $cap['info'][0]['area'][0]['polygon'];
			$this->circle				= $cap['info'][0]['area'][0]['circle'];
			$this->geocode			= $cap['info'][0]['area'][0]['geocode'];
			
			/**
			 * Include Cap converter files
			 */
			 
			// Get geocodes
			include './convert/area_'.$area_c.'.conf.php';
			$geocode = $standard;
			unset($standard);
			
			// Get input style
			include './convert/conv_'.$input.'.conf.php';
			$input = $conv;
			unset($conv);
			
			// Get standard if not included
			include './convert/std_'.$std_c.'.conf.php';
			$standard = $conv;
			unset($conv);
			
			// Get output style
			include './convert/conv_'.$output.'.conf.php';
			$output = $conv;
			unset($conv);

			/**
			 * Input Cap -> Standard
			 */ 
			 
			if(!empty($input->conv->hazard->type->tag->name))
			{
				foreach($cap['info'][0][$input->conv->hazard->type->tag->name] as $val_arr)
				{
					if( $val_arr['valueName'] ==  $input->conv->hazard->type->tag->val )
					{
						$ConvCap['type'] =  $input->{$input->conv->hazard->type->tag->val}[$val_arr['value']];
					}
				}						
			}
			
			if(!empty($input->conv->hazard->level->tag->name))
			{
				foreach($cap['info'][0][$input->conv->hazard->level->tag->name] as $val_arr)
				{
					if( $val_arr['valueName'] ==  $input->conv->hazard->level->tag->val )
					{
						$ConvCap['level'] =  $input->{$input->conv->hazard->level->tag->val}[$val_arr['value']];
					}							
				}				
			}
			
			if(!empty($input->conv->geocode->tag->name))
			{
				if(is_array($input->conv->geocode->tag->val))
				{
					foreach($input->conv->geocode->tag->val as $geocodeval)
					{
						if($geocodeval == "NUTS" || $geocodeval == "NUTS1" || $geocodeval == "NUTS2" || $geocodeval == "NUTS3")
						{
							foreach($cap['info'][0]['area'] as $key => $tmp)
							{
								foreach($cap['info'][0]['area'][$key][$input->conv->geocode->tag->name] as $val_arr)
								{
									if( $val_arr['valueName'] ==  $geocodeval )
									{
										$ConvCap['geocode'][$val_arr['value']] =  $geocode->geocode->nuts[$val_arr['value']]; // geocode->nuts['NL32']
									}
								}
							}
						}
						else
						{
							foreach($cap['info'][0]['area'] as $key => $tmp)
							{
								foreach($cap['info'][0]['area'][$key][$input->conv->geocode->tag->name] as $val_arr)
								{
									if( $val_arr['valueName'] ==  $geocodeval )
									{
										$ConvCap['geocode'][$input->{$geocodeval}[$val_arr['value']]] =  $input->{$geocodeval}[$val_arr['value']];
									}
								}
							}
						}
					}
				}
				else
				{
					foreach($cap['info'][0]['area'] as $key => $tmp)
					{
						foreach($cap['info'][0]['area'][$key][$input->conv->geocode->tag->name] as $val_arr)
						{
							if( $val_arr['valueName'] ==  $input->conv->geocode->tag->val )
							{
								$ConvCap['geocode'][$input->{$input->conv->geocode->tag->val}[$val_arr['value']]] =  $input->{$input->conv->geocode->tag->val}[$val_arr['value']];
							}				
						}
					}
				}
				$ConvCap['geocode'] = array_unique($ConvCap['geocode']);
			}
			
			/**
			 * Standard -> Output Cap 
			 */ 
	 
			if(!empty($output->conv->hazard->type->tag->name))
			{
				$output_to_flip = $output->{$output->conv->hazard->type->tag->val};
				$output_val = array_flip($output_to_flip);
				$ToConvCap[$output->conv->hazard->type->tag->name][$output->conv->hazard->type->tag->val] =  $output_val[$ConvCap['type']];										
			}
			
			if(!empty($output->conv->hazard->level->tag->name))
			{
				$output_to_flip = $output->{$output->conv->hazard->level->tag->val};
				$output_val = array_flip($output_to_flip);
				$ToConvCap[$output->conv->hazard->level->tag->name][$output->conv->hazard->level->tag->val] =  $output_val[$ConvCap['level']];			
			}
			
			
			if(!empty($output->conv->geocode->tag->name))
			{
				if(is_array($output->conv->geocode->tag->val))
				{
					foreach($output->conv->geocode->tag->val as $geocodeval)
					{
						if($geocodeval == "NUTS" || $geocodeval == "NUTS1" || $geocodeval == "NUTS2" || $geocodeval == "NUTS3")
						{
							foreach($ConvCap['geocode'] as $key => $geo_code_val)
							{
								if(strlen($key) == 3) // NUTS NUTS1
								{
									$ToConvCap[$output->conv->geocode->tag->name]['NUTS1'] = $key;	
								}
								elseif(strlen($key) == 4)
								{
									$ToConvCap[$output->conv->geocode->tag->name]['NUTS2'] = $key;	
								}
								elseif(strlen($key) == 5)
								{
									$ToConvCap[$output->conv->geocode->tag->name]['NUTS3'] = $key;	
								}
							}													
						}
					}
				}
				else
				{
					$geocodeval = $output->conv->geocode->tag->val;
					if($geocodeval == "NUTS" || $geocodeval == "NUTS1" || $geocodeval == "NUTS2" || $geocodeval == "NUTS3")
					{
						foreach($ConvCap['geocode'] as $key => $geo_code_val)
						{									
							if(strlen($key) == 3) // NUTS NUTS1
							{
								$ToConvCap[$output->conv->geocode->tag->name]['NUTS1'] = $key;	
							}
							elseif(strlen($key) == 4)
							{
								$ToConvCap[$output->conv->geocode->tag->name]['NUTS2'] = $key;	
							}
							elseif(strlen($key) == 5)
							{
								$ToConvCap[$output->conv->geocode->tag->name]['NUTS3'] = $key;	
							}
						}							
					}
					else
					{
						$output_to_flip = $output->{$output->conv->geocode->tag->val};
						$output_val = array_flip($output_to_flip);
						foreach($ConvCap['geocode'] as $key => $geo_code_val)
						{
							$ToConvCap[$output->conv->geocode->tag->name][$output->conv->geocode->tag->val] =  $output_val[$key];		
						}
					}
				}
			}
			
			foreach($output->std as $tagName => $val)
			{
				$this->{$tagName} =	$val;
			}
			
			unset($this->eventCode);
			unset($this->parameter);
			unset($this->geocode);
			unset($this->area);

			$tmp = $this->language;
			unset($this->language);
			$this->language[] = $tmp;

			foreach($ToConvCap as $key => $arr)
			{
				$i=0;
				foreach($ToConvCap[$key] as $key2 => $arr2)
				{
					if($key == "eventCode" || $key == "parameter")
					{
						$this->{$key}["valueName"][] = $key2;
						$this->{$key}["value"][]		 = $arr2;
					}
					elseif($key == "geocode")
					{
						$this->{$key}["valueName"][] = $key2;
						$this->{$key}["value"][]		 = $arr2;
					}
					else
					{
						$this->{$key}[$key2] = $ToConvCap[$key][$key2];
					}
				}
			}
			
			foreach($output->using as $tag => $bool)
			{
				if($bool == 0) unset($this->{$tag});	
			}
			
			/**
			 * Create Convert Cap File
			 *
			 */
			$convcap = new CAP_Class($this, true);					
			$convcap->buildCap();
			$convcap->destination = $cap_output_path;
			$path = $convcap->createFile();
			
			return $convcap->cap;	
		}
			
		/*
		 * Function to Debug cap.create.class.php
		 *
		 * @return array 	$this 	All content of the Class
		 */	
		function Debug()
		{
			print '<pre>';
				print_r($this);
			print '</pre>';			
			exit;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	/*
	
				// First Level -----------------------------------------------------------------------------------------------------------------------------------------------
			foreach($cap as $tag1 => $innerCap)
			{
				if(!is_array($innerCap))
				{
					if($this->translate_output($innerCap))
					{
						$trans[$tag1] = $this->translate_output($innerCap);
					}
				}
				else // Second Level ---------------------------------------------------------------------------------------------------------------------------------------
				{					
					foreach($innerCap as $tag2 => $infoCap)
					{
						if(!is_array($infoCap))
						{
							if($this->translate_output($infoCap))
							{
								$trans[$tag1][$tag2] =$this->translate_output($infoCap);
							}
						}
						else // Eventcode parameter and area -------------------------------------------------------------------------------------------------------------------
						{							
							foreach($infoCap as $tag3 => $deepCap)
							{
								if(!is_array($deepCap))
								{
									if($this->translate_output($deepCap))
									{
										$trans[$tag1][$tag2][$tag3] =$this->translate_output($deepCap);
									}
								}
								else // Deepest point in the Cap -------------------------------------------------------------------------------------------------------------------
								{									
									foreach($deepCap as $tag4 => $deepestCap) 
									{
										if(!is_array($deepestCap))
										{
											if($this->translate_output($deepestCap))
											{
												$trans[$tag1][$tag2][$tag3][$tag4] = $this->translate_output($deepestCap);
											}
										}
										else // Deepest point in the Cap ---------------------------------------------------------------------------------------------------------------
										{									
											foreach($deepestCap as $tag5 => $arrayCap) // like eventcode parameter and area
											{
												if(!is_array($arrayCap))
												{
													if($this->translate_output($arrayCap))
													{
														if(key($move_action) == $arrayCap)
														{
															$convert[] = $arrayCap;
														}
														$convert[] = $this->translate_output($arrayCap);
														$trans[$tag1][$tag2][$tag3][$tag4][$tag5] = $this->translate_output($arrayCap);
													}
												}
												else // Deepest point in the Cap -----------------------------------------------------------------------------------------------------------
												{									
													foreach($arrayCap as $tag6 => $geocode) 
													{
														if(!is_array($geocode))
														{
															if($this->translate_output($geocode))
															{
																$trans[$tag1][$tag2][$tag3][$tag4][$tag5][$tag6] = $this->translate_output($geocode);
															}
														}
														else // Deepest point in the Cap -------------------------------------------------------------------------------------------------------
														{									
															foreach($geocode as $tag7 => $geocodevalue) 
															{
																if($this->translate_output($geocodevalue))
																{
																	if(key($move_action) == $arrayCap)
																	{
																		$convert[] = $arrayCap;
																	}
																	$convert[] = $this->translate_output($geocodevalue);
																	$trans[$tag1][$tag2][$tag3][$tag4][$tag5][$tag6][$tag7] = $this->translate_output($geocodevalue);
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
	
	*/	
	
?>