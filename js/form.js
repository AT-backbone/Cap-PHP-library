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

	$('div').on('pageshow',function(event, ui){
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
	} );
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
	var aktive_type = false;
	var aktive_level = false;
	var something_changed = false;
	var warning_detail_changed = false;
	function ini_meteo_map()
	{
		changes_arr['idx'] = [];
		changes_arr['change'] = [];

		$(window).bind('beforeunload', function(){
			if(something_changed)
			{
				return 'Are you sure you want to leave?';
			}
		});

		$('input, textarea').change(function() {
			changes_arr['change'].push(this.value);
			changes_arr['idx'].push(this.id);
			changes_arr['last'] = (changes_arr['idx'].length - 1);
			//console.log( changes_arr );
			something_changed = true;
			if(aktive_warning_aid > 0)
			{
				warning_detail_changed = true;
			}
		});

		$('#Undo').click(function() {
			document.execCommand('undo', false, null);
		});

		$('#Redo').click(function() {
			document.execCommand('redo', false, null);
		});

		$('#submit_cap').on('click', function(){
			get_all_warnings();
		});

		$('#sav_war').on('click', function(){
			save_warning_detail();
		});	

		$('#del_war').on('click', function(){
			delete_warning_detail();
		});

		$('#green_no').on('click', function(){
			make_white_areas_green(0);
		});

		$('#green_yes').on('click', function(){
			make_white_areas_green(1);
		});

		$('#send_no').on('click', function(){
			send_all_proce_cap(0);
		});

		$('#send_yes').on('click', function(){
			send_all_proce_cap(1);
		});


		$('li:not(#map-container)').on('click', function(){
			aktive_type = false;
			$('#awareness_toolbox .awareness').css('border', '');
			$('#awareness_toolbox .awareness').css('opacity', 1);
			aktive_level = false;
			$('#awareness_color_toolbox .awareness').css('border', '');
			$('#awareness_color_toolbox .awareness').css('opacity', 1);
			$('#svg-id').css('cursor', 'auto');
			$('#awareness_color_toolbox').css('display', '');
		});
		$('#awareness_toolbox .awareness').on('click', function(){
			if(aktive_type != $(this).attr('type'))
			{
				$('#awareness_toolbox .awareness').css('opacity', 0.5);
				$(this).css('opacity', 1);
				$('#awareness_toolbox .awareness').css('border', '');
				$(this).css('border', '1px solid #0000ff');
				var position = $(this).position();
				$('#awareness_color_toolbox').css('top', (position.top - 16));
				$('#awareness_color_toolbox').css('display', 'block');
				aktive_type = parseInt($(this).attr('type'));
			}
			else
			{
				aktive_type = false;
				$('#awareness_toolbox .awareness').css('border', '');
				$('#awareness_toolbox .awareness').css('opacity', 1);
				aktive_level = false;
				$('#awareness_color_toolbox .awareness').css('border', '');
				$('#awareness_color_toolbox .awareness').css('opacity', 1);
				$('#svg-id').css('cursor', 'auto');
				$('#awareness_color_toolbox').css('display', '');
			}
		});

		$('#awareness_color_toolbox .awareness').on('click', function(){
			if(aktive_level != $(this).attr('level'))
			{
				$('#awareness_color_toolbox .awareness').css('opacity', 0.5);
				$(this).css('opacity', 1);
				$('#awareness_color_toolbox .awareness').css('border', '');
				$(this).css('border', '1px solid #0000ff');
				aktive_level = parseInt($(this).attr('level'));
				$('#svg-id').css('cursor', 'url(includes/meteoalarm/fill.png), auto');
			}
			else
			{
				aktive_level = false;
				$('#awareness_color_toolbox .awareness').css('border', '');
				$('#awareness_color_toolbox .awareness').css('opacity', 1);
				$('#svg-id').css('cursor', 'auto');
			}
		});

		/*
		area_text
		area_to
		area_from
		area_level
		area_type
		area_name
		*/


		svg = d3.select("#svg-id");
		$('#svg-id polygon').on('click', function(){
			calc_map_aktion(this, 'user');
		});
	}
	var level_c = [];
	level_c[0] = '#ffffee';
	level_c[1] = '#99FF99';
	level_c[2] = '#FFFF66';
	level_c[3] = '#FFCC33';
	level_c[4] = '#FF6666';
	level_c[5] = '#AF0064';

	function calc_map_aktion(tmp_this, is_auto)
	{
		/*Get*/
		lang_1 = $('#lang_1').val();
		cinfo = parseInt($('.'+$(tmp_this).attr('class')).attr('cinfo'));
		aid = parseInt($('.'+$(tmp_this).attr('class')).attr('aid'));
		area_arr[aid] = [];
		area_arr[aid]['name'] = $('.'+$(tmp_this).attr('class')).attr('area_name');

		for (ty=0; ty < cinfo; ty++) 
		{	//aid
			level = $('.'+$(tmp_this).attr('class')).attr('area_level_'+ty+'');
			type = $('.'+$(tmp_this).attr('class')).attr('area_type_'+ty+'');
			text = $('.'+$(tmp_this).attr('class')).attr('area_text_'+ty+'');
			text_0 = $('.'+$(tmp_this).attr('class')).attr('area_text_'+ty+'_en-gb');
			inst_0 = $('.'+$(tmp_this).attr('class')).attr('area_inst_'+ty+'_en-gb');
			text_1 = $('.'+$(tmp_this).attr('class')).attr('area_text_'+ty+'_'+lang_1);
			inst_1 = $('.'+$(tmp_this).attr('class')).attr('area_inst_'+ty+'_'+lang_1);
			to = $('.'+$(tmp_this).attr('class')).attr('area_to_'+ty+'');
			from = $('.'+$(tmp_this).attr('class')).attr('area_from_'+ty+'');
			ident = $('.'+$(tmp_this).attr('class')).attr('area_ident_'+ty+'');
			area_arr[aid][ty] = [];
			
			area_arr[aid][ty]['level'] 	= level;
			area_arr[aid][ty]['type'] 	= type;
			area_arr[aid][ty]['text'] 	= text;
			area_arr[aid][ty]['text_0']	= text_0;
			area_arr[aid][ty]['inst_0']	= inst_0;
			area_arr[aid][ty]['text_1']	= text_1;
			area_arr[aid][ty]['inst_1']	= inst_1;
			area_arr[aid][ty]['from'] 	= from;
			area_arr[aid][ty]['to'] 	= to;
			area_arr[aid][ty]['ident']	= ident;
		}

		if(aktive_level != false && aktive_type != false)
		{
			// Paint mode !!!
			/*
				color: 1 #29d660  2 #ffff00  3 #fecb31  4 #fe0104 
			*/
			processing = true;
			tmp_wid = 0;
			cinfo = parseInt($('.'+$(tmp_this).attr('class')).attr('cinfo'));
			aid = parseInt($('.'+$(tmp_this).attr('class')).attr('aid'));
			
			$.each(area_arr[aid], function (key, data) {
				if(data !== undefined)
				{
					if(parseInt(aktive_type) == parseInt(data['type']))
					{
						//console.log(data);
						processing = false;
						tmp_wid = key;
					}
				}
			});

			if(processing && cinfo < 3)
			{
				$(tmp_this).css('fill', level_c[aktive_level]);

				ty = cinfo;
				area_arr[aid][ty] = [];
				area_arr[aid][ty]['level'] 	= parseInt(aktive_level);
				area_arr[aid][ty]['type'] 	= parseInt(aktive_type);
				area_arr[aid][ty]['text'] 	= "";
				area_arr[aid][ty]['text_0']	= "";
				area_arr[aid][ty]['inst_0']	= "";
				area_arr[aid][ty]['text_1']	= "";
				area_arr[aid][ty]['inst_1']	= "";

				var date = new Date();
				area_arr[aid][ty]['to'] 	= date.yyyymmdd()+" 23:59:59";
				area_arr[aid][ty]['from'] 	= date.yyyymmdd()+" 00:00:00";
				
				if(cinfo != 3) $('.'+$(tmp_this).attr('class')).attr('cinfo', cinfo + 1 );

				$(tmp_this).attr('area_level_'+ty+'', area_arr[aid][ty]['level']);
				$(tmp_this).attr('area_type_'+ty+'', area_arr[aid][ty]['type']);

				$(tmp_this).attr('area_to_'+ty+'', area_arr[aid][ty]['to']);
				$(tmp_this).attr('area_from_'+ty+'', area_arr[aid][ty]['from']);
				$(tmp_this).attr('area_ident_'+ty+'', area_arr[aid][ty]['ident']);
				//console.log(area_arr[aid][ty]);
				//$(tmp_this).attr('area_type['+cinfo+']', aktive_type);
			}
			else if(processing == false)
			{
				area_arr[aid][tmp_wid]['level'] = parseInt(aktive_level);
				$(tmp_this).attr('area_level_'+tmp_wid+'', area_arr[aid][tmp_wid]['level']);
				//console.log(area_arr[aid][ty]);
			}

			tmp_level = 0;
			$.each(area_arr[aid], function (key, data) {
				if(data !== undefined)
				{
					if(parseInt(data['level']) > tmp_level)
					{
						tmp_level = parseInt(data['level']);
					}
				}
			});
			$(tmp_this).css('fill', level_c[tmp_level]);
		}
		else
		{

			tmp_level = 0;
			$.each(area_arr[aid], function (key, data) {
				if(data !== undefined)
				{
					if(parseInt(data['level']) > tmp_level)
					{
						tmp_level = parseInt(data['level']);
					}
				}
			});
			$(tmp_this).css('fill', level_c[tmp_level]);
		}
		/*Show*/
		if(is_auto != "auto")
		{
			if($('.'+$(tmp_this).attr('class')).attr('sel') == 1 && aktive_level == false && aktive_type == false)
			{
				$('.'+$(tmp_this).attr('class')).css('stroke-width', '1');
				$('.'+$(tmp_this).attr('class')).css('stroke', 'black');
				$('.'+$(tmp_this).attr('class')).css('fill-opacity', 1);
				$('.'+$(tmp_this).attr('class')).attr('sel', 0);
				area_arr[aid].pop();
				delete(area_arr[aid]);
			}
			else
			{
				$('.'+$(tmp_this).attr('class')).css('stroke-width', '3px');
				$('.'+$(tmp_this).attr('class')).css('stroke', 'blue');
				$('.'+$(tmp_this).attr('class')).css('fill-opacity', 0.6);
				$('.'+$(tmp_this).attr('class')).attr('sel', 1);
			}
		}
		// show Area in Proccess list
		out = "";
		area_real_len = 0;
		tmp_problem = 0;
		$.each(area_arr, function (key, data) {
			if(data !== undefined)
			{
				value = data;
				area_real_len++;
				if(area_list_on == false)
				{
					$('#work_toolbox').animate({
						right: ($('#process_toolbox').width())+"px"
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
					coun = 1;
					$.each(value, function (key2, data) {
						if(data !== undefined && coun <= 3)
						{
							coun++;
							// Warnungen
							tmp_level = parseInt(data['level']);
							tmp_type = parseInt(data['type']);
							tmp_text_0 = data['text_0'];
							tmp_inst_0 = data['inst_0'];
							tmp_text_1 = data['text_1'];
							tmp_inst_1 = data['inst_1'];
							tmp_to = data['to'];
							tmp_from = data['from'];
							tmp_ident = data['ident'];

							if((tmp_text_0 == "" || tmp_text_0 === undefined) && (tmp_text_1 == "" || tmp_text_1 === undefined)) problem = 1;
							else problem = 0;

							if(tmp_type < 10) tmp_type_f = '0'+tmp_type;
							else tmp_type_f = tmp_type;
							//out+= ' ' + tmp_level + ' ' + tmp_type + ' ';
							if(problem)	out+= '<div class="problem awareness level_'+tmp_level+'" ktive="1" onclick="area_warning_detail('+key+', '+key2+', this)"><img src="includes/meteoalarm/warn-typs_'+tmp_type_f+'.png"><span class="problem_callsign">!</span></div>';
							else 		out+= '<div class="awareness level_'+tmp_level+'" aktive="1" onclick="area_warning_detail('+key+', '+key2+', this)"><img src="includes/meteoalarm/warn-typs_'+tmp_type_f+'.png"></div>';
							if(problem) tmp_problem = 1;
						}
					});
					
					if(tmp_problem)	out+= '<div class="divtextscroll problem_text">' + value['name'] + '</div>'; // name
					else 			out+= '<div class="divtextscroll">' + value['name'] + '</div>'; // name
				out+= '</div>';
			}
		});

		if(area_real_len < 1)
		{
			$('#work_toolbox').animate({
				right: "-1px"
			}, 500);
			$('#process_toolbox').animate({
				right: "-"+($('#process_toolbox').width() + 2)+"px"
			}, 500);
			area_list_on = false;
		}

		$('#process_toolbox').html(out);
		delete(out);
	}

	var aktive_warning_aid;
	var aktive_warning_key;
	function area_warning_detail(aid, key_type, tmp_this)
	{
		if(warning_detail_changed == true) 
		{
			if(!confirm($('#chang_without_save').val()))
			{
				change_aktive = false
			}
			else 
			{
				change_aktive = true;
				warning_detail_changed = false;
			}
		}
		else
		{
			change_aktive = true;
		}
		if(change_aktive)
		{
			//console.log($('#svg-id polygon[aid='+aid+']').attr('area_name'));
			aktive_warning_aid = aid;
			aktive_warning_key = key_type;
			$('.process_toolbox_area .awareness').css('border', '');
			$(tmp_this).css('border', '1px solid #0000ff');
			if(key_type > -1)
			{
				lang_1 = $('#lang_1').val();
				tmp_area_name 		= $('#svg-id polygon[aid='+aid+']').attr('area_name');
				tmp_area_text_0 	= $('#svg-id polygon[aid='+aid+']').attr('area_text_'+key_type+'_en-gb');
				tmp_area_inst_0 	= $('#svg-id polygon[aid='+aid+']').attr('area_inst_'+key_type+'_en-gb');
				tmp_area_text_1 	= $('#svg-id polygon[aid='+aid+']').attr('area_text_'+key_type+'_'+lang_1+'');
				tmp_area_inst_1 	= $('#svg-id polygon[aid='+aid+']').attr('area_inst_'+key_type+'_'+lang_1+'');
				tmp_area_to 		= $('#svg-id polygon[aid='+aid+']').attr('area_to_'+key_type+'');
				tmp_area_from 		= $('#svg-id polygon[aid='+aid+']').attr('area_from_'+key_type+'');

				$('#desc_0').val('');
				$('#inst_0').val('');
				$('#desc_1').val('');
				$('#inst_1').val('');
				$('#AreaDetailDIV').css('background-color', '#ffffff');
				$('#AreaDetailUL').css('opacity', 1);
				$('#AreaDetailUL').css('pointer-events', 'auto ');

				$('#left_area_name').html(tmp_area_name);

				$('#desc_0').val((tmp_area_text_0)).trigger('input');
				$('#inst_0').val((tmp_area_inst_0)).trigger('input');
				$('#desc_1').val((tmp_area_text_1)).trigger('input');
				$('#inst_1').val((tmp_area_inst_1)).trigger('input');

				if(tmp_area_from.length > 11)
				{
					$('#from_0').val(tmp_area_from.slice(11)).trigger('input');
				}
				else
				{
					$('#from_0').val(tmp_area_from).trigger('input');
				}
				if(tmp_area_to.length > 11)
				{
					$('#to_0').val(tmp_area_to.slice(11)).trigger('input');
				}
				else
				{
					$('#to_0').val(tmp_area_to).trigger('input');	
				}
			}
			else
			{
				$('#desc_0').val('').trigger('input');
				$('#inst_0').val('').trigger('input');
				$('#desc_1').val('').trigger('input');
				$('#inst_1').val('').trigger('input');
				$('#from_0').val('00:00').trigger('input');
				$('#to_0').val('00:00').trigger('input');
				tmp_area_name = $('#svg-id polygon[aid='+aid+']').attr('area_name');
				$('#left_area_name').html(tmp_area_name).trigger('input');
				$('#AreaDetailDIV').css('background-color', '#cccccc');
				$('#AreaDetailUL').css('pointer-events', 'none');
				$('#AreaDetailUL').css('opacity', 0.5);
			}
		}
	}

	function save_warning_detail()
	{
		//aktive_warning_aid
		//aktive_warning_key
		if(aktive_warning_key > -1)
		{
			lang_1 = $('#lang_1').val();
			area_arr[aktive_warning_aid][aktive_warning_key]['text_0']	= $('#desc_0').val();
			area_arr[aktive_warning_aid][aktive_warning_key]['inst_0']	= $('#inst_0').val();
			area_arr[aktive_warning_aid][aktive_warning_key]['text_1']	= $('#desc_1').val();
			area_arr[aktive_warning_aid][aktive_warning_key]['inst_1']	= $('#inst_1').val();
			area_arr[aktive_warning_aid][aktive_warning_key]['from'] 	= $('#from_0').val();
			area_arr[aktive_warning_aid][aktive_warning_key]['to'] 		= $('#to_0').val();
			$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_text_'+aktive_warning_key+'_en-gb', area_arr[aktive_warning_aid][aktive_warning_key]['text_0']);
			$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+aktive_warning_key+'_en-gb', area_arr[aktive_warning_aid][aktive_warning_key]['inst_0']);
			$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_text_'+aktive_warning_key+'_'+lang_1+'', area_arr[aktive_warning_aid][aktive_warning_key]['text_1']);
			$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+aktive_warning_key+'_'+lang_1+'', area_arr[aktive_warning_aid][aktive_warning_key]['inst_1']);
			$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_to_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['to']);
			$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_from_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['from']);
			$('#from_0').val('');
			$('#to_0').val('');
			$('#desc_0').val('');
			$('#inst_0').val('');
			$('#desc_1').val('');
			$('#inst_1').val('');
			$('#AreaDetailDIV').css('background-color', '#cccccc');
			$('#AreaDetailUL').css('pointer-events', 'none');
			$('#AreaDetailUL').css('opacity', 0.5);
			$('.process_toolbox_area .awareness').css('border', '');
			calc_map_aktion($('#svg-id polygon[aid='+aktive_warning_aid+']'), 'auto');
			warning_detail_changed = false;
			something_changed = true;
		}
	}

	function delete_warning_detail()
	{
		if(aktive_warning_aid > 0 && confirm($('#del_warn_lang').val()))
		{
			something_changed = true;
			cinfo = parseInt($('#svg-id polygon[aid='+aktive_warning_aid+']').attr('cinfo'));
			lang_1 = $('#lang_1').val();
			
			for (ty=0; ty < cinfo; ty++) 
			{
				level 	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_level_'+ty+'');
				type 	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_type_'+ty+'');
				text 	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'');
				text_text_0	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'_en-gb');
				text_inst_0	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+ty+'_en-gb');
				text_text_1	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'_'+lang_1+'');
				text_inst_1	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+ty+'_'+lang_1+'');
				to 		= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_to_'+ty+'');
				from 	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_from_'+ty+'');
				ident 	= $('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_ident_'+ty+'');
				area_arr[aid][ty] = [];
				
				area_arr[aid][ty]['level']	= level;
				area_arr[aid][ty]['type']	= type;
				area_arr[aid][ty]['text']	= text;
				area_arr[aid][ty]['to']		= to;
				area_arr[aid][ty]['from']	= from;
				area_arr[aid][ty]['ident']	= ident;
			}
			delete(area_arr[aid][aktive_warning_key]);

			$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('cinfo', cinfo - 1);
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_level_'+aktive_warning_key+'');
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_type_'+aktive_warning_key+'');
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_text_'+aktive_warning_key+'_en-gb');
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_inst_'+aktive_warning_key+'_en-gb');
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_text_'+aktive_warning_key+'_'+lang_1+'');
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_inst_'+aktive_warning_key+'_'+lang_1+'');
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_to_'+aktive_warning_key+'');
			$('#svg-id polygon[aid='+aktive_warning_aid+']').removeAttr('area_from_'+aktive_warning_key+'');

			tmp_key = 0;
			$.each(area_arr[aid], function (key2, data) {
				if(data !== undefined)
				{
					// Warnungen
					tmp_level = parseInt(data['level']);
					tmp_type = parseInt(data['type']);
					tmp_text = data['text'];
					tmp_to = data['to'];
					tmp_from = data['from'];
					tmp_ident = data['ident'];

					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_level_'+tmp_key+'', tmp_level);
					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_type_'+tmp_key+'', tmp_type);
					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_text_'+tmp_key+'_en-gb', text_text_0);
					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+tmp_key+'_en-gb', text_inst_0);
					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_text_'+tmp_key+'_'+lang_1+'', text_text_1);
					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+tmp_key+'_'+lang_1+'', text_inst_1);
					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_to_'+tmp_key+'', tmp_to);
					$('#svg-id polygon[aid='+aktive_warning_aid+']').attr('area_from_'+tmp_key+'', tmp_from);
					tmp_key++;
				}
			});

			$('#desc_0').val('');
			$('#inst_0').val('');
			$('#desc_1').val('');
			$('#inst_1').val('');
			$('#AreaDetailDIV').css('background-color', '#cccccc');
			$('#AreaDetailUL').css('opacity', 0.5);

			$('#left_area_name').html('');
			calc_map_aktion($('#svg-id polygon[aid='+aktive_warning_aid+']'), 'auto');
		}
	}

	var area_arr_final = {};
	var area_green = {};
	function get_all_warnings()
	{
		$('svg polygon').each(function (key, data) {
			lang_1 = $('#lang_1').val();
			cinfo = parseInt($(this).attr('cinfo'));
			aid = parseInt($(this).attr('aid'));
			eid = $(this).attr('eid');
			if(area_arr_final[aid] === undefined)
			{
				area_arr_final[aid] = {};
				if(cinfo < 1) cinfo = 1;
				for (ty=0; ty < cinfo; ty++) 
				{
					area_arr_final[aid][ty] = {};
					area_arr_final[aid][ty]['name'] = $(this).attr('area_name');
					area_arr_final[aid][ty]['eid'] = eid;

					level 	= parseInt($(this).attr('area_level_'+ty+''));
					type 	= parseInt($(this).attr('area_type_'+ty+''));
					text 	= $(this).attr('area_text_'+ty+'');
					text_0 	= $(this).attr('area_text_'+ty+'_en-gb');
					inst_0 	= $(this).attr('area_inst_'+ty+'_en-gb');
					text_1 	= $(this).attr('area_text_'+ty+'_'+lang_1);
					inst_1 	= $(this).attr('area_inst_'+ty+'_'+lang_1);
					to 		= $(this).attr('area_to_'+ty+'');
					from 	= $(this).attr('area_from_'+ty+'');
					ident 	= $(this).attr('area_ident_'+ty+'');
					
					if(level > 0 && type > 0)
					{
						area_arr_final[aid][ty]['eid'] 		= eid;
						area_arr_final[aid][ty]['level'] 	= level;
						area_arr_final[aid][ty]['type'] 	= type;
						area_arr_final[aid][ty]['text'] 	= text;
						area_arr_final[aid][ty]['text_0']	= text_0;
						area_arr_final[aid][ty]['inst_0']	= inst_0;
						area_arr_final[aid][ty]['text_1']	= text_1;
						area_arr_final[aid][ty]['inst_1']	= inst_1;
						area_arr_final[aid][ty]['from'] 	= from;
						area_arr_final[aid][ty]['to'] 		= to;
						area_arr_final[aid][ty]['ident']	= ident;
					}

					delete(level, type ,text ,text_0,inst_0,text_1,inst_1,to,from,ident);
				}
			}
		});
		console.log(area_arr_final);
		var jsonOb = JSON.stringify(area_arr_final);

		$.post(
			"lib/cap.create.from_js_array.php",
			{cap_array:jsonOb},
			function(r){
				//your success response
				//alert('OK!');
				area_green = jQuery.parseJSON(r);
				if(area_green !== null)
				{
					send_final(r);
				}
				else
				{
					make_white_areas_green(0);
				}
			}
		);
	}

	function send_final(r)
	{
		//console.log(r);
		var content = '<form>';
		$.each(jQuery.parseJSON(r), function(index, data) {
			// file position and name: data
				content+= '<div class="cbGroup'+data['aid']+'">';
					content+= '<label for="checkbox-'+data['aid']+'">'+data['name']+'</label>';
				content+= '</div>';
		});
		content+= '</form>';

		$('[type="checkbox"]').checkboxradio();
		$('[type="checkbox"]').checkboxradio("refresh");

		$('#set').append( content ).trigger('create');
		$('#set').collapsibleset( "refresh" );
		
		$.each(jQuery.parseJSON(r), function(index, data) {
			var newBox = '<input type="checkbox" name="checkbox-'+data['aid']+'" id="checkbox-'+data['aid']+'" checked="checked"/>';
			$(".cbGroup"+data['aid']).append(newBox).trigger('create');
		});

		$('div .ui-checkbox').css('margin', '-1px 0');

		$('#CAPpopupDialog').popup();
		$('#CAPpopupDialog').popup( "open" );
	}

	var area_green_final = {};
	function make_white_areas_green(yesno)
	{
		if(yesno == 1)
		{
			var date = new Date();
			console.log(area_green);
			$.each(area_green, function(index, data){
				aid = data['aid'];
				if(area_green_final[aid] === undefined)
				{
					area_green_final[aid] = {};
					cinfo = 3;
					for (ty=0; ty < cinfo; ty++) 
					{
						area_green_final[aid][ty] = {};
						area_green_final[aid][ty]['name'] = data['name'];

						level 	= 1;
						type 	= (ty + 1);
						//to 		= 
						//from 	= 
						
						if(level > 0 && type > 0)
						{
							area_green_final[aid][ty]['eid'] 	= data['eid'];
							area_green_final[aid][ty]['level'] 	= level;
							area_green_final[aid][ty]['type'] 	= type;
							area_green_final[aid][ty]['text_0']	= 'no warning';
							area_green_final[aid][ty]['from'] 	= date.yyyymmdd() + ' 00:00:00';
							area_green_final[aid][ty]['to'] 	= date.yyyymmdd() + ' 23:59:59';
						}
					}
				}
			});

			var jsonOb = JSON.stringify(area_green_final);

			$.post(
				"lib/cap.create.from_js_array.php",
				{cap_array:jsonOb, no_del:1},
				function(r){
					//your success response
					//alert('OK!');
					area_green_final_resp = r; // shoud be NULL
					//send_final(r);
				}
			);
		}

		$('#CAPpopupDialog').popup( "close" );
		setTimeout(function(){
        	$('#CAP_Send_popupDialog').popup();
			$('#CAP_Send_popupDialog').popup( "open" );
        }, 100);
		
	}

	function send_all_proce_cap(yesno)
	{
		if(yesno == 1)
		{
			$.post(
				"lib/cap.meteoalarm.webservices.multi_import.php",
				{no_del:1},
				function(r){
					//your success response
					alert('OK!');
					$('#CAP_Send_popupDialog').popup( "close" );
					//send_final(r);
				}
			);
		}
		$('#CAP_Send_popupDialog').popup( "close" );
	}

	function policlick(aid)
	{
		$('[sel=0]').css('stroke', 'black');
		$('[sel=0]').css('stroke-width', '1');

		$('.pol_'+aid).css('stroke-width', '3px');
		$('.pol_'+aid).css('stroke', 'blue');
	}

	function poliaway(aid)
	{
		if($('.pol_'+aid).attr('sel') == 0)
		{
			$('.pol_'+aid).css('stroke', 'black');
			$('.pol_'+aid).css('stroke-width', '1');
		}
	}

	Date.prototype.yyyymmdd = function() {
		var yyyy = this.getFullYear().toString();
		var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
		var dd  = this.getDate().toString();
		return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
	};