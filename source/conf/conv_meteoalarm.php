<?php
	/**
	 ************************************************************************************************************************************
	 * Convert Template
	 * Meteoalarm 06.10.15
	 ************************************************************************************************************************************
	 */
	$conv->on 																							= 1;
	$conv->name 																						= "meteoalarm";
	
	// Diffrents to a standard Cap or importent value's
	$conv->category 																				= "met";
	
	$conv->responseType 																		= "none";
	
	// Specific parameter name
	$conv->parameter[] 																			= "awareness_level";
		// set convert to eventCode:GROUP
		$conv->conv->hazard->level->tag->name									= "parameter"; 
		$conv->conv->hazard->level->tag->val									= "awareness_level";
		
		// posible hazard level
		$conv->awareness_level["1; green; Minor"] 						= 0; 		// 1; green; Minor
		$conv->awareness_level["1; green; Minor"] 						= 1; 		// 1; green; Minor
		$conv->awareness_level["2; yellow; Moderate"] 				= 2; 		// 2; yellow; Moderate
		$conv->awareness_level["2; yellow; Moderate"] 				= 3; 		// 2; yellow; Moderate
		$conv->awareness_level["2; yellow; Moderate"] 				= 4; 		// 2; yellow; Moderate
		$conv->awareness_level["3; orange; Severe"] 					= 5; 		// 3; orange; Severe
		$conv->awareness_level["3; orange; Severe"] 					= 6; 		// 3; orange; Severe
		$conv->awareness_level["3; orange; Severe"] 					= 7; 		// 3; orange; Severe
		$conv->awareness_level["4; red; Extreme"] 						= 8; 		// 4; red; Extreme
		$conv->awareness_level["4; red; Extreme"] 						= 9; 		// 4; red; Extreme
	
	// Specific parameter name
	$conv->parameter[] 																			= "awareness_type";
		// set convert to eventCode:GROUP
		$conv->conv->hazard->type->tag->name									= "parameter"; 
		$conv->conv->hazard->type->tag->val										= "awareness_type";
	
		$conv->awareness_type["1; Wind"] 											= 'wind';
		$conv->awareness_type["2; snow-ice"] 									= 'snow';
		$conv->awareness_type["3; Thunderstorm"] 							= 'thunderstorm';
		$conv->awareness_type["4; Fog"] 											= 'fog';
		$conv->awareness_type["5; high-temperature"]					= 'heat';
		$conv->awareness_type["6; low-temperature"] 					= 'cold';
		$conv->awareness_type["7; coastalevent"] 							= 'coastal';
		$conv->awareness_type["8; forest-fire"] 							= 'forest-fire';
		$conv->awareness_type["9; avalanches"] 								= 'avalanches';
		$conv->awareness_type["10; Rain"] 										= 'rain';
		$conv->awareness_type["12; flooding"] 								= 'flooding';
		$conv->awareness_type["13; rain-flood"] 							= 'rain-flood';
	
		//standard filler
		$conv->awareness_type["10; Rain"] 										= 'hail';		
		$conv->awareness_type["2; snow-ice"] 									= 'ice';		// 2; snow-ice
		$conv->awareness_type["2; snow-ice"] 									= 'glaze';	// 2; snow-ice
		$conv->awareness_type[""] 														= 'thaw';		
		$conv->awareness_type[""] 														= 'lake';		
		$conv->awareness_type["2; snow-ice"] 									= 'frost';	// 2; snow-ice
		
		$conv->conv->geocode->tag->name												= "geocode"; 
		$conv->conv->geocode->tag->val[]											= "NUTS";

		$conv->conv->geocode->tag->val[]											= "NUTS2";

		$conv->conv->geocode->tag->val[]											= "NUTS3";

		$conv->conv->geocode->tag->val[]											= "EMMA_ID";
					
		// meteoalarm using
		$conv->using['identifier'] 														= 1;
		$conv->using['sender']																= 1;
		$conv->using['sent']																	= 1;
		$conv->using['status']																= 1;
		$conv->using['msgType']																= 1;
		$conv->using['references']														= 1;
		$conv->using['scope']																	= 1;
       									
		$conv->using['language']															= 1;
		$conv->using['category']															= 1;
		$conv->using['event']																	= 1;
		$conv->using['responseType']													= 1;
		$conv->using['urgency']																= 1;
		$conv->using['severity']															= 1;
		$conv->using['certainty']															= 1;
		$conv->using['audience']															= 1;
		$conv->using['eventCode']															= 0;
		$conv->using['effective']															= 1;
		$conv->using['onset']																	= 1;
		$conv->using['expires']																= 1;
		$conv->using['senderName']														= 1;
		$conv->using['headline']															= 1;
		$conv->using['description']														= 1;
		$conv->using['instruction']														= 1;
		$conv->using['web']																		= 1;
		$conv->using['contact']																= 0;
		$conv->using['parameter']															= 1;
    									
		$conv->using['areaDesc']															= 1;
		$conv->using['polygon']																= 0;
		$conv->using['circle']																= 0;
		$conv->using['geocode']																= 1;