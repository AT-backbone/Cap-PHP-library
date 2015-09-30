<?php
/*
 *  Copyright (c) 2015  Guido Schratzer   <schratzerg@backbone.co.at>
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
 *	\file      	/class/cap.form.class.php
 *  \ingroup   	core
 *	\brief      JQuery Form to Insert Data to CAP-File and create config File
 */


/**
 *	Class to manage generation of HTML components
 *	Only common components must be here.
 *
 */

	class CAP_Form{
		
    /**
     * Output input field for CAP 1.2 value's
     *
     * @param   string	$type			Type/Tag of CAP 1.2
     * @param   string	$lang			the language (in RFC 3066)
     * @return	string						HTML edit field
     */
		function InputStandard($type, $lang="")
		{
			global $conf, $langs;
			
			switch($type)
			{
				case 'identifier':
					$out = '<div id="Identapend">';
						$out.= '<label>'.$langs->trans("LabelIdentifier").': </label>';
						$out.= '<div class="ui-grid-c">';
							$out.= '<div class="ui-block-a" style="width: 200px;"><input placeholder="WMO OID" type="text" maxlength="22" name="identifier[WMO]"  value="'.$conf->identifier->WMO_OID.'"></div>';
							if(!empty($conf->identifier->ISO))			$out.= '<div class="ui-block-b" style="width: 45px;"><input placeholder="ISO" type="text" maxlength="4" name="identifier[ISO]"  value="'.$conf->identifier->ISO.'"></div>';
							if($conf->identifier->time->on == true) $out.= '<div class="ui-block-c" style="width: 160px;"><input placeholder="YYMMDDHHMMSS" type="text" maxlength="14" name="identifier[time]" value="'.date('ymdHis').'"></div>'; // YYMMDDHHMMSS
							else																		$out.= '<div class="ui-block-c" style="width: 160px;"><input placeholder="YYMMDDHHMMSS" type="text" maxlength="14" name="identifier[time]" ></div>'; // YYMMDDHHMMSS
							$out.= '<div class="ui-block-d" style="width: 200px;"><input placeholder="Warning ID" type="text" maxlength="22" name="identifier[ID]" value="'.$conf->identifier->ID_ID.'"></div>';
						$out.= '</div>';
					$out.= '</div>';
					break;
					
				case 'sender':
					$out = '<input placeholder="sender" type="text" name="sender" >';
					break;
					
				case 'sent':
					$out = '<div id="Sentapend">';
						$out.= '<label>'.$langs->trans("LabelSent").': </label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;"><input type="date" name="sent[date]" value="'.date('Y-m-d').'"></div>';
							$out.= '<div class="ui-block-b" style="width: 155px;"><input type="time" name="sent[time]" step="1" value="'.date('H:i:s').'"></div>';
							$out.= '<div class="ui-block-c" style="width: 155px;"><input type="time" name="sent[UTC]" value="'.date('H:i', date('P')).'"></div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;
				
				case 'references': 
					$out = '<input placeholder="references" type="text" name="references">'; // web / identifier / sent 
					break;
				
				case 'status': 
					// Actual / Test / Exercise / System / Test / Draft
					$status = $this->buildSelect("status", array( "Actual" => "Actual", "Test" => "Test", "Exercise" => "Exercise", "System" => "System", "Test" => "Test", "Draft" => "Draft" ), "data-native-menu=\"false\""); 

				 	// Alert / Update / Cancel / Ack / Error
					$msgType = $this->buildSelect("msgType", array( "Alert" => "Alert", "Update" => "Update", "Cancel" => "Cancel", "Ack" => "Ack", "Error" => "Error" ), "data-native-menu=\"false\" id=\"msgType\""); 

					// Public / Restricted / Private
					$scope = $this->buildSelect("scope", array( "Public" => "Public", "Restricted" => "Restricted", "Private" => "Private" ), "data-native-menu=\"false\"");  

						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" id="TypeMessage">';
							$out.= '<legend>'.$langs->trans("LabelSetTheTypesOfTheMessage").':</legend>';							
								$out.= $status;					
								$out.= $msgType;							
								$out.= $scope;
						$out.= '</fieldset>';
				break;
				
				case 'category': // Geo / Met / Safety / Security / Rescue / Fire / Health / Env / Transport / Infra / CBRNE / Other
					$category = $this->buildSelect("category", array( "Geo" => "Geophysical", "Met" => "Weather", "Safety" => "Public Safety", "Security" => "Security", "Rescue" => "Rescue", "Fire" => "Fire", "Health" => "Health", "Env" => "Environmental", "Transport" => "Transportation", "Infra" => "Infrastructure", "CBRNE" => "Weapon of Mass Destructio", "Other" => "Otherwise Categorized" ), "data-native-menu=\"false\"", "Category");

					// Shelter / Evacuate / Prepare / Execute / Avoid / Monitor / Assess / AllClear / None
					$responseType = $this->buildSelect("responseType", array( "Shelter" => "Take Shelter", "Evacuate" => "Evacuate", "Prepare" => "Make Preparations", "Execute" => "Execute Pre-Planned Action", "Avoid" => "Avoid the affected Area", "Monitor" => "Monitor Conditions", "Assess" => "Evaluate Situation", "AllClear" => "Resume Normal Activities", "None" => "Take No Action" ), "data-native-menu=\"false\"", "Response Type");
			    
						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">';
							$out.= '<legend>'.$langs->trans("LabelSetTheHazardType").':</legend>';							
								$out.= $category;					
								$out.= $responseType;	
						$out.= '</fieldset>';
				
				break;

				case 'urgency': 
					// Immediate / Expected / Future / Past
					$urgency = $this->buildSelect("urgency", array( "Immediate" => "Immediate", "Expected" => "Expected", "Future" => "Future", "Past" => "Past" ), "data-native-menu=\"false\"", "Urgency");
					
					// Extreme / Severe / Moderate / Minor / Unknown 
					$severity = $this->buildSelect("severity", array( "Minor" => "Minor", "Moderate" => "Moderate", "Severe" => "Severe", "Extreme" => "Extreme", "Unknown" => "Unknown" ), "data-native-menu=\"false\"", "Severity");

					// Observed / Likely / Possible/ Unlikely / Unknown
					$certainty = $this->buildSelect("certainty", array( "Unlikely" => "Unlikely", "Possible" => "Possible", "Likely" => "Likely", "Observed" => "Observed", "Unknown" => "Unknown" ), "data-native-menu=\"false\"", "Certainty");
				
						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">';
							$out.= '<legend>'.$langs->trans("LabelSetThePriorityOfTheMessage").':</legend>';							
								$out.= $urgency;					
								$out.= $severity;							
								$out.= $certainty;
						$out.= '</fieldset>';
				
				break;		

				case 'audience': 
					$out = '<input placeholder="audience" type="text" name="audience">';
					break;
					
				case 'eventCode': 
					$out = '<div id="Eventappend">';
						$out.= '<label for="sent[date]">'.$langs->trans("LabelEventCode").': </label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="eventCode[valueName][]"></div>';
							$out.= '<div class="ui-block-b"><input placeholder="Value" type="text" name="eventCode[value][]"></div>';
							$out.= '<div class="ui-block-c"><input type="button" onclick="plusEventCodeInput()" value="+"></div>';
						$out.= '</div>';
					$out.= '</div>';
					break;
				
				case 'effective': 					
					$out = '<div id="Effectiveapend">';
						$out.= '<label>'.$langs->trans("LabelEffective").': </label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;"><input type="date" name="effective[date]" value="'.date('Y-m-d').'"></div>';
							$out.= '<div class="ui-block-b" style="width: 155px;"><input type="time" name="effective[time]" step="1" value="'.date('H:i:s').'"></div>';
							$out.= '<div class="ui-block-c" style="width: 155px;"><input type="time" name="effective[UTC]" value="'.date('H:i', date('P')).'"></div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;

				case 'onset': 					
					$out = '<div id="Onsetapend">';
						$out.= '<label>'.$langs->trans("LabelOnset").': </label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;"><input type="date" name="onset[date]" value="'.date('Y-m-d').'"></div>';
							$out.= '<div class="ui-block-b" style="width: 155px;"><input type="time" name="onset[time]" step="1" value="'.date('H:i:s').'"></div>';
							$out.= '<div class="ui-block-c" style="width: 155px;"><input type="time" name="onset[UTC]" value="'.date('H:i', date('P')).'"></div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;
					
				case 'expieres': 					
					$out = '<div id="Expieresapend">';
						$out.= '<label>'.$langs->trans("LabelExpires").': </label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;"><input type="date" name="expieres[date]" value=""></div>';
							$out.= '<div class="ui-block-b" style="width: 155px;"><input type="time" name="expieres[time]" step="1" value=""></div>';
							$out.= '<div class="ui-block-c" style="width: 155px;"><input type="time" name="expieres[UTC]" value=""></div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;

				case 'senderName': 
					$out = '<input placeholder="senderName" type="text" name="senderName">'; 
					break;
					
				case 'lang':
					$langs_arr = $this->getlang();		
					$lang_S = $this->buildSelect("language_select", $langs_arr, "data-native-menu=\"false\" id=\"language\"", "Language");
					
					$extralang = '<div data-role="controlgroup" data-type="horizontal">';
					foreach($langs_arr as $key => $langs_val)
					{
						$extralang.= '<a href="#" class="ui-btn Lang_Button" role="button" id="'.$key.'_Button" style="display:none; border-right: 1px solid #dddddd;">'.$langs_val.' <span id="'.$key.'_Remove_Button" style="color:red; padding-left: 5px;">X</span><input type="hidden" name="language[]" id="'.$key.'_language_input" value=""></a>';
					}
					$extralang.= '</div>';
					
					$out = $lang_S;			
					$out.= $extralang;		
					break;
					
				case 'event': 

					$langs_arr = $this->getlang();	
					$extralang = "";
					foreach($langs_arr as $key => $langs_val)
					{
						$extralang.= '<div class="lang_input" id="'.$key.'" style="display:none;">';
						
								$extralang.= '<input placeholder="event" type="text" name="event['.$key.']">';

								$extralang.= '<input placeholder="headline" type="text" name="headline['.$key.']">';

								$extralang.= '<textarea placeholder="description" name="description['.$key.']"></textarea>';

								$extralang.= '<input placeholder="instruction" type="text" name="instruction['.$key.']">';

						$extralang.= '</div>';
					}
					
					$out = $extralang;
					break;
					
				case 'web': 
					$out = '<input placeholder="web" type="text" name="web">'; 
					break;
					
				case 'contact': 
					$out = '<input placeholder="contact" type="text" name="contact">'; 
					break;

				case 'parameter': 
					$out = '<div id="Parameterappend">';
						$out.='<label for="sent[date]">Parameter: </label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="parameter[valueName][]"></div>';
							$out.= '<div class="ui-block-b"><input placeholder="Value" type="text" name="parameter[value][]"></div>';
							$out.= '<div class="ui-block-c"><input type="button" onclick="plusParameterInput()" value="+"></div>';
						$out.= '</div>';
					$out.= '</div>';
					break;
					
				/*
				 * Area
				 */					
					case 'areaDesc': 
						$out = '<input placeholder="areaDesc" type="text" name="areaDesc">';
						break;
	
					case 'polygon': 
						$out = '<input placeholder="polygon" type="text" name="polygon">';
						break;
	
					case 'circle': 
						$out = '<input placeholder="circle" type="text" name="circle">';
						break;
						
					case 'geocode': // ]
						$out = '<input placeholder="Valuename" type="text" name="geocode[valueName][]"><input placeholder="geocode Value" type="text" name="geocode[value][]"><input type="button" onclick="plusInput(\'geocode\')" value="+">';
						
						$out = '<div id="Geocodeappend">';
							$out.='<label for="sent[date]">'.$langs->trans("LabelGeocode").': </label>';
							$out.= '<div class="ui-grid-b">';
								$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="geocode[valueName][]"></div>';
								$out.= '<div class="ui-block-b"><input placeholder="Value" type="text" name="geocode[value][]"></div>';
								$out.= '<div class="ui-block-c"><input type="button" onclick="plusGeocodeInput()" value="+"></div>';
								$out.= '</div>';
						$out.= '</div>';
						
						break;															

					/*
					 * Conf input => [conf]
					 */					 
					 
					case 'cap_save':
						if($conf->cap->save == 1) $onoroff = 'checked=""';
						else $onoroff = '';
						$out = '<label for="identifier_time">'.$langs->trans("LabelSaveCapsInOutputFolder").':</label>';
						$out.= '<input type="checkbox" data-role="flipswitch" name="conf[cap][save]" id="cap_save" '.$onoroff.' data-theme="b">';
						break;
					
					case 'cap_output':
							$out = $langs->trans("LabelOutputOfTheCap").': <input type="text" placeholder="Cap Output" name="conf[cap][output]" value="'.$conf->cap->output.'">';
						break;

					case 'ID_ID':
							$out = $langs->trans("LabelIdentifierNumber").': <input type="number" placeholder="Identifier Number" name="conf[identifier][ID_ID]" value="'.$conf->identifier->ID_ID.'">';
						break;
					case 'WMO_OID':
							$out = $langs->trans("LabelWMO_OID").': <input type="text" placeholder="WMO OID" name="conf[identifier][WMO_OID]" value="'.$conf->identifier->WMO_OID.'">';
						break;
						
					case 'ISO':
						$out = $langs->trans("LabelISO").': <input type="text" maxsize="2" placeholder="ISO" name="conf[identifier][ISO]" value="'.$conf->identifier->ISO.'">'; 
					 break;
					 
					case 'identifier_time':
						if($conf->identifier->time->on == 1) $onoroff = 'checked=""';
						else $onoroff = '';
						$out = '<label for="identifier_time">'.$langs->trans("LabelAutomaticIdentifierTime").':</label>';
						$out.= '<input type="checkbox" data-role="flipswitch" name="conf[identifier][time][on]" id="identifier_time" '.$onoroff.' data-theme="b">';
						break;	
						
					case 'lang_conf':
						$out = '<label for="lang_conf">'.$langs->trans("LabelUsableLanguages").':</label>';
						$out.= '<select name="conf[select][lang][]" id="lang_conf" data-native-menu="false" multiple="multiple" data-iconpos="left">';
						foreach($conf->lang as $key => $lang_name)
						{
							if($conf->select->lang[$key] == false)
							{
								$out.= '<option value="'.$key.'">'.$lang_name.'</option>';
							}
							else 
							{
								$out.= '<option value="'.$key.'" selected="selected">'.$lang_name.'</option>';
							}
						}

						$out.= '</select>';
						break;
						
					case 'lang_conf_plus':
							$out = '<div id="LangAappend">';
								$out.='<label for="sent[date]">'.$langs->trans("LabelAddLanguage").': </label>';
								$out.= '<div class="ui-grid-b">';
									$out.= '<div class="ui-block-a"><input type="text" maxsize="5" placeholder="RFC 3066" name="conf[lang][key]" id="lang_conf_plus_key"></div>';
									$out.= '<div class="ui-block-b"><input type="text" name="conf[lang][name]" placeholder="Name" id="lang_conf_plus_name"></div>';
									$out.= '<div class="ui-block-c" style="width: 54px;"><input type="button" onclick="plusLangInput()" value="+" data-theme="b"></div>';
									$out.= '</div>';
							$out.= '</div>';
						break;
						
					case 'lang_conf_remove':
							$out = '<div id="LangRappend">';
								$out.='<label for="sent[date]">'.$langs->trans("LabelRemoveLanguage").': </label>';
								$out.= '<div class="ui-grid-a">';
									$out.= '<div class="ui-block-a">';
										$out.= '<select id="lang_remove" data-native-menu="false" data-iconpos="left">';
										foreach($conf->lang as $key => $lang_name)
										{
											$out.= '<option value="'.$key.'">'.$lang_name.'</option>';
										}		
										$out.= '</select>';
									$out.= '</div>';
									$out.= '<div class="ui-block-b" style="width: 54px;">';
										$out.= '<input type="button" onclick="minusLangInput()" value="-" id="lang_remove_input_button" data-theme="b">';
										$out.= '<input type="hidden" id="lang_remove_input" value="remove">';
									$out.= '</div>';
								$out.= '</div>';
							$out.= '</div>';
						break;
						
					case 'webservice_on':
							if($conf->webservice->on == 1) $onoroff = 'checked=""';
							else $onoroff = '';
							$out = '<label for="identifier_time">Webservice:</label>';
							$out.= '<input type="checkbox" data-role="flipswitch" name="conf[webservice][on]" id="identifier_time" '.$onoroff.' data-theme="b">';
						break;
								
					case 'webservice_password':
							$out = '<input type="password" placeholder="Webservice Password" name="conf[webservice][password]">';
						break;
						
					case 'webservice_securitykey':
							$out = '<input type="text" placeholder="Webservice Security Key" name="conf[webservice][securitykey]">';
						break;
						
					case 'webservice_sourceapplication':
							$out = '<input type="text" placeholder="Webservice Sourceapplication" name="conf[webservice][sourceapplication]">';
						break;
						
					case 'webservice_login':
							$out = '<input type="text" placeholder="Webservice Login" name="conf[webservice][login]">';
						break;
						
					case 'webservice_entity':
							$out = '<input type="text" placeholder="Webservice Entity" name="conf[webservice][entity]">';
						break;
						
					case 'webservice_destination':
							$out = '<input type="url" placeholder="Webservice Destination" name="conf[webservice][destination]">';
						break;
						
					case 'webservice_WS_METHOD':
							$out = '<input type="text" placeholder="Webservice WS_METHOD" name="conf[webservice][WS_METHOD]">';
						break;
						
					case 'webservice_ns':
							$out = '<input type="text" placeholder="Webservice ns" name="conf[webservice][ns]">';
						break;
						
					case 'webservice_WS_DOL_URL':
							$out = '<input type="text" placeholder="Webservice WS_DOL_URL" name="conf[webservice][WS_DOL_URL]">';
						break;
						
					case 'capview';
							$out = '<textarea readonly="" id="capviewtextarea"></textarea>';
						break;
					/*
					 * Default
					 */
					default:
							$out = '<input type="text" placeholder="'.$type.'" name="'.$type.'">';
						break;
			}
			//$out.= $this->InputStandard('sent');
			return $out;
		}
		
		/**
     * Output Html select
     *
     * @param   string	$name					The POST/GET name of the select
     * @param   array		$data					the content of the select array("option value" => "option Name")
     * @param   string  $option
     * @param   string  $placeholder 
     * @param 	int 		$empty 				if 1 then make a empty value 
     * @return	string								HTML select field
     */
		function buildSelect($name= "", $data = array(), $option = "", $placeholder = "", $empty=0)
		{
			$out = '<select name="'.$name.'" '.$option.'>';
			
				if($empty == 1)
				{
					$out.='<option></option>';
				}
				
				if($placeholder)
				{
					$out.= '<option value="#" data-placeholder="true">'.$placeholder.'</option>';
				}
			
				foreach($data as $data_val => $data_name)
				{
					$out.= '<option value="'.$data_val.'">';
					$out.= $data_name;
					$out.= '</option>';
				}
			
			$out.= '</select>';
			return $out;
		}
		
		/**
     * Output RFC 3066 Array
     *     
     * @return	string						Array with RFC 3066 Array
     */
		function getlang($config = false){
			global $conf;
			
			$out_tmp = $conf->lang;

			foreach($out_tmp as $key => $lang_name)
			{
				if($conf->select->lang[$key] == true) $out[$key] = $out_tmp[$key];
			}
			
			return $out;
		}
		
		/**
     * Output Type Array
     *
     * @return	array						Array with the first CAP 1.2 entery's
     */
		function Types()
		{
			// Alert Page		
			$type['alert'][] = "identifier";	
			$type['alert'][] = "status";
			$type['alert'][] = "category";
			$type['alert'][] = "urgency";				      
      
      //$type['alert'][] = "references"; // will be automaticlie added by msgType Update and Cancel
     	
     	// Detail Page
     	$type['alert']['detail'][] = "sent";
     	
      $type['alert']['detail'][] = "effective";
      $type['alert']['detail'][] = "onset";
      $type['alert']['detail'][] = "expieres";
      
			$type['alert']['detail'][] = "eventCode";
			$type['alert']['detail'][] = "parameter";
			
			// Info Page	
			$type['info'][] = "lang";
			$type['info'][] = "event";	
			$type['info'][] = "senderName";
			$type['info'][] = "sender";		
			$type['info'][] = "audience";
			$type['info'][] = "contact";
			$type['info'][] = "web";

			// Area Page
			$type['area'][] = "areaDesc";
			$type['area'][] = "polygon";
			$type['area'][] = "circle";
			$type['area'][] = "geocode";

			// conf Page	
			$type['conf'][] = "cap_save";
			$type['conf'][] = "cap_output";
					
			$type['conf'][] = "WMO_OID";
			$type['conf'][] = "ISO";			
			$type['conf'][] = "ID_ID";
			$type['conf'][] = "identifier_time";
			
			$type['conf'][] = "lang_conf_plus";
			$type['conf'][] = "lang_conf_remove";
			$type['conf'][] = "lang_conf";
			
			/* TO DO
			$type['conf'][] = "webservice_on";
			$type['conf']['detail'][] = "webservice_securitykey";
			$type['conf']['detail'][] = "webservice_sourceapplication";
			$type['conf']['detail'][] = "webservice_login";
			$type['conf']['detail'][] = "webservice_password";
			$type['conf']['detail'][] = "webservice_entity";
			$type['conf']['detail'][] = "webservice_destination";
			$type['conf']['detail'][] = "webservice_WS_METHOD";
			$type['conf']['detail'][] = "webservice_ns";
			$type['conf']['detail'][] = "webservice_WS_DOL_URL";
			*/
			
			$type['capview'][] = 'capview';

      return $type;
		}
		
		function Pages()
		{
			global $langs;
			$pages['alert'] 				= $langs->trans("TitleAlert");
			$pages['alert']['next'] = 'info';
			
			$pages['info']  				= $langs->trans("TitleInfo");
			$pages['info']['next'] 	= 'area';
			
			$pages['area']  				= $langs->trans("TitleArea");
			$pages['area']['next'] 	= 'capview';
			
			$pages['capview'] 		 	= $langs->trans("TitleCapView");
			$pages['conf']['send'] 	= true; 
			
			$pages['conf']  				= $langs->trans("TitleConfig");
			
						
			return $pages;
		}
		
				
		/**
     * Output Html Form
     *
     * @return	string						HTML Form
     */
		function Form()
		{
			global $langs;
			$out = '<head>';
				$out.= '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery.min.js"></script>';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery-ui.min.js"></script>';
				$out.= '<link rel="stylesheet" type="text/css" href="css/cap_form.css">';
			
				$out.= '<link rel="stylesheet" href="includes/jquery.mobile/jquery.mobile-1.4.5.min.css" />';
				$out.= '<script src="includes/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>';
			
				$out.= '<link rel="stylesheet" href="css/BackboneMobile.css" />';
 				$out.= '<link rel="stylesheet" href="css/jquery.mobile.icons.min.css" />';
			$out.= '</head>';
			$out.= '<body>';
			
			$out.= '<form method="POST" id="capform" name="capform" action="index.php" enctype="multipart/form-data" >';
			$out.= '<input type="hidden" name="action" value="create">';

					$Type_arr = $this->Types();
					foreach($Type_arr as $pagename => $TypePage)
					{
						$out.= '<div data-role="page" id="'.$pagename.'">';
						
							$out.= '<div data-role="panel" data-display="overlay" id="'.$pagename.'_panel">';
    						$out.= '<!-- panel content goes here -->';
    						$out.= '<ul data-role="listview">';
    							
    							$out.= '<li style="height: 91px;">';
    								$out.= '<img src="source/conf/logo.jpg" style="border: 1px solid black;border-radius: 45px;width: 20%;margin: 10px 0px 0px 10px;">';
    								$out.= '<h1>';
    									$out.= 'Cap Creator';
    								$out.= '</h1>';
    								$out.= '<br>';
    								$out.= '<span style="font-size: 10px;">';
    									$out.= 'Cap v1.2';
    								$out.= '</span>';
    							$out.= '</li>';
    							
    							$Pages_arr = $this->Pages();
									foreach($Pages_arr as $link => $Page_Name)
									{
										if($link == $pagename) 	$out.= '<li data-theme="b"><a href="#'.$link.'">'.$Page_Name.'</a></li>';
										else 										$out.= '<li><a href="#'.$link.'">'.$Page_Name.'</a></li>';
									}
									
								$out.= '</ul>';
							$out.= '</div>';
							
							$out.= '<div data-theme="b" data-role="header">';								
								$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
								$out.= '<h1>'.$Pages_arr[$pagename].'</h1>';							
							$out.= '</div>';
							
							
							// Main
							$out.= '<div class="ui-content ui-page-theme-a" data-form="ui-page-theme-a" data-theme="a" role="main">';
								
								$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';									
									$out.= '<ul data-role="listview" data-divider-theme="b">';
									
									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">'.$Pages_arr[$pagename].'</h1></li>';
									
										foreach($TypePage as $key => $type)
										{							
											if(is_numeric($key))
											{	
												$out.= '<li>';
													$out.= $this->InputStandard($type);
												$out.= '</li>';
											}
										}
									
									$out.= '</ul>';
								$out.= '</div>';	
								
								if(count ($TypePage['detail']) >= 1)
								{
									$out.= '<div data-role="collapsible" data-theme="b" data-content-theme="a">';
										$out.= '<h2>Detail</h2>';
										$out.= '<ul data-role="listview">';
	
											foreach($TypePage['detail'] as $key_ex => $type_ex)
											{		
												$out.= '<li id="'.$type_ex.'DIV" class="ui-field-contain">'.$this->InputStandard($type_ex).'</li>';
											}						
															
										$out.= '</ul>';
									$out.= '</div>';		
								}
								
							$out.= '</div>';
							
							$out.= '<div data-role="footer" data-theme="b">';						
								//if($Pages_arr[$pagename]['next'] == true) $out.= '<ul data-role="listview" data-inset="true"><li><a href="#info"><h1>Next</h1></a></li></ul>';
								if($Pages_arr[$pagename] == 'Alert') 					$out.= '<ul data-role="listview" data-inset="true"><li><a href="#info"><h1>Next</h1></a></li></ul>';
								if($Pages_arr[$pagename] == 'Info') 					$out.= '<ul data-role="listview" data-inset="true"><li><a href="#area"><h1>Next</h1></a></li></ul>';
								if($Pages_arr[$pagename] == 'Area') 					$out.= '<ul data-role="listview" data-inset="true"><li><a href="#capview"><h1>Next</h1></a></li></ul>';
								if($Pages_arr[$pagename] == 'Cap View') 			$out.= '<input type="submit" value="'.$langs->trans("Submit").'">';
								if($Pages_arr[$pagename] == 'Configuration') 	$out.= '<input class="ui-btn" type="button" value="Save" onclick="ajax_conf()">';
							$out.= '</div>';
							
						$out.= '</div>';
					}

			$out.= '</form>';
			
			
			$out.= '</body>';
			$out.= 
			'
			<script>
				$( document ).ready(function() {
					
					updateCapXML();
									
					$( "input, select" ).change(function() {
						updateCapXML();
					});
					
					
					$( "#msgType" ).change(function() {
						if($( "#msgType" ).val() == "Update" || $( "#msgType" ).val() == "Cancel")
						{		
							if(typeof $("#LIreferences").html()  === "undefined")
							{
								$("#TypeMessage").after(\'<li id="LIreferences" class="ui-li-static ui-body-inherit ui-last-child"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="references" type="text" name="references"></div></li>\');
							}
							else
							{
								$("#LIreferences").show();
							}
						}
						else
						{
							if(typeof $("#LIreferences").html()  !== "undefined")
							{
								$("#LIreferences").hide();
							}
						}
					});
					
					$( "#language" ).change(function() {
						
						$( ".lang_input" ).each(function( index )
						{
							if($( "#language" ).val() == $(this).attr("id"))
							{
								$(this).show();
							}
							else
							{
								$(this).hide();
							}					
						});	
						
						$("#" + $( "#language" ).val() + "_Button").show();
						$( "#" + $( "#language" ).val() + "_language_input" ).val($( "#language" ).val());
						
						$( ".Lang_Button" ).each(function( index )
						{
							if( $( "#language" ).val() + "_Button" == $(this).attr("id"))
							{
								$(this).css("box-shadow", "0px 0px 11px rgb(0, 126, 255)");
							}
							else
							{
								$(this).css("box-shadow", "");
							}
						});
						
					});';
					
					$langs_arr = $this->getlang();	
					
					foreach($langs_arr as $key => $langs_val)
					{
						
						$out.= '
										$( "#'.$key.'_Remove_Button" ).click(function() {
											$(this).parent("a").hide();			
											$(\'input[name="event['.$key.']"]\').val("");
											$(\'input[name="headline['.$key.']"]\').val("");
											$(\'input[name="description['.$key.']"]\').val("");
											$(\'input[name="instruction['.$key.']"]\').val("");
											$( "#'.$key.'_language_input" ).val("delete");
										});
									 ';
					}
					
					foreach($langs_arr as $key => $langs_val)
					{
						
						$out.= '$( "#'.$key.'_Button" ).click(function() {';
							$out.= '
											if($( "#'.$key.'_language_input" ).val() != "delete")
											{
												$( "#'.$key.'_language_input" ).val("'.$key.'");
											}
											else
											{
												$( "#'.$key.'_language_input" ).val("");
											}
											
											$( ".Lang_Button" ).each(function( index )
											{
												if( "'.$key.'_Button" == $(this).attr("id"))
												{
													$(this).css("box-shadow", "0px 0px 11px rgb(0, 126, 255)");
												}
												else
												{
													$(this).css("box-shadow", "");
												}
											});
											
											$( ".lang_input" ).each(function( index )
											{
												if( "'.$key.'" == $(this).attr("id"))
												{
													$(this).show();
												}
												else
												{
													$(this).hide();
												}					
											});				
							';			
						$out.= '});';
					}
					
				$out.= '
				});
				
				
				function updateCapXML()
				{
					var url = "index.php?cap=1"; // the script where you handle the form input.
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	$("#capviewtextarea").val(data);
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function ajax_conf()
				{
					var url = "index.php?conf=1"; // the script where you handle the form input.
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	location.reload();
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function plusLangInput()
				{
					var url = "index.php?conf=1"; // the script where you handle the form input.
					
					key  = $("#lang_conf_plus_key").val();
					$("#lang_conf_plus_name").attr("name", "conf[lang][" + key + "]");
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	location.reload();
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function minusLangInput()
				{
					var url = "index.php?conf=1"; // the script where you handle the form input.
					
					key  = $("#lang_remove").val();
					$("#lang_remove_input").attr("name", "conf[lang][remove][" + key + "]");
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	location.reload();
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function plusParameterInput()
				{
					$("#Parameterappend").after(\'<div class="ui-grid-b"><div class="ui-block-a"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Valuename" type="text" name="parameter[valueName][]"></div></div><div class="ui-block-b"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Value" type="text" name="parameter[value][]"></div></div><div class="ui-block-c"></div></div>\');
				}
				
				function plusEventCodeInput()
				{
					$("#Eventappend").after(\'<div class="ui-grid-b"><div class="ui-block-a"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Valuename" type="text" name="eventCode[valueName][]"></div></div><div class="ui-block-b"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Value" type="text" name="eventCode[value][]"></div></div><div class="ui-block-c"></div></div>\');
				}
				
				function plusGeocodeInput()
				{
					$("#Geocodeappend").after(\'<div class="ui-grid-b"><div class="ui-block-a"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Valuename" type="text" name="geocode[valueName][]"></div></div><div class="ui-block-b"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Value" type="text" name="geocode[value][]"></div></div><div class="ui-block-c"></div></div>\');
				}
				
			</script>';
			
			return $out;
		}
		
		/**
		 * Function to install the interface of the Cap PHP library
		 *
		 * @return	string 	$out
		 */
		function CapView($content, $ID)
		{
			global $conf;
			
			$out = '<head>';
				$out.= '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery.min.js"></script>';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery-ui.min.js"></script>';
				$out.= '<link rel="stylesheet" type="text/css" href="css/cap_form.css">';
			
				$out.= '<link rel="stylesheet" href="includes/jquery.mobile/jquery.mobile-1.4.5.min.css" />';
				$out.= '<script src="includes/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>';
			
				$out.= '<link rel="stylesheet" href="css/BackboneMobile.css" />';
 				$out.= '<link rel="stylesheet" href="css/jquery.mobile.icons.min.css" />';
			$out.= '</head>';
			$out.= '<body>';			
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?conf=1" enctype="multipart/form-data" >';
			
					$out.= '<div data-role="page" id="capview">';

						$out.= '<div data-role="panel" data-display="overlay" id="'.$pagename.'_panel">';
  						$out.= '<!-- panel content goes here -->';
  						$out.= '<ul data-role="listview" data-inset="true">';
  						
  							$Pages_arr = $this->Pages();
								foreach($Pages_arr as $link => $Page_Name)
								{
									$out.= '<li><a href="#'.$link.'">'.$Page_Name.'</a></li>';
								}
								
							$out.= '</ul>';
						$out.= '</div>';
						
						$out.= '<div data-theme="b" data-role="header">';								
							$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
							$out.= '<h1>'.$ID.'.cap</h1>';						
						$out.= '</div>';
					
						$out.= '<div role="main" class="ui-content">';							
										
							$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';	
								$out.= '<ul data-role="listview" data-divider-theme="b">';
									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">'.$langs->trans("LabelCapViewOf").': '.$ID.'.cap</h1></li>';	
									if($conf->cap->save == 1) $out.= '<li><a href="'.$conf->cap->output.'/'.$ID.'.cap" download data-ajax="false">Download '.$ID.'.cap</a></li>';
									$out.= '<li>';
										$out.= '<textarea readonly>';						
											$out.= $content;
										$out.= '</textarea>';
									$out.= '</li>';
								$out.= '</ul>';
							$out.= '</div>';

						$out.= '</div><!-- /content -->';
					
						$out.= '<div data-role="footer" data-theme="b">';

						$out.= '</div><!-- /footer -->';
						
					$out.= '</div><!-- /page -->';
			
				$out.= '</form>';			
			$out.= '</body>';
			
			return $out;
		}
		
		/**
		 * Function to conect the identifier to one string
		 *
		 * @return	array 	$_POST
		 */
		function MakeIdentifier($post)
		{
			$temp = $post[identifier][WMO].".".$post[identifier][ISO].".".$post[identifier][time].".".$post[identifier][ID];
			unset($post[identifier]);
			$post[identifier] = $temp;
			return $post;
		}
		
		/**
		 * Function to install the interface of the Cap PHP library
		 *
		 * @return	string 	$out
		 */
		function install()
		{
			
				$out = '<head>';
				$out.= '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery.min.js"></script>';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery-ui.min.js"></script>';
				$out.= '<link rel="stylesheet" type="text/css" href="css/cap_form.css">';
			
				$out.= '<link rel="stylesheet" href="includes/jquery.mobile/jquery.mobile-1.4.5.min.css" />';
				$out.= '<script src="includes/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>';
			
				$out.= '<link rel="stylesheet" href="css/BackboneMobile.css" />';
 				$out.= '<link rel="stylesheet" href="css/jquery.mobile.icons.min.css" />';
			$out.= '</head>';
			$out.= '<body>';			
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?conf=1" enctype="multipart/form-data" >';
			
					$out.= '<div data-role="page">';

						$out.= '<div data-role="header">';
							$out.= '<h1>Install Cap PHP Library Interface</h1>';
						$out.= '</div><!-- /header -->';
					
						$out.= '<div role="main" class="ui-content">';							
										
							$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';									
								$out.= '<ul data-role="listview" data-divider-theme="b">';
									
									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">Configuration</h1></li>';

									$Type_arr = $this->Types();
									foreach($Type_arr['conf'] as $key => $type)
									{							
										if(is_numeric($key))
										{	
											$out.= '<li>';
												$out.= $this->InputStandard($type);
											$out.= '</li>';
										}
									}	
								
								$out.= '</ul>';								
							$out.= '</div>';	
							
								if(count ($Type_arr['conf']['detail']) >= 1)
								{
									$out.= '<div data-role="collapsible" data-theme="b" data-content-theme="a">';
										$out.= '<h2>Webservice</h2>';
										$out.= '<ul data-role="listview">';
	
											foreach($Type_arr['conf']['detail'] as $key_ex => $type_ex)
											{		
												$out.= '<li id="'.$type_ex.'DIV" class="ui-field-contain">'.$this->InputStandard($type_ex).'</li>';
											}						
															
										$out.= '</ul>';
									$out.= '</div>';		
								}
							
							$out.= '<input type="submit" value="Submit" data-theme="a">';

						$out.= '</div><!-- /content -->';
					
						$out.= '<div data-role="footer">';
							$out.= '<h4>office@backbone.co.at</h4>';
						$out.= '</div><!-- /footer -->';
						
					$out.= '</div><!-- /page -->';
			
				$out.= '</form>';			
			$out.= '</body>';
			
			return $out;
		}
		
		/**
		 * Function to save the conf post information to the conf
		 *
		 * @return	null
		 */	
		function PostToConf($post)
		{	
			global $conf;
			
			/*
			 * Special
			 */
		 	
			 // set langs
			$lang_arr = $post['lang'];
			unset($post['lang']);
			foreach($lang_arr as $lang_key => $lang_name)
			{			 	 
			 	if($lang_key != "key" && $lang_name != "name" && $lang_key != "remove")
			 	{
			 		$conf->lang[$lang_key] = $lang_name;
		 		}
		 	}
 	
 				// conf[lang][remove][en-GB]:remove -> conf[lang][remove][remove]:en-GB
			$rmv_lang_arr = array_flip($lang_arr['remove']);
			unset($post['lang']['remove']);
			foreach($conf->lang as $lang_key => $lang_name)
			{			 	 
			 	if(in_array($lang_key, $rmv_lang_arr))
			 	{
			 		unset($conf->lang[$lang_key]);
			 	}
		 	}
			 
			// set visible langs
			$lang_arr = $post['select']['lang'];
			unset($post['select']);
			foreach($conf->select->lang as $lang_name => $lang_boolen)
			{			 	 
			 	if(in_array($lang_name, $lang_arr))
			 	{
			 		$conf->select->lang[$lang_name] = 1;
			 	}
			 	else
			 	{
			 		$conf->select->lang[$lang_name] = 0;
			 	}
		 	}
			 
			// specifie the automatic time set
			if($post['identifier']['time']['on'] == "on")
		 	{
			 	$conf->identifier->time->on = 1;
			}	
			else
			{
			 	$conf->identifier->time->on = 0;
			}		
			unset($post['identifier']['time']);
				
			if($post['cap']['save'] == "on")
		 	{
			 	$conf->cap->save = 1;
			}	
			else
			{
			 	$conf->cap->save = 0;
			}		
			unset($post['cap']['save']);
			/* 
			 * Reguler
			 */
			if(is_array($post))
			{
				foreach($post as $obj_name => $obj_val)
				{
					
					if(is_array($obj_val))
					{
						foreach($obj_val as $obj_2_name => $obj_2_val)
						{
						
							if(is_array($obj_2_val))
							{
								foreach($obj_2_val as $obj_3_name => $obj_3_val)
								{
									$conf->{$obj_name}->{$obj_2_name}->{$obj_3_name} = $obj_3_val;
								} // Level 2
							}
							else
							{
								$conf->{$obj_name}->{$obj_2_name} = $obj_2_val;
							}
							
						} // Level 1
					}
					else
					{
						$conf->{$obj_name}->{$obj_1_name} = $obj_1_val;
					}
					
				} // Base
			}
			else
			{
				$conf->{$obj_name} = $obj_val;
			}
			
			print_r($post);
			print_r($conf);
		}
	
		/**
		 * Function to chnge Configuration in the conf.php file
		 *
		 * @return	null
		 */	
		function WriteConf($write = true)
		{			
			global $conf;
			
			$out = "<?php"."\n";
			$out.= "date_default_timezone_set('".$conf->timezone->set_default."');"."\n";
			// CONF BASE
			if(is_object($conf) || is_array($conf_arr))
			{
				foreach($conf as $conf_name => $conf_arr)
				{
					
					// LEVEL 1
					if(is_object($conf_arr) || is_array($conf_arr))
					{
						foreach($conf_arr as $conf_name_var => $conf_val)
						{
							
							// LEVEL 2
							if(is_object($conf_val) || is_array($conf_val))
							{
								foreach($conf_val as $conf_name_level_2_var => $conf_level_2_val)
								{								
									if(is_array($conf_val))
									{
										$space = $this->ConfSpaces("$"."conf->".$conf_name."->".$conf_name_var."['".$conf_name_level_2_var."']");
										if(!is_numeric($conf_level_2_val)){ $string_or_number = "'"; }else{ $string_or_number = ""; }
										$out.= "$"."conf->".$conf_name."->".$conf_name_var."['".$conf_name_level_2_var."']".$space."= ".$string_or_number.$conf_level_2_val.$string_or_number.";"."\n";
									}
									elseif(is_object($conf_val))
									{ 
										$space = $this->ConfSpaces("$"."conf->".$conf_name."->".$conf_name_var."->".$conf_name_level_2_var);
										if(!is_numeric($conf_level_2_val)){ $string_or_number = "'"; }else{ $string_or_number = ""; }
										$out.= "$"."conf->".$conf_name."->".$conf_name_var."->".$conf_name_level_2_var.$space."= ".$string_or_number.$conf_level_2_val.$string_or_number.";"."\n";
									}
								} // foreach conf_val								
							} // is_array conf_val
							else
							{				
								if(is_array($conf_arr))
								{
									$space = $this->ConfSpaces("$"."conf->".$conf_name."['".$conf_name_var."']");
									if(!is_numeric($conf_val)){ $string_or_number = "'"; }else{ $string_or_number = ""; }
									$out.= "$"."conf->".$conf_name."['".$conf_name_var."']".$space."= ".$string_or_number.$conf_val.$string_or_number.";"."\n";
								}
								elseif(is_object($conf_arr))
								{
									$space = $this->ConfSpaces("$"."conf->".$conf_name."->".$conf_name_var);
									if(!is_numeric($conf_val)){ $string_or_number = "'"; }else{ $string_or_number = ""; }
									$out.= "$"."conf->".$conf_name."->".$conf_name_var.$space."= ".$string_or_number.$conf_val.$string_or_number.";"."\n";
								}
							} // else is_array conf_val
							
						} // foreach conf_arr
					} // is_array conf_arr
					else
					{
						if(is_array($conf_arr))
						{
							$space = $this->ConfSpaces("$"."conf['".$conf_name."']");
							if(!is_numeric($conf_arr)){ $string_or_number = "'"; }else{ $string_or_number = ""; }
							$out.= "$"."conf['".$conf_name."']".$space."= ".$string_or_number.$conf_arr.$string_or_number.";"."\n";
						}
						elseif(is_object($conf_arr))
						{
							$space = $this->ConfSpaces("$"."conf->".$conf_name);
							if(!is_numeric($conf_arr)){ $string_or_number = "'"; }else{ $string_or_number = ""; }
							$out.= "$"."conf->".$conf_name.$space."= ".$string_or_number.$conf_arr.$string_or_number.";"."\n";
						}
					} // else is_object conf_arr					
					$out.= "\n";
					
				} // foreach conf
			} // is_array conf
			else
			{
				$write = false;
				print 'FAIL TO READ CONF';	
			} // else is_object conf
					
			$out.= "?>";
			
			if($write == true)
			{
				$conf_file = fopen("source/conf/conf.php", "w") or print("Unable to open conf!");
				fwrite($conf_file, $out);
				fclose($conf_file);
			}
			else
			{
				print ($out);
			}
		}		
		
		function ConfSpaces($string)
		{
			//$space = '                                                     '; // 55 spaces Standard
			$space = '';
			$i = (55 - strlen($string));
			while( $i > 0 )
			{ 
				$space.= ' '; 
				$i--;
			}
			return $space;
		}
	}
	
?>