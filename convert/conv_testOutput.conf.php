<?php
	/**
	 ************************************************************************************************************************************
	 * Convert Template
	 * Meteoalarm 06.10.15
	 ************************************************************************************************************************************
	 */
	 
	//-------------------------------------------------------------------------------------------------------------------------------------------------
	
	//$conv->info['tag'][]														= "parameter";
	//$conv->info['ValueName'][]											= "-1";
	
	$conv->info['tag'][]														= "parameter";
	$conv->info['ValueName'][]											= "awareness_level";
	                                          			
	$conv->info['tag'][]														= "parameter";
	$conv->info['ValueName'][]											= "awareness_type";	
	
	$conv->info['tag'][]														= "geocode";
	$conv->info['ValueName'][]											= "NUTS2";		
	
	//$conv->info['tag'][]														= "web";
	//$conv->info['ValueName'][]											= "-1";
	
	//$conv->info['tag'][] 														= "headline";
	//$conv->info['ValueName'][]											= "-1";
                                            	
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
		$conv->structure['tag']['info'][]							= "parameter";
		
		// area block
			$conv->structure['tag']['info']['area'][]		= "areaDesc";
			$conv->structure['tag']['info']['area'][]		= "geocode";

	//-------------------------------------------------------------------------------------------------------------------------------------------------
	$conv->move['NUTS2'][]													= "area_code";
	
	//$conv->move['web'][]														= "coninfo";
	
	//$conv->insert['headline'][]											= "copy_paste_event";
	//$conv->move['parameter'][]											= "moveinfo";
	
	$conv->move['awareness_level'][]								= "hazard_level"; // lösche den source awareness_level oder überschreibe den destinaion awareness_level
		$conv->translate['1; green; Minor'][]						= 'Minor';
		$conv->translate['2; yellow; Moderate'][]				= 'Moderate';
		$conv->translate['3; orange; Severe'][]					= 'Severe';
		$conv->translate['4; red; Extreme'][]						= 'Extreme';
                                            	  	
	$conv->move['awareness_type'][]									= "hazard_type"; // lösche den source awareness_level oder überschreibe den destinaion awareness_level
		$conv->translate['1; Wind'][]										= "wind";
		$conv->translate['2; snow-ice'][]								= "snow";
		$conv->translate['3; Thunderstorm'][]						= "thunderstorm";
		$conv->translate['4; Fog'][]										= "fog";
		$conv->translate['5; high-temperature'][]				= "heat";
		$conv->translate['6; low-temperature'][]				= "cold";
		$conv->translate['7; coastalevent'][]						= "coastal";
		$conv->translate['8; forest-fire'][]						= "forest fire";
		$conv->translate['9; avalanches'][]							= "avalanches";
		$conv->translate['10; Rain'][]									= "rain";
		$conv->translate['12; flooding'][]							= "flooding";
		$conv->translate['13; rain-flood'][]						= "rain-flood";
