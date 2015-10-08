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
	$conv->std->category 																							= "met";
	
	$conv->eventCode[]																						= "PROFILE_VERSION";
	$conv->eventCode[]																						= "LICENSE";
	$conv->eventCode[]																						= "II";	
	
	$conv->eventCode[]																						= "GROUP";
	// set convert to eventCode:GROUP
	$conv->conv->hazard->type->tag->name													= "eventCode"; 
	$conv->conv->hazard->type->tag->val														= "GROUP";
	
		$conv->GROUP['THUNDERSTORM'] 																= "thunderstorm";
		$conv->GROUP['WIND'] 																				= "wind";
		$conv->GROUP['RAIN'] 																				= "rain";
		$conv->GROUP['HAIL'] 																				= "hail";
		$conv->GROUP['SNOWFALL']																		= "snow";
		$conv->GROUP['SNOWDRIFT']																		= "ice";
		$conv->GROUP['FOG'] 																				= "fog";
		$conv->GROUP['FROST']																				= "frost";
		$conv->GROUP['GLAZE'] 																			= "glaze";
		$conv->GROUP['THAW']																				= "thaw";
		$conv->GROUP['LAKE']																				= "lake";
		$conv->GROUP['HEAT'] 																				= "heat";		
		
		// standard filler
		$conv->GROUP['cold']																				= "FROST";
		$conv->GROUP['coastal']																			= "";
		$conv->GROUP['flooding']																		= "";
		$conv->GROUP['rain-flood']																	= "";
		$conv->GROUP['avalanches']																	= "";

	
	$conv->eventCode[]																						= "AREA_COLOR";
	// set convert to eventCode:AREA_COLOR
	$conv->conv->hazard->level->tag->name													= "eventCode"; 
	$conv->conv->hazard->level->tag->val													= "AREA_COLOR";
					
		// posible hazard level 																					// rgb(204, 153, 255)
		$conv->AREA_COLOR["100 180 255"] 														= 0; 	// Hellblau = Grün Keine
		$conv->AREA_COLOR["204 153 255"] 														= 1; 	// Rosa Vorabinformation
		$conv->AREA_COLOR["255 128 128"] 														= 1; 	// Rosa Vorabinformation
		$conv->AREA_COLOR["255 255 0"] 															= 2;	// Gelb Wetterwarnung
		$conv->AREA_COLOR["255 255 0"] 															= 3;	// Gelb Wetterwarnung
		$conv->AREA_COLOR["255 255 0"] 															= 4;	// Gelb Wetterwarnung
		$conv->AREA_COLOR["255 153 0"] 															= 5; 	// Orange Markantes Wetter
		$conv->AREA_COLOR["255 153 0"] 															= 6; 	// Orange Markantes Wetter
		$conv->AREA_COLOR["255 153 0"] 															= 7; 	// Orange Markantes Wetter
		$conv->AREA_COLOR["255 0 0"] 																= 8; 	// Rot Unwetterwarnung
		$conv->AREA_COLOR["175 0 100"] 															= 9;	// Violett Extreme Wetterwarnung	
	
	
	$conv->area->geocode[] 																				= "WARNCELLID";
	$conv->area->geocode[] 																				= "STATE";
	
	// set convert to eventCode:AREA_COLOR
	$conv->conv->geocode->tag->name																= "geocode"; 
	$conv->conv->geocode->tag->val																= "STATE";
	
	$conv->STATE['BW'] 																						= "DE1";
	$conv->STATE['BY'] 																						= "DE2";
	$conv->STATE['BE'] 																						= "DE3";
	$conv->STATE['BL'] 																						= "DE3";
	$conv->STATE['BB'] 																						= "DE4";
	$conv->STATE['HB'] 																						= "DE5";
	$conv->STATE['HH'] 																						= "DE6";
	$conv->STATE['HE'] 																						= "DE7";
	$conv->STATE['MV'] 																						= "DE8";
	$conv->STATE['NI'] 																						= "DE9";
	$conv->STATE['NW'] 																						= "DEA";
	$conv->STATE['RP'] 																						= "DEB";
	$conv->STATE['SL'] 																						= "DEC";
	$conv->STATE['SN'] 																						= "DED";
	$conv->STATE['ST'] 																						= "DEE";
	$conv->STATE['SH'] 																						= "DEF";
	$conv->STATE['TH'] 																						= "DEG";
	
		// DWD using
		$conv->using['identifier'] 														= 1;
		$conv->using['sender']																= 1;
		$conv->using['sent']																	= 1;
		$conv->using['status']																= 1;
		$conv->using['msgType']																= 1;
		$conv->using['references']														= 1;
		$conv->using['scope']																	= 1;
		
		$conv->using['source']																= 0;		
		$conv->using['restriction']														= 0;
		$conv->using['addresses']															= 0;
		$conv->using['code']																	= 0;
		$conv->using['note']																	= 0;
		$conv->using['incidents']															= 0;
       									
		$conv->using['language']															= 1;
		$conv->using['category']															= 1;
		$conv->using['event']																	= 1;
		$conv->using['responseType']													= 1;
		$conv->using['urgency']																= 1;
		$conv->using['severity']															= 1;
		$conv->using['certainty']															= 1;
		$conv->using['audience']															= 1;
		$conv->using['eventCode']															= 1;
		$conv->using['effective']															= 1;
		$conv->using['onset']																	= 1;
		$conv->using['expires']																= 1;
		$conv->using['senderName']														= 1;
		$conv->using['headline']															= 1;
		$conv->using['description']														= 1;
		$conv->using['instruction']														= 1;
		$conv->using['web']																		= 1;
		$conv->using['contact']																= 1;
		$conv->using['parameter']															= 0;
    									
		$conv->using['areaDesc']															= 1;
		$conv->using['polygon']																= 1;
		$conv->using['circle']																= 0;
		$conv->using['geocode']																= 1;