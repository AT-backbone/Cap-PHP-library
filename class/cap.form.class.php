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
 *	\file      	cap.form.class.php
 *  \ingroup   	Form
 *	\brief      File of class with all html predefined components
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
			switch($type)
			{
				case 'identifier':
					$out = '<input placeholder="identifier" type="text" size="120" name="identifier" required>';
					break;
					
				case 'sender':
					$out = '<input placeholder="sender" type="text" name="sender" required>';
					break;
					
				case 'sent':
					$out = '<input type="date" name="sent[date]" required><input type="time" name="sent[time]" step="1" required> + <input type="time" name="sent[UTC]" required>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
					break;
				
				case 'status': // Actual / Test / Exercise / System / Test / Draft
					$out = $this->buildSelect("status", array( "Actual" => "Actual", "Test" => "Test", "Exercise" => "Exercise", "System" => "System", "Test" => "Test", "Draft" => "Draft" )); 
					break;
					
				case 'msgType': // Alert / Update / Cancel / Ack / Error
					$out = $this->buildSelect("msgType", array( "Alert" => "Alert", "Update" => "Update", "Cancel" => "Cancel", "Ack" => "Ack", "Error" => "Error" )); 
					break;
					
				case 'references': 
					$out = '<input placeholder="references" type="text" name="references">'; // web / identifier / sent 
					break;
					
				case 'scope': 
					$out = $this->buildSelect("scope", array( "Public" => "Public", "Restricted" => "Restricted", "Private" => "Private" ));  // Public / Restricted / Private
					break;
				
				/*
				 * Info
				 */
				
				case 'category': // Geo / Met / Safety / Security / Rescue / Fire / Health / Env / Transport / Infra / CBRNE / Other
					$out = $this->buildSelect("category", array( "Geo" => "Geo", "Met" => "Met", "Safety" => "Safety", "Security" => "Security", "Rescue" => "Rescue", "Fire" => "Fire", "Health" => "Health", "Env" => "Env", "Transport" => "Transport", "Infra" => "Infra", "CBRNE" => "CBRNE", "Other" => "Other" ));
					break;
					
				case 'event': 
					$out = '<input placeholder="event" type="text" name="event['.$lang.']" required>';
					break;
				
				case 'responseType': // Shelter / Evacuate / Prepare / Execute / Avoid / Monitor / Assess / AllClear / None
					$out = $this->buildSelect("responseType", array( "Shelter" => "Shelter", "Evacuate" => "Evacuate", "Prepare" => "Prepare", "Execute" => "Execute", "Avoid" => "Avoid", "Monitor" => "Monitor", "Assess" => "Assess", "AllClear" => "AllClear", "None" => "None" ));
					break;	

				case 'urgency': // Immediate / Expected / Future / Past
					$out = $this->buildSelect("urgency", array( "Immediate" => "Immediate", "Expected" => "Expected", "Future" => "Future", "Past" => "Past" ));
					break;	
					
				case 'severity': // Extreme / Severe / Moderate / Minor / Unknown
					$out = $this->buildSelect("severity", array( "Extreme" => "Extreme", "Severe" => "Severe", "Moderate" => "Moderate", "Minor" => "Minor", "Unknown" => "Unknown" ));
					break;		

				case 'certainty': // Observed / Likely / Possible/ Unlikely / Unknown
					$out = $this->buildSelect("certainty", array( "Observed" => "Observed", "Likely" => "Likely", "Possible" => "Possible", "Unlikely" => "Unlikely", "Unknown" => "Unknown" ));
					break;		

				case 'audience': 
					$out = '<input placeholder="audience" type="text" name="audience">';
					break;
					
				case 'eventCode': 
					$out = '<input placeholder="eventCode Valuename" type="text" name="eventCode[valueName][]"><input placeholder="eventCode Value" type="text" name="eventCode[value][]"><input type="button" onclick="plusInput(\'eventCode\')" value="+">';
					break;
				
				case 'effective': 
					$out = '<input type="date" name="effective[date]"><input type="time" name="effective[time]" step="1"> + <input type="time" name="effective[UTC]">'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
					break;

				case 'onset': 
					$out = '<input type="date" name="onset[date]"><input type="time" name="onset[time]" step="1"> + <input type="time" name="onset[UTC]">'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
					break;
					
				case 'expieres': 
					$out = '<input type="date" name="expieres[date]"><input type="time" name="expieres[time]" step="1"> + <input type="time" name="expieres[UTC]">'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
					break;

				case 'senderName': 
					$out = '<input placeholder="senderName" type="text" name="senderName">'; 
					break;
					
				case 'headline': 
					$out = '<input placeholder="headline" type="text" name="headline['.$lang.']">'; 
					break;

				case 'description': 
					$out = '<input placeholder="description" type="text" name="description['.$lang.']">';
					break;

				case 'instruction': 
					$out = '<input placeholder="instruction" type="text" name="instruction['.$lang.']">'; 
					break;
					
				case 'web': 
					$out = '<input placeholder="web" type="text" name="web">'; 
					break;
					
				case 'contact': 
					$out = '<input placeholder="contact" type="text" name="contact">'; 
					break;

				case 'parameter': 
					$out = '<input placeholder="parameter Valuename" type="text" name="parameter[valueName][]"><input placeholder="parameter Value" type="text" name="parameter[value][]"><input type="button" onclick="plusInput(\'parameter\')" value="+">';
					break;
					
				/*
				 * Area
				 */
					
					case 'areaDesc': 
						$out = '<input placeholder="areaDesc" type="text" name="areaDesc" required>';
						break;
	
					case 'polygon': 
						$out = '<input placeholder="polygon" type="text" name="polygon">';
						break;
	
					case 'circle': 
						$out = '<input placeholder="circle" type="text" name="circle">';
						break;
						
					case 'geocode': // ]
						$out = '<input placeholder="geocode Valuename" type="text" name="geocode[valueName][]"><input placeholder="geocode Value" type="text" name="geocode[value][]"><input type="button" onclick="plusInput(\'geocode\')" value="+">';
						break;															

			}
			//$out.= $this->InputStandard('sent');
			return $out;
		}
		
		/**
     * Output Html select
     *
     * @param   string	$name			The POST/GET name of the select
     * @param   array		$data			the content of the select array("option value" => "option Name")
     * @param 	int 		$empty 		if 1 then make a empty option
     * @return	string						HTML select field
     */
		function buildSelect($name= "", $data = array(), $empty=0)
		{
			$out = '<select name="'.$name.'">';
			
				if($empty == 1)
				{
					$out.='<option></option>';
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
		function getlang(){
			
			$out['en-GB'] = 'english';
			$out['ca'] = 'català';
			$out['cs'] = 'ceština';
			$out['da-DK'] = 'dansk';			
			$out['de-DE'] = 'deutsch';			
			$out['es-ES'] = 'español';
			$out['et'] = 'eesti';			
			$out['eu'] = 'euskera';
			$out['fr-FR'] = 'français';
			$out['gl'] = 'galego';
			$out['hr-HR'] = 'hrvatski';
			$out['is'] = 'íslenska';
			$out['it-IT'] = 'italiano';
			$out['lt'] = 'lietuviu';
			$out['lv'] = 'latviešu';
			$out['hu-HU'] = 'magyar';
			$out['mt'] = 'malti';
			$out['nl-NL'] = 'nederlands';
			$out['no'] = 'norsk';
			$out['pl'] = 'polski';
			$out['pt-PT'] = 'português';
			$out['ro'] = 'româna';
			$out['sr'] = 'српски';
			$out['sl'] = 'slovenšcina';
			$out['sk'] = 'slovencina';
			$out['fi-FI'] = 'suomi';
			$out['sv-SE'] = 'svenska';
			$out['el-GR'] = 'Ελληνικά';
			$out['bg'] = 'bulgarian';
			$out['mk'] = 'македонски';
			
			return $out;
		}
		
		/**
     * Output Type Array
     *
     * @return	string						Array with the first CAP 1.2 entery's
     */
		function StaticTypes()
		{
			$type[] = "identifier";
      $type[] = "sender";
      $type[] = "sent";
      $type[] = "status";
      $type[] = "msgType";
      $type[] = "references";
     	$type[] = "scope";
      $type[] = "effective";
      $type[] = "onset";
      $type[] = "expieres";
      
      return $type;
		}
		
		/**
     * Output Type Array
     *
     * @return	string						Array with the info CAP 1.2 entery's
     */
		function LangTypes()
		{
			$type[] = "category";
			$type[] = "responseType";
			$type[] = "urgency";
			$type[] = "severity";
			$type[] = "certainty";
			$type[] = "audience";
			$type[] = "eventCode";
			$type[] = "senderName";
			$type[] = "web";
			$type[] = "contact";
			$type[] = "parameter";
			$type[] = "areaDesc";
			$type[] = "polygon";
			$type[] = "circle";
			$type[] = "geocode";
      
      return $type;
		}
				
		/**
     * Output Html Form
     *
     * @return	string						HTML Form
     */
		function Form()
		{
			$out = '<script type="text/javascript" src="jquery/jquery.min.js"></script>';
			$out.= '<script type="text/javascript" src="jquery/jquery-ui.min.js"></script>';
			$out.= '<link rel="stylesheet" type="text/css" href="cap_form.css">';
			
			$out.= '<form method="POST" id="capform" name="capform" action="cap.php" enctype="multipart/form-data" >';
			$out.= '<input type="hidden" name="action" value="create">';

				$out.= '<table width="100%">';
				
									
					$langs_arr = $this->getlang();
						
					$out.= '<tr id="troflang">';
						$out.= '<td colspan="6" id="tdoflang">';
						
							$langs= '<select id="langs" style="padding: 0px 0px 0px 7px;">';
								foreach($langs_arr as $key => $langs_val){
										$langs.= '<option value="'.$key.'">';
											$langs.= $langs_val;
										$langs.= '</option>';										
								}
							$langs.= '</select>';	
								
							$out.= $langs;			
							$out.= '<span id="plusSpeach">+ Language </span>';
						$out.= '</td>';
					$out.= '</tr>';					
					
					$extralang = "";
					foreach($langs_arr as $key => $langs_val)
					{
						$extralang.= '<tr id="'.$key.'" class="langs_text" style="display:none">';
							$extralang.= '<td colspan="6">';
									$extralang.= '<input placeholder="Event" type="text" name="event['.$key.']" style="width: 100%;">';
								$extralang.= '<br>';
									$extralang.= '<input placeholder="Header" type="text" name="headline['.$key.']" style="width: 100%;">';
								$extralang.= '<br>';
									$extralang.= '<textarea placeholder="Desciption" name="description['.$key.']" style="width: 100%; height: 150px;"></textarea>';
								$extralang.= '<br>';
									$extralang.= '<textarea placeholder="Instruction" name="instruction['.$key.']" style="width: 100%;margin-bottom: 10px;"></textarea>';
							$extralang.= '</td>';
						$extralang.= '</tr>';
					}
					
					$out.=$extralang;
					
					$out.= '<tr>';
						$out.= '<td colspan="6">';
							$out.= '<br>';
						$out.= '</td>';
					$out.= '</tr>';
			
					$StaticType_arr = $this->StaticTypes();
					foreach($StaticType_arr as $type)
					{
							$out.= '<tr id="'.$type.'DIV"><td>'.$type.': </td><td>'.$this->InputStandard($type).'</td></tr>';
					}

					$LangType_arr = $this->LangTypes();
					foreach($LangType_arr as $type)
					{
								$out.= '<tr id="'.$type.'DIV"><td>'.$type.': </td><td>'.$this->InputStandard($type).'</td></tr>';
					}
					
					$out.= '<tr>';
						$out.= '<td colspan="6">';
							$out.= '<br>';
						$out.= '</td>';
					$out.= '</tr>';
					
					$out.= '<tr>';
						$out.= '<td colspan="2" width="50%">';
							$out.= '<select name="output">';
								$out.= '<option value="cap">';
									$out.= 'Cap';
								$out.= '</option>';
								$out.= '<option value="xml">';
									$out.= 'Xml';
								$out.= '</option>';
							$out.= '</select>';							
							$out.= '<input type="button" onclick="updateCapXML()" name="update" value="update">';
							$out.= '<div readonly id="XmlCap">';
								$out.= '<textarea readonly id="XmlCapTA">'; 
									// Dynamic content
								$out.= '</textarea>';
							$out.= '</div>';
						$out.= '</td>';

						$out.= '<td colspan="3"  width="50%">';
							$out.= 'View';
							$out.= '<div id="view">';

							$out.= '</div>';
						$out.= '</td>';
					$out.= '</tr>';

				$out.= '</table>';
				$out.= '<span style="float:right;"><input type="submit" name="Submit" value="Submit"></span>';
				
			$out.= '</form>';
			
			$out.= 
			'
			<script>
				$( document ).ready(function() {
				
    			$("#plusSpeach").click(function(){
						$("#langs").before("<span class=\"spanlang taphide\" name=\""+$( "#langs option:selected" ).val()+"\" onclick=\"changeExLang(\'"+$( "#langs option:selected" ).val()+"\')\"><input name=\"language[]\" type=\"hidden\" value=\""+$( "#langs option:selected" ).val()+"\">"+$( "#langs option:selected" ).text()+" <span style=\"color:red;\" onclick=\"delExLang(\'"+$( "#langs option:selected" ).val()+"\')\">X</span></span>");
						
						if(changedExLang == 0)
						{
							$("#" + $( "#langs option:selected" ).val()).show();
							
							$( ".spanlang" ).each(function( index )
							{
								if(index == 0) $(this).removeClass( "taphide" ).addClass( "tapshow" );
								else $(this).removeClass( "tapshow" ).addClass( "taphide" );
							});
							
							changedExLang = 1;
							
						}
						
						$("#langs option:selected").attr("disabled", "disabled");
						$("#langs option:not(:disabled)").first().attr("selected", "selected");
						
						length = $("#langs > option").length;
						if(length == 0)
						{
							$("#langs").remove();
							$("#plusSpeach").remove();
						}
						
						
					});
					
					$( "input, select" ).change(function() {
						updateCapXML();
					});
					
				});
				
				var changedExLang = 0;
				function changeExLang(lang)
				{
						$( ".langs_text" ).each(function( index )
						{
							if($(this).attr("id") != lang)
							{
  							$(this).hide();	  						
  						}
						});
						
						$("#"+lang).show();	
						
						$( ".spanlang" ).each(function( index )
						{
							if( $(this).attr("name") == lang ){
								$(this).removeClass( "taphide" ).addClass( "tapshow" );
							}
							else
							{							
								$(this).removeClass( "tapshow" ).addClass( "taphide" );								
							}
						});
						
						
						changedExLang = 1;
				}
				
				function delExLang(lang)
				{
					$( ".spanlang" ).each(function( index )
					{
							if( $(this).attr("name") == lang )
							{
								$(\'#langs option[value="\' + lang + \'"]\').removeAttr("disabled");
								$(this).remove();
							}
					});
				}
				
				function updateCapXML()
				{
					var url = "cap.php?cap=1"; // the script where you handle the form input.
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	$("#XmlCapTA").text(data);
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function plusInput(type_v)
				{
					var url = "cap.php"; // the script where you handle the form input.
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: {type: type_v}, // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	$("#"+type_v+"DIV").after(data);
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				
				
			</script>';
			
			return $out;
		}
	}
	
?>