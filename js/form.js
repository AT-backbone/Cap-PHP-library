$( document ).ready(function() 
{
	// Inital ClockPicker Addon
	$('.clockpicker').clockpicker().find('input').change(function(){
		//console.log(this.value);
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

	ini_meteo_map();
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
		        success: function(data)
		        {					        
							$( "#Saved_conf" ).popup( "open" );
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

	var time = Date.now || function() {
		return +new Date;
	};

	var pol_sel = 0;
	var svg;
	var area_arr = []; // areay with all area names
	var area_list_on = false;
	var changes_arr = [];
	function ini_meteo_map()
	{
		$( window ).resize(function() {
			$('#svg-id').attr('width', $('#canvas').width());
			$('#svg-id').attr('height', $('#canvas').height());
		});
		$(function() {
			$('#svg-id').attr('width', $('#canvas').width());
			$('#svg-id').attr('height', $('#canvas').height());
			panZoomInstance = svgPanZoom('#svg-id', {
				zoomEnabled: true,
				zoomScaleSensitivity: 0.5,
				dblClickZoomEnabled: false,
				preventMouseEventsDefault: false, 
				controlIconsEnabled: true,
				fit: true,
				center: true,
				minZoom: 0.5,
			});
		})

		changes_arr['idx'] = [];
		changes_arr['change'] = [];

		$('input, textarea').change(function() {
			changes_arr['change'].push(this.value);
			changes_arr['idx'].push(this.id);
			changes_arr['last'] = (changes_arr['idx'].length - 1);
			console.log( changes_arr );
		});

		$('#Undo').click(function() {
			document.execCommand('undo', false, null);
		});

		$('#Redo').click(function() {
			document.execCommand('redo', false, null);
		});

		svg = d3.select("#svg-id");
		/*
		area_text
		area_to
		area_from
		area_level
		area_type
		area_name
		*/
		$('#svg-id polygon').on('click', function(){

			/*Get*/
			cinfo = parseInt($('.'+$(this).attr('class')).attr('cinfo'));
			aid = parseInt($('.'+$(this).attr('class')).attr('aid'));
			area_arr[aid] = [];
			area_arr[aid]['name'] = $('.'+$(this).attr('class')).attr('area_name');

			for (ty=0; ty < cinfo; ty++) 
			{	//aid
				level = $('.'+$(this).attr('class')).attr('area_level['+ty+']');
				type = $('.'+$(this).attr('class')).attr('area_type['+ty+']');
				text = $('.'+$(this).attr('class')).attr('area_text['+ty+']');
				to = $('.'+$(this).attr('class')).attr('area_to['+ty+']');
				from = $('.'+$(this).attr('class')).attr('area_from['+ty+']');
				ident = $('.'+$(this).attr('class')).attr('area_ident['+ty+']');
				area_arr[aid][ty] = [];
				
				area_arr[aid][ty]['level'] 	= level;
				area_arr[aid][ty]['type'] 	= level;
				area_arr[aid][ty]['text'] 	= text;
				area_arr[aid][ty]['to'] 	= to;
				area_arr[aid][ty]['from'] 	= from;
				area_arr[aid][ty]['ident']	= ident;
			}

			/*Show*/

			if($('.'+$(this).attr('class')).attr('sel') == 1)
			{
				$('.'+$(this).attr('class')).css('stroke-width', '1');
				$('.'+$(this).attr('class')).css('stroke', 'grey');
				$('.'+$(this).attr('class')).css('fill-opacity', 1);
				$('.'+$(this).attr('class')).attr('sel', 0);
				area_arr[aid].pop();
				delete(area_arr[aid]);
			}
			else
			{
				$('.'+$(this).attr('class')).css('stroke-width', '3px');
				$('.'+$(this).attr('class')).css('stroke', 'blue');
				$('.'+$(this).attr('class')).css('fill-opacity', 0.6);
				$('.'+$(this).attr('class')).attr('sel', 1);
			}

			// show Area in Proccess list
				out = "";
				area_real_len = 0;
				$.each(area_arr, function (key, data) {
					if(data !== undefined)
					{
						value = data;
						area_real_len++;
						if(area_list_on == false)
						{
							$('#work_toolbox').animate({
								right: $('#process_toolbox').width()+"px"
							}, 500);
							$('#process_toolbox').animate({
								right: "-1px"
							}, 500);
							area_list_on = true;
						}

						out+= '<div class="process_toolbox_area" id="aid_'+key+'">';
							
							for (noty=1; noty <= (3 - value.length); noty++) 
							{
								out+= '<div class="awareness" aktive="2" onclick="area_warning_detail('+key+', -1, this)"><img src="includes/meteoalarm/warn-typs_11.png"></div>';
							}

							$.each(value, function (key2, data) {
								if(data !== undefined)
								{
									// Warnungen
									tmp_level = parseInt(data['level']);
									tmp_type = parseInt(data['type']);
									tmp_text = data['text'];
									tmp_to = data['to'];
									tmp_from = data['from'];
									tmp_ident = data['ident'];

									if(tmp_type < 10) tmp_type_f = '0'+tmp_type;
										//out+= ' ' + tmp_level + ' ' + tmp_type + ' ';
									out+= '<div class="awareness level_'+tmp_level+'" aktive="1" onclick="area_warning_detail('+key+', '+key2+', this)"><img src="includes/meteoalarm/warn-typs_'+tmp_type_f+'.png"></div>';
								}
							});

							out+= '<div class="divtextscroll">' + value['name'] + '</div>'; // name
						out+= '</div>';
					}
				});

				if(area_real_len < 1)
				{
					$('#work_toolbox').animate({
						right: "0px"
					}, 500);
					$('#process_toolbox').animate({
						right: "-"+($('#process_toolbox').width() + 1)+"px"
					}, 500);
					area_list_on = false;
				}

				$('#process_toolbox').html(out);
				delete(out);
		});
	}

	function area_warning_detail(aid, key_type, tmp_this)
	{
		//console.log($('#svg-id polygon[aid='+aid+']').attr('area_name'));
		$('.process_toolbox_area .awareness').css('border', '');
		$(tmp_this).css('border', '1px solid #0000ff');
		if(key_type > -1)
		{
			lang_1 = $('#lang_1').val();
			tmp_area_name 		= $('#svg-id polygon[aid='+aid+']').attr('area_name');
			tmp_area_text_0 	= $('#svg-id polygon[aid='+aid+']').attr('area_text['+key_type+'][en-gb]');
			tmp_area_inst_0 	= $('#svg-id polygon[aid='+aid+']').attr('area_inst['+key_type+'][en-gb]');
			tmp_area_text_1 	= $('#svg-id polygon[aid='+aid+']').attr('area_text['+key_type+']['+lang_1+']');
			tmp_area_inst_1 	= $('#svg-id polygon[aid='+aid+']').attr('area_inst['+key_type+']['+lang_1+']');
			tmp_area_to 		= $('#svg-id polygon[aid='+aid+']').attr('area_to['+key_type+']');
			tmp_area_from 		= $('#svg-id polygon[aid='+aid+']').attr('area_from['+key_type+']');

			$('#desc_0').html('');
			$('#inst_0').html('');
			$('#desc_1').html('');
			$('#inst_1').html('');
			$('#AreaDetailUL').css('opacity', 1);

			$('#left_area_name').html(tmp_area_name);

			$('#desc_0').html((tmp_area_text_0)).trigger('input');
			$('#inst_0').html((tmp_area_inst_0)).trigger('input');
			$('#desc_1').html((tmp_area_text_1)).trigger('input');
			$('#inst_1').html((tmp_area_inst_1)).trigger('input');

			$('#from_0').val(tmp_area_from.slice(11)).trigger('input');
			$('#to_0').val(tmp_area_to.slice(11)).trigger('input');
		}
		else
		{
			$('#desc_0').html('').trigger('input');
			$('#inst_0').html('').trigger('input');
			$('#desc_1').html('').trigger('input');
			$('#inst_1').html('').trigger('input');
			$('#from_0').val('00:00').trigger('input');
			$('#to_0').val('00:00').trigger('input');
			tmp_area_name = $('#svg-id polygon[aid='+aid+']').attr('area_name');
			$('#left_area_name').html(tmp_area_name).trigger('input');
			$('#AreaDetailUL').css('opacity', 1);
		}
	}

	function policlick(aid)
	{
		$('[sel=0]').css('stroke', 'grey');
		$('[sel=0]').css('stroke-width', '1');

		$('.pol_'+aid).css('stroke-width', '3px');
		$('.pol_'+aid).css('stroke', 'blue');
	}

	function poliaway(aid)
	{
		if($('.pol_'+aid).attr('sel') == 0)
		{
			$('.pol_'+aid).css('stroke', 'grey');
			$('.pol_'+aid).css('stroke-width', '1');
		}
	}