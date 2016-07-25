$( document ).ready(function() 
{
	// Inital ClockPicker Addon
	$('.clockpicker').clockpicker().find('input').change(function(){
		//console.log(this.value);
	});
	
	if($('#init_map').val() != 1)
	{
		updateCapXML();
	}
		
	if($('#init_map').val() != 1)
	{
		$( "input, select" ).change(function() {
			updateCapXML();
			updateColor(this);
			dependencies_js();
		});
	}
	
	
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

	if($('#init_map').val() == 1 && $('#plugin').val() != 1)
	{
		ini_meteo_map();
	}
	else if($('#init_map').val() == 1 && $('#plugin').val() == 1)
	{
		init_plugin_map();
	}

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
		        	$("#capviewtextarea").textinput( "refresh" );
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
		        	if(datare != "") alert(datare);
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
		if($('#init_map').val() != 1) // not wehn PAINT and ALERT aktive !!!
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
	var mk_pro_interval;
	var aktive_types;
	function ini_meteo_map()
	{
		$('div').on('pageshow',function(event, ui){
			if($('#map_main_div svg').html())
			{
				panZoomInstance = svgPanZoom('#map_main_div svg', {
					zoomEnabled: true,
					zoomScaleSensitivity: 0.5,
					dblClickZoomEnabled: false,
					preventMouseEventsDefault: false, 
					controlIconsEnabled: true,
					fit: true,
					center: true,
					minZoom: 0.5,
				});
			}
		});

		$('#CountryInfo').html($('#map_main_div svg').attr('country'));

		if($('#map_main_div svg').attr('process') > 0)
		{
			$('#mk_process_info').html($('#mk_process_lang').val());

			loading_dots();
			$("#submit_cap").addClass('ui-disabled');
			mk_pro_interval = setInterval(function(){ 
				$.ajax({
					type: "POST",
					url: 'lib/cap.meteoalarm.webservices.mkv.php',
					data: '', // serializes the forms elements.
					success: function(data)
					{			
						if(parseInt(data) > 0)
						{
							//loading_dots();
						}
						else
						{
							$( "#submit_cap" ).removeClass('ui-disabled');
							location.reload();	
						}
					}
				});
			}, 30000);
		}

		aktive_types = [];
		for (var i = 1; i <= 13; i++) {
			awt_bool = $('#map_main_div svg').attr('awt_'+i);
			aktive_types[i] = awt_bool;
			if(i < 10) i_tmp = '0' + i;
			else i_tmp = i;
			$('#left_box_type_' + i_tmp).attr('aktive', awt_bool);
			if(awt_bool == "0") $("#type option[value='"+i+"']").remove();
		};

		//console.log(aktive_types);

		changes_arr['idx'] = [];
		changes_arr['change'] = [];

		$(window).bind('beforeunload', function(){
			if(something_changed)
			{
				//e.returnValue = 'If you refresh the page your data will be lost';
				//return confirm('If you refresh the page your data will be lost');
				//return 'If you refresh the page your data will be lost';
				//return confirm("Confirm refresh");
				return 'Are you sure you want to leave?';
			}
		});

		$('input:not([type=radio]), textarea ').change(function() {
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

		$('input[type=radio]').change(function() {
			if( $('input[name=multisel_area_name_bool]:checked').val() == "0")
			{
				$('#emmaid_select').selectmenu( "disable" );
				aktive_warning_type = 0;
				$('#emmaid_select option').prop('selected', false);
				$('#emmaid_select').selectmenu( "refresh" );
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
					$('#from_0').val('');
					$('#to_0').val('');
					$.each(lang, function(lindex, ldata){
						$('#desc_'+lindex).val('');
						$('#inst_'+lindex).val('');
					});
					$('#AreaDetailDIV').css('background-color', '#cccccc');
					$('#AreaDetailUL').css('pointer-events', 'none');
					$('#AreaDetailUL').css('opacity', 0.5);
					$('#info_text').css('display', 'inherit');
					$('.process_toolbox_area .awareness').css('border', '');
					//calc_map_aktion($('#map_main_div svg polygon[aid='+aktive_warning_aid+']'), 'auto');
				}
			}
			else if( $('input[name=multisel_area_name_bool]:checked').val() == "1")
			{
				$('#emmaid_select').selectmenu( "enable" );
				aktive_warning_type = 0;
			}
		});

		$('#emmaid_select').change(function(r) {

			$('#map_main_div svg polygon').css('stroke-width', '1');
			$('#map_main_div svg polygon').css('stroke', 'black');
			//$('#map_main_div svg polygon').css('stroke-dasharray', '0,0');
			$('#map_main_div svg polygon').css('filter', '');
			$('#map_main_div svg polygon'+'[main=1]').attr('sel', 0);

			$.each($(this).val(), function(index, data){
				$('#map_main_div svg polygon[aid='+data+']').css('stroke-width', '3px');
				$('#map_main_div svg polygon[aid='+data+']').css('stroke', 'blue');
				//$('#map_main_div svg polygon').css('stroke-dasharray', '0,0');
				$('#map_main_div svg polygon[aid='+data+']').css('filter', 'url(#css_brightness)');
				$('#map_main_div svg polygon[aid='+data+']'+'[main=1]').attr('sel', 1);
				calc_map_aktion($('#map_main_div svg polygon[aid='+data+']'), 'auto');
			});
			
			//console.log(r);
			//area_warning_detail(aid, key_type, tmp_this);
		});

		//$( "#CAP_SOAP_popupDialog" ).collapsible({
		//	expand: function( event, ui ) {
		//		$('#CAP_SOAP_popupDialog').popup( "reposition");
		//	}
		//});

		$( "#CAP_SOAP_popupDialog" ).on( "collapsibleexpand", function( event, ui ) { 
			$("#CAP_SOAP_popupDialog").popup("reposition", {positionTo: 'origin'});
			$('#CAP_SOAP_popupDialog').resize();
		});

		$( "#CAP_SOAP_popupDialog" ).on( "collapsiblecollapse", function( event, ui ) {
			$("#CAP_SOAP_popupDialog").popup("reposition", {positionTo: 'origin'});
			$('#CAP_SOAP_popupDialog').resize();
		});

		$('#reload').click(function() {
			data = $('#day').val();
			if(data == "" || data === undefined) data = 0;
			type = $('#type').val();
			if(type == "" || type === undefined || type == 0) type = "";
			window.location = "map.php?data="+data+'&type='+type;
		});

		$('#Undo').click(function() {
			document.execCommand('undo', false, null);
		});

		$('#Redo').click(function() {
			document.execCommand('redo', false, null);
		});

		$('#submit_cap').on('click', function(){
			if($('.problem').html() == "" || $('.problem').html() === undefined)
				get_all_warnings();
			else
				$('#Error_popupDialog').popup('open');
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

		$('#green_edit').on('click', function(){
			make_white_areas_green(-1);
		});

		$('.ui-collapsible-set').on('click', function(){
			tempScrollTop = $(window).scrollTop();
			alert(tempScrollTop);
		});

		$('#send_no').on('click', function(){
			send_all_proce_cap(0);
		});

		$('#send_yes').on('click', function(){
			send_all_proce_cap(1);
		});

		//$('#map_main_div svg').mouseenter(function() {
		//	disableScroll();
		//});
//
		//$('#map_main_div svg').mouseleave(function() {
		//	enableScroll();
		//});

		$('li:not(#map-container)').on('click', function(){
			aktive_type = false;
			$('#awareness_toolbox .awareness').css('border', '');
			$('#awareness_toolbox .awareness[aktive=1]').css('opacity', 1);
			aktive_level = false;
			$('#awareness_color_toolbox .awareness').css('border', '');
			$('#awareness_color_toolbox .awareness').css('opacity', 1);
			$('#map_main_div svg').css('cursor', 'auto');
			$('#awareness_color_toolbox').css('display', '');
		});
		$('#awareness_toolbox .awareness[aktive=1]').on('click', function(){
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
				$('#awareness_toolbox .awareness[aktive=1]').css('opacity', 1);
				aktive_level = false;
				$('#awareness_color_toolbox .awareness').css('border', '');
				$('#awareness_color_toolbox .awareness').css('opacity', 1);
				$('#map_main_div svg').css('cursor', 'auto');
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
				$('#map_main_div svg').css('cursor', 'url(includes/meteoalarm/fill.png), auto');
			}
			else
			{
				aktive_level = false;
				$('#awareness_color_toolbox .awareness').css('border', '');
				$('#awareness_color_toolbox .awareness').css('opacity', 1);
				$('#map_main_div svg').css('cursor', 'auto');
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


		svg = d3.select("#map_main_div svg");
		$('#map_main_div svg polygon').on('click', function(){
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

	var lang = [];

	function calc_map_aktion(tmp_this, is_auto)
	{
		//delete(area_arr);
		//area_arr = [];
		/*Get*/
		//this_class= $(tmp_this).attr('class');
		//this_classtest= $('.'+this_class+'[main=1]').attr('aid');
		lang_1 = $('#lang_1').val();
		$('input[name=langs]').each(function(index, data){
			lang[index] = $(data).val();
		});
		cinfo = parseInt($('.'+$(tmp_this).attr('class')+'[main=1]').attr('cinfo'));
		aid = parseInt($('.'+$(tmp_this).attr('class')+'[main=1]').attr('aid'));
		delete(area_arr[aid]);
		area_arr[aid] = [];
		area_arr[aid]['name'] = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_name');
		for (ty=0; ty < cinfo; ty++) 
		{	//aid
			level = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_level_'+ty+'');
			type = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_type_'+ty+'');
			//text = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_text_'+ty+'');
			text = [];
			inst = [];
			$.each(lang, function(lindex, ldata){
				text[lindex] = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_text_'+ty+'_' + ldata);
				inst[lindex] = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_inst_'+ty+'_' + ldata);
				//text[1] = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_text_'+ty+'_'+lang_1);
				//inst[1] = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_inst_'+ty+'_'+lang_1);
			});
			to = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_to_'+ty+'');
			from = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_from_'+ty+'');
			ident = $('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_ident_'+ty+'');
			
			//if(parseInt(level) > 1)
			//{
				area_arr[aid][ty] = [];
				
				area_arr[aid][ty]['level'] 	= level;
				area_arr[aid][ty]['type'] 	= type;
				//area_arr[aid][ty]['text'] 	= text;
				$.each(lang, function(lindex, ldata){
					area_arr[aid][ty]['text_' + lindex]	= text[lindex];
					area_arr[aid][ty]['inst_' + lindex]	= inst[lindex];
				});
				//area_arr[aid][ty]['text_1']	= text_1;
				//area_arr[aid][ty]['inst_1']	= inst_1;
				area_arr[aid][ty]['from'] 	= from;
				area_arr[aid][ty]['to'] 	= to;
				area_arr[aid][ty]['ident']	= ident;
			//}
		}

		if(aktive_level != false && aktive_type != false)
		{
			// Paint mode !!!
			/*
				color: 1 #29d660  2 #ffff00  3 #fecb31  4 #fe0104 
			*/
			processing = true;
			tmp_wid = 0;
			cinfo = parseInt($('.'+$(tmp_this).attr('class')+'[main=1]').attr('cinfo'));
			aid = parseInt($('.'+$(tmp_this).attr('class')+'[main=1]').attr('aid'));
			
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
				//area_arr[aid][ty]['text'] 	= "";
				$.each(lang, function(lindex, ldata){
					area_arr[aid][ty]['text_' + lindex]	= "";
					area_arr[aid][ty]['inst_' + lindex]	= "";
				});
				//area_arr[aid][ty]['text_1']	= "";
				//area_arr[aid][ty]['inst_1']	= "";

				var date = new Date();
				area_arr[aid][ty]['to'] 	= date.yyyymmdd()+" 23:59:59";
				area_arr[aid][ty]['from'] 	= date.yyyymmdd()+" 00:00:00";
				
				if(cinfo != 3) $('.'+$(tmp_this).attr('class')+'[main=1]').attr('cinfo', cinfo + 1 );

				$('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_level_'+ty+'', area_arr[aid][ty]['level']);
				$('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_type_'+ty+'', area_arr[aid][ty]['type']);

				$('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_to_'+ty+'', area_arr[aid][ty]['to']);
				$('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_from_'+ty+'', area_arr[aid][ty]['from']);
				$('.'+$(tmp_this).attr('class')+'[main=1]').attr('area_ident_'+ty+'', area_arr[aid][ty]['ident']);
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
			if(tmp_level == 0 && $(tmp_this).attr('level_1') == 1) $(tmp_this).css('fill', level_c[1]);
			else $(tmp_this).css('fill', level_c[tmp_level]);
		}
		/*Show*/
		if(is_auto != "auto")
		{
			if($('.'+$(tmp_this).attr('class')+'[main=1]').attr('sel') == 1 && aktive_level == false && aktive_type == false)
			{
				$('.'+$(tmp_this).attr('class')).css('stroke-width', '1');
				$('.'+$(tmp_this).attr('class')).css('stroke', 'black');
				//$('.'+$(tmp_this).attr('class')).css('stroke-dasharray', '0,0');
				$('.'+$(tmp_this).attr('class')).css('filter', '');
				$('.'+$(tmp_this).attr('class')+'[main=1]').attr('sel', 0);
				area_arr[aid].pop();
				delete(area_arr[aid]);

				$('#emmaid_select option[value=' + aid + ']').prop('selected', false);
				$('#emmaid_select').selectmenu( "refresh" );
				if($('#emmaid_select option:selected').val() == undefined)
				{
					$('#right_area_type').html('');
					$.each(lang, function(lindex, ldata){
						$('#desc_' + lindex).val('').trigger('input');
						$('#inst_' + lindex).val('').trigger('input');
					});
					//$('#desc_1').val('').trigger('input');
					//$('#inst_1').val('').trigger('input');
					$('#from_0').val('00:00').trigger('input');
					$('#to_0').val('00:00').trigger('input');
					tmp_area_name = $('#map_main_div svg polygon[aid='+aid+']').attr('area_name');
					//$('#left_area_name').html(tmp_area_name).trigger('input');
					$('#AreaDetailDIV').css('background-color', '#cccccc');
					$('#AreaDetailUL').css('pointer-events', 'none');
					$('#AreaDetailUL').css('opacity', 0.5);
					$('#info_text').css('display', 'inherit');
				}
				//$(tmp_this).css('border', '');
			}
			else
			{
				$('.'+$(tmp_this).attr('class')).css('stroke-width', '3px');
				$('.'+$(tmp_this).attr('class')).css('stroke', 'blue');
				//$('.'+$(tmp_this).attr('class')).css('stroke-dasharray', '0,0');
				$('.'+$(tmp_this).attr('class')).css('filter', 'url(#css_brightness)');
				$('.'+$(tmp_this).attr('class')+'[main=1]').attr('sel', 1);
			}
		}

		tmp_awt_sel = [];
		$('.process_toolbox_area').each(function(index, data){
			tmp_this = this;
			$('#'+$(tmp_this).attr('id')+' div').each(function(index2, data2){
				if($(this).css('border-width') == "3px")
				{
					if(tmp_awt_sel[$(tmp_this).attr('id')] === undefined) tmp_awt_sel[$(tmp_this).attr('id')] = [];
					tmp_awt_sel[$(tmp_this).attr('id')][index2] = 1;
				}
			});
		});
		//alert(tmp_awt_sel);
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
					noty_i = 0;
					$.each(value, function (key2, data) {
						if(data['type'] !== undefined)
						{
							noty_i++;
						}
					});
					for (noty=1; noty <= (3 - noty_i); noty++) 
					{
						out+= '<div class="awareness" aktive="2" onclick="area_warning_detail('+key+', -1, this)"><img src="includes/meteoalarm/warn-typs_11.png"></div>';
					}
					coun = 1;

					$.each(value, function (key2, data) {
						if(data['type'] !== undefined && coun <= 3)
						{
							coun++;
							problem = 0;
							// Warnungen
							tmp_level = 0;
							tmp_level = parseInt(data['level']);
							tmp_type = parseInt(data['type']);
							tmp_text = [];
							tmp_inst = [];
							$.each(lang, function(lindex, ldata){
								tmp_text[lindex] = data['text_' + lindex];
								tmp_inst[lindex] = data['inst_' + lindex];
							});
							//tmp_text_1 = data['text_1'];
							//tmp_inst_1 = data['inst_1'];
							tmp_to = data['to'];
							tmp_from = data['from'];
							tmp_ident = data['ident'];

							if((tmp_text[0] == "" || tmp_text[0] === undefined)) problem = 1;
							else problem = 0;

							if(tmp_type < 10) tmp_type_f = '0'+tmp_type;
							else tmp_type_f = tmp_type;
							//out+= ' ' + tmp_level + ' ' + tmp_type + ' ';
							if(tmp_level > 1) // zeige keine level 1 oder 0
							{
								if(tmp_awt_sel['aid_'+key] !== undefined) 
								{
									if(tmp_awt_sel['aid_'+key][(key2 + noty_i + 1)] !== undefined ) 
									{
										if(tmp_awt_sel['aid_'+key][(key2 + noty_i + 1)] == 1) css_selected='style="border: 3px solid rgb(0, 0, 255)"';
										else css_selected = '';
									}
									else css_selected = '';
								}
								else css_selected = '';

								if(problem)	out+= '<div class="problem awareness level_'+tmp_level+'" aktive="1" onclick="area_warning_detail('+key+', '+key2+', this)" '+css_selected+'><img src="includes/meteoalarm/warn-typs_'+tmp_type_f+'.png"><span class="problem_callsign">!</span></div>';
								else 		out+= '<div class="awareness level_'+tmp_level+'" aktive="1" onclick="area_warning_detail('+key+', '+key2+', this)" '+css_selected+'><img src="includes/meteoalarm/warn-typs_'+tmp_type_f+'.png"></div>';
								if(problem) tmp_problem = 1;
							}
							else
							{
								out+= '<div class="awareness" aktive="2" onclick="area_warning_detail('+key2+', -1, this)"><img src="includes/meteoalarm/warn-typs_11.png"></div>';
							}
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
	var aktive_warning_type;
	var aktive_warning_level;
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
		if( $('input[name=multisel_area_name_bool]:checked').val() == "1" && $('#emmaid_select option[value=' + aid + ']').prop('selected') == true) // aktive_warning_type
		{
			$('#emmaid_select option[value=' + aid + ']').prop('selected', false);
			$('#emmaid_select').selectmenu( "refresh" );
			if($('#emmaid_select option:selected').val() == undefined)
			{
				$('#right_area_type').html('');
				$.each(lang, function(lindex, ldata){
					$('#desc_' + lindex).val('').trigger('input');
					$('#inst_' + lindex).val('').trigger('input');
				});
				//$('#desc_1').val('').trigger('input');
				//$('#inst_1').val('').trigger('input');
				$('#from_0').val('00:00').trigger('input');
				$('#to_0').val('00:00').trigger('input');
				tmp_area_name = $('#map_main_div svg polygon[aid='+aid+']').attr('area_name');
				//$('#left_area_name').html(tmp_area_name).trigger('input');
				$('#AreaDetailDIV').css('background-color', '#cccccc');
				$('#AreaDetailUL').css('pointer-events', 'none');
				$('#AreaDetailUL').css('opacity', 0.5);
				$('#info_text').css('display', 'inherit');
				aktive_warning_type = 0;
			}
			$(tmp_this).css('border', '');
			multi = false;
		}
		else if($('input[name=multisel_area_name_bool]:checked').val() == "1" && aktive_warning_type !== undefined && aktive_warning_type != 0 && aktive_warning_type != "" && aktive_warning_type !=  $('#map_main_div svg polygon[aid='+aid+']').attr('area_type_'+key_type))
		{
			multi = true;
			aktive_warning_type = undefined;
			if($('#emmaid_select option:selected').val() != undefined)
			{
				$('#emmaid_select option:selected').each(function(index, data){
					$(data).prop('selected', false);
				});
				$('#emmaid_select').selectmenu( "refresh" );
			}
			$('.process_toolbox_area .awareness').css('border', '');
		}
		else
		{
			multi = true;
		}
		if(change_aktive && multi)
		{
			//console.log($('#map_main_div svg polygon[aid='+aid+']').attr('area_name'));
			aktive_warning_aid = aid;
			eid = $('#map_main_div svg polygon[aid='+aid+']').attr('eid');
			aktive_warning_key = key_type;
			aktive_warning_type =  $('#map_main_div svg polygon[aid='+aid+']').attr('area_type_'+key_type);
			aktive_warning_level =  $('#map_main_div svg polygon[aid='+aid+']').attr('area_level_'+key_type);
			if( $('input[name=multisel_area_name_bool]:checked').val() == "0") $('.process_toolbox_area .awareness').css('border', '');
			$(tmp_this).css('border', '3px solid #0000ff');
			tmp_area_text = [];
			tmp_area_inst = [];
			if(key_type > -1)
			{
				lang_1 = $('#lang_1').val();
				tmp_area_name 		= $('#map_main_div svg polygon[aid='+aid+']').attr('area_name');
				$.each(lang, function(lindex, ldata){
					tmp_area_text[lindex] 	= $('#map_main_div svg polygon[aid='+aid+']').attr('area_text_'+key_type+'_' + ldata);
					tmp_area_inst[lindex] 	= $('#map_main_div svg polygon[aid='+aid+']').attr('area_inst_'+key_type+'_' + ldata);
				});
				//tmp_area_text_1 	= $('#map_main_div svg polygon[aid='+aid+']').attr('area_text_'+key_type+'_'+lang_1+'');
				//tmp_area_inst_1 	= $('#map_main_div svg polygon[aid='+aid+']').attr('area_inst_'+key_type+'_'+lang_1+'');
				tmp_area_to 		= $('#map_main_div svg polygon[aid='+aid+']').attr('area_to_'+key_type+'');
				tmp_area_from 		= $('#map_main_div svg polygon[aid='+aid+']').attr('area_from_'+key_type+'');

				$.each(lang, function(lindex, ldata){
					$('#desc_'+lindex).val('');
					$('#inst_'+lindex).val('');
				});
				$('#AreaDetailDIV').css('background-color', '#ffffff');
				$('#AreaDetailUL').css('opacity', 1);
				$('#info_text').css('display', 'none');
				$('#AreaDetailUL').css('pointer-events', 'auto');

				//$('#left_area_name').html(tmp_area_name);
				//console.log( $('input[name=multisel_area_name_bool]:checked').val());
				if( $('input[name=multisel_area_name_bool]:checked').val() == "1") // aktive_warning_key
				{
					$('#emmaid_select option[value=' + aid + ']').prop('selected', true);
				}
				else
				{
					$('#emmaid_select option').prop('selected', false);
					$('#emmaid_select option[value=' + aid + ']').prop('selected', true);
				}

				$('#emmaid_select').selectmenu( "refresh" );
				$('#right_area_type').html('');
				$('#right_area_type').append($(tmp_this).clone());

				$.each(lang, function(lindex, ldata){
					$('#desc_' + lindex).val((tmp_area_text[lindex])).trigger('input');
					$('#inst_' + lindex).val((tmp_area_inst[lindex])).trigger('input');
				});
				//$('#desc_1').val((tmp_area_text_1)).trigger('input');
				//$('#inst_1').val((tmp_area_inst_1)).trigger('input');

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
				$.each(lang, function(lindex, ldata){
					$('#desc_'+lindex).val('').trigger('input');
					$('#inst_'+lindex).val('').trigger('input');
				});
				$('#from_0').val('00:00').trigger('input');
				$('#to_0').val('00:00').trigger('input');
				tmp_area_name = $('#map_main_div svg polygon[aid='+aid+']').attr('area_name');
				//$('#left_area_name').html(tmp_area_name).trigger('input');
				$('#AreaDetailDIV').css('background-color', '#cccccc');
				$('#AreaDetailUL').css('pointer-events', 'none');
				$('#AreaDetailUL').css('opacity', 0.5);
				$('#info_text').css('display', 'inherit');
			}
		}
	}

	function save_warning_detail()
	{
		//aktive_warning_aid
		//aktive_warning_key
		if(aktive_warning_key > -1)
		{
			if( $('input[name=multisel_area_name_bool]:checked').val() == "0")
			{
				lang_1 = $('#lang_1').val();
				$.each(lang, function(lindex, ldata){
					area_arr[aktive_warning_aid][aktive_warning_key]['text_' + lindex]	= $('#desc_' + lindex).val();
					area_arr[aktive_warning_aid][aktive_warning_key]['inst_' + lindex]	= $('#inst_' + lindex).val();
				});
				//area_arr[aktive_warning_aid][aktive_warning_key]['text_1']	= $('#desc_1').val();
				//area_arr[aktive_warning_aid][aktive_warning_key]['inst_1']	= $('#inst_1').val();
				area_arr[aktive_warning_aid][aktive_warning_key]['from'] 	= $('#from_0').val();
				area_arr[aktive_warning_aid][aktive_warning_key]['to'] 		= $('#to_0').val();
				$.each(lang, function(lindex, ldata){
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+aktive_warning_key+'_' + ldata, area_arr[aktive_warning_aid][aktive_warning_key]['text_' + lindex]);
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+aktive_warning_key+'_' + ldata, area_arr[aktive_warning_aid][aktive_warning_key]['inst_' + lindex]);
				});
				//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+aktive_warning_key+'_'+lang_1+'', area_arr[aktive_warning_aid][aktive_warning_key]['text_1']);
				//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+aktive_warning_key+'_'+lang_1+'', area_arr[aktive_warning_aid][aktive_warning_key]['inst_1']);
				$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_to_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['to']);
				$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_from_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['from']);
				$('#from_0').val('');
				$('#to_0').val('');
				$.each(lang, function(lindex, ldata){
					$('#desc_'+lindex).val('');
					$('#inst_'+lindex).val('');
				});
				$('#AreaDetailDIV').css('background-color', '#cccccc');
				$('#AreaDetailUL').css('pointer-events', 'none');
				$('#AreaDetailUL').css('opacity', 0.5);
				$('#info_text').css('display', 'inherit');
				$('.process_toolbox_area .awareness').css('border', '');
				calc_map_aktion($('#map_main_div svg polygon[aid='+aktive_warning_aid+']'), 'auto');
				warning_detail_changed = false;
				something_changed = true;
			}
			else // when multiselect is aktive
			{
				$('#emmaid_select option:checked').each(function(index, data){
					test_aktive_warning_aid = $(data).val();
					if(area_arr[test_aktive_warning_aid] !== undefined)
					{
						//area_arr[test_aktive_warning_aid].length
						$.each(area_arr[test_aktive_warning_aid], function(index, data){
							if(data['type'] == aktive_warning_type)
							{
								if(data['level'] == aktive_warning_level)
								{
									arr_to_clone = test_aktive_warning_aid;
									arr_key_to_clone = index;
								}
							}
						});
					}
				});
				$('#emmaid_select option:checked').each(function(index, data){
					aktive_warning_aid = $(data).val();
					aktive_warning_name = $(data).html();
					if(area_arr[aktive_warning_aid] === undefined)
					{
						area_arr[aktive_warning_aid] = [];
						area_arr[aktive_warning_aid][0] = area_arr[arr_to_clone][arr_key_to_clone]
					}
					else if(arr_to_clone != aktive_warning_aid)
					{
						if(area_arr[aktive_warning_aid]['name'] === undefined) area_arr[aktive_warning_aid]['name'] = aktive_warning_name;
						if(area_arr[aktive_warning_aid][area_arr[aktive_warning_aid].length - 1] === undefined)
						{
							area_arr[aktive_warning_aid][area_arr[aktive_warning_aid].length] = area_arr[arr_to_clone][arr_key_to_clone];
						}
						if(area_arr[aktive_warning_aid][area_arr[aktive_warning_aid].length] === undefined)
						{
							unterschied = true;
							$.each(area_arr[aktive_warning_aid], function(index, data){
								if(data['type'] == area_arr[arr_to_clone][arr_key_to_clone]['type'] && data['level'] == area_arr[arr_to_clone][arr_key_to_clone]['level']) 
									unterschied = false;
							});
							if(unterschied == true)
							{
								area_arr[aktive_warning_aid][area_arr[aktive_warning_aid].length] = area_arr[arr_to_clone][arr_key_to_clone];
							}
						}
					}
				});
				$('#emmaid_select option:checked').each(function(index, data){
					aktive_warning_aid = $(data).val();
					lang_1 = $('#lang_1').val();
					$.each(lang, function(lindex, ldata){
						area_arr[aktive_warning_aid][aktive_warning_key]['text_' + lindex]	= $('#desc_'+lindex).val();
						area_arr[aktive_warning_aid][aktive_warning_key]['inst_' + lindex]	= $('#inst_'+lindex).val();
					});
					//area_arr[aktive_warning_aid][aktive_warning_key]['text_1']	= $('#desc_1').val();
					//area_arr[aktive_warning_aid][aktive_warning_key]['inst_1']	= $('#inst_1').val();
					area_arr[aktive_warning_aid][aktive_warning_key]['from'] 	= $('#from_0').val();
					area_arr[aktive_warning_aid][aktive_warning_key]['to'] 		= $('#to_0').val();
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('cinfo', area_arr[aktive_warning_aid].length);
					$.each(lang, function(lindex, ldata){
						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+aktive_warning_key+'_'+ldata, area_arr[aktive_warning_aid][aktive_warning_key]['text_'+lindex]);
						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+aktive_warning_key+'_'+ldata, area_arr[aktive_warning_aid][aktive_warning_key]['inst_'+lindex]);
					});
					//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+aktive_warning_key+'_'+lang_1+'', area_arr[aktive_warning_aid][aktive_warning_key]['text_1']);
					//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+aktive_warning_key+'_'+lang_1+'', area_arr[aktive_warning_aid][aktive_warning_key]['inst_1']);
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_to_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['to']);
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_from_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['from']);
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_level_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['level']);
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_type_'+aktive_warning_key+'', area_arr[aktive_warning_aid][aktive_warning_key]['type']);
					calc_map_aktion($('#map_main_div svg polygon[aid='+aktive_warning_aid+']'), 'auto');
				});
	
				$('#right_area_type').html('');
				$('#from_0').val('');
				$('#to_0').val('');
				$.each(lang, function(lindex, ldata){
					$('#desc_'+lindex).val('');
					$('#inst_'+lindex).val('');
				});
				$('#AreaDetailDIV').css('background-color', '#cccccc');
				$('#AreaDetailUL').css('pointer-events', 'none');
				$('#AreaDetailUL').css('opacity', 0.5);
				$('#info_text').css('display', 'inherit');
				$('.process_toolbox_area .awareness').css('border', '');

				$('#emmaid_select option').prop('selected', false);
				$('#emmaid_select').selectmenu( "refresh" );

				//calc_map_aktion($('#map_main_div svg polygon[aid='+aktive_warning_aid+']'), 'auto');
				warning_detail_changed = false;
				something_changed = true;
				aktive_warning_type = 0;
			}
		}
	}

	function delete_warning_detail()
	{
		if(aktive_warning_aid > 0 && confirm($('#del_warn_lang').val()))
		{
			if( $('input[name=multisel_area_name_bool]:checked').val() == "1")
			{
				$('#emmaid_select option:checked').each(function(index, data){
					aktive_warning_aid = $(data).val();
					something_changed = true;
					cinfo = parseInt($('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('cinfo'));
					lang_1 = $('#lang_1').val();
					
					for (ty=0; ty < cinfo; ty++) 
					{
						level 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_level_'+ty+'');
						type 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_type_'+ty+'');
						text_text = [];
						text_inst = [];
						$.each(lang, function(lindex, ldata){
						//text 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'');
							text_text[lindex]	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'_'+ldata);
							text_inst[lindex]	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+ty+'_'+ldata);
						});
						//text_text_1	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'_'+lang_1+'');
						//text_inst_1	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+ty+'_'+lang_1+'');
						to 		= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_to_'+ty+'');
						from 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_from_'+ty+'');
						ident 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_ident_'+ty+'');
						area_arr[aktive_warning_aid][ty] = [];
						
						area_arr[aktive_warning_aid][ty]['level']	= level;
						area_arr[aktive_warning_aid][ty]['type']	= type;
						//area_arr[aktive_warning_aid][ty]['text']	= text;
						$.each(lang, function(lindex, ldata){
							area_arr[aktive_warning_aid][ty]['text_' + lindex]	= text_text[lindex];
							area_arr[aktive_warning_aid][ty]['inst_' + lindex]	= text_inst[lindex];
						});
						area_arr[aktive_warning_aid][ty]['to']		= to;
						area_arr[aktive_warning_aid][ty]['from']	= from;
						area_arr[aktive_warning_aid][ty]['ident']	= ident;
					}

					//length_tmp = area_arr[aktive_warning_aid].length;
					delete(area_arr[aktive_warning_aid][aktive_warning_key]);
					//area_arr[aktive_warning_aid].length = length_tmp - 1;

					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('cinfo', cinfo - 1);
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_level_'+aktive_warning_key+'');
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_type_'+aktive_warning_key+'');
					$.each(lang, function(lindex, ldata){
						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_text_'+aktive_warning_key+'_' + ldata);
						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_inst_'+aktive_warning_key+'_' + ldata);
					});
					//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_text_'+aktive_warning_key+'_'+lang_1+'');
					//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_inst_'+aktive_warning_key+'_'+lang_1+'');
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_to_'+aktive_warning_key+'');
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_from_'+aktive_warning_key+'');

					tmp_key = 0;
					$.each(area_arr[aktive_warning_aid], function (key2, data) {
						if(data !== undefined)
						{
							// Warnungen
							tmp_level = parseInt(data['level']);
							tmp_type = parseInt(data['type']);
							tmp_text = data['text'];
							tmp_to = data['to'];
							tmp_from = data['from'];
							tmp_ident = data['ident'];

							$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_level_'+tmp_key+'', tmp_level);
							$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_type_'+tmp_key+'', tmp_type);
							$.each(lang, function(lindex, ldata){
								$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+tmp_key+'_'+ldata, text_text[lindex]);
								$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+tmp_key+'_'+ldata, text_inst[lindex]);
							});
							//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+tmp_key+'_'+lang_1+'', text_text_1);
							//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+tmp_key+'_'+lang_1+'', text_inst_1);
							$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_to_'+tmp_key+'', tmp_to);
							$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_from_'+tmp_key+'', tmp_from);
							tmp_key++;
						}
					});
					
					$('#right_area_type').html('');
					$.each(lang, function(lindex, ldata){
						$('#desc_'+lindex).val('');
						$('#inst_'+lindex).val('');
					});
					//$('#desc_1').val('');
					//$('#inst_1').val('');
					$('#AreaDetailDIV').css('background-color', '#cccccc');
					$('#AreaDetailUL').css('opacity', 0.5);
					$('#info_text').css('display', 'inherit');

					//$('#left_area_name').html('');
					calc_map_aktion($('#map_main_div svg polygon[aid='+aktive_warning_aid+']'), 'auto');
				});
				$('#emmaid_select option').prop('selected', false);
				$('#emmaid_select').selectmenu( "refresh" );
				aktive_warning_type = 0;
			}
			else // multiselect ist nicht aktive !
			{
				aktive_warning_aid = $(data).val();
				something_changed = true;
				cinfo = parseInt($('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('cinfo'));
				lang_1 = $('#lang_1').val();
				
				for (ty=0; ty < cinfo; ty++) 
				{
					level 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_level_'+ty+'');
					type 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_type_'+ty+'');
					//text 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'');
					text_text = [];
					text_inst = [];
					$.each(lang, function(lindex, ldata){
						text_text[lindex]	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'_'+ldata);
						text_inst[lindex]	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+ty+'_'+ldata);
					});
					//text_text_1	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+ty+'_'+lang_1+'');
					//text_inst_1	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+ty+'_'+lang_1+'');
					to 		= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_to_'+ty+'');
					from 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_from_'+ty+'');
					ident 	= $('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_ident_'+ty+'');
					area_arr[aktive_warning_aid][ty] = [];
					
					area_arr[aktive_warning_aid][ty]['level']	= level;
					area_arr[aktive_warning_aid][ty]['type']	= type;
					//area_arr[aktive_warning_aid][ty]['text']	= text;
					$.each(lang, function(lindex, ldata){
						area_arr[aktive_warning_aid][ty]['text_' + lindex]	= text_text[lindex];
						area_arr[aktive_warning_aid][ty]['inst_' + lindex]	= text_inst[lindex];
					});
					area_arr[aktive_warning_aid][ty]['to']		= to;
					area_arr[aktive_warning_aid][ty]['from']	= from;
					area_arr[aktive_warning_aid][ty]['ident']	= ident;
				}

				//length_tmp = area_arr[aktive_warning_aid].length;
				delete(area_arr[aktive_warning_aid][aktive_warning_key]);
				//area_arr[aktive_warning_aid].length = length_tmp - 1;

				$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('cinfo', cinfo - 1);
				$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_level_'+aktive_warning_key+'');
				$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_type_'+aktive_warning_key+'');
				$.each(lang, function(lindex, ldata){
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_text_'+aktive_warning_key+'_'+ldata);
					$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_inst_'+aktive_warning_key+'_'+ldata);
				});
				//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_text_'+aktive_warning_key+'_'+lang_1+'');
				//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_inst_'+aktive_warning_key+'_'+lang_1+'');
				$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_to_'+aktive_warning_key+'');
				$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').removeAttr('area_from_'+aktive_warning_key+'');

				tmp_key = 0;
				$.each(area_arr[aktive_warning_aid], function (key2, data) {
					if(data !== undefined)
					{
						// Warnungen
						tmp_level = parseInt(data['level']);
						tmp_type = parseInt(data['type']);
						//tmp_text = data['text'];
						text_text = [];
						text_inst = [];
						$.each(lang, function(lindex, ldata){
							text_text[lindex] = data['text_' + lindex];
							text_inst[lindex] = data['inst_' + lindex];
						});
						tmp_to = data['to'];
						tmp_from = data['from'];
						tmp_ident = data['ident'];

						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_level_'+tmp_key+'', tmp_level);
						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_type_'+tmp_key+'', tmp_type);
						$.each(lang, function(lindex, ldata){
							$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+tmp_key+'_'+ldata, text_text[lindex]);
							$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+tmp_key+'_'+ldata, text_inst[lindex]);
						});
						//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_text_'+tmp_key+'_'+lang_1+'', text_text_1);
						//$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_inst_'+tmp_key+'_'+lang_1+'', text_inst_1);
						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_to_'+tmp_key+'', tmp_to);
						$('#map_main_div svg polygon[aid='+aktive_warning_aid+']').attr('area_from_'+tmp_key+'', tmp_from);
						tmp_key++;
					}
				});
				
				$('#right_area_type').html('');
				$.each(lang, function(lindex, ldata){
					$('#desc_'+lindex).val('');
					$('#inst_'+lindex).val('');
				});
				//$('#desc_1').val('');
				//$('#inst_1').val('');
				$('#AreaDetailDIV').css('background-color', '#cccccc');
				$('#AreaDetailUL').css('opacity', 0.5);
				$('#info_text').css('display', 'inherit');

				//$('#left_area_name').html('');
				calc_map_aktion($('#map_main_div svg polygon[aid='+aktive_warning_aid+']'), 'auto');
				aktive_warning_type = 0;
			}
		}
	}

	var area_arr_final = {};
	var area_green = {};
	function get_all_warnings()
	{
		$('svg polygon[main=1]').each(function (key, data) {
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
					area_arr_final[aid][ty]['name'] 	= $(this).attr('area_name');
					area_arr_final[aid][ty]['eid'] 		= eid;
					area_arr_final[aid][ty]['level_1']	= $(this).attr('level_1');

					level 	= parseInt($(this).attr('area_level_'+ty+''));
					type 	= parseInt($(this).attr('area_type_'+ty+''));
					text	= [];
					inst	= [];
					tmp_this = this;
					$.each(lang, function(lindex, ldata){
						//text 	= $(this).attr('area_text_'+ty+'');
						text[lindex]	= $(tmp_this).attr('area_text_'+ty+'_'+ldata);
						inst[lindex]	= $(tmp_this).attr('area_inst_'+ty+'_'+ldata);
					});
					//this = tmp_this;
					//text_1 	= $(this).attr('area_text_'+ty+'_'+lang_1);
					//inst_1 	= $(this).attr('area_inst_'+ty+'_'+lang_1);
					to 		= $(this).attr('area_to_'+ty+'');
					from 	= $(this).attr('area_from_'+ty+'');
					ident 	= $(this).attr('area_ident_'+ty+'');
					
					if(level > 0 && type > 0)
					{
						area_arr_final[aid][ty]['eid'] 		= eid;
						area_arr_final[aid][ty]['level'] 	= level;
						area_arr_final[aid][ty]['type'] 	= type;
						//area_arr_final[aid][ty]['text'] 	= text;
						$.each(lang, function(lindex, ldata){
							area_arr_final[aid][ty]['text_'+lindex]	= text[lindex];
							area_arr_final[aid][ty]['inst_'+lindex]	= inst[lindex];
						});
						//area_arr_final[aid][ty]['text_1']	= text_1;
						//area_arr_final[aid][ty]['inst_1']	= inst_1;
						area_arr_final[aid][ty]['from'] 	= from;
						area_arr_final[aid][ty]['to'] 		= to;
						area_arr_final[aid][ty]['ident']	= ident;
					}

					delete(level, type ,text,inst,to,from,ident);
				}
			}
		});
	
		awt_ok = [];
		awt_ok[0] = 0;
		for (var ty = 1; ty <= 13; ty++) 
		{
			if($('#map_main_div svg').attr('awt_'+ty) == 1)
			{
				awt_ok[ty] = 1;
			}
			else
			{
				awt_ok[ty] = 0;
			}
		}
		var awt_ok_js = JSON.stringify(awt_ok);
		
		data = $('#day').val();
		if(data == "" || data === undefined) data = 0;
		//console.log(area_arr_final);
		var jsonOb = JSON.stringify(area_arr_final);

		JQ_loader('Loading', 'b');

		$.post(
			"lib/cap.create.from_js_array.2.php",
			{cap_array:jsonOb, data:data, awt:awt_ok_js},
			function(r){
				//your success response
				//alert('OK!');
				if (/^[\],:{}\s]*$/.test(r.replace(/\\["\\\/bfnrtu]/g, '@').
				replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
				replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
				  //the json is ok
					area_green = jQuery.parseJSON(r);
				}else{
				  //the json is not ok
				  alert(r);
				}
				//area_green = jQuery.parseJSON(r);
				if(area_green !== null)
				{
					if (/^[\],:{}\s]*$/.test(r.replace(/\\["\\\/bfnrtu]/g, '@').
					replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
					replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
					  //the json is ok
						send_final(r);
					}
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
		var content = '<form><ul data-role="listview" data-inset="true" data-shadow="false" id="GreenUL" style="margin-top: 0px;">';
		var r_arr = jQuery.parseJSON(r);
		tmp_name ='';
		li_bool = false;
		$.each(r_arr, function(index, data) {
		
			if(data['name'] != tmp_name)
			{
				if(li_bool) content+= '</div>';
				if(li_bool) content+= '</li>';
				if(li_bool) content+= '<li data-iconpos="right" data-inset="false" data-mini="true" class="lang_collaps type_collaps">'; /*data-role="collapsible"  */ 
				else 		content+= '<li data-iconpos="right" data-inset="false" data-mini="true" class="lang_collaps type_collaps" style="border-top: 1px solid #dddddd !important;">'; /*data-role="collapsible"  */ 
					content+= '<h2 style="margin: 0px;">'+data['name']+'</h2>';
					content+= '<div id="green_div_'+data['aid']+'" style="height: 30px;">';
					tmp_name = data['name'];
					li_bool = true;
			}
				
				if(data['type'] < 10) 
					content+= $('#left_box_type_0'+data['type']).closest('div')[0].outerHTML;
				else 
					content+= $('#left_box_type_'+data['type']).closest('div')[0].outerHTML;
				
				//content+= '<br>'+data['type']+': <input type="checkbox" name="checkbox-'+data['aid']+'" id="checkbox-'+data['aid']+'" checked="checked" value="'+data['type']+'"/>';
		});
		content+= '</ul></form>';

		$('[type="checkbox"]').checkboxradio();
		$('[type="checkbox"]').checkboxradio("refresh");

		$('#set').append( content ).trigger('create');
		$('#set').collapsibleset( "refresh" );

		$.each(r_arr, function(index, data) {
			$('#green_div_'+data['aid']+' div').css('background-color', '#29d660');
			$('#green_div_'+data['aid']+' div').css('float', 'left');
			$('#green_div_'+data['aid']+' div').addClass('green_area_type_sel');
			if(data['type'] < 10)
				$('#green_div_'+data['aid']+' #left_box_type_0'+data['type']).attr('AaidTtype','a'+data['aid']+'t'+data['type']);
			else
				$('#green_div_'+data['aid']+' #left_box_type_'+data['type']).attr('AaidTtype','a'+data['aid']+'t'+data['type']);
			console.log(index + ' / ' + r_arr.length);
		});

		$('.green_area_type_sel').on('click', function(){
			if($(this).attr('no_green') != 1)
			{
				$(this).css('background-color', '#ffffff');
				$(this).attr('no_green', 1);
			}
			else
			{
				$(this).css('background-color', '#29d660');
				$(this).attr('no_green', 0);
			}
		});

		$('.type_collaps .ui-collapsible-content').css('padding','13px');

		//$('#set div').trigger('create');

		$('div .ui-checkbox').css('margin', '-1px 0');

		$.mobile.loading( "hide" );

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
				}

				cinfo = 3;
				//for (var ty = 1; ty <= 13; ty++) 
				//{
					aaidttype="a461t1"

				if($('#map_main_div svg').attr('awt_'+data['type']) == 1 && $('[aaidttype='+'a'+data['aid']+'t'+data['type']+']').attr('no_green') != 1)
				{
					area_green_final[aid][data['type']] = {};
					area_green_final[aid][data['type']]['name'] = data['name'];

					level 	= 1;
					type 	= data['type'];
					//to 	= 
					//from 	= 
					
					if(level > 0 && type > 0)
					{
						area_green_final[aid][data['type']]['eid'] 	= data['eid'];
						area_green_final[aid][data['type']]['level'] 	= level;
						area_green_final[aid][data['type']]['type'] 	= type;
						area_green_final[aid][data['type']]['text_0']	= 'no warning';
						offset = $('#timezone_h').html();

						area_green_final[aid][data['type']]['exutc'] 	= '+00:00';
						area_green_final[aid][data['type']]['from'] 	= date.yyyymmddH() + ' 23:00:00';
						area_green_final[aid][data['type']]['to'] 	= date.yyyymmdd() + ' 22:59:59';
					}
				}
				//}
			});
			
			data = $('#day').val();
			if(data == "" || data === undefined) data = 0;
			var jsonOb = JSON.stringify(area_green_final);
			
			$.mobile.loading( "hide" );
			JQ_loader('Loading', 'b');
			
			$.post(
				"lib/cap.create.from_js_array.2.php",
				{cap_array:jsonOb, no_del:1, data:data},
				function(r){
					//your success response
					//alert('OK!');
					$.mobile.loading( "hide" );
					area_green_final_resp = r; // shoud be NULL
					//send_final(r);
				}
			);
		}
		else if(yesno == -1)
		{
			$('.type_collaps').collapsible( "expand" );
		}

		if(yesno != -1)
		{
			$.mobile.loading( "hide" );
			$('#CAPpopupDialog').popup( "close" );
			setTimeout(function(){
				$('#set').html('').trigger('create');
				$('#CAP_Send_popupDialog').popup();
				$('#CAP_Send_popupDialog').popup( "open" );
			}, 100);
		}
	}

	function send_all_proce_cap(yesno)
	{
		if(yesno == 1)
		{
			JQ_loader('Loading', 'b');

			$.post(
				"lib/cap.meteoalarm.webservices.multi_import.php",
				{no_del:1},
				function(r){
					$.mobile.loading( "hide" );
					//your success response
					$('#SOAPUL').html(r).trigger('create');
					
					$('#CAP_Send_popupDialog').popup( "close" );
					setTimeout(function(){
						$('#CAP_SOAP_popupDialog').popup();
						$('#CAP_SOAP_popupDialog').popup( "open" );
					}, 100);
					setTimeout(function(){
						//something_changed=false;
						$('#mk_process_info').html($('#mk_process_lang').val());

						loading_dots();
						$("#submit_cap").addClass('ui-disabled');

						mk_pro_interval = setInterval(function(){ 
							$.ajax({
								type: "POST",
								url: 'lib/cap.meteoalarm.webservices.mkv.php',
								data: '', // serializes the forms elements.
								success: function(data)
								{			
									if(parseInt(data) > 0)
									{
										//loading_dots();
									}
									else
									{
										$( "#submit_cap" ).removeClass('ui-disabled');
										location.reload();
									}
								}
							});
						}, 30000);
					}, 100);
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
		//$('[sel=0]').css('stroke-dasharray', '0,0');

		$('.pol_'+aid).css('stroke', 'blue');
		$('.pol_'+aid).css('stroke-width', '3px');
		//$('.pol_'+aid).css('stroke-dasharray', '0,0');
	}

	function poliaway(aid)
	{
		if($('.pol_'+aid).attr('sel') == 0)
		{
			$('.pol_'+aid).css('stroke', 'black');
			$('.pol_'+aid).css('stroke-width', '1');
			//$('.pol_'+aid).css('stroke-dasharray', '0,0');
		}
	}

	function init_svg()
	{
		svghtml = $('#map_main_div svg').children();
		console.log(svghtml);
		if(svghtml)
		{
			panZoomInstance = svgPanZoom('#map_main_div svg', {
				zoomEnabled: true,
				zoomScaleSensitivity: 0.5,
				dblClickZoomEnabled: false,
				preventMouseEventsDefault: false, 
				controlIconsEnabled: true,
				fit: true,
				center: true,
				minZoom: 0.5,
				onZoom: function(){
					zoomscal = (1 / panZoomInstance.getZoom());
					$('pattern').css('transform', 'scale('+zoomscal+', '+zoomscal+')');
					$('pattern image').css('transform', 'scale('+zoomscal+', '+zoomscal+')');
				}
			});
			clearInterval(svg_intervall);
		}
	}

	/**
	 *   Plugin init
	 */
	var area_data = {};
	var area_info = {};
	function init_plugin_map()
	{
		var svg_intervall = setInterval(function(){ init_svg() }, 500);
		//$('#map_main_div svg').prepend('<filter id="css_brightness"><feComponentTransfer><feFuncR type="linear" slope="0.5"/><feFuncG type="linear" slope="0.5"/><feFuncB type="linear" slope="0.5"/></feComponentTransfer></filter>').trigger('create');
		$('#map_main_div svg').css('min-height', '645px');
		//$('div').on('pageshow',function(event, ui){
		//	if($('#map_main_div svg g').html())
		//	{
		//		panZoomInstance = svgPanZoom('#map_main_div svg', {
		//			zoomEnabled: true,
		//			zoomScaleSensitivity: 0.5,
		//			dblClickZoomEnabled: false,
		//			preventMouseEventsDefault: false, 
		//			controlIconsEnabled: true,
		//			fit: true,
		//			center: true,
		//			minZoom: 0.5,
		//			onZoom: function(){
		//				zoomscal = (1 / panZoomInstance.getZoom());
		//				$('pattern').css('transform', 'scale('+zoomscal+', '+zoomscal+')');
		//				$('pattern image').css('transform', 'scale('+zoomscal+', '+zoomscal+')');
		//			}
		//		});
		//	}
		//});

		lang_1 = $('#lang_1').val();
		$('input[name=langs]').each(function(index, data){
			lang[index] = $(data).val();
		});

		area_info['sel_type'] = 0;
		area_info['problem'] = true;
		$('#map_main_div svg g, path, polygon').each(function(index){
			id = $(this).attr('id');
			if(id !== undefined)
			{
				$(this).css('stroke', 'black');
				$(this).css('stroke-width', '1');
				$(this).css('fill', 'lightgrey');

				area_data[id] = {};
				area_data[id]['name'] = $('#emmaid_select option[value='+id+']').text();
				area_data[id]['eid'] = id;
				area_data[id]['sel'] = 0;
				area_data[id]['sel_type'] = 0;
				area_data[id]['type'] = {};
				area_data[id]['level'] = {};
				area_data[id]['desc'] = {};
				area_data[id]['inst'] = {};
				area_data[id]['from'] = {};
				area_data[id]['to'] = {};
			}
			delete(id);
		});

		$( "#CAP_SOAP_popupDialog" ).on( "collapsibleexpand", function( event, ui ) { 
			$("#CAP_SOAP_popupDialog").popup("reposition", {positionTo: 'origin'});
			$('#CAP_SOAP_popupDialog').resize();
		});

		$( "#CAP_SOAP_popupDialog" ).on( "collapsiblecollapse", function( event, ui ) {
			$("#CAP_SOAP_popupDialog").popup("reposition", {positionTo: 'origin'});
			$('#CAP_SOAP_popupDialog').resize();
		});

		$('#reload').click(function() {
			data = $('#day').val();
			if(data == "" || data === undefined) data = 0;
			type = $('#type').val();
			if(type == "" || type === undefined || type == 0) type = "";
			window.location = "map.php?data="+data+'&type='+type;
		});

		$('#Undo').click(function() {
			document.execCommand('undo', false, null);
		});

		$('#Redo').click(function() {
			document.execCommand('redo', false, null);
		});

		$('#emmaid_select').change(function(r) {
			$.each(area_data, function(id, data){
				area_data[id]['sel_type'] = 0;
				$('#'+id).css('fill', 'lightgrey');
			});
			$.each($(this).val(), function(index, id){
				if(area_data[id]['type'][area_info['sel_type']] < 1 || area_data[id]['type'][area_info['sel_type']] == undefined) 
				{
					area_data[id]['type'][area_info['sel_type']] = area_data[area_info['last_area']]['type'][area_info['sel_type']];
				}
				area_data[id]['sel_type'] = area_info['sel_type'];
				//area_data[id]['sel_type'] = area_info['sel_type'];

				area_data[id]['sel'] = 1;
				$('#'+id).css('filter', 'url(#css_brightness)');
				$('#'+id).css('stroke', 'blue');
				$('#'+id).css('stroke-width', '3px');
				$('#'+id).css('fill', 'url(#pattern_l'+area_data[id]['type'][area_info['sel_type']]+'t'+area_info['sel_type']+')');
			});
			plugin_calc_map();
			//console.log(r);
			//area_warning_detail(aid, key_type, tmp_this);
		});


		$(document).on('popupafterclose', '#emmaid_select-listbox-popup', function () {
			plugin_show_type(false);
		});
		
		$('#submit_cap').on('click', function(){
			if(area_info['problem'] == true)
				plugin_get_all_warnings();
			else
				$('#Error_popupDialog').popup('open');
		});

		$('#sav_war').on('click', function(){
			plugin_save_warning_detail();
		});	
		$('#del_war').on('click', function(){
			plugin_delete_warning_detail();
		});

		$('#green_no').on('click', function(){
			make_white_areas_green(0);
		});

		$('#green_yes').on('click', function(){
			make_white_areas_green(1);
		});

		$('#green_edit').on('click', function(){
			make_white_areas_green(-1);
		});

		$('.ui-collapsible-set').on('click', function(){
			tempScrollTop = $(window).scrollTop();
			alert(tempScrollTop);
		});

		$('#send_no').on('click', function(){
			send_all_proce_cap(0);
		});

		$('#send_yes').on('click', function(){
			send_all_proce_cap(1);
		});

		//$('#map_main_div svg').mouseenter(function() {
		//	disableScroll();
		//});
//
		//$('#map_main_div svg').mouseleave(function() {
		//	enableScroll();
		//});

		$('li:not(#map-container)').on('click', function(){
			aktive_type = false;
			$('#awareness_toolbox .awareness').css('border', '');
			$('#awareness_toolbox .awareness[aktive=1]').css('opacity', 1);
			aktive_level = false;
			$('#awareness_color_toolbox .awareness').css('border', '');
			$('#awareness_color_toolbox .awareness').css('opacity', 1);
			$('#map_main_div svg').css('cursor', 'auto');
			$('#awareness_color_toolbox').css('display', '');
		});
		$('#awareness_toolbox .awareness[aktive=1]').on('click', function(){
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
				$('#awareness_toolbox .awareness[aktive=1]').css('opacity', 1);
				aktive_level = false;
				$('#awareness_color_toolbox .awareness').css('border', '');
				$('#awareness_color_toolbox .awareness').css('opacity', 1);
				$('#map_main_div svg').css('cursor', 'auto');
				$('#awareness_color_toolbox').css('display', '');
			}

			plugin_show_type(aktive_type);
		});

		$('#awareness_color_toolbox .awareness').on('click', function(){
			if(aktive_level != $(this).attr('level'))
			{
				$('#awareness_color_toolbox .awareness').css('opacity', 0.5);
				$(this).css('opacity', 1);
				$('#awareness_color_toolbox .awareness').css('border', '');
				$(this).css('border', '1px solid #0000ff');
				aktive_level = parseInt($(this).attr('level'));
				$('#map_main_div svg').css('cursor', 'url(includes/meteoalarm/fill.png), auto');
			}
			else
			{
				aktive_level = false;
				$('#awareness_color_toolbox .awareness').css('border', '');
				$('#awareness_color_toolbox .awareness').css('opacity', 1);
				$('#map_main_div svg').css('cursor', 'auto');
			}
		});

		$('#map_main_div svg g, path, polygon').mouseover(function() {
			id = $(this).attr('id');
			if(id !== undefined)
			{
				$.each(area_data, function(index, data){
					if(data['sel'] != 1) 
					{
						$('#'+index).css('stroke', 'black');
						$('#'+index).css('stroke-width', '1');
					}
				});

				$(this).css('stroke', 'blue');
				$(this).css('stroke-width', '3px');
			}
		});

		$('#map_main_div svg g, path, polygon').mouseleave(function() {
			id = $(this).attr('id');
			if(id !== undefined)
			{
				if(area_data[id]['sel'] == 0)
				{
					$(this).css('stroke', 'black');
					$(this).css('stroke-width', '1');
				}
			}
		});

		$('#map_main_div svg g, path, polygon').on('click', function() {
			id = $(this).attr('id');
			if(id !== undefined)
			{
				if(aktive_type == false || aktive_level == false)
				{
					if(area_data[id]['sel'] == 0)
					{
						area_data[id]['sel'] = 1;
						$(this).css('filter', 'url(#css_brightness)');
						$(this).css('stroke', 'blue');
						$(this).css('stroke-width', '3px');
					}
					else
					{
						area_data[id]['sel'] = 0;
						$(this).css('filter', '');
					}
				}
				else
				{
					area_data[id]['type'][aktive_type] = aktive_level;
					$(this).css('fill', 'url(#pattern_l'+aktive_level+'t'+aktive_type+')');
				}
				plugin_calc_map();
			}
		});
	}

	function plugin_calc_map()
	{
		keepopen = false;
		out = '';
		area_info['problem'] = false;
		$.each(area_data, function(index, data){
			id = index;
			if(data['sel'] == 1)
			{
				keepopen = true;
				noty_i = 0;
				$.each(data['type'], function (type, level) {
					if(level !== undefined) noty_i++;
				});
				sel_type = data['sel_type'];

				out+= '<div class="process_toolbox_area" id="aid_'+id+'" style="padding-right: 0px;padding-left: 0px;width: 300px; background-color: silver;">';
					//out+= '<div class="awareness" aktive="2" onclick="area_warning_detail('+id+', -1, this)">';
					//	out+= '<img src="includes/meteoalarm/warn-typs_11.png">';
					//out+= '</div>';
					out+= '<div class="proccess_type_area" style="width: 127px;height: 45px;float: left;overflow-y: hidden; pointer-events: all;">';
						if(noty_i > 3) size =  (noty_i * 40);
						else size = 121;
						out+= '<div class="proccess_type_area_scroll" style="position: relative;padding-left: 6px;width: '+size+'px;">';
							
							for (noty=1; noty <= (3 - noty_i); noty++) 
							{
								out+= '<div class="awareness" aktive="2" onclick="plugin_area_warning_detail(\''+id+'\', -1, this)"><img src="includes/meteoalarm/warn-typs_11.png"></div>';
							}

							$.each(data['type'], function(type, level){
								css_selected = ''; // when selelcted type border
								if(sel_type == type) css_selected='border: 3px solid rgb(0, 0, 255);';
								imgsrc = $('#left_box_type_'+type+' img').attr('src');
								levelc = $('div[level='+level+']').css('background-color');
								not_sel_able = '';
								if(area_info['sel_type'] > 0 && area_info['sel_type'] != type) not_sel_able = 'opacity: 0.5;';
								//out+= '<div class="problem awareness" style="background-color:'+levelc+';" aktive="1" onclick="area_warning_detail('+id+', '+type+', this)" '+css_selected+'>';
								//	out+= '<img src="'+imgsrc+'"><span class="problem_callsign">!</span>';
								//out+= '</div>';
								if(data['desc'] == undefined || data['desc'][type] == undefined || data['desc'][type][0] == undefined) 
								{
									out+= '<div class="problem awareness" style="background-color:'+levelc+'; '+css_selected+' '+not_sel_able+'" aktive="1" type="'+type+'" onclick="plugin_area_warning_detail(\''+id+'\', '+type+', this)" >';
									out+= '<img src="'+imgsrc+'"><span class="problem_callsign">!</span>';
								}
								else 
								{
									out+= '<div class="awareness" style="background-color:'+levelc+'; '+css_selected+' '+not_sel_able+'" aktive="1" type="'+type+'" onclick="plugin_area_warning_detail(\''+id+'\', '+type+', this)" >';
									out+= '<img src="'+imgsrc+'">';
									area_info['problem'] = true;
								}
								out+= '</div>';
							});
						out+= '</div>';
					out+= '</div>';
				
					//out=+ '<div class="awareness" aktive="2" onclick="area_warning_detail('+key2+', -1, this)">';
					//	out=+ '<img src="includes/meteoalarm/warn-typs_11.png">';
					//out=+ '</div>';
					aname=$('option[value='+id+']').text();
					//out+= '<div class="divtextscroll problem_text">' + aname + '</div>';
					out+= '<div class="divtextscroll" style="pointer-events: all;">' + aname + '</div>';
				out+= '</div>';
			}
		});
		$('#process_toolbox').html(out);

		// Open Area List
		if(area_list_on == false)
		{
			if(keepopen == true)
			{
				$('#work_toolbox').animate({
					right: ($('#process_toolbox').width())+"px"
				}, 500);
				$('#process_toolbox').finish();
				$('#process_toolbox').animate({
					right: "-1px"
				}, 500);
				area_list_on = true;
			}
		}
		else
		{
			// cLose Area List
			if(keepopen == false)
			{
				$('#work_toolbox').animate({
					right: "-1px"
				}, 500);
				$('#process_toolbox').finish();
				$('#process_toolbox').animate({
					right: "-"+($('#process_toolbox').width() + 2)+"px"
				}, 500);
				area_list_on = false;
			}
		}
		
	}

	function plugin_show_type(type)
	{
		$.each(area_data, function(index, data){
			id = index;
			if(id !== undefined)
			{
				if(type != false)
				{
					level = data['type'][type];
					if(level !== undefined)
					{
						$('#'+id).css('fill', 'url(#pattern_l'+level+'t'+type+')');
					}
					else
					{
						$('#'+id).css('fill', 'lightgrey');
					}
				}
				else
				{
					level_tmp = 0;
					if(data['type'].length < 1 || data['type'].length === undefined)
					{
						$('#'+id).css('fill', 'lightgrey');
					}
					$.each(data['type'], function(type2, level2){
						if(level_tmp < level2)
						{
							if(level2 !== undefined)
							{
								$('#'+id).css('fill', 'url(#pattern_l'+level2+'t'+type2+')');
							}
							else
							{
								$('#'+id).css('fill', 'lightgrey');
							}
							level_tmp = level2;
						}
					});
				}
			}
		});
	}

	function plugin_area_warning_detail(id, type, tmp_this)
	{
		if(area_data[id]['type'][type] > 0)
		{
			area_info['last_area'] = id;
			if(area_data[id]['sel_type'] == type)
			{
				$('#emmaid_select option[value=' + id + ']').prop('selected', false);
				$('#emmaid_select').selectmenu( "refresh" );
				if($('#emmaid_select option:selected').val() == undefined)
				{
					$('#right_area_type').html('');
					$.each(lang, function(lindex, ldata){
						$('#desc_' + lindex).val('').trigger('input');
						$('#inst_' + lindex).val('').trigger('input');
					});
					//$('#desc_1').val('').trigger('input');
					//$('#inst_1').val('').trigger('input');
					$('#from_0').val('00:00').trigger('input');
					$('#to_0').val('23:59').trigger('input');
					//$('#left_area_name').html(tmp_area_name).trigger('input');
					$('#AreaDetailDIV').css('background-color', '#cccccc');
					$('#AreaDetailUL').css('pointer-events', 'none');
					$('#AreaDetailUL').css('opacity', 0.5);
					$('#info_text').css('display', 'inherit');
					aktive_warning_type = 0;
				}
				$(tmp_this).css('border', '');
				area_data[id]['sel_type'] = 0;
				seltypereset = true;
				$.each(area_data, function(id, data){
					if(data['sel_type'] == area_info['sel_type'])
					{
						seltypereset = false;
					}
				});
				if(seltypereset)
				{
					area_info['sel_type'] = 0;
				}
			}
			else if(area_info['sel_type'] == type || area_info['sel_type'] == 0)
			{
				area_info['sel_type'] = type;
				area_data[id]['sel_type'] = type;
				$(tmp_this).css('border', '3px solid blue');
				$.each(lang, function(lindex, ldata){
					$('#desc_'+lindex).val('');
					$('#inst_'+lindex).val('');
				});
				$('#AreaDetailDIV').css('background-color', '#ffffff');
				$('#AreaDetailUL').css('opacity', 1);
				$('#info_text').css('display', 'none');
				$('#AreaDetailUL').css('pointer-events', 'auto');

				//$('#left_area_name').html(tmp_area_name);
				//console.log( $('input[name=multisel_area_name_bool]:checked').val());
				if( $('input[name=multisel_area_name_bool]:checked').val() == "1") // aktive_warning_key
				{
					$('#emmaid_select option[value=' + id + ']').prop('selected', true);
				}
				else
				{
					$('#emmaid_select option').prop('selected', false);
					$('#emmaid_select option[value=' + id + ']').prop('selected', true);
				}

				$('#emmaid_select').selectmenu( "refresh" );
				$('#right_area_type').html('');
				$('#right_area_type').append($(tmp_this).clone());

				$.each(lang, function(lindex, ldata){
					if(area_data[id]['desc'][type] !== undefined) if(area_data[id]['desc'][type][lindex] !== undefined) $('#desc_' + lindex).val((area_data[id]['desc'][type][lindex])).trigger('input');
					if(area_data[id]['inst'][type] !== undefined) if(area_data[id]['inst'][type][lindex] !== undefined) $('#inst_' + lindex).val((area_data[id]['inst'][type][lindex])).trigger('input');
				});
				//$('#desc_1').val((tmp_area_text_1)).trigger('input');
				//$('#inst_1').val((tmp_area_inst_1)).trigger('input');

				if(area_data[id]['from'][type] !== undefined)
				{
					$('#from_0').val(area_data[id]['from'][type]).trigger('input');
				}
				else
				{
					$('#from_0').val('00:00').trigger('input');
				}
				if(area_data[id]['to'][type] !== undefined)
				{
					$('#to_0').val(area_data[id]['to'][type]).trigger('input');
				}
				else
				{
					$('#to_0').val('23:59').trigger('input');
				}
			}
			if(area_info['sel_type'] != 0) 
			{
				$('.awareness[type!='+area_info['sel_type']+']').css('opacity', '0.5');
				$('.awareness[type='+area_info['sel_type']+']').css('opacity', '1');
			}
			else
			{
				$('.awareness[aktive!=2]').css('opacity', '1');
			}
		}
	}

	function plugin_save_warning_detail()
	{
		$.each(area_data, function(id, data){
			if(data['sel_type'] == area_info['sel_type'])
			{
				area_data[id]['level'][data['sel_type']] = area_data[id]['type'][data['sel_type']];

				area_data[id]['desc'][data['sel_type']] = {};
				area_data[id]['inst'][data['sel_type']] = {};
				$.each(lang, function(lindex, ldata){
					area_data[id]['desc'][data['sel_type']][lindex] = $('#desc_' + lindex).val();
					area_data[id]['inst'][data['sel_type']][lindex] = $('#inst_' + lindex).val();
				});

				area_data[id]['from'][data['sel_type']] = $('#from_0').val();
				area_data[id]['to'][data['sel_type']] = $('#to_0').val();
				area_data[id]['sel_type'] = 0;
			}
		});
		area_info['sel_type'] = 0;

		$('#right_area_type').html('');
		$.each(lang, function(lindex, ldata){
			$('#desc_' + lindex).val('').trigger('input');
			$('#inst_' + lindex).val('').trigger('input');
		});
		//$('#desc_1').val('').trigger('input');
		//$('#inst_1').val('').trigger('input');
		$('#from_0').val('00:00').trigger('input');
		$('#to_0').val('23:59').trigger('input');
		//$('#left_area_name').html(tmp_area_name).trigger('input');
		$('#AreaDetailDIV').css('background-color', '#cccccc');
		$('#AreaDetailUL').css('pointer-events', 'none');
		$('#AreaDetailUL').css('opacity', 0.5);
		$('#info_text').css('display', 'inherit');

		plugin_show_type(false);
		plugin_calc_map()
	}

	function plugin_delete_warning_detail()
	{
		if(confirm($('#del_warn_lang').val()))
		{
			$.each(area_data, function(id, data){
				if(data['sel_type'] == area_info['sel_type'])
				{
					delete(area_data[id]['type'][data['sel_type']]);
					area_data[id]['desc'][area_data[id]['sel_type']] = {};
					area_data[id]['inst'][area_data[id]['sel_type']] = {};
					area_data[id]['from'][area_data[id]['sel_type']] = '00:00';
					area_data[id]['to'][area_data[id]['sel_type']] = '00:00';
					area_data[id]['sel_type'] = 0;
				}
			});
			area_info['sel_type'] = 0;
			$('#right_area_type').html('');
			$.each(lang, function(lindex, ldata){
				$('#desc_' + lindex).val('').trigger('input');
				$('#inst_' + lindex).val('').trigger('input');
			});
			//$('#desc_1').val('').trigger('input');
			//$('#inst_1').val('').trigger('input');
			$('#from_0').val('00:00').trigger('input');
			$('#to_0').val('00:00').trigger('input');
			//$('#left_area_name').html(tmp_area_name).trigger('input');
			$('#AreaDetailDIV').css('background-color', '#cccccc');
			$('#AreaDetailUL').css('pointer-events', 'none');
			$('#AreaDetailUL').css('opacity', 0.5);
			$('#info_text').css('display', 'inherit');

			plugin_show_type(false);
			plugin_calc_map();
		}
	}

	function plugin_get_all_warnings()
	{
		console.log(area_data);
		cap_engine = $('#cap_engine').val();
		plugin_name = $('#plugin_name').val();
		data = $('#day').val();
		if(data == "" || data === undefined) data = 0;
		var jsonOb = JSON.stringify(area_data);
		$.post(
			cap_engine,
			{cap_array:jsonOb, data:data, use_plugin: plugin_name},
			function(r){
				//your success response
				alert(r);
			}
		);
	}

	var mk_pro_dot_interval;
	var mk_pro_dot_interval_i = 0;
	function loading_dots()
	{
		$('#mk_process_info').html($('#mk_process_lang').val() + ' ');
		mk_pro_dot_interval_i = 0;
		mk_pro_dot_interval = setInterval(function(){ 
			if(mk_pro_dot_interval_i < 3)
			{
				$('#mk_process_info').html($('#mk_process_info').html() + '.');
				mk_pro_dot_interval_i++;
			}
			else
			{
				$('#mk_process_info').html($('#mk_process_lang').val() + ' ');
				mk_pro_dot_interval_i = 0;
			}
		}, 1000);
	}

	function JQ_loader(text_tmp, theme_tmp)
	{
		$.mobile.loading( "show", {
			text: text_tmp,
			textVisible: true,
			theme: theme_tmp,
			html: ""
		});
	}

	function JQ_loader(text_tmp, theme_tmp, html_tmp)
	{
		$.mobile.loading( "show", {
			text: text_tmp,
			textVisible: true,
			theme: theme_tmp,
			html: html_tmp
		});
	}

	function JQ_loader_off()
	{
		$.mobile.loading( "hide" );
	}

	Date.prototype.yyyymmdd = function() {
		var yyyy = this.getFullYear().toString();
		var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
		var dd  = this.getDate().toString();
		return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
	};

		Date.prototype.yyyymmddH = function() {
		var yyyy = this.getFullYear().toString();
		var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
		var dd  = (this.getDate() - 1).toString();
		return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
	};

	function getFirstKey(data) {
		for (var prop in data)
		return prop;
	}

	var keys = {37: 1, 38: 1, 39: 1, 40: 1};

	function preventDefault(e) {
		e = e || window.event;
		if (e.preventDefault)
		e.preventDefault();
		e.returnValue = false;  
	}

	function preventDefaultForScrollKeys(e) {
		if (keys[e.keyCode]) {
			preventDefault(e);
			return false;
		}
	}

	function disableScroll() {
		if (window.addEventListener) // older FF
		window.addEventListener('DOMMouseScroll', preventDefault, false);
		window.onwheel = preventDefault; // modern standard
		window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
		window.ontouchmove  = preventDefault; // mobile
		document.onkeydown  = preventDefaultForScrollKeys;
	}

	function enableScroll() {
		if (window.removeEventListener)
		window.removeEventListener('DOMMouseScroll', preventDefault, false);
		window.onmousewheel = document.onmousewheel = null; 
		window.onwheel = null; 
		window.ontouchmove = null;  
		document.onkeydown = null;  
	}