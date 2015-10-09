<?php
/*
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
 *	\file      	cap.create.class.php
 *  \ingroup   	build
 *	\brief      File of class with CAP 1.2 builder
 *	\standards  from http://docs.oasis-open.org/emergency/cap/v1.2/CAP-v1.2-os.html
 *
 */
	
	require_once 'cap.write.class.php'; // for the XML / CAP view

	class Convert_CAP_Class{
		var $output = "CAP"; // CAP / XML
		var $cap = "";
		var $destination = "source/cap";
		var $debug = "";

		
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
			var $expires 		= array();	// The expiry time of the information of the alert message 												/ Form: <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min> Offset to UTC -> 2015-01-08T15:00:13+01:00
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
		
		/**
     * Converts Caps to standard and then to output
     *
     * @param 	Array			$cap 							the cap content    
   	 * @param 	string    $std_c						path of the Standrad  convert file
     * @param 	string    $area_c 					path of the Area convert file
     * @param 	string    $input						path of the input  convert file
     * @param 	string    $output						path of the output convert file
     * @param 	string    $cap_output_path 	path to the ouput from the cap     
     * @return	array 											convertet cap or error
     */
		function convert($cap, $std_c, $area_c, $input, $output, $cap_output_path)
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
	
?>