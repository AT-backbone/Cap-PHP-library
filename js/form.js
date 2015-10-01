			$( document ).ready(function() 
			{
				
				updateCapXML();
								
				$( "input, select" ).change(function() {
					updateCapXML();
				});
				
				
				$( "#msgType" ).change(function() {
					if($( "#msgType" ).val() == "Update" || $( "#msgType" ).val() == "Cancel")
					{		
						if(typeof $("#LIreferences").html()  === "undefined")
						{
							$("#TypeMessage").after('<li id="LIreferences" class="ui-li-static ui-body-inherit ui-last-child"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="references" type="text" name="references"></div></li>');
						}
						else
						{
							$("#LIreferences").show();
						}
					}
					else
					{
						if(typeof $("#LIreferences").html()  !== "undefined")
						{
							$("#LIreferences").hide();
						}
					}
				});
				
				$( "#language" ).change(function() {
					
					$( ".lang_input" ).each(function( index )
					{
						if($( "#language" ).val() == $(this).attr("id"))
						{
							$(this).show();
						}
						else
						{
							$(this).hide();
						}					
					});	
					
					$("#" + $( "#language" ).val() + "_Button").show();
					$( "#" + $( "#language" ).val() + "_language_input" ).val($( "#language" ).val());
					
					$( ".Lang_Button" ).each(function( index )
					{
						if( $( "#language" ).val() + "_Button" == $(this).attr("id"))
						{
							$(this).css("box-shadow", "0px 0px 11px rgb(0, 126, 255)");
						}
						else
						{
							$(this).css("box-shadow", "");
						}
					});
					
				});
			});
				function updateCapXML()
				{
					var url = "index.php?cap=1"; // the script where you handle the form input.
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	$("#capviewtextarea").val(data);
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function ajax_conf()
				{
					var url = "index.php?conf=1"; // the script where you handle the form input.
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	location.reload();
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function plusLangInput()
				{
					var url = "index.php?conf=1"; // the script where you handle the form input.
					
					key  = $("#lang_conf_plus_key").val();
					$("#lang_conf_plus_name").attr("name", "conf[lang][" + key + "]");
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	location.reload();
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function minusLangInput()
				{
					var url = "index.php?conf=1"; // the script where you handle the form input.
					
					key  = $("#lang_remove").val();
					$("#lang_remove_input").attr("name", "conf[lang][remove][" + key + "]");
					
					$.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {					        	
					        	location.reload();
					        }
					       });
					
					return false; // avoid to execute the actual submit of the form.
				}
				
				function plusParameterInput()
				{
					$("#Parameterappend").after('<div class="ui-grid-b"><div class="ui-block-a"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Valuename" type="text" name="parameter[valueName][]"></div></div><div class="ui-block-b"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Value" type="text" name="parameter[value][]"></div></div><div class="ui-block-c"></div></div>');
				}
				
				function plusEventCodeInput()
				{
					$("#Eventappend").after('<div class="ui-grid-b"><div class="ui-block-a"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Valuename" type="text" name="eventCode[valueName][]"></div></div><div class="ui-block-b"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Value" type="text" name="eventCode[value][]"></div></div><div class="ui-block-c"></div></div>');
				}
				
				function plusGeocodeInput()
				{
					$("#Geocodeappend").after('<div class="ui-grid-b"><div class="ui-block-a"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Valuename" type="text" name="geocode[valueName][]"></div></div><div class="ui-block-b"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input placeholder="Value" type="text" name="geocode[value][]"></div></div><div class="ui-block-c"></div></div>');
				}