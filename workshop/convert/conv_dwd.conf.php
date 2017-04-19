<?php
	/**
	 ************************************************************************************************************************************
	 * Convert Template
	 * DWD 06.10.15
	 ************************************************************************************************************************************
	 */
	 	 
	//-------------------------------------------------------------------------------------------------------------------------------------------------
	
	//eventCode
	
	$conv->info['tag'][]														= "eventCode";
	$conv->info['ValueName'][]											= "AREA_COLOR";	
	                                          			
	$conv->info['tag'][]														= "eventCode";
	$conv->info['ValueName'][]											= "GROUP";	

	$conv->info['tag'][]														= "eventCode";
	$conv->info['ValueName'][]											= "PROFILE_VERSION";	
	
	$conv->info['tag'][]														= "eventCode";
	$conv->info['ValueName'][]											= "LICENSE";
										
	$conv->info['tag'][]														= "eventCode";
	$conv->info['ValueName'][]											= "II";	
	
	// geocode
	
	$conv->info['tag'][]														= "geocode";
	$conv->info['ValueName'][]											= "STATE";	
	
	$conv->info['tag'][]														= "geocode";
	$conv->info['ValueName'][]											= "WARNCELLID";	

	$conv->info['tag'][]														= "geocode";
	$conv->info['ValueName'][]											= "SIGN";	
	
	$conv->info['tag'][]														= "geocode";
	$conv->info['ValueName'][]											= "ALTITUDE";	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------                          	

	// alert block                                        	  	
	$conv->structure['tag'][]												= "identifier";
	$conv->structure['tag'][]												= "sender";
	$conv->structure['tag'][]												= "sent";
	$conv->structure['tag'][]												= "status";
	$conv->structure['tag'][]												= "msgType";
	$conv->structure['tag'][]												= "scope";
	
		// info block
		$conv->structure['tag']['info'][]							= "language";
		$conv->structure['tag']['info'][]							= "category";
		$conv->structure['tag']['info'][]							= "event";
		$conv->structure['tag']['info'][]							= "responseType";
		$conv->structure['tag']['info'][]							= "eventCode";
		$conv->structure['tag']['info'][]							= "urgency";
		$conv->structure['tag']['info'][]							= "severity";
		$conv->structure['tag']['info'][]							= "certainty";
		$conv->structure['tag']['info'][]							= "effective";
		$conv->structure['tag']['info'][]							= "onset";
		$conv->structure['tag']['info'][]							= "expires";
		$conv->structure['tag']['info'][]							= "senderName";
		$conv->structure['tag']['info'][]							= "headline";
		$conv->structure['tag']['info'][]							= "description";
		$conv->structure['tag']['info'][]							= "instruction";		
		$conv->structure['tag']['info'][]							= "web";
		$conv->structure['tag']['info'][]							= "contact";
		
		// area block
			$conv->structure['tag']['info']['area'][]		= "areaDesc";
			$conv->structure['tag']['info']['area'][]		= "polygon";
			$conv->structure['tag']['info']['area'][]		= "geocode";
			$conv->structure['tag']['info']['area'][]		= "altitude";
			$conv->structure['tag']['info']['area'][]		= "ceiling";

	//-------------------------------------------------------------------------------------------------------------------------------------------------
	
	
	// do not move's
	$conv->move['PROFILE_VERSION'][]								= "donotmove1";
	$conv->move['LICENSE'][]												= "donotmove2";
	$conv->move['II'][]															= "donotmove3";
	
	$conv->move['WARNCELLID'][]											= "donotmove4";	
	$conv->move['SIGN'][]														= "donotmove5";	
	$conv->move['ALTITUDE'][]												= "donotmove6";	
	
	$conv->move['STATE'][]													= "area_code";
	
		$conv->translate['BL'][] 												= "BERLIN";		
		$conv->translate['BW'][]												=	"BADEN-WÜRTTEMBERG";
		$conv->translate['BY'][]												=	"BAYERN";
		$conv->translate['BE'][]												=	"BERLIN";
		$conv->translate['BB'][]												=	"BRANDENBURG";
		$conv->translate['HB'][]												=	"BREMEN";
		$conv->translate['HH'][]												=	"HAMBURG";
		$conv->translate['HE'][]												=	"HESSEN";
		$conv->translate['MV'][]												=	"MECKLENBURG-VORPOMMERN";
		$conv->translate['NS'][]												=	"NIEDERSACHSEN";
		$conv->translate['NW'][]												=	"NORDRHEIN-WESTFALEN";
		$conv->translate['NRW'][]												=	"NORDRHEIN-WESTFALEN";
		$conv->translate['RP'][]												=	"RHEINLAND-PFALZ";
		$conv->translate['SL'][]												=	"SAARLAND";
		$conv->translate['SN'][]												=	"SACHSEN";
		$conv->translate['SA'][]												=	"SACHSEN-ANHALT";
		$conv->translate['SH'][]												=	"SCHLESWIG-HOLSTEIN";
		$conv->translate['TH'][]												=	"THÜRINGEN";
	
	$conv->move['AREA_COLOR'][]											= "hazard_level"; // lösche den source AREA_COLOR oder überschreibe den destinaion AREA_COLOR
	
		$conv->translate["255 128 128"][] 						= 'Minor'; 	// Hellblau = Grün Keine
		$conv->translate["204 153 255"][] 						= 'Minor'; 	// Rosa Vorabinformation
		
		$conv->translate["255 255 0"][] 							= 'Moderate';	// Gelb Wetterwarnung
		
		$conv->translate["255 153 0"][] 							= 'Severe'; 	// Orange Markantes Wetter
		
		$conv->translate["255 0 0"][] 								= 'Extreme'; 	// Rot Unwetterwarnung
		$conv->translate["175 0 100"][] 							= 'Extreme';	// Violett Extreme Wetterwarnung	

                                            	  	
	$conv->move['GROUP'][]													= "hazard_type"; // lösche den source awareness_level oder überschreibe den destinaion awareness_level
	
		$conv->translate['THUNDERSTORM'][] 						= "thunderstorm";
		$conv->translate['WIND'][] 										= "wind";
		$conv->translate['RAIN'][] 										= "rain";
		$conv->translate['HAIL'][] 										= "hail";
		$conv->translate['SNOWFALL'][]								= "snow";
		$conv->translate['SNOWDRIFT'][]								= "ice";
		$conv->translate['FOG'][] 										= "fog";
		$conv->translate['FROST'][]										= "frost";
		$conv->translate['GLAZE'][] 									= "glaze";
		$conv->translate['THAW'][]										= "thaw";
		$conv->translate['LAKE'][]										= "lake";
		$conv->translate['HEAT'][] 										= "heat";		
		$conv->translate['FROST'][]										= "cold";

	 //-------------------------------------------------------------------------------------------------------------------------------------------------