<?php
	/**
	 ************************************************************************************************************************************
	 * Convert Template
	 * DWD 06.10.15
	 ************************************************************************************************************************************
	 */
	$conv->on 																										= 1;
	$conv->name 																									= "dwd";
	
	// Diffrents to a standard Cap or importent value's
	$conv->category 																							= "met";
	
	$conv->eventCode[]																						= "PROFILE_VERSION";
	$conv->eventCode[]																						= "LICENSE";
	$conv->eventCode[]																						= "II";	
	
	$conv->eventCode[]																						= "GROUP";
	// set convert to eventCode:GROUP
	$conv->conv->hazard->type->tag->name													= "eventCode"; 
	$conv->conv->hazard->type->tag->val														= "GROUP";
	
		$conv->eventCode->GROUP['thunderstorm'] 										= "THUNDERSTORM";
		$conv->eventCode->GROUP['wind'] 														= "WIND";
		$conv->eventCode->GROUP['rain'] 														= "RAIN";
		$conv->eventCode->GROUP['hail'] 														= "HAIL";
		$conv->eventCode->GROUP['snow']															= "SNOWFALL";
		$conv->eventCode->GROUP['ice']															= "SNOWDRIFT";
		$conv->eventCode->GROUP['fog'] 															= "FOG";
		$conv->eventCode->GROUP['frost']														= "FROST";
		$conv->eventCode->GROUP['glaze'] 														= "GLAZE";
		$conv->eventCode->GROUP['thaw']															= "THAW";
		$conv->eventCode->GROUP['lake']															= "LAKE";
		$conv->eventCode->GROUP['heat'] 														= "HEAT";		
		
		// standard filler
		$conv->eventCode->GROUP['cold']															= "FROST";
		$conv->eventCode->GROUP['coastal']													= "";
		$conv->eventCode->GROUP['flooding']													= "";
		$conv->eventCode->GROUP['rain-flood']												= "";
		$conv->eventCode->GROUP['avalanches']												= "";

	
	$conv->eventCode[]																						= "AREA_COLOR";
	// set convert to eventCode:AREA_COLOR
	$conv->conv->hazard->level->tag->name													= "eventCode"; 
	$conv->conv->hazard->level->tag->val													= "AREA_COLOR";
					
		// posible hazard level
		$conv->parameter->awareness_level[0] 												= "100, 180, 255"; 	// Hellblau = Grün Keine
		$conv->parameter->awareness_level[1] 												= "255, 128, 128"; 	// Rosa Vorabinformation
		$conv->parameter->awareness_level[2] 												= "255, 255, 0"; 		// Gelb Wetterwarnung
		$conv->parameter->awareness_level[3] 												= "255, 255, 0"; 		// Gelb Wetterwarnung
		$conv->parameter->awareness_level[4] 												= "255, 153, 0";   	// Orange Markantes Wetter
		$conv->parameter->awareness_level[5] 												= "255, 153, 0";   	// Orange Markantes Wetter
		$conv->parameter->awareness_level[6] 												= "255, 0, 0";     	// Rot Unwetterwarnung
		$conv->parameter->awareness_level[7] 												= "255, 0, 0";     	// Rot Unwetterwarnung
		$conv->parameter->awareness_level[8] 												= "175, 0, 100";  	// Violett Extreme Wetterwarnung		
		$conv->parameter->awareness_level[9] 												= "175, 0, 100";  	// Violett Extreme Wetterwarnung		
	
	
	$conv->area->geocode[] 																				= "WARNCELLID";
	$conv->area->geocode[] 																				= "STATE";
	
	// set convert to eventCode:AREA_COLOR
	$conv->conv->hazard->level->tag->name													= "geocode"; 
	$conv->conv->hazard->level->tag->val													= "STATE";
	
	$conv->geocode->nuts['DE1'] = "BW";
	$conv->geocode->nuts['DE2'] = "BY";
	$conv->geocode->nuts['DE3'] = "BE";
	$conv->geocode->nuts['DE4'] = "BB";
	$conv->geocode->nuts['DE5'] = "HB";
	$conv->geocode->nuts['DE6'] = "HH";
	$conv->geocode->nuts['DE7'] = "HE";
	$conv->geocode->nuts['DE8'] = "MV";
	$conv->geocode->nuts['DE9'] = "NI";
	$conv->geocode->nuts['DEA'] = "NW";
	$conv->geocode->nuts['DEB'] = "RP";
	$conv->geocode->nuts['DEC'] = "SL";
	$conv->geocode->nuts['DED'] = "SN";
	$conv->geocode->nuts['DEE'] = "ST";
	$conv->geocode->nuts['DEF'] = "SH";
	$conv->geocode->nuts['DEG'] = "TH";