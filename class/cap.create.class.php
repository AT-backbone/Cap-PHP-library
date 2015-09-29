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

	class CAP_Class{
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
			var $expieres 		= array();	// The expiry time of the information of the alert message 												/ Form: <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min> Offset to UTC -> 2015-01-08T15:00:13+01:00
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
				$this->expieres			= $post['expieres'];
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
     * Put CAP 1.2 content in $this->cap
     *
     * @return	None
     */
		function buildCap()
		{
			$xml = new xml(/*ver*/'1.0',/*encoding*/'utf-8',array('standalone'=>'yes'));
			$xml->tag_open('alert',array('xmlns' => 'urn:oasis:names:tc:emergency:cap:1.2'));
			
					
				$xml->tag_simple('identifier', $this->identifier);
				$xml->tag_simple('sender', $this->sender);
				$xml->tag_simple('sent', date("Y-m-d\TH:i:s" , strtotime($this->sent['date']." ".$this->sent['time'] ))."+".date("H:i",strtotime($this->sent['UTC'])));
				$xml->tag_simple('status', $this->status);
				$xml->tag_simple('msgType', $this->msgType);
				$xml->tag_simple('references', $this->references);
				$xml->tag_simple('scope', $this->scope);
				
				if(count($this->language) > 0)
				foreach($this->language as $lang)
				{
					if(!empty($lang))
					{
						$xml->tag_open('info');
						
							$xml->tag_simple('language', $lang);
							$xml->tag_simple('category', $this->category);
							$xml->tag_simple('event', $this->event[$lang]);
							$xml->tag_simple('responseType', $this->responseType);
							$xml->tag_simple('urgency', $this->urgency);
							$xml->tag_simple('severity', $this->severity);
							$xml->tag_simple('certainty', $this->certainty);
							$xml->tag_simple('audience', $this->audience);
							
							if(! empty($this->eventCode['valueName'][0]))
							foreach($this->eventCode['valueName'] as $key => $eventCode)
							{
								$xml->tag_open('eventCode');							
									$xml->tag_simple('valueName', $this->eventCode['valueName'][$key]);
									$xml->tag_simple('value', $this->eventCode['value'][$key]);							
								$xml->tag_close('eventCode');
							}
							
							// 2015-01-15T00:04:01+01:00
							//$this->debug = date("Y-m-d\TH:i:s" , strtotime($this->effective['date']." ".$this->effective['time'] ))."+".date("H:i",strtotime($this->effective['UTC']));
							$xml->tag_simple('effective', date("Y-m-d\TH:i:s" , strtotime($this->effective['date']." ".$this->effective['time'] ))."+".date("H:i",strtotime($this->effective['UTC'])));
							$xml->tag_simple('onset', date("Y-m-d\TH:i:s" , strtotime($this->onset['date']." ".$this->onset['time'] ))."+".date("H:i",strtotime($this->onset['UTC'])));
							$xml->tag_simple('expires', date("Y-m-d\TH:i:s" , strtotime($this->expieres['date']." ".$this->expieres['time'] ))."+".date("H:i",strtotime($this->expieres['UTC'])));
							
							
							$xml->tag_simple('senderName', $this->senderName);
							$xml->tag_simple('headline', $this->headline[$lang]);
							$xml->tag_simple('description', $this->description[$lang]);
							$xml->tag_simple('instruction', $this->instruction[$lang]);
							$xml->tag_simple('web', $this->web);
							$xml->tag_simple('contact', $this->contact);
							
							if(! empty($this->parameter['valueName'][0]))
							foreach($this->parameter['valueName'] as $key => $parameter)
							{
								$xml->tag_open('parameter');						
									$xml->tag_simple('valueName', $this->parameter['valueName'][$key]);
									$xml->tag_simple('value', $this->parameter['value'][$key]);							
								$xml->tag_close('parameter');						
							} // foreach parameter
							
							// look if area zone is used
							if(! empty($this->areaDesc) || ! empty($this->polygon)  || ! empty($this->circle) || ! empty($this->geocode['valueName'][0]))
							{
								$xml->tag_open('area');
							
									$xml->tag_simple('areaDesc', $this->areaDesc);
									$xml->tag_simple('polygon', $this->polygon);
									$xml->tag_simple('circle', $this->circle);
								
									if(! empty($this->geocode['valueName'][0]))
									foreach($this->geocode['valueName'] as $key => $geocode)
									{
										$xml->tag_open('geocode');						
											$xml->tag_simple('valueName', $this->geocode['valueName'][$key]);
											$xml->tag_simple('value', $this->geocode['value'][$key]);							
										$xml->tag_close('geocode');
									} // foreach geocode
								
								$xml->tag_close('area');
							}
												
						$xml->tag_close('info');	
					} // lang is not empty
				}// Foreach info lang
					
			$xml->tag_close('alert');
			
			$this->cap = $xml->output();
		}		
			
		/**
     * Create File
     *
     * @return	path of the New CAP 1.2
     */
		function createFile()
		{
			$capfile = fopen($this->destination.'/'.$this->identifier.'.cap', "w") or die("Unable to open file!");
			fwrite($capfile, $this->cap);
			fclose($capfile);
			
			// convert in UTF-8
			$data = file_get_contents($this->destination.'/'.$this->identifier.'.cap');
			$data = mb_convert_encoding($data, 'UTF-8', 'OLD-ENCODING');
			file_put_contents($this->destination.'/'.$this->identifier.'.cap', $data);
			
			return $this->destination.'/'.$this->identifier.'.cap';
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