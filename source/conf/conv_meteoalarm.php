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
		
		$conv->parameter->awareness_level['Minor'] 						= "1; green; Minor"; 				// 1; green; Minor
		$conv->parameter->awareness_level['Moderate'] 				= "2; yellow; Moderate"; 		// 2; yellow; Moderate
		$conv->parameter->awareness_level['Severe'] 					= "3; orange; Severe"; 			// 3; orange; Severe
		$conv->parameter->awareness_level['Extreme'] 					= "4; red; Extreme"; 				// 4; red; Extreme
		
		// posible hazard level
		$conv->parameter->awareness_level[0] 									= "1; green; Minor"; 				// 1; green; Minor
		$conv->parameter->awareness_level[1] 									= "1; green; Minor"; 				// 1; green; Minor
		$conv->parameter->awareness_level[2] 									= "2; yellow; Moderate"; 		// 2; yellow; Moderate
		$conv->parameter->awareness_level[3] 									= "2; yellow; Moderate"; 		// 2; yellow; Moderate
		$conv->parameter->awareness_level[4] 									= "2; yellow; Moderate"; 		// 2; yellow; Moderate
		$conv->parameter->awareness_level[5] 									= "3; orange; Severe"; 			// 3; orange; Severe
		$conv->parameter->awareness_level[6] 									= "3; orange; Severe"; 			// 3; orange; Severe
		$conv->parameter->awareness_level[7] 									= "3; orange; Severe"; 			// 3; orange; Severe
		$conv->parameter->awareness_level[8] 									= "4; red; Extreme"; 				// 4; red; Extreme
		$conv->parameter->awareness_level[9] 									= "4; red; Extreme"; 				// 4; red; Extreme
	
	// Specific parameter name
	$conv->parameter[] 																			= "awareness_type";
		// set convert to eventCode:GROUP
		$conv->conv->hazard->type->tag->name									= "parameter"; 
		$conv->conv->hazard->type->tag->val										= "awareness_type";
	
		$conv->parameter->awareness_type['wind'] 							= "1; Wind"; 								// 1; Wind
		$conv->parameter->awareness_type['snow'] 							= "2; snow-ice"; 						// 2; snow-ice
		$conv->parameter->awareness_type['thunderstorm'] 			= "3; Thunderstorm"; 				// 3; Thunderstorm
		$conv->parameter->awareness_type['fog'] 							= "4; Fog"; 								// 4; Fog
		$conv->parameter->awareness_type['heat']							= "5; high-temperature"; 		// 5; high-temperature
		$conv->parameter->awareness_type['cold'] 							= "6; low-temperature"; 		// 6; low-temperature
		$conv->parameter->awareness_type['coastal'] 					= "7; coastalevent"; 				// 7; coastalevent
		$conv->parameter->awareness_type['forest-fire'] 			= "8; forest-fire"; 				// 8; forest-fire
		$conv->parameter->awareness_type['avalanches'] 				= "9; avalanches"; 					// 9; avalanches
		$conv->parameter->awareness_type['rain'] 							= "10; Rain"; 							// 10; Rain
		$conv->parameter->awareness_type['flooding'] 					= "12; flooding"; 					// 12; flooding
		$conv->parameter->awareness_type['rain-flood'] 				= "13; rain-flood"; 				// 13; rain-flood
	
		//standard filler
		$conv->parameter->awareness_type['hail'] 							= "";
		$conv->parameter->awareness_type['ice'] 							= "2; snow-ice"; 						// 2; snow-ice
		$conv->parameter->awareness_type['glaze'] 						= "2; snow-ice"; 						// 2; snow-ice
		$conv->parameter->awareness_type['thaw'] 							= "";
		$conv->parameter->awareness_type['lake'] 							= "";
		$conv->parameter->awareness_type['frost'] 						= "2; snow-ice"; 						// 2; snow-ice
		

	$conv->area->geocode[]																	= "NUTS1";
	$conv->area->geocode[]																	= "NUTS2";
	$conv->area->geocode[]																	= "NUTS3";
	$conv->area->geocode[]																	= "EMMAID";