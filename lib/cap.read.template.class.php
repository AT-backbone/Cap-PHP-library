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
	
	//require_once 'cap.write.class.php'; // for the XML / CAP view
	class Read_CAP_TEMPLATE_Class
	{
		
		function output_template(){
		
			$error = array();
			$r = array();
	
			
			foreach($this as $key => $val){
				if($key == 'required') continue;
				if($key == 'subclass') continue;
				//if(!in_array($key,$this->required) && empty($val)) continue;
					if(is_object($val)){
						$r[$key] = $val->output_template();
					}elseif(is_array($val)){
						foreach($val as $k => $v){
							if(is_object($v)){
								$r[$key][$k] = $v->output_template();
							}else{
								$r[$key][$k] = $v;	
							}
						}
					}else
						$r[$key] = $val;
			}
			return $r;
		}
		
		/**
		 *    \Short-Desc     Retourne la liste déroulante des pays actifs, dans la langue de l'utilisateur
		 *    \param     selected         Id ou Code pays ou Libelle pays pré-sélectionné
		 *    \param     htmlname         Nom de la liste deroulante
		 *    \param     htmloption       Options html sur le select
		 *    \todo      trier liste sur noms après traduction plutot que avant
		 */
		function formError_template($Error_Num="",$Error_Process="",$Error_function="",$Error_Log="",$Error_Tipp="")
		{
			/* ============================================================================== */
			/* 									    Error style                               */
			/* ============================================================================== */
			//z.b: 		$notavailable = $html->formataError(505,"CapById.php","SQL","Warning is no longer available!","");
			//result:   Error[505]: CapById.php | SQL | Error-log: Warning is no longer available!
			$anfang = '<div style="font-family:Batang;font-size:17px;color:#CC3300;padding-left:30px;">';
	
			if($Error_Num!="") 		$Error = 'Error['.$Error_Num.']:';
			if($Error_Process!="") 	$Error.= ' '.$Error_Process; 
			if($Error_function!="") $Error.= ' | '.$Error_function; 
			if($Error_Log!="") 		$Error.= ' | Error-log: '.$Error_Log;
			if($Error_Tipp!="") 	$Error.= ' | Error-help: '.$Error_Tipp;
			$ende = '</div>';
			$rückgabe = $anfang.$Error.$ende;
			return $rückgabe;
		}
		
		function buildFromArray_template($a,$key=''){
			foreach($a as $k => $v){
			if($key) $k = $key;
				
				if(is_array($v) ){
					$this->buildFromArray_template($v,$k);
				}elseif(isset($this->subclass[$k])){
					$class = $this->subclass[$k];
					$nc = new $class();
					
					$nc->buildFromArray_template($v);
				
					$this->{'_set'.ucfirst($k)}( $nc );
				}else{
					$this->{'_set'.ucfirst($k)}( trim((string) $v) );
				}
		
					
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
	
	}

	class alert_template extends Read_CAP_TEMPLATE_Class{
			/*
				CAP Alert Message version 1.2
			*/
			//protected $required = array('identifier', 'sender', 'sent', 'status', 'msgType', 'scope');
			protected $subclass = array('info'=>'info_template');
			var   $identifier;
			var   $sender;
			var   $sent;
			var   $msgType;
			var   $source;
			var   $scope;
			var   $restriction;
			var   $addresses;
			private $codeIndex = 0;
			var   $code = array();
			var   $note;
			var   $references;
			var   $incidents;
			var   $info = array();
			private $infoIndex = 0;
			
			function __construct($xml=''){				
				if(is_file($xml)){
					$f = simplexml_load_file ($xml);					
				}elseif(is_array($xml) || is_object($xml)){
					$f = $xml;
				
				}else $f = false;			
				if($f) $this->buildFromArray_template($f);
			}
	
			
			function toString(){
				$r =  '<pre>';
				$r.= print_r($this, true);
				$r.= '</pre>';
				return $r;	
			}
			function __toString(){
				return print_r($this->potput(),true);
				
			}
	
			
			function _setIdentifier($str = ""){
				$this->identifier = $str;
			}
			
			function _setSender($str = ""){
				$this->sender = $str;
			}
			
			function _setSent($dateTime){
					$this->sent = $dateTime;
			}
			function _setStatus($str){
				switch($str){
					case 'Actual':
					case 'Exercise':
					case 'System':
					case 'Test':
					case 'Draft':
						$this->status = $str;
						return true;
					break;
					default:
						return false;
					break;
				}
				
			}
			
			function _setMsgType($str){
				switch($str){
					case 'Alert':
					case 'Update':
					case 'Cancel':
					case 'Ack':
					case 'Error':
						$this->msgType = $str;
						return true;
					break;
					default:
						/*
						 Fehler ausgabe, wenn das Feld nicht der voragben entpsircht.
						*/
						return false;
					break;
				}
			}
			
			function _setSource($str = ""){
				$this->source = $str;
			}
	
			function _setScope($str){
				switch($str){
					case 'Public':
					case 'Restricted':
					case 'Private':
						$this->scope = $str;
						return true;
					break;
					default:
						return false;
					break;
				}
			}
			
			function _setRestriction($str = ""){
				$this->restriction = $str;
			}
	
			function _setAddresses($str = ""){
				$this->addresses = $str;
			}
			function _setCode($str = ""){
				$this->code[$this->codeIndex] = $str;
				$this->codeIndex++;
			}
			function _setNote($str = ""){
				$this->note = $str;
			}
			function _setReferences($str = ""){
				$this->references = $str;
			}
			function _setIncidents($str = ""){
				$this->incidents = $str;
			}
			function _setInfo(info_template $str){
				$this->info[$this->infoIndex] = $str;
				$this->infoIndex++;
			}
	
	
			
		}
	
	
	
		class info_template extends Read_CAP_TEMPLATE_Class{
			//protected $required = array('category', 'event', 'urgency', 'severity', 'certainty');
			protected $subclass = array('eventCode'=>'parameter_template', 'parameter'=>'parameter_template', 'resource'=>'resource_template', 'area' => 'area_template');
			var   $language = "en-US";
			var   $category = array();
			private $categoryIndex = 0;
			var   $event;
			var   $responseType = array();
			private $responseTypeIndex=0;
			var   $urgency;
			var   $severity;
			var   $certainty;
			var   $audience;
			var   $eventCode = array();
			private $eventCodeIndex=0;
			var   $effective;
			var   $onset;
			var   $expires;
			var   $senderName;
			var   $headline;
			var   $description;
			var   $instruction;
			var   $web;
			var   $contact;
			var   $parameter = array();
			private $parameterIndex=0;
			var   $resource = array();
			private $resourceIndex=0;
			var   $area = array();
			private $areaIndex=0;
			
			function __construct(){
			}
			
			function Read_CAP_TEMPLATE_Class(){
				
			}
			function _setLanguage($str = ""){
				$this->language = $str;
			}
			function _setCategory($str){
				switch($str){
					case 'Geo':
					case 'Met':
					case 'Safety':
					case 'Security':
					case 'Rescue':
					case 'Fire':
					case 'Health':
					case 'Env':
					case 'Transport':
					case 'Infra':
					case 'CBRNE':
					case 'Other':
						$this->category = $str;
						return true;
					break;
					default:
						return false;
					break;
				}
				
			}
			function _setEvent($str = ""){
				$this->event = $str;
			}
			function _setResponseType($str){
				switch($str){
					case 'Shelter':
					case 'Evacuate':
					case 'Prepare':
					case 'Execute':
					case 'Avoid':
					case 'Monitor':
					case 'Assess':
					case 'AllClear':
					case 'None':
						$this->responseType[$this->responseTypeIndex] = $str;
						$this->responseTypeIndex++;
						return true;
					break;
					default:
						$this->responseType[$this->responseTypeIndex] = 'None';
						$this->responseTypeIndex++;
						return false;
					break;
				}
				
			}
			
			function _setUrgency($str){
				switch($str){
					case 'Immediate':
					case 'Expected':
					case 'Future':
					case 'Past':
					case 'Unknown':
						$this->urgency = $str;
						return true;
					break;
					default:
						return false;
					break;
				}
				
			}
			function _setSeverity($str){
				switch($str){
					case 'Extreme':
					case 'Severe':
					case 'Moderate':
					case 'Minor':
					case 'Unknown':
						$this->severity = $str;
						return true;
					break;
					default:
						return false;
					break;
				}
				
			}		
			function _setCertainty($str){
				switch($str){
					case 'Observed':
					case 'Likely':
					case 'Possible':
					case 'Unlikely':
					case 'Unknown':
						$this->certainty = $str;
						return true;
					break;
					default:
						return false;
					break;
				}
				
			}
			function _setAudience($str = ""){
				$this->audience = $str;
			}		
			
		
			function _setEventCode(parameter $eventCode){
				$this->eventCode[$this->eventCodeIndex] = $eventCode;
				$this->eventCodeIndex++;
			}
			
			function _setEffective($dateTime){
					$this->effective = $dateTime;
			}
			
			function _setOnset($dateTime){
					$this->onset = $dateTime;
			}	
			
			function _setExpires($dateTime){
					$this->expires = $dateTime;
			}	
			function _setSenderName($str = ""){
				$this->senderName = $str;
			}		
			
			function _setHeadline($str = ""){
				$this->headline = $str;
			}		
			
			function _setDescription($str = ""){
				$this->description = $str;
			}		
			
			function _setInstruction($str = ""){
				$this->instruction = $str;
			}		
			
			function _setWeb($str = ""){
				//if(preg_match('°^(http|https|ftp)\://(((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])|([a-zA-Z0-9_\-\.])+\.(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum|uk|me))((:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+\&%\$#\=~])*)$°', $str))
					$this->web = $str;
				//else return false;
			}		
	
			function _setContact($str = ""){
				$this->contact = $str;
			}	
	
			function _setParameter(parameter_template $parameter){
				$this->parameter[$this->parameterIndex] = $parameter;
				$this->parameterIndex++;
			}
			
			function _setResource(resource_template $resource){
				$this->resource[$this->resourceIndex] = $resource;
				$this->resourceIndex++;
			}
			
			function _setArea(area_template $area){
				$this->area[$this->areaIndex] = $area;
				$this->areaIndex++;
			}
			
				
		}
	
	
		class area_template extends Read_CAP_TEMPLATE_Class{
			//protected $required = array('areaDesc');
			protected $subclass = array('geocode'=>'parameter_template');
			var   $areaDesc;
			var   $polygon = array();
			private $polygonIndex = 0;   
			var   $circle = array();
			private $circleIndex = 0;
			var   $geocode = array();
			private $geocodeIndex = 0;
			var   $altitude;
			var   $ceiling;
			
			function __construct(){
			}
			
			function _setAreaDesc($str){
				$this->areaDesc = (string) $str;
			}
	
			function _setPolygon($polygon){
				$this->polygon[$this->polygonIndex] = (string) $polygon;
				$this->polygonIndex++;
			}
			
			function _setCircle($circle){
				$this->circle[$this->circleIndex] = (string) $circle;
				$this->circleIndex++;
			}
			
			function _setGeocode(parameter_template $geocode){
				$this->geocode[$this->geocodeIndex] = (object) $geocode;
				$this->geocodeIndex++;
			}
	
			function _setAltitude($str){
				$this->altitude = (string) $str;
			}
			
			function _setCeiling($str){
				$this->ceiling = (string) $str;
			}		
	
		}
		
		class resource_template extends Read_CAP_TEMPLATE_Class{
			//protected $required = array('resourceDesc','mimeType');
			var   $resourceDesc;
			var   $mimeType;
			var   $size;
			var   $uri;
			var   $derefUri;
			var   $digest;
					
			function _setResourceDesc($str = ""){
				$this->resourceDesc = (string) $str;
			}
			function _setMimeType($str = ""){
				$this->mimeType = (string) $str;
			}
			function _setSize($str = ""){
				$this->size = (string) $str;
			}
			function _setUri($str = ""){
				$this->uri = (string) $str;
			}
			function _setDerefUri($str = ""){
				$this->derefUri = (string) $str;
			}
			function _setDigest($str = ""){
				$this->digest = (string) $str;
			}
		
		}
			
		
		class parameter_template extends Read_CAP_TEMPLATE_Class{
			//protected $required = array('valueName','value');
			var   $valueName;
			var   $value;
			
			function __construct($vn="", $v=""){
				$this->_setValueName($vn);
				$this->_setValue($v);		
			}
			
			function _setValueName($str = ""){
				$this->valueName = (string) $str;
			}
			
			function _setValue($str = ""){
				$this->value = (string) $str;
			}
		}
		
		class CAPdateTime_template{
			
			var  $t;
			function __construct($t=0){
				$this->setTime($t);
				
			}
			function __toString(){
				return $this->t;	
			}
			function output(){
				return $this->t;	
				
			}
			function getTime(){
				return $this->t;	
			}
			function setTime($t){
				if(is_int($t))
					$this->t=date('c',$t);
				elseif(is_string($t))
					$this->t=date('c',strtotime($t));
			}
		}

?>