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