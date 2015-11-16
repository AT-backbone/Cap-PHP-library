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
     * initialize Class with Data
     *
     * @param   string	$post			Array with Type/Tag of CAP 1.1
     * @return	None
     */
		function __construct($post = "")
		{
			if(is_array($post))
			{
				$this->output[] 			= $post['output'];
				$this->identifier[] 	= $post['identifier'];
				$this->sender[]				= $post['sender'];
				$this->sent[]					= $post['sent'];
				$this->status[]				= $post['status'];
				$this->msgType[]			= $post['msgType'];
				$this->references[]		= $post['references'];
				$this->scope[]				= $post['scope'];
				
				foreach($post['info'] as $key => $info)
				{
					
					$this->language[]			= $info['language'];
					$this->category[]			= $info['category'];
					$this->event[]				= $info['event'];
					$this->responseType[]	= $info['responseType'];
					$this->urgency[]			= $info['urgency'];
					$this->severity[]			= $info['severity'];
					$this->certainty[]		= $info['certainty'];
					$this->audience[]			= $info['audience'];
					$this->eventCode[]		= $info['eventCode'];
					$this->effective[]		= $info['effective'];
					$this->onset[]				= $info['onset'];
					$this->expires[]			= $info['expires'];
					$this->senderName[]		= $info['senderName'];
					$this->headline[]			= $info['headline'];
					$this->description[]	= $info['description'];
					$this->instruction[]	= $info['instruction'];
					$this->web[]					= $info['web'];
					$this->contact[]			= $info['contact'];
					$this->parameter[]		= $info['parameter'];
					
					foreach($info['area'] as $key2 => $area)
					{					
						$this->areaDesc[]			= $area['areaDesc'];
						$this->polygon[]			= $area['polygon'];
						$this->circle[]				= $area['circle'];
						$this->geocode[]			= $area['geocode'];
					}
				}
				
				$this->cap 					= $post;
			}
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
		
    /**
     * Output input field for CAP 1.1 value's
     *
     * @param   string	$type			Type/Tag of CAP 1.1
     * @param   string	$lang			the language (in RFC 3066)
     * @return	string						HTML edit field
     */
		function InputStandard($type, $lang="")
		{
			global $conf, $langs, $AreaCodesArray, $ParameterArray;
			
			$st['date'] = date('Y-m-d');
			$st['time'] = date('H:i:s');
			$st['zone'] = date('H:i', date('P'));

			
			switch($type)
			{
				case 'CapButton':
					// TODO SELECT FOR TEMPLATE HERE
					$out = '</li></ul></div>'; // exit li
					$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
					$out.= '<div data-role="listview" class="ui-grid-a" style="height: 200px;margin: 0px 0px 0px 0px;">';
						$out.= '<div class="ui-block-a" style="padding-right: 7.5px;">';							
							$out.= '<a href="#alert" style="text-decoration: none;"><div class="ui-btn ui-input-btn ui-btn-b ui-corner-all ui-shadow" style="height: 85px;padding-top: 85px;">';
								$out.=  $langs->trans("NewCap");
							$out.= '</div></a>';
						$out.= '</div>';
						$out.= '<div class="ui-block-b" style="padding-left: 7.5px;">';
							$out.= '<a href="index.php?read=1#alert" data-ajax="false" style="text-decoration: none;"><div class="ui-btn ui-input-btn ui-btn-b ui-corner-all ui-shadow" style="height: 85px;padding-top: 85px;">';
								$out.=  $langs->trans("ReadCap");
							$out.= '</div></a>';
						$out.= '</div>';
					$out.= '</div>';
					$out.= '<ul data-role="listview" style="margin-top: 7.5px;"><li>'; // enter li
						$out.= '<a href="#conf" >'.$langs->trans("TitleConfig").'</a>';
					$out.= '</li><li>';
					break;
					
				case 'identifier':
					$out = '<div id="Identapend">';
						$out.= '<label>'.$langs->trans("LabelIdentifier").': '.$this->tooltip($type, $langs->trans("LabelIdentifierDesc")).'</label>';
						if(!is_array($this->identifier))
						{
							$out.= '<div class="ui-grid-c">';
								if(!empty($conf->identifier->WMO_OID))	$out.= '<div class="ui-block-a" style="width: 200px;"><input placeholder="WMO OID" type="text" maxlength="22" name="identifier[WMO]"  value="'.$conf->identifier->WMO_OID.'"></div>';
								if(!empty($conf->identifier->ISO))			$out.= '<div class="ui-block-b" style="width: 45px;"><input placeholder="ISO" type="text" maxlength="4" name="identifier[ISO]"  value="'.$conf->identifier->ISO.'"></div>';
								if($conf->identifier->time->on == true) $out.= '<div class="ui-block-c" style="width: 160px;"><input placeholder="YYMMDDHHMMSS" type="text" maxlength="14" name="identifier[time]" value="'.date('ymdHis').'"></div>'; // YYMMDDHHMMSS
								if(!empty($conf->identifier->ID_ID))	 	$out.= '<div class="ui-block-d" style="width: 200px;"><input placeholder="Warning ID" type="text" maxlength="22" name="identifier[ID]" value="'.$conf->identifier->ID_ID.'"></div>';
								if(empty($conf->identifier->ID_ID)) 		$out.= '<div class="ui-block-d"><input placeholder="Warning ID" type="text" name="identifier[ID]"  value="'.$this->identifier[0].'"></div>';
							$out.= '</div>';
						}
						else
						{
							$out.= '<input placeholder="" type="text" name="identifier[ID]"  value="'.$this->identifier[0].'">';
						}
					$out.= '</div>';					
					break;
					
				case 'sender':
					$out = '<legend>'.$langs->trans("Labelsender").': '.$this->tooltip($type, $langs->trans("LabelsenderDesc")).'</legend>';	
					$out.= '<input placeholder="sender" type="text" name="sender" value="'.$this->sender[0].'">';
					break;
					
				case 'sent':
					if($this->sent[0]) $st = $this->make_cap_time($this->sent[0]);
					$out = '<div id="Sentapend">';
						$out.= '<label>'.$langs->trans("LabelSent").': '.$this->tooltip($type, $langs->trans("LabelSentDesc")).'</label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input type="date" name="sent[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input  type="time" name="sent[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 155px;">';
								$out.= '<input type="time" name="sent[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;
				
				case 'references': 
					$out = '<legend>'.$langs->trans("Labelreferences").': '.$this->tooltip($type, $langs->trans("LabelreferencesDesc")).'</legend>';	
					$out.= '<input placeholder="references" type="text" name="references" value="'.$this->references[0].'">'; // web / identifier / sent 
					break;
				
				case 'status': 
					// Actual / Test / Exercise / System / Test / Draft
					$status = $this->buildSelect("status", array( "Actual" => "Actual", "Test" => "Test", "Exercise" => "Exercise", "System" => "System", "Test" => "Test", "Draft" => "Draft" ), "data-native-menu=\"false\"", "Status", $this->status[0]); 

				 	// Alert / Update / Cancel / Ack / Error
					$msgType = $this->buildSelect("msgType", array( "Alert" => "Alert", "Update" => "Update", "Cancel" => "Cancel", "Ack" => "Ack", "Error" => "Error" ), "data-native-menu=\"false\" id=\"msgType\"", "MsgType", $this->msgType[0]); 

					// Public / Restricted / Private
					$scope = $this->buildSelect("scope", array( "Public" => "Public", "Restricted" => "Restricted", "Private" => "Private" ), "data-native-menu=\"false\"", "Scope", $this->scope[0]);  

						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" id="TypeMessage">';
							$out.= '<legend>'.$langs->trans("LabelSetTheTypesOfTheMessage").': '.$this->tooltip($type, $langs->trans("LabelSetTheTypesOfTheMessageDesc")).'</legend>';							
								$out.= $status;					
								$out.= $msgType;							
								$out.= $scope;
						$out.= '</fieldset>';
				break;
				
				case 'category': // Geo / Met / Safety / Security / Rescue / Fire / Health / Env / Transport / Infra / CBRNE / Other
					$category = $this->buildSelect("category", array( "Geo" => $langs->trans("Geo"), "Met" => $langs->trans("Met"), "Safety" => $langs->trans("Safety"), "Security" => $langs->trans("Security"), "Rescue" => $langs->trans("Rescue"), "Fire" => $langs->trans("Fire"), "Health" => $langs->trans("Health"), "Env" => $langs->trans("Env"), "Transport" => $langs->trans("Transport"), "Infra" => $langs->trans("Infra"), "CBRNE" => $langs->trans("CBRNE"), "Other" => $langs->trans("Other") ), "data-native-menu=\"false\"", "Category", $this->category[0]);
					// Shelter / Evacuate / Prepare / Execute / Avoid / Monitor / Assess / AllClear / None
					$responseType = $this->buildSelect("responseType", array( "Shelter" => $langs->trans("Shelter"), "Evacuate" => $langs->trans("Evacuate"), "Prepare" => $langs->trans("Prepare"), "Execute" => $langs->trans("Execute"), "Avoid" => $langs->trans("Avoid"), "Monitor" => $langs->trans("Monitor"), "Assess" => $langs->trans("Assess"), "AllClear" => $langs->trans("AllClear"), "None" => $langs->trans("None") ), "data-native-menu=\"false\"", "Response Type", ($this->responseType[0][0]));

						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">';
							$out.= '<legend>'.$langs->trans("LabelSetTheHazardType").': '.$this->tooltip($type, $langs->trans("LabelSetTheHazardTypeDesc")).'</legend>';							
								$out.= $category;					
								$out.= $responseType;	
						$out.= '</fieldset>';
				
				break;

				case 'urgency': 
					// Immediate / Expected / Future / Past
					$urgency = $this->buildSelect("urgency", array( "Immediate" => "Immediate", "Expected" => "Expected", "Future" => "Future", "Past" => "Past" ), "data-native-menu=\"false\"", "Urgency", $this->urgency[0]);
					
					// Extreme / Severe / Moderate / Minor / Unknown 
					$severity = $this->buildSelect("severity", array( "Minor" => "Minor", "Moderate" => "Moderate", "Severe" => "Severe", "Extreme" => "Extreme", "Unknown" => "Unknown" ), "data-native-menu=\"false\"", "Severity", $this->severity[0]);

					// Observed / Likely / Possible/ Unlikely / Unknown
					$certainty = $this->buildSelect("certainty", array( "Unlikely" => "Unlikely", "Possible" => "Possible", "Likely" => "Likely", "Observed" => "Observed", "Unknown" => "Unknown" ), "data-native-menu=\"false\"", "Certainty", $this->certainty[0]);
				
						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">';
							$out.= '<legend>'.$langs->trans("LabelSetThePriorityOfTheMessage").': '.$this->tooltip($type, $langs->trans("LabelSetThePriorityOfTheMessageDesc")).'</legend>';							
								$out.= $urgency;					
								$out.= $severity;							
								$out.= $certainty;
						$out.= '</fieldset>';
				
				break;		

				case 'audience': 
					$out = '<legend>'.$langs->trans("Labelaudience").': '.$this->tooltip($type, $langs->trans("LabelaudienceDesc")).'</legend>';	
					$out.= '<input placeholder="audience" type="text" name="audience" value="'.$this->audience[0].'">';
					break;
					
				case 'eventCode': 
					$out = '<div id="Eventappend">';
						$out.= '<legend>'.$langs->trans("LabelEventCode").': '.$this->tooltip($type, $langs->trans("LabelEventCodeDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
							foreach($this->eventCode[0] as $key => $eventCode)
							{
								$out.= '<div class="ui-grid-b">';
									$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="eventCode[valueName][]" value="'.$eventCode['valueName'].'"></div>';
									$out.= '<div class="ui-block-b"><input placeholder="Value Value" type="text" name="eventCode[value][]" value="'.$eventCode['value'].'"></div>';
								$out.= '</div>';
							}	
							$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="eventCode[valueName][]"></div>';
							$out.= '<div class="ui-block-b"><input placeholder="Value" type="text" name="eventCode[value][]"></div>';
							$out.= '<div class="ui-block-c"><input type="button" onclick="plusEventCodeInput()" value="+"></div>';
						$out.= '</div>';
					$out.= '</div>';
					break;
				
				case 'effective-Clock':
						$out.= '<div class="input-group clockpicker" data-autoclose="true">';
							$out.= '<input type="time" class="form-control" value="09:30" step="2">';
							$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
								$out.= '<span class="glyphicon glyphicon-time"></span>';
							$out.= '</span>';
						$out.= '</div>';
					break;
				
				case 'effective': 					
					if($this->effective[0]) $st = $this->make_cap_time($this->effective[0]);
					$out = '<div id="Effectiveapend">';
						$out.= '<legend>'.$langs->trans("LabelEffective").': '.$this->tooltip($type, $langs->trans("LabelEffectiveDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input type="date" name="effective[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input  type="time" name="effective[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 155px;">';
								$out.= '<input type="time" name="effective[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;

				case 'onset': 					
					if($this->onset[0]) $st = $this->make_cap_time($this->onset[0]);
					$out = '<div id="Onsetapend">';
						$out.= '<legend>'.$langs->trans("LabelOnset").': '.$this->tooltip($type, $langs->trans("LabelOnsetDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input type="date" name="onset[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input  type="time" name="onset[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 155px;">';
								$out.= '<input type="time" name="onset[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;
					
				case 'expires': 					
					if($this->expires[0]) $st = $this->make_cap_time($this->expires[0]);
					$out = '<div id="Expieresapend">';
						$out.= '<legend>'.$langs->trans("LabelExpires").': '.$this->tooltip($type, $langs->trans("LabelExpiresDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input type="date" name="expires[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input  type="time" name="expires[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 155px;">';
								$out.= '<input type="time" name="expires[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
					$out.= '</div>';
					break;

				case 'senderName': 
					$out = '<legend>'.$langs->trans("LabelsenderName").': '.$this->tooltip($type, $langs->trans("LabelsenderNameDesc")).'</legend>';	
					$out.= '<input placeholder="senderName" type="text" name="senderName" value="'.$this->senderName[0].'">'; 
					break;
					
				case 'info':
					$out = 'TEST';
					break;
					
				case 'lang':
					$langs_arr = $this->getlang();	
						
					foreach($langs_arr as $key_l => $val_l)
					{
						if(in_array($key,$this->language)) unset($langs_arr[$key]);
					}
					
					$lang_S = $this->buildSelect("language_select", $langs_arr, "data-native-menu=\"false\" id=\"language\"", $langs->trans("LabelLanguage"));
					
					$extralang = '<div data-role="controlgroup" data-type="horizontal">';
					
					$styleD[true] = "";
					$styleD[false] = "display:none;";
					//if(is_array($this->language)) die(print_r($this->language)); // Array ( [0] => en-GB [1] => de-DE ) 1
					
					foreach($langs_arr as $key => $langs_val)
					{
						if(in_array($key,$this->language)) $display = true; else $display = false;
						$extralang.= '<a href="#" class="ui-btn Lang_Button" role="button" id="'.$key.'_Button" style="'.$styleD[$display].' border-right: 1px solid #dddddd;">'.$langs_val.' <span id="'.$key.'_Remove_Button" style="color:red; padding-left: 5px;">X</span><input type="hidden" name="language[]" id="'.$key.'_language_input" value=""></a>';
						if($display == true) $extralang.= '<input type="hidden" value="'.$key.'" name="language[]">';
					}
					$extralang.= '</div>';
					
					$out = $lang_S;			
					$out.= $extralang;		
					break;
					
				case 'event': 

					$langs_arr = $this->getlang();	
					$extralang = "";
					$styleD[true] = "";
					$styleD[false] = "display:none;";
					$i = 0;
					
					foreach($langs_arr as $key => $langs_val)
					{
						
						if(in_array($key,$this->language) && $i < 1) $display = true; else $display = false;
						
						$extralang.= '<div class="lang_input" id="'.$key.'" style="'.$styleD[$display].'">';
						
								$extralang.= '<input placeholder="event" type="text" name="event['.$key.']" value="'.$this->event[$i].'">';

								$extralang.= '<input placeholder="headline" type="text" name="headline['.$key.']" value="'.$this->headline[$i].'">';

								$extralang.= '<textarea placeholder="description" name="description['.$key.']">'.$this->description[$i].'</textarea>';

								$extralang.= '<input placeholder="instruction" type="text" name="instruction['.$key.']" value="'.$this->instruction[$i].'">';

						$extralang.= '</div>';
						
						$i++;
					}
					
					$out = $extralang;
					break;
					
				case 'web': 
					$out = '<legend>'.$langs->trans("Labelweb").': '.$this->tooltip($type, $langs->trans("LabelwebDesc")).'</legend>';	
					$out.= '<input placeholder="web" type="text" name="web" value="'.$this->web[0].'">'; 
					break;
					
				case 'contact': 
					$out = '<legend>'.$langs->trans("Labelcontact").': '.$this->tooltip($type, $langs->trans("LabelcontactDesc")).'</legend>';	
					$out.= '<input placeholder="contact" type="text" name="contact" value="'.$this->contact[0].'">'; 
					break;

				case 'parameter': 
					$l_level = array( "Unknown", "Minor", "Moderate", "Severe", "Extreme"  );
					
					if(is_array($ParameterArray['AWT']))
					foreach($ParameterArray['AWT'] as $key => $area_arr)
					{
						$S_Param_AWT[$area_arr['id'].'; '.$area_arr['hazard_type']] = $area_arr['hazard_type_DESC'];
						$G_Param_AWT[$area_arr['id'].'; '.$area_arr['hazard_type']] = 'awareness_type'; //awareness_type awareness_level
					}
					
					if(is_array($ParameterArray['AWL']))
					foreach($ParameterArray['AWL'] as $key => $area_arr)
					{
						$S_Param_AWL[$area_arr['id'].'; '.$area_arr['hazard_level'].';'.$l_level[$area_arr['id']]] = $area_arr['hazard_level'];
						$G_Param_AWL[$area_arr['id'].'; '.$area_arr['hazard_level'].';'.$l_level[$area_arr['id']]] = 'awareness_level'; //awareness_type awareness_level
						$this->level_color[$area_arr['hazard_level']] = $area_arr['hazard_level_color'];
					}
					//die(print_r($S_Param_AWL));
					if(is_array($S_Param_AWT) && is_array($S_Param_AWT))
					{
						$out.= '<legend>'.$langs->trans("LabelGeocodeWebservice").': '.$this->tooltip($type, $langs->trans("LabelGeocodeWebserviceDesc")).'</legend>';							
						$out.= $this->buildSelectValueName('parameter[value][]', 'parameter[valueName][]', 'parameter_awt',$S_Param_AWT, $G_Param_AWT, $this->geocode[0]);

						$out.= '<legend>'.$langs->trans("LabelGeocodeWebservice").': '.$this->tooltip($type, $langs->trans("LabelGeocodeWebserviceDesc")).'</legend>';							
						$out.= $this->buildSelectValueName('parameter[value][]', 'parameter[valueName][]', 'parameter_awl',$S_Param_AWL, $G_Param_AWL, $this->geocode[0]);
					}
					else
					{
						$out = '<div id="Parameterappend">';
							$out.= '<legend>'.$langs->trans("LabelParameter").': '.$this->tooltip($type, $langs->trans("LabelParameterDesc")).'</legend>';	
							$out.= '<div class="ui-grid-b">';
								foreach($this->parameter[0] as $key => $parameter)
								{
									$out.= '<div class="ui-grid-b">';
										$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="parameter[valueName][]" value="'.$parameter['valueName'].'"></div>';
										$out.= '<div class="ui-block-b"><input placeholder="Value Value" type="text" name="parameter[value][]" value="'.$parameter['value'].'"></div>';
									$out.= '</div>';
								}	
								$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="parameter[valueName][]"></div>';
								$out.= '<div class="ui-block-b"><input placeholder="Value" type="text" name="parameter[value][]"></div>';
								$out.= '<div class="ui-block-c"><input type="button" onclick="plusParameterInput()" value="+"></div>';
							$out.= '</div>';
						$out.= '</div>';
					}
					break;
					
				/*
				 * Area
				 */					
					case 'areaDesc': 
						$out = '<legend>'.$langs->trans("LabelareaDesc").': '.$this->tooltip($type.'tool', $langs->trans("LabelareaDescDesc")).'</legend>';
						$out.= '<input placeholder="areaDesc" type="text" name="areaDesc" id="areaDesc" value="'.$this->areaDesc[0].'">';
						break;
	
					case 'polygon': 
						$out = '<legend>'.$langs->trans("Labelpolygon").': '.$this->tooltip($type.'tool', $langs->trans("LabelpolygonDesc")).'</legend>';	
						$out.= '<input placeholder="polygon" type="text" name="polygon" id="polygon" value="'.$this->polygon[0][0].'">';
						break;
	
					case 'circle': 
						$out = '<legend>'.$langs->trans("Labelcircle").': '.$this->tooltip($type.'tool', $langs->trans("LabelcircleDesc")).'</legend>';	
						$out.= '<input placeholder="circle" type="text" name="circle" id="circle" value="'.$this->circle[0][0].'">';
						break;
						
					case 'map':
							$out = '<div id="map" style="height: 480px;" class="map"></div>';
							$out.= '<div id="mapinfo" class="mapinfo">';								
								$out.='<ul data-role="listview">';
									$out.='<li>';
										$out.='<label for="dragCircle">'.$langs->trans("Labelpolygon").':</label><select name="drawPolygon" id="drawPolygon" data-role="slider" data-theme="b" data-mini="true"><option value="0">Off</option><option value="1">On</option></select>';
									$out.='</li>';
									$out.='<li>';
										$out.='<label for="dragCircle">'.$langs->trans("Labelcircle").':</label><select name="dragCircle" id="dragCircle" data-role="slider" data-theme="b" data-mini="true"><option value="0">Off</option><option value="1">On</option></select>';
									$out.='</li>';
								$out.='</ul>';
							$out.= '</div>';
						break;
						
					case 'geocode':
						// $out.= $AreaCodesArray;
						foreach($AreaCodesArray as $key => $area_arr)
						{
							$S_Area[$area_arr['geocode']] = $area_arr['AreaCaption'];
							$G_Area[$area_arr['geocode']] = $area_arr['geotype'];
						}
						
						if(is_array($S_Area))
						{
							$out.= '<legend>'.$langs->trans("LabelGeocodeWebservice").': '.$this->tooltip($type, $langs->trans("LabelGeocodeWebserviceDesc")).'</legend>';							
							$out.= $this->buildSelectValueName('geocode[value][]', 'geocode[valueName][]', 'geocode',$S_Area, $G_Area, $this->geocode[0]);
						}
						else
						{
							$out = '<div id="Geocodeappend">';
								$out.= '<legend>'.$langs->trans("LabelGeocode").': '.$this->tooltip($type, $langs->trans("LabelGeocodeDesc")).'</legend>';	
								foreach($this->geocode[0] as $key => $geocode)
								{
									$out.= '<div class="ui-grid-b">';
										$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="geocode[valueName][]" value="'.$geocode['valueName'].'"></div>';
										$out.= '<div class="ui-block-b"><input placeholder="geocode Value" type="text" name="geocode[value][]" value="'.$geocode['value'].'"></div>';
									$out.= '</div>';
								}	
								$out.= '<div class="ui-grid-b">';
									$out.= '<div class="ui-block-a"><input placeholder="Valuename" type="text" name="geocode[valueName][]"></div>';
									$out.= '<div class="ui-block-b"><input placeholder="Value" type="text" name="geocode[value][]"></div>';
									$out.= '<div class="ui-block-c"><input type="button" onclick="plusGeocodeInput()" value="+"></div>';
								$out.= '</div>';							
							$out.= '</div>';
						}
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
							$out = '<legend>'.$langs->trans("Labelcap_output").': '.$this->tooltip($type.'tool', $langs->trans("Labelcap_outputDesc")).'</legend>';
							$out.= '<input type="text" placeholder="Cap Output" name="conf[cap][output]" value="'.$conf->cap->output.'">';
						break;
					
					case 'conf_output':
							$out = $langs->trans("LabelOutputOfTheConf").': <input type="text" placeholder="Cap Output" name="conf[conf][output]" value="'.$conf->conf->output.'">';
						break;	
						

					case 'ID_ID':
							$out = $langs->trans("LabelIdentifierNumber").': <input type="number" placeholder="Identifier Number" name="conf[identifier][ID_ID]" value="'.$conf->identifier->ID_ID.'">';
						break;
					case 'WMO_OID':
							$out = '<legend>'.$langs->trans("LabelWMO_OID").': '.$this->tooltip($type.'tool', $langs->trans("LabelWMO_OIDDesc")).'</legend>';
							$out.= '<input type="text" placeholder="WMO OID" name="conf[identifier][WMO_OID]" value="'.$conf->identifier->WMO_OID.'">';
						break;
						
					case 'ISO':
						$out = $langs->trans("LabelISO").': <input type="text" maxsize="2" placeholder="ISO" name="conf[identifier][ISO]" value="'.$conf->identifier->ISO.'">'; 
					 break;
					 
					case 'identifier_time':
						if($conf->identifier->time->on == 1) $onoroff = 'checked=""';
						else $onoroff = '';
						$out = '<legend>'.$langs->trans("LabelAutomaticIdentifierTime").': '.$this->tooltip($type.'tool', $langs->trans("LabelAutomaticIdentifierTimeDesc")).'</legend>';
						//$out = '<label for="identifier_time">'.$langs->trans("LabelAutomaticIdentifierTime").':</label>';
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
						
					case 'lang_conf_use':
							$out = '<label for="lang_conf_use">'.$langs->trans("Labellang_conf_use").':</label>';
							$out.= '<select name="conf[user][lang]" id="lang_conf_use" data-native-menu="false" data-iconpos="left">';
							foreach($conf->trans as $key => $lang_name)
							{
								if($conf->user->lang != $key)
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
								//$out.='<label for="sent[date]">'.$langs->trans("LabelAddLanguage").': </label>';
								$out.= '<legend>'.$langs->trans("Labellang_conf_plus").': '.$this->tooltip($type.'tool', $langs->trans("Labellang_conf_plusDesc")).'</legend>';
								$out.= '<div class="ui-grid-b">';
									$out.= '<div class="ui-block-a"><input type="text" maxsize="5" placeholder="RFC 3066" name="conf[lang][key]" id="lang_conf_plus_key"></div>';
									$out.= '<div class="ui-block-b"><input type="text" name="conf[lang][name]" placeholder="Name" id="lang_conf_plus_name"></div>';
									$out.= '<div class="ui-block-c" style="width: 54px;"><input type="button" onclick="plusLangInput()" value="+" data-theme="b"></div>';
									$out.= '</div>';
							$out.= '</div>';
						break;
						
					case 'lang_conf_remove':
							$out = '<div id="LangRappend">';
								//$out.='<label for="sent[date]">'.$langs->trans("LabelRemoveLanguage").': </label>';
								$out.= '<legend>'.$langs->trans("Labellang_conf_remove").': '.$this->tooltip($type.'tool', $langs->trans("Labellang_conf_removeDesc")).'</legend>';
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
							$out = '<label for="identifier_time">'.$langs->trans("Webservice").':</label>';
							$out.= '<input type="checkbox" data-role="flipswitch" name="conf[webservice][on]" id="identifier_time" '.$onoroff.' data-theme="b">';
						break;
								
					case 'webservice_password':
							$out = $langs->trans("Labelwebservice_password").':<input type="text" name="conf[webservice][password]" value="'.$conf->webservice->password.'">';
						break;
						
					case 'webservice_securitykey':
							$out = $langs->trans("Labelwebservice_securitykey").':<input type="text" name="conf[webservice][securitykey]" value="'.$conf->webservice->securitykey.'">';
						break;
						
					case 'webservice_sourceapplication':
							$out = $langs->trans("Labelwebservice_sourceapplication").':<input type="text" name="conf[webservice][sourceapplication]" value="'.$conf->webservice->sourceapplication.'">';
						break;
						
					case 'webservice_login':
							$out = $langs->trans("Labelwebservice_login").':<input type="text" name="conf[webservice][login]" value="'.$conf->webservice->login.'">';
						break;
						
					case 'webservice_entity':
							$out = $langs->trans("Labelwebservice_entity").':<input type="text" name="conf[webservice][entity]" value="'.$conf->webservice->entity.'">';
						break;
						
					//case 'webservice_destination':
					//		$out.= '<legend>'.$langs->trans("Labelwebservice_destination").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_destinationDesc")).'</legend>';
					//		$out.= '<input type="text" name="conf[webservice][destination]" value="'.$conf->webservice->destination.'">';
					//	break;
						
					case 'webservice_WS_METHOD':
							$out = $langs->trans("webservice_WS_METHOD").':<input type="text" name="conf[webservice][WS_METHOD]" value="'.$conf->webservice->WS_METHOD.'">';
						break;
						
					case 'webservice_ns':
							$out = '<legend>'.$langs->trans("Labelwebservice_ns").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_nsDesc")).'</legend>';
							$out.= '<input type="text" name="conf[webservice][ns]" value="'.$conf->webservice->ns.'">';
						break;
						
					case 'webservice_WS_DOL_URL':
							$out = '<legend>'.$langs->trans("Labelwebservice_WS_DOL_URL").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_WS_DOL_URLDesc")).'</legend>';
							$out.= '<input type="text" name="conf[webservice][WS_DOL_URL]" value="'.$conf->webservice->WS_DOL_URL.'">';
						break;
						
					case 'capview':
							$out = '<textarea id="capviewtextarea"></textarea>';
						break;
						
					case 'caplist':
						$out = '</form><form method="POST" id="capform2" name="capform2" action="index.php?read=1" enctype="multipart/form-data" data-ajax="false">';
						$out.= '<input type="file" name="uploadfile" id="uploadfile"><input type="submit" value="'.$langs->trans('LabelUpload').'" name="upload" data-ajax="false">';
						$out.= '<fieldset data-role="controlgroup">';
						
								foreach(scandir($conf->cap->output) as $num => $capfilename)
								{
									if($num > 1)
									{
										$out.= '<div class="ui-grid-a">';
											$out.= '<div class="ui-block-a" style="width:90%"><input type="radio" name="location" id="cap_file_'.$num.'" value="'.urlencode($capfilename).'">';
											$out.= '<label for="cap_file_'.$num.'">'.$capfilename.'</label></div>';
											$out.= '<div class="ui-block-b" style="width:10%"><a href="#cap_file_'.$num.'_delete" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini"><span style="color:#ff0000">X</span></a></div>';
										$out.= '</div>';
										
										$out.= '<div data-role="popup" id="cap_file_'.$num.'_delete" data-theme="a" data-overlay-theme="b" class="ui-content" style="max-width:340px; padding-bottom:2em;">';
											$out.= '<h3>Delete File?</h3>';
											$out.= '<a href="index.php?delete='.urlencode($capfilename).'" data-ajax="false" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini">Delete</a>';
											$out.= '<a href="#" data-rel="back" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini">Cancel</a>';
										$out.= '</div>';
									}
								}
						$out.= '</fieldset>';
						$out.= '<input type="submit" value="<h1>'.$langs->trans("Read").'</h1>" data-ajax="false">';
						$out.= '</form><form method="POST" id="capform" name="capform" action="index.php" enctype="multipart/form-data" data-ajax="false">';
						break;
					/*
					 * Default
					 */
					default:
							
							$out = '<div>';
								$out.= '<label for="'.$type.'">'.$langs->trans("Label".$type).': '.$this->tooltip($type, $langs->trans("Info".$type)).'</label>';
								$out.= '<input type="text" placeholder="'.$type.'" name="'.$type.'">';
							$out.= '</div>';
						break;
			}
			//$out.= $this->InputStandard('sent');
			return $out;
		}
		
		/**
		 * Output HTML Info field
		 *
		 * @param string $name 						The name in the info field
		 * @param string $info 						The info in the info field
		 * @return string 								HTML select field
		 */
		 
		 function tooltip($name, $info, $alttext='ToolboxInfo')
		 {
		 		$out.= '<a href="#'.$name.'" data-rel="popup" data-transition="pop" class="my-tooltip-btn ui-btn ui-alt-icon ui-nodisc-icon ui-btn-inline ui-icon-info ui-btn-icon-notext" title="'.$alttext.'">'.$name.'</a>';
		 		$out.= '<div data-role="popup" id="'.$name.'" class="ui-content" data-theme="a" style="max-width:100%;">';
				$out.= $info;
				$out.= '</div>';
			
			return $out;
		 }
		
		var $script = "";
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
		function buildSelectValueName($name, $name2, $name3, $S_Area, $G_Area, $select = array())
		{
			$out = '<select name="'.$name.'" id="'.$name3.'-select" data-native-menu="false" multiple="multiple">';
				$out.='<option></option>';							
				foreach($S_Area as $data_val => $data_name)
				{
					$sel = false;
					foreach($select as $key => $select_code	) 
					{
						if($select_code['value'] == $data_val)
						{
							$sel = true;
						}
					}
					
					if($sel == true)
					{
						$out.= '<option '.$style_color.' value="'.$data_val.'" selected>';
					}
					else
					{
						$out.= '<option '.$style_color.' value="'.$data_val.'">';
					}
	
					$out.= $data_name;
					$out.= '</option>';
				}							
			$out.= '</select>';
			
			foreach($S_Area as $data_val => $data_name)
			{
				$out.= '<input type="checkbox" name="'.$name2.'" value="'.$G_Area[$data_val].'" id="'.$data_name.'" style="display: none;">';
			}
			
			$this->script.= 	'
							$( document ).ready(function() 
							{							
								var res = $( "#'.$name3.'-select" ).val();
								if($.isArray(res))
								{
								  var data = "";
								  $("input[name=\''.$name2.'\']").prop("checked", false);								  

									$.each( res, function( i, val ) {
										  $( \'#\' + val).prop("checked", true);
									});
								}
							});
							
							$( "#'.$name3.'-select" ).change(function() {
							
							  var res = $( "#'.$name3.'-select" ).val();
							  var data = "";
							  $(\'input[name="'.$name2.'"]\').prop("checked", false);
							  
							  $.each( res, function( i, val ) {
								  $(val).prop("checked", true);
								});
								
							});											
							';
						
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
		function buildSelect($name= "", $data = array(), $option = "", $placeholder = "", $selected="", $empty=0)
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
					if($selected == $data_val)
					{
						$out.= '<option value="'.$data_val.'" selected>';
					}
					else
					{
						$out.= '<option value="'.$data_val.'">';
					}
					$out.= $data_name;
					$out.= '</option>';
				}
			
			$out.= '</select>';
			return $out;
		}
		
		function make_cap_time($time)
		{
			$time_arr = explode("T", $time);
			$ctime['date'] = $time_arr[0];
			$time_arr_time = explode("+", $time_arr[1]);
			$ctime['time'] = $time_arr_time[0];
			$ctime['zone'] = $time_arr_time[1];
			
			return $ctime;
		}
	
		/**
     * encrypt and decrypt function for passwords
     *     
     * @return	string
     */
		function encrypt_decrypt($action, $string, $key) 
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
		
		/**
     * Output RFC 3066 Array
     *     
     * @return	string						Array with RFC 3066 Array
     */
		function getlang($config = false){
			global $conf;
			
			if(is_array($this->language))
			{
				foreach($this->language as $key => $lang_name)
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
		
		/**
     * Output Type Array
     *
     * @return	array						Array with the first CAP 1.1 entery's
     */
		function Types()
		{
			//$type['main'][] = "CapButton";
			
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
      $type['alert']['detail'][] = "expires";
      
			$type['alert']['detail'][] = "eventCode";
			$type['alert']['detail'][] = "parameter";
			
			$type['alert']['detail'][] = "source";
			$type['alert']['detail'][] = "restriction";
			$type['alert']['detail'][] = "addresses";
			$type['alert']['detail'][] = "code";
			$type['alert']['detail'][] = "note";
			$type['alert']['detail'][] = "incidents";
			
			// Info Page	
			//$type['info'][] = "info";
			$type['info'][] = "lang";
			$type['info'][] = "event";	
			$type['info'][] = "senderName";
			$type['info'][] = "sender";		
			$type['info'][] = "audience";
			$type['info'][] = "contact";
			$type['info'][] = "web";

			// Area Page
			$type['area'][] = "areaDesc";
			$type['area'][] = "geocode";
			$type['area'][] = "polygon";
			$type['area'][] = "circle";
			$type['area'][] = "map";			

			// conf Page	
			$type['conf'][] = "cap_save";
			$type['conf'][] = "cap_output";
			
			// $type['conf'][] = "conf_output";
					
			$type['conf'][] = "WMO_OID";
			$type['conf'][] = "ISO";			
			$type['conf'][] = "ID_ID";
			$type['conf'][] = "identifier_time";
			
			$type['conf'][] = "lang_conf_use";
			$type['conf'][] = "lang_conf_plus";
			$type['conf'][] = "lang_conf_remove";
			$type['conf'][] = "lang_conf";
			
			/* TO DO */
			$type['conf'][] = "webservice_on";
			$type['conf']['detail'][] = "webservice_securitykey";
			$type['conf']['detail'][] = "webservice_sourceapplication";
			$type['conf']['detail'][] = "webservice_login";
			$type['conf']['detail'][] = "webservice_password";
			$type['conf']['detail'][] = "webservice_entity";
			//$type['conf']['detail'][] = "webservice_destination";
			$type['conf']['detail'][] = "webservice_WS_METHOD";
			$type['conf']['detail'][] = "webservice_ns";
			$type['conf']['detail'][] = "webservice_WS_DOL_URL";
			
			
			$type['capview'][] = 'capview';
			
			$type['read'][] = 'caplist';

      return $type;
		}
		
		function Pages()
		{
			global $langs;
			//$pages['#main'] 					= $langs->trans("TitleMain");
			
			$pages['#alert'] 					= $langs->trans("TitleAlert");
			//$pages['alert']['next'] = 'info';
			
			$pages['#info']  					= $langs->trans("TitleInfo");
			//$pages['info']['next'] 	= 'area';
			
			$pages['#area']  					= $langs->trans("TitleArea");
			//$pages['area']['next'] 	= 'capview';
			
			$pages['#capview'] 		 		= $langs->trans("TitleCapView");
			//$pages['conf']['send'] 	= true; 
			
			$pages['#read'] 		 		= $langs->trans("TitleCapList");
			//$pages['conf']['send'] 	= true; 
			
			$pages['?conv=1#capconv']	= $langs->trans("TitleCapConv");
			$pages['noajax'][]				= '?conv=1#capconv';
			
			$pages['#conf']  					= $langs->trans("TitleConfig");
			
						
			return $pages;
		}
		
	  /**
     * Output Html Head
     *
     * @return	string						HTML Head
     */
		function Header_llx()
		{			
			$out = '<head>';
				$out.= '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery.min.js"></script>';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery-ui.min.js"></script>';
				$out.= '<link rel="stylesheet" type="text/css" href="css/cap_form.css">';
				
				$out.= '<link rel="icon" type="image/png" href="conf/logo.jpg">';
				
				$out.= '<script type="text/javascript" src="js/form.js"></script>';
				$out.= '<script type="text/javascript" src="js/map.js"></script>';
			
				$out.= '<link rel="stylesheet" href="includes/jquery.mobile/jquery.mobile-1.4.5.min.css" />';
				$out.= '<script src="includes/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>';
			
				$out.= '<link rel="stylesheet" href="css/BackboneMobile.css" />';
 				$out.= '<link rel="stylesheet" href="css/jquery.mobile.icons.min.css" />';
 				
 				// OpenStreetMap
				$out.= '<script src="includes/jquery/jquery.geo.min.js"></script>';
				
				// Clockpicker Addon
				$out.= '<link rel="stylesheet" type="text/css" href="includes/plugin/jquery-clockpicker.min.css">';
				$out.= '<script type="text/javascript" src="includes/plugin/jquery-clockpicker.min.js"></script>';
				
				$out.= '<title>Cap Creator</title>';

			$out.= '</head>';
			
			return $out;
		}
		
				
		/**
     * Output Html Form
     *
     * @return	string						HTML Form
     */
		function Form()
		{
			global $langs;
			
			$out = $this->Header_llx();
			
			$out.= '<body>';			
			$out.= '<form method="POST" id="capform" name="capform" action="index.php" enctype="multipart/form-data" data-ajax="false">';
			$out.= '<input type="hidden" name="action" value="create">';

					$Type_arr = $this->Types();
					foreach($Type_arr as $pagename => $TypePage)
					{
						$out.= '<div data-role="page" id="'.$pagename.'">';
						
							$out.= '<div data-role="panel" data-display="push" id="'.$pagename.'_panel">';
    						$out.= '<!-- panel content goes here -->';
    						$out.= '<ul data-role="listview">';
    							
    							$out.= '<li style="height: 91px;">';
    								$out.= '<img src="conf/logo.jpg" style="border: 1px solid black;border-radius: 45px;width: 20%;margin: 10px 0px 0px 10px;">';
    								$out.= '<h1>';
    									$out.= 'Cap Creator';
    								$out.= '</h1>';
    								$out.= '<br>';
    								$out.= '<span style="font-size: 10px;">';
    									$out.= 'Cap v1.1';
    								$out.= '</span>';
    							$out.= '</li>';
    							
    							$Pages_arr = $this->Pages();
									foreach($Pages_arr as $link => $Page_Name)
									{
										if(in_array($link, $Pages_arr['noajax'])) $data = 'data-ajax="false"';
										if($link != 'noajax')
										{
											if($link == '#'.$pagename) 	$out.= '<li data-theme="b"><a href="'.$link.'" '.$data.'>'.$Page_Name.'</a></li>';
											else 										$out.= '<li><a href="'.$link.'" '.$data.'>'.$Page_Name.'</a></li>';
										}
										unset($data);
									}
									
								$out.= '</ul>';
							$out.= '</div>';
							
							$out.= '<div data-theme="b" data-role="header">';								
								$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
								$out.= '<h1>'.$Pages_arr['#'.$pagename].'</h1>';							
							$out.= '</div>';
							
							
							// Main
							$out.= '<div class="ui-content ui-page-theme-a" data-form="ui-page-theme-a" data-theme="a" role="main">';
								
								$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';									
									$out.= '<ul data-role="listview" data-divider-theme="b">';
									
									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">'.$Pages_arr['#'.$pagename].'</h1></li>';
									
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
								if($pagename == 'alert') 					$out.= '<ul data-role="listview" data-inset="true"><li><a href="#info"><h1>'.$langs->trans('Next').'</h1></a></li></ul>';
								if($pagename == 'info') 					$out.= '<ul data-role="listview" data-inset="true"><li><a href="#area"><h1>'.$langs->trans('Next').'</h1></a></li></ul>';
								if($pagename == 'area') 					$out.= '<ul data-role="listview" data-inset="true"><li><a href="#capview"><h1>'.$langs->trans('Next').'</h1></a></li></ul>';
								if($pagename == 'capview') 				$out.= '<input type="submit" value="'.$langs->trans("Submit").'" data-ajax="false">';
								if($pagename == 'conf') 					$out.= '<input class="ui-btn" type="button" value="'.$langs->trans('Save').'" onclick="ajax_conf()">';
								
									if($pagename == 'conf') $out.= '<div data-role="popup" id="Saved_conf" style="text-align: center; vertical-align: middle; width: 200px; height: 40px; background: rgba(4, 255, 0, 0.65); color: #000; font-size: 22px; padding: 10px 0px 0px 0px; text-shadow: 0px 0px 0px #000;">';

									if($pagename == 'conf') $out.= $langs->trans('Saved!');
									if($pagename == 'conf') $out.= '</div>';
							
							$out.= '</div>';
							
						$out.= '</div>';
					}

			$out.= '</form>';
			
			$out.= '<script>
							'.$this->script.'
							</script>';
			
			$out.= '</body>';
			$out.= 
			'
			<script>					
				';
					
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
			global $conf, $langs;
			
			$out = $this->Header_llx();
			
			$out.= '<body>';			
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?webservice=1" enctype="multipart/form-data" data-ajax="false">';
					$out.= '<input type="hidden" name="filename" value="'.$ID.'">';
					$out.= '<div data-role="page" id="capview">';
					
						$out.= '<div data-theme="b" data-role="header">';								
							//$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
							$out.= '<a href="index.php" data-ajax="false" data-theme="b" class="ui-btn ui-icon-delete ui-btn-icon-notext" style="border: none;">'.$langs->trans("Cancel").'</a>';
							$out.= '<h1>'.$ID.'.cap</h1>';						
						$out.= '</div>';
					
						$out.= '<div role="main" class="ui-content">';							
										
							$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';	
								$out.= '<ul data-role="listview" data-divider-theme="b">';
									
									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">'.$langs->trans("LabelCapViewOf").': '.$ID.'.cap</h1></li>';	
									
									if($conf->cap->save == 1) $out.= '<li><a href="'.$conf->cap->output.'/'.$ID.'.cap" download data-ajax="false">Download '.$ID.'.cap</a></li>';
									
									if($conf->webservice->on == 1) $out.= '<li><input type="submit" value="<h1>'.$langs->trans("sendviaSoap").'</h1>" data-ajax="false"></li>';
									
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
		
		function ListCap()
		{
			global $conf, $langs;
			
			$out = $this->Header_llx();
			
			$out.= '<body>';			
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?conv=1" enctype="multipart/form-data" data-ajax="false">';
					$out.= '<div data-role="page" id="capview">';
					
							$out.= '<div data-role="panel" data-display="push" id="'.$pagename.'_panel">';
    						$out.= '<!-- panel content goes here -->';
    						$out.= '<ul data-role="listview">';
    							
    							$out.= '<li style="height: 91px;">';
    								$out.= '<img src="conf/logo.jpg" style="border: 1px solid black;border-radius: 45px;width: 20%;margin: 10px 0px 0px 10px;">';
    								$out.= '<h1>';
    									$out.= 'Cap Creator';
    								$out.= '</h1>';
    								$out.= '<br>';
    								$out.= '<span style="font-size: 10px;">';
    									$out.= 'Cap v1.1';
    								$out.= '</span>';
    							$out.= '</li>';
    							
    							$Pages_arr = $this->Pages();
									foreach($Pages_arr as $link => $Page_Name)
									{
										if(!in_array($link, $Pages_arr['noajax'])) $data = 'data-ajax="false"'; // turn all links to ajax off (when not jquery can not link to the other pages)
										if($link != 'noajax')
										{
											if($link == '?conv=1#capconv') 	$out.= '<li data-theme="b"><a href="'.$link.'" '.$data.'>'.$Page_Name.'</a></li>';
											else 														$out.= '<li><a href="'.$_SERVER[PHP_SELF].$link.'" '.$data.'>'.$Page_Name.'</a></li>';
										}
										unset($data);
									}
									
								$out.= '</ul>';
							$out.= '</div>';
							
							$out.= '<div data-theme="b" data-role="header">';								
								$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
								$out.= '<h1>Cap Converter</h1>';							
							$out.= '</div>';
					
						$out.= '<div role="main" class="ui-content">';							
										
							$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';	
								
								// get all convert files
								$std_tmp = scandir('convert/');								
								foreach($std_tmp as $num => $filename)
								{
									if(substr($filename, 0, 4) != "std_") 
									{
										unset($std_tmp[$num]);
									}
									else
									{
										$std_converter[substr($filename, 4, -9)] = substr($filename, 4, -9);
									}
								}
								
								$area_tmp = scandir('convert/');								
								foreach($area_tmp as $num => $filename)
								{
									if(substr($filename, 0, 5) != "area_") 
									{
										unset($area_tmp[$num]);
									}
									else
									{
										$area_converter[substr($filename, 5, -9)] = substr($filename, 5, -9);
									}
								}

								
								$std = $this->buildSelect("stdconverter", $std_converter, "data-native-menu=\"false\"", "", "standard");
								$area = $this->buildSelect("areaconverter", $area_converter, "data-native-menu=\"false\"", "", "standard"); 
								
									$out.= '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" id="converter">';
										$out.= '<legend>'.$langs->trans("SelectStdAreaCap").': '.$this->tooltip('SelectStdAreaCaptool', $langs->trans("InfoSelectStdAreaCap")).'</legend>';							
											$out.= $std;					
											$out.= $area;							
									$out.= '</fieldset>';
								
								// get all convert files
								$converter_tmp = scandir('convert/');								
								foreach($converter_tmp as $num => $filename)
								{
									if(substr($filename, 0, 5) != "conv_") 
									{
										unset($converter_tmp[$num]);
									}
									else
									{
										$converter[substr($filename, 5, -9)] = substr($filename, 5, -9);
									}
								}
								
								$input = $this->buildSelect("inputconverter", $converter, "data-native-menu=\"false\"", "", "standard");
								$output = $this->buildSelect("outputconverter", $converter, "data-native-menu=\"false\"", "", "standard"); 
								
									$out.= '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" id="converter">';
										$out.= '<legend>'.$langs->trans("SelectInputandOutputCap").': '.$this->tooltip('SelectInputandOutputCaptool', $langs->trans("InfoSelectInputandOutputCap")).'</legend>';							
											$out.= $input;					
											$out.= $output;							
									$out.= '</fieldset>';
									
									
									$out.= '<input type="file" name="uploadfile" id="uploadfile">';
									// or								
									$out.= '<fieldset data-role="controlgroup">';
										$out.= '<legend>'.$langs->trans("SelectCaps").':</legend>';
											foreach(scandir($conf->cap->output) as $num => $capfilename)
											{
												if($num > 1)
												{
													$out.= '<input type="radio" name="location" id="cap_file_'.$num.'" value="'.urlencode($capfilename).'">';
													$out.= '<label for="cap_file_'.$num.'">'.$capfilename.'</label>';
												}
											}			
									$out.= '</fieldset>';
									
									$out.= '<input type="submit" value="<h1>'.$langs->trans("Convert").'</h1>" data-ajax="false">';
									
							$out.= '</div>';

						$out.= '</div><!-- /content -->';
					
						$out.= '<div data-role="footer" data-theme="b">';

						$out.= '</div><!-- /footer -->';
						
					$out.= '</div><!-- /page -->';
			
				$out.= '</form>';			
			$out.= '</body>';
			
			return $out;
		}
		
		function Webservice($ID)
		{
			global $conf, $langs, $out;
			
			$out = $this->Header_llx();
			
			$out.= '<body>';			
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?conf=1" enctype="multipart/form-data" >';
					/**
					 *
					 *  WEBSERVICE
					 *
					 */
					 if($conf->webservice->on == 1)
					 {
					 	
					 	$out.= '<div data-role="page" id="capview">';
							
							// HEADER
							$out.= '<div data-theme="b" data-role="header">';								
								//$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
								$out.= '<a href="index.php" data-ajax="false" data-theme="b" class="ui-btn ui-icon-delete ui-btn-icon-notext" style="border: none;">'.$langs->trans("Cancel").'</a>';
								$out.= '<h1>'.$ID.'.cap</h1>';						
							$out.= '</div>';
						
							// MAIN
							$out.= '<div role="main" class="ui-content">';							
											
								$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';	
									
									// decryp password
									$conf->webservice->password = $this->encrypt_decrypt(2, $conf->webservice->password);
									
									include("lib/cap.webservices.php");
									
									$conf->webservice->password = $this->encrypt_decrypt(1, $conf->webservice->password);
									
								$out.= '</div>';
	
							$out.= '</div><!-- /content -->';
						
							// FOOTER
							$out.= '<div data-role="footer" data-theme="b">';
	
							$out.= '</div><!-- /footer -->';
							
						 $out.= '</div><!-- /page -->';
					 }
					/**
					 *
					 *  WEBSERVICE
					 *
					 */
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
			if(!empty($post[identifier][WMO]) || ! empty($post[identifier][ISO]) || ! empty($post[identifier][time])) 
			{
				$temp = "";
				$i = 0;
				foreach($post[identifier] as $id_val)
				{
					if($i == 0)
					{
						$temp.= $id_val;
					}
					else
					{
						$temp.= ".".$id_val;					
					}
					if(!empty($id_val)) $i++;
				}
				
				unset($post[identifier]);
				$post[identifier] = $temp;
			}
			else
			{
				$temp = $post[identifier][ID];
				unset($post[identifier]);
				$post[identifier] = $temp;
			}
			return $post;
		}
		
		/**
		 * Function to install the interface of the Cap PHP library
		 *
		 * @return	string 	$out
		 */
		function install()
		{
			
			$out = $this->Header_llx();
			
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
			
			if(! is_dir($post['cap']['output']))
			{
				mkdir($post['cap']['output'], 0774);				
			}
			
			if(! is_dir($post['conf']['output']))
			{
				mkdir($post['conf']['output'], 0774);
			}
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
			
			if($post['webservice']['on'] == "on")
		 	{
			 	$conf->webservice->on = 1;
			}	
			else
			{
			 	$conf->webservice->on = 0;
			}		
			unset($post['webservice']['on']);
			
			// crypt pass
			if($conf->webservice->password == $post['webservice']['password']) 
			{
				
			}
			else
			{
				$conf->webservice->password = $this->encrypt_decrypt(1, $post['webservice']['password']);
				unset($post['webservice']['password']);
			}
			
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
				$conf_file = fopen("conf/conf.php", "w") or print("Unable to open conf!");
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