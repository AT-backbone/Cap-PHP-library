			$( document ).ready(function() 
			{
				// Inital ClockPicker Addon
				$('.clockpicker').clockpicker().find('input').change(function(){
					console.log(this.value);
				});
				
				updateCapXML();
								
				$( "input, select" ).change(function() {
					updateCapXML();
					updateColor(this);
					dependencies_js();
				});
				
				
				$( "#webservice_switch" ).change(function() {
					if($( "#webservice_switch" ).prop('checked'))
					{
						$("#conf-detail").show();
					}
					else
					{
						$("#conf-detail").hide();
					}
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

				var xhr_of_upcapxml
				function updateCapXML()
				{
					var url = "index.php?cap=1"; // the script where you handle the form input.

					//kill the request
					if(xhr_of_upcapxml && xhr_of_upcapxml.abort) xhr_of_upcapxml.abort()

					xhr_of_upcapxml = $.ajax({
					      	type: "POST",
					        url: url,
					        data: $("#capform").serialize(), // serializes the forms elements.
					        success: function(data)
					        {			
					        	$("#capviewtextarea").val(data);
					        },
							error: function(errorThrown){
								console.log( errorThrown);
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
					        success: function(datare)
					        {
					        	if(datare) alert(datare);
								else $( "#Saved_conf" ).popup( "open" );
								setTimeout(function(){
									window.location = "index.php#conf";
								}, 1500);
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
				
				function updateColor(this_element)
				{
					if($(this_element).val())
					{
						if($( this_element ).is("select"))
						{
							$( this_element ).parent( ).find("a").addClass( "ui-btn-f" ); // its a select
						}
						else
						{
							$( this_element ).parent( ).addClass( "ui-body-f" ); // its a input
						}
					}
					else
					{
						if($( this_element ).is("select"))
						{
							if(!$(this_element).prop('required'))
							{
								$( this_element ).parent( ).find("a").removeClass( "ui-btn-f" ); // its a select
							}
							else
							{
								$( this_element ).parent( ).find("a").removeClass( "ui-btn-f" ).addClass( "ui-btn-c" ); // its a select
							}
						}
						else
						{
							if(!$(this_element).prop('required'))
							{
								$( this_element ).parent( ).removeClass( "ui-body-f" ); // its a input
							}
							else
							{
								$( this_element ).parent( ).removeClass( "ui-body-f" ).addClass( "ui-body-c" ); // its a input
							}
						}
					}
				}