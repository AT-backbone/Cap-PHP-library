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
 *	\file      	stabdard_menu.lib.php
 *  \ingroup   	menu
 */
 
		function Types()
		{
			global $conf, $langs;
			
			// Alert Page		
			$type['alert'][] = "identifier";	
			$type['alert'][] = "status";
			$type['alert'][] = "category";
			$type['alert'][] = "urgency";	
      //$type['alert'][] = "references"; // will be automaticlie added by msgType Update and Cancel
      
     	// Alert Detail Page
     		$type['conf']['detail']['value'][] = "DUMMY";
	     	$type['alert']['detail']['value'][] = "sent";
	     	
	      $type['alert']['detail']['value'][] = "effective";
	      $type['alert']['detail']['value'][] = "onset";
	      $type['alert']['detail']['value'][] = "expires";
	      
				$type['alert']['detail']['value'][] = "eventCode";
				$type['alert']['detail']['value'][] = "parameter";
				
				$type['alert']['detail']['value'][] = "source";
				$type['alert']['detail']['value'][] = "restriction";
				$type['alert']['detail']['value'][] = "addresses";
				$type['alert']['detail']['value'][] = "code";
				$type['alert']['detail']['value'][] = "note";
				$type['alert']['detail']['value'][] = "incidents";
				$type['alert']['detail']['name'] = $langs->trans("Detail");
			
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
			
			// Conf Page	
			$type['conf'][] = "cap_save";
			$type['conf'][] = "cap_output";
			
			// $type['conf'][] = "conf_output";
			// Identifier conf
			$type['conf'][] = "WMO_OID";
			$type['conf'][] = "ISO";			
			$type['conf'][] = "ID_ID";
			$type['conf'][] = "identifier_time";
			
			$type['conf'][] = "template";
			
			// Lang conf
			$type['conf'][] = "lang_conf_use";
			$type['conf'][] = "lang_conf_plus";
			$type['conf'][] = "lang_conf_remove";
			$type['conf'][] = "lang_conf";
			
			// Webservice conf
			$type['conf'][] = "webservice_on";
		
				$type['conf']['detail']['value'][] = "DUMMY";
				$type['conf']['detail']['value'][] = "webservice_securitykey";
				$type['conf']['detail']['value'][] = "webservice_sourceapplication";
				$type['conf']['detail']['value'][] = "webservice_login";
				$type['conf']['detail']['value'][] = "webservice_password";
				$type['conf']['detail']['value'][] = "webservice_entity";
				$type['conf']['detail']['value'][] = "webservice_WS_METHOD";
				$type['conf']['detail']['value'][] = "webservice_ns";
				$type['conf']['detail']['value'][] = "webservice_WS_DOL_URL";
				$type['conf']['detail']['name'] = $langs->trans("WebserviceConfiguration");
				
				
			// CAP View
			$type['capview'][] 	= 'capview';
			
			// Cap List 
			$type['read'][] 		= 'caplist';
			
			// LOGIN POPUP
			$type['Login'][] 		= 'login_popup';
			
			// PAGES WOH DO NOT USE DETAIL FUNKTION
			$type['info']['detail']['value'] 		= ""; // no detail
			$type['info']['detail']['name']  		= ""; // no detail
			
			$type['area']['detail']['value'] 		= ""; // no detail			
			$type['area']['detail']['name']  		= ""; // no detail
			
			$type['read']['detail']['value'] 		= ""; // no detail
			$type['read']['detail']['name']  		= ""; // no detail
				
			$type['Login']['detail']['value'] 	= ""; // no detail
			$type['Login']['detail']['name']  	= ""; // no detail
			
			$type['capview']['detail']['value'] = ""; // no detail
			$type['capview']['detail']['name']  = ""; // no detail

      return $type;
		}
		
		function Pages()
		{
			global $langs;
			//$pages['#MAIN'] 					= $langs->trans("TitleMain");
			//$pages['next']['name']['PAGENAME'] = 'NEXT_PGAENAME';
			
			$pages['#alert'] 					= $langs->trans("TitleAlert");
			
			$pages['#info']  					= $langs->trans("TitleInfo");
			
			$pages['#area']  					= $langs->trans("TitleArea");
			
			$pages['#capview'] 		 		= $langs->trans("TitleCapView");
			
			$pages['#read'] 		 		= $langs->trans("TitleCapList");
			
			$pages['?conv=1#capconv']	= $langs->trans("TitleCapConv");
			
			$pages['#conf']  					= $langs->trans("TitleConfig");
			
			// Links
			$pages['next']['name']['alert'] = 'info';
			$pages['next']['name']['info'] 	= 'area';
			$pages['next']['name']['area'] 	= 'capview';
			
			// Input or else
			$pages['next']['nolink']['capview'] = '<input type="submit" value="'.$langs->trans("Submit").'" data-ajax="false">';
			
			$pages['next']['nolink']['conf'] 		= '<input class="ui-btn" type="button" value="'.$langs->trans('Save').'" onclick="ajax_conf()">';
			$pages['next']['nolink']['conf'] 	 .= '<div data-role="popup" id="Saved_conf" style="text-align: center; vertical-align: middle; width: 200px; height: 40px; background: rgba(4, 255, 0, 0.65); color: #000; font-size: 22px; padding: 10px 0px 0px 0px; text-shadow: 0px 0px 0px #000;">';
			$pages['next']['nolink']['conf'] 	 .= $langs->trans('Saved!');
			$pages['next']['nolink']['conf'] 	 .= '</div>';

			// Page without ajax (pagelink)
			$pages['noajax'][]				= '?conv=1#capconv';
			//$pages['noajax'][]				= '#login';
			
			// Pages that shoud be a dialog
			//$pages['#login'] 					= $langs->trans("TitleLogin");
			$pages['popup'][] 				= 'Login'; // intial login as popup (Translate name is $langs->trans("TitleLogin) )
			
			return $pages;
		}
		
		function TypeStatus()
		{
			/*
			Requirenes level
			key : theme : desc
			O   : A     : optional
			OD  : B     : optional (Dark)			
			C   : E     : conditional
			R   : C     : required
			*/
			
			$status['identifier'] 	= "R";
			
			// Group
			$status['status'] 			= "R";			
			$status['msgType'] 			= "R";
			$status['scope'] 				= "R";
			
			// Group
			$status['category']	 		= "R";			
			$status['responseType']	= "R";
			
			// Group
			$status['urgency'] 			= "R";			
			$status['severity'] 		= "R";
			$status['certainty'] 		= "R";

			$status['sent'] 				= "R";
			$status['lang'] 				= "R";

			// Group
			$status['event'] 				= "R";			
			$status['headline'] 		= "O";
			$status['description']	= "O";
			$status['instruction']	= "O";

			$status['sender'] 			= "R";
			$status['areaDesc'] 		= "R";
			$status['geocode'] 			= "R";

			$status['restriction'] 	= "C"; // required when Scope is = Restricted
			$status['required']['restriction']['Scope'] = 'Restricted'; // restriction is required when Scope is Restricted
			
			$status['addresses'] 		= "C"; // required when Scope is = Private
			$status['required']['addresses']['Scope'] = 'Private'; // addresses is required when Scope is Private
			
			$status['effective'] 		= "O";
			$status['onset'] 				= "O";
			$status['expires'] 			= "O";
			$status['eventCode'] 		= "O";
			$status['parameter'] 		= "O";
			$status['source'] 			= "O";
			$status['code'] 				= "O";
			$status['note'] 				= "O";
			$status['incidents'] 		= "O";
			$status['senderName'] 	= "O";
			$status['audience'] 		= "O";
			$status['contact'] 			= "O";
			$status['web'] 					= "O";
			$status['polygon'] 			= "O";
			$status['circle'] 			= "O";
			$status['map'] 					= "O";
			
			return $status;
		}
		
		function dependencies()
		{
			// if select parameter_awt is 10; Rain than select category = MET
			//  if     ----  name			  ----				  is     ----				than 		   ----  name   ----     is      ---
			$depends['select']	['id']	['parameter_awt-select']	['10; Rain']						['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['9; avalanches']				['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['8; forest-fire']			['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['7; coastalevent']			['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['6; low-temperature']	['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['5; high-temperature']	['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['4; Fog']							['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['3; Thunderstorm']			['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['2; snow-ice']					['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['1; Wind']							['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['12; flooding']				['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['13; rain-flood']			['select']	['name'] 	['category'] 			= "Met";
			$depends['select']	['id']	['parameter_awt-select']	['14; Refugees']				['select']	['name'] 	['category'] 			= "Met";

			$depends['select']	['id']	['parameter_awl-select']	['1; green; Minor']			['select']	['name'] 	['severity'] 			= "Minor";
			$depends['select']	['id']	['parameter_awl-select']	['2; yellow; Moderate']	['select']	['name'] 	['severity'] 			= "Moderate";
			$depends['select']	['id']	['parameter_awl-select']	['3; orange; Severe']		['select']	['name'] 	['severity'] 			= "Severe";
			$depends['select']	['id']	['parameter_awl-select']	['4; red; Extreme']			['select']	['name'] 	['severity'] 			= "Extreme";

			$depends['select']	['id']	['parameter_awl-select']	['1; green; Minor']			['select']	['name'] 	['urgency'] 			= "Expected";
			$depends['select']	['id']	['parameter_awl-select']	['2; yellow; Moderate']	['select']	['name'] 	['urgency'] 			= "Expected";
			$depends['select']	['id']	['parameter_awl-select']	['3; orange; Severe']		['select']	['name'] 	['urgency'] 			= "Expected";
			$depends['select']	['id']	['parameter_awl-select']	['4; red; Extreme']			['select']	['name'] 	['urgency'] 			= "Expected";

			$depends['select']	['id']	['parameter_awl-select']	['1; green; Minor']			['select']	['name'] 	['certainty'] 		= "Likely";
			$depends['select']	['id']	['parameter_awl-select']	['2; yellow; Moderate']	['select']	['name'] 	['certainty'] 		= "Likely";
			$depends['select']	['id']	['parameter_awl-select']	['3; orange; Severe']		['select']	['name'] 	['certainty'] 		= "Likely";
			$depends['select']	['id']	['parameter_awl-select']	['4; red; Extreme']			['select']	['name'] 	['certainty'] 		= "Likely";

			$depends['select']	['id']	['parameter_awl-select']	['1; green; Minor']			['select']	['name'] 	['responseType'] 	= "None";
			$depends['select']	['id']	['parameter_awl-select']	['2; yellow; Moderate']	['select']	['name'] 	['responseType'] 	= "None";
			$depends['select']	['id']	['parameter_awl-select']	['3; orange; Severe']		['select']	['name'] 	['responseType'] 	= "None";
			$depends['select']	['id']	['parameter_awl-select']	['4; red; Extreme']			['select']	['name'] 	['responseType'] 	= "None";

			$depends['select']	['id']	['parameter_awl-select']	['1; green; Minor']			['select']	['name'] 	['status'] 				= "Actual";
			$depends['select']	['id']	['parameter_awl-select']	['2; yellow; Moderate']	['select']	['name'] 	['status'] 				= "Actual";
			$depends['select']	['id']	['parameter_awl-select']	['3; orange; Severe']		['select']	['name'] 	['status'] 				= "Actual";
			$depends['select']	['id']	['parameter_awl-select']	['4; red; Extreme']			['select']	['name'] 	['status'] 				= "Actual";

			$depends['select']	['id']	['parameter_awl-select']	['1; green; Minor']			['select']	['name'] 	['msgType'] 			= "Alert";
			$depends['select']	['id']	['parameter_awl-select']	['2; yellow; Moderate']	['select']	['name'] 	['msgType'] 			= "Alert";
			$depends['select']	['id']	['parameter_awl-select']	['3; orange; Severe']		['select']	['name'] 	['msgType'] 			= "Alert";
			$depends['select']	['id']	['parameter_awl-select']	['4; red; Extreme']			['select']	['name'] 	['msgType'] 			= "Alert";

			$depends['select']	['id']	['parameter_awl-select']	['1; green; Minor']			['select']	['name'] 	['scope'] 				= "Public";
			$depends['select']	['id']	['parameter_awl-select']	['2; yellow; Moderate']	['select']	['name'] 	['scope'] 				= "Public";
			$depends['select']	['id']	['parameter_awl-select']	['3; orange; Severe']		['select']	['name'] 	['scope'] 				= "Public";
			$depends['select']	['id']	['parameter_awl-select']	['4; red; Extreme']			['select']	['name'] 	['scope'] 				= "Public";
			 
			return $depends;
		}

?>