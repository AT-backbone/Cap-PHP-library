<?php
	/**
	 ************************************************************************************************************************************
	 * Standard Convert Template
	 * Standard 06.10.15
	 * DO NOT CHANGE THIS
	 ************************************************************************************************************************************
	 */

	$conv->identifier 	 																		= "";
	$conv->sender			   																		= "";
	$conv->sent					 																		= "";
	$conv->status			   																		= "";
	$conv->msgType			 																		= "";
	$conv->references		 																		= "";
	$conv->scope				 																		= "";
	$conv->language			 																		= "";
	$conv->category			 																		= "";
	$conv->event				 																		= "";
	$conv->responseType	 																		= "";
	$conv->urgency			 																		= "";
	$conv->severity			 																		= "";
	$conv->certainty		 																		= "";
	$conv->audience			 																		= "";
	$conv->eventCode		 																		= "";
	$conv->effective		 																		= "";
	$conv->onset				 																		= "";
	$conv->expires			 																		= "";
	$conv->senderName		 																		= "";
	$conv->headline			 																		= "";
	$conv->description	 																		= "";
	$conv->instruction	 																		= "";
	$conv->web					 																		= "";
	$conv->contact			 																		= "";
	$conv->parameter		 																		= "";
	
	$conv->areaDesc			 																		= "";
	$conv->polygon			 																		= "";
	$conv->circle				 																		= "";

	// possible hazard types
	$conv->hazard->type[]																		= "thunderstorm";
	$conv->hazard->type[]																		= "wind";
	$conv->hazard->type[]																		= "rain";
	$conv->hazard->type[]																		= "hail";
	$conv->hazard->type[]																		= "snow";
	$conv->hazard->type[]																		= "ice";
	$conv->hazard->type[]																		= "fog";
	$conv->hazard->type[]																		= "glaze";
	$conv->hazard->type[]																		= "thaw";
	$conv->hazard->type[]																		= "lake";
	$conv->hazard->type[]																		= "frost";
	$conv->hazard->type[]																		= "cold";
	$conv->hazard->type[]																		= "heat";
	$conv->hazard->type[]																		= "coastal";
	$conv->hazard->type[]																		= "flooding";
	$conv->hazard->type[]																		= "rain-flood";
	$conv->hazard->type[]																		= "avalanches";
	
	// possible hazard level
	$conv->hazard->level[] 																	= 0;
	$conv->hazard->level[] 																	= 1;
	$conv->hazard->level[] 																	= 2;
	$conv->hazard->level[] 																	= 3;
	$conv->hazard->level[] 																	= 4;
	$conv->hazard->level[] 																	= 5;
	$conv->hazard->level[] 																	= 6;
	$conv->hazard->level[] 																	= 7;
	$conv->hazard->level[] 																	= 8;
	$conv->hazard->level[] 																	= 9;
	
	$conv->geocode->type 																		= "";
	$conv->geocode->value																		= "";
