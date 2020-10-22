$( document ).ready(function()
{
	// Inital ClockPicker Addon
	$('.clockpicker').clockpicker().find('input').change(function(){
		//console.log(this.value);
	});

	$( document ).on( "pageinit", "#capview", function( event ) {
		updateCapXML();
	});

	if($('#init_map').val() != 1)
	{
		$( "input, select" ).change(function() {
			updateCapXML();
			updateColor(this);
			if (typeof dependencies_js !== "undefined") {
				dependencies_js();
			}
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

	$( "#webservice_2_switch" ).change(function() {
		if($( "#webservice_2_switch" ).prop('checked'))
		{
			$("#conf-detail-2").show();
		}
		else
		{
			$("#conf-detail-2").hide();
		}
	});

	$( "#proxy_switch" ).change(function() {
		if($( "#proxy_switch" ).prop('checked')){
			$('.ProxyInput').css("opacity", "1");
			$('.ProxyInput input').prop('readonly', false);
		}else{
			$('.ProxyInput').css("opacity", "0.3");
			$('.ProxyInput input').prop('readonly', true);
		}
	});
	$( "#proxy_switch" ).trigger("change");

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

	$( "#from_0, #to_0" ).change(function() {
		var dnow = new Date();
		var now = dnow.getHours()+':'+dnow.getMinutes();
		var fromtime = $("#from_0").val();
		var totime = $("#to_0").val();
		var dfromtime = new Date(dnow.yyyy_mm_dd() +" "+ fromtime);
		var dtotime = new Date(dnow.yyyy_mm_dd() +" "+ totime);
		if($('#date_collaps').hasClass('ui-collapsible-collapsed')){
			if(fromtime > totime){
				var dtoday = new Date();
				var d = new Date($("#to_date").val());
				//to date is now tomorrow
				if(dtoday.toDateString() === d.toDateString())
				{
					d.setDate(dnow.getDate() + 1);
					$("#to_date").val(d.yyyy_mm_dd());
					$("#to_date").trigger("change");
				}
			}
			if(dtotime < dnow && dfromtime < dnow){
				var d = new Date($("#from_date").val());
				d.setDate(dnow.getDate() + 1);
				$("#from_date").val(d.yyyy_mm_dd());
				$("#from_date").trigger("change");

				var d = new Date($("#to_date").val());
				d.setDate(dnow.getDate() + 1);
				$("#to_date").val(d.yyyy_mm_dd());
				$("#to_date").trigger("change");
			}else if(dtotime < dnow){
				var d = new Date($("#to_date").val());
				d.setDate(dnow.getDate() + 1);
				$("#to_date").val(d.yyyy_mm_dd());
				$("#to_date").trigger("change");
			}
		}
	});

	$('#legdatefrom').html($("#today").val());
	$('#legdateto').html($('#today').val());

	if(document.getElementById("from_date")) {
		var from = document.getElementById("from_date");
		from.value = $("#today").val();
		var to = document.getElementById("to_date");
		to.value = $("#today").val();	
	}
	
	$( "#from_date, #to_date" ).change(function() {

		var from = document.getElementById("from_date").value;
		var to = document.getElementById("to_date").value;
		console.log('from: ' +from);
		console.log('to: ' +to);		
		
		var date1 = new Date(from);
		var date2 = new Date(to);
		var diffTime = Math.abs(date2 - date1);
		var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
		console.log(diffDays);

		if(diffDays > 7) {
			console.log('> 7');

			date1.setDate(date1.getDate() + 7);
			var dd = date1.getDate();
			var mm = date1.getMonth() + 1;
			var y = date1.getFullYear();

			if(mm < 10) mm = 0+''+ mm;

			var format = y + '-' + mm + '-' + dd;

			document.getElementById("to_date").value = format;
		}


		$('#legdatefrom').html($("#from_date").val());
		$('#legdateto').html($("#to_date").val());

		

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

	function validateCap(){
		var data = $("#capviewtextarea").val();
		var webservice_on = $('#webservice_aktive').val();
		$.ajax({
			type: "POST",
			url: "lib/ajax/validate.ajax.php",
			data: {webservice_aktive: webservice_on,cap: data}, // serializes the forms elements.
			success: function(data)
			{
				$("#resultValidate").html(data);
			},
			error: function(errorThrown)
			{
				console.log( errorThrown);
			}
		});
	}

	function ajax_conf()
	{
		var url = "index.php?conf=1"; // the script where you handle the form input.
		JQ_loader("Saving ...", "b");
		$.ajax({
		      	type: "POST",
		        url: url,
		        data: $("#capform").serialize(), // serializes the forms elements.
		        success: function(datare)
		        {
		        	if(datare != "") alert(datare);
						//else $( "#Saved_conf" ).popup( "open" );
						setTimeout(function(){
							location.reload(true);
						}, 1000);
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

	var next_calc = 0;
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
	var lang = [];

	var svg_intervall;
	function init_svg()
	{
		svghtml = $('#map_main_div svg').children();
		//console.log(svghtml);
		if($(svghtml).html())
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
	var ind_lang = {};
	function init_plugin_map()
	{
		svg_intervall = setInterval(function(){ init_svg() }, 500);
		$('#map_main_div svg').css('min-height', '645px');

		if($('#map_main_div svg').attr('process') > 0)
		{
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
							//location.reload();
							$('#symbol').show();
						}
					}
				});
			}, 30000);
		}

		if($('#plugin_name').val() == 'webservice')
		{
			$('#CountryInfo').html($('#map_main_div svg').attr('country'));
			aktive_types = [];
			for (var i = 1; i <= 13; i++) {
				awt_bool = $('#map_main_div svg').attr('awt_'+i);
				aktive_types[i] = awt_bool;
				if(i < 10) i_tmp = '0' + i;
				else i_tmp = i;
				$('#left_box_type_' + i_tmp).attr('aktive', awt_bool);
				if(awt_bool == "0") $("#type option[value='"+i+"']").remove();
			};
		}

		lang_1 = $('#lang_1').val();
		$('input[name=langs]').each(function(index, data){
			lang[index] = $(data).val();
			ind_lang[$(data).val()] = index;
		});

		area_info['sel_type'] = 0;
		area_info['problem'] = false;
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
				area_data[id]['emma_id'] = $(this).attr('eid');
				area_data[id]['sel'] = 0;
				area_data[id]['sel_type'] = 0;
				area_data[id]['type'] = {};
				area_data[id]['level'] = {};
				area_data[id]['desc'] = {};
				area_data[id]['inst'] = {};
				area_data[id]['date_from'] = {};
				area_data[id]['date_to'] = {};
				area_data[id]['from'] = {};
				area_data[id]['to'] = {};
				area_data[id]['identifier'] = {};
			}
			delete(id);
		});

		if(area_vl !== undefined)
		{
			if(area_vl != "" && area_vl[0] != "Error2")
			{
				today = $('#today').val();
				// console.log(today);

				$.each(area_vl, function(index, val){

					val['from'] = val['from'].split(" ");
					val['to'] = val['to'].split(" ");

					if(val['from'][0] == today || val['to'][0] == today)
					{
						area_data[val['aid']]['type'][val['type']] = val['level'];

						area_data[val['aid']]['emma_id'] = val['EMMA_ID'];

						area_data[val['aid']]['date_from'][val['type']]= val['from'][0];
						area_data[val['aid']]['date_to'][val['type']]= val['to'][0];

						area_data[val['aid']]['from'][val['type']] = val['from'][1];
						area_data[val['aid']]['to'][val['type']] = val['to'][1];

						if(! $.isArray(area_data[val['aid']]['desc'][val['type']])) area_data[val['aid']]['desc'][val['type']] = {};
						$.each(val['desc'], function(lang_name, text){
							if(text)
							{
								lkey = ind_lang[lang_name];
								if(lkey == undefined) lkey = 0;
								area_data[val['aid']]['desc'][val['type']][lkey] = text;
							}else{
								lkey = 0;
								area_data[val['aid']]['desc'][val['type']][lkey] = "no description";
							}
						});
						if(! $.isArray(area_data[val['aid']]['inst'][val['type']])) area_data[val['aid']]['inst'][val['type']] = {};
						if(val['inst'] != undefined)
						$.each(val['inst'], function(lang_name, text){
							if(text)
							{
								area_data[val['aid']]['inst'][val['type']][ind_lang[lang_name]] = text;
							}
						});

						area_data[val['aid']]['identifier'][val['type']] = val['identifier'];

						$('#'+val['aid']).css('fill', 'url(#pattern_l'+val['level']+'t'+val['type']+')');
					}
				});
				// a webservice is aktive
				plugin_show_type(false);
			}
		}

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
		});


		$(document).on('popupafterclose', '#emmaid_select-listbox-popup', function () {
			plugin_show_type(false);
		});

		$('#submit_cap').on('click', function(){
			if(area_info['problem'] == false)
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
			plugin_make_white_areas_green(0);
		});

		$('#green_yes').on('click', function(){
			plugin_make_white_areas_green(1);
		});

		$('#green_edit').on('click', function(){
			plugin_make_white_areas_green(-1);
		});

		$('.ui-collapsible-set').on('click', function(){
			tempScrollTop = $(window).scrollTop();
			alert(tempScrollTop);
		});

		$('#send_no').on('click', function(){
			plugin_send_all_proce_cap(0);
		});

		$('#send_yes').on('click', function(){
			plugin_send_all_proce_cap(1);
		});

		$("#desc_0").on('change keyup paste input', function(){
			if($("#desc_0").val() == "") {
				$("#desc_0").addClass('required');
			} else {
				$("#desc_0").removeClass('required');
			}
		});

		// Enable and disable scrolling in process toolbox inner and outer!
		$('#process_toolbox_inner').mouseenter(function() {
			$('#process_toolbox').css('pointer-events', 'all');
		});
		$('#process_toolbox_inner').mouseleave(function() {
			$('#process_toolbox').css('pointer-events', 'none');
		});

		$('li:not(#map-container)').on('click', function(){
			aktive_type = false;
			$('#awareness_toolbox .awareness').css('border', '');
			$('#awareness_toolbox .awareness[aktive=1]').css('opacity', 1);
			aktive_level = false;
			$('#awareness_color_toolbox .awareness').css('border', '');
			$('#awareness_color_toolbox .awareness').css('opacity', 1);
			$('#map_main_div svg').css('cursor', 'auto');
			$('#awareness_color_toolbox').css('display', '');
			plugin_show_type(aktive_type);
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

		$(document).bind('click', function(e) {
			if($(e.target).closest('#awareness_toolbox').length === 0 && $(e.target).closest('#map_main_div svg g, path, polygon').length === 0 && $(e.target).closest('#awareness_color_toolbox').length === 0)
			{
				aktive_type = false;
				$('#awareness_toolbox .awareness').css('border', '');
				$('#awareness_toolbox .awareness[aktive=1]').css('opacity', 1);
				aktive_level = false;
				$('#awareness_color_toolbox .awareness').css('border', '');
				$('#awareness_color_toolbox .awareness').css('opacity', 1);
				$('#map_main_div svg').css('cursor', 'auto');
				$('#awareness_color_toolbox').css('display', '');
				plugin_show_type(aktive_type);
			}
		});

		$('#map_main_div svg g, path, polygon').mouseover(function() {
			id = $(this).attr('id');
			if(id !== undefined && $('#emmaid_select option[value='+id+']').text() != "")
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
			if(id !== undefined && $('#emmaid_select option[value='+id+']').text() != "")
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
			if(id !== undefined && $('#emmaid_select option[value='+id+']').text() != "")
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
					if(area_data[id]['sel'] == 0)
					{
						area_data[id]['sel'] = 1;
						$(this).css('filter', 'url(#css_brightness)');
						$(this).css('stroke', 'blue');
						$(this).css('stroke-width', '3px');
					}

					if(area_data[id]['type'][aktive_type] == "1")
					{
						area_data[id]['from'][aktive_type] = '00:00';
						area_data[id]['to'][aktive_type] = '23:59';
					}

					if (aktive_level == 1 && (area_data[id]['from'][aktive_type] === undefined || area_data[id]['from'][aktive_type] == "")) {
						area_data[id]['from'][aktive_type] = '00:00:00';
						area_data[id]['to'][aktive_type] = '23:59:00';
					}
					area_data[id]['type'][aktive_type] = aktive_level;
					area_data[id]['desc'][aktive_type] = {};
					area_data[id]['inst'][aktive_type] = {};
					delete(area_data[id]['date_from'][aktive_type]);
					delete(area_data[id]['date_to'][aktive_type]);
					$(this).css('fill', 'url(#pattern_l'+aktive_level+'t'+aktive_type+')');
				}
				plugin_calc_map();
			}
		});

		if(area_vl !== undefined)
		{
			if(area_vl[0] != "Error2")
			{
				plugin_calc_map(); // init warnings data first time (when data5)
			}
		}
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
					out+= '<div class="proccess_type_area" style="width: 127px;height: 45px;float: left;overflow-y: hidden; pointer-events: all;">';
						if(noty_i > 3) size =  (noty_i * 40);
						else size = 121;
						out+= '<div class="proccess_type_area_scroll" style="position: relative;padding-left: 6px;width: '+size+'px;">';

							for (noty=1; noty <= (3 - noty_i); noty++)
							{
								out+= '<div class="awareness" aktive="2" onclick="plugin_area_warning_detail(\''+id+'\', -1, this)"><img src="includes/meteoalarm/warn-typs_11.png"></div>';
							}

							keysSorted = Object.keys(data['type']).sort(function(a,b){return data['type'][b] - data['type'][a]});
							//alert(keysSorted);     // bar,me,you,foo
							$.each(keysSorted, function(index, type){
								level = data['type'][type];
								css_selected = ''; // when selelcted type border
								if(sel_type == type) css_selected='border: 3px solid rgb(0, 0, 255);';
								imgsrc = $('#left_box_type_'+type+' img').attr('src');
								levelc = $('div[level='+level+']').css('background-color');
								not_sel_able = '';
								if(area_info['sel_type'] > 0 && area_info['sel_type'] != type) not_sel_able = 'opacity: 0.5;';
								if(level > 1 && (data['desc'] == undefined || data['desc'][type] == undefined || data['desc'][type][0] == undefined || data['desc'][type][0] == ""))
								{
									out+= '<div class="problem awareness" style="background-color:'+levelc+'; '+css_selected+' '+not_sel_able+'" aktive="1" type="'+type+'" onclick="plugin_area_warning_detail(\''+id+'\', '+type+', this)" >';
									out+= '<img src="'+imgsrc+'"><span class="problem_callsign">!</span>';
									area_info['problem'] = true;
								}
								else
								{
									out+= '<div class="awareness" style="background-color:'+levelc+'; '+css_selected+' '+not_sel_able+'" aktive="1" type="'+type+'" onclick="plugin_area_warning_detail(\''+id+'\', '+type+', this)" >';
									out+= '<img src="'+imgsrc+'">';
								}
								out+= '</div>';
							});
						out+= '</div>';
					out+= '</div>';

					aname=$('#emmaid_select option[value='+id+']').text();

					out+= '<div class="divtextscroll" style="pointer-events: all;">' + aname + '</div>';
				out+= '</div>';
			}
			else
			{
				keysSorted = Object.keys(data['type']).sort(function(a,b){return data['type'][b] - data['type'][a]});
				$.each(keysSorted, function(index, type){
					level = data['type'][type];
					if(level > 1 && (data['desc'] == undefined || data['desc'][type] == undefined || data['desc'][type][0] == undefined && data['type'][type] !== undefined && data['type'][type] != 0 ))
					{
						area_info['problem'] = true;
					}
				});
			}
		});
		$('#process_toolbox_inner').html(out);

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
					$('#from_0').val('00:00').trigger('input');
					$('#to_0').val('23:59').trigger('input');

					var d = new Date();

					// $('#legdatefrom, #from_date').val(d.yyyy_mm_dd()).trigger('change');
					// $('#legdateto, #to_date').val(d.yyyy_mm_dd()).trigger('change');
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
					$('#desc_'+lindex).val('').trigger('input');
					$('#inst_'+lindex).val('').trigger('input');
				});
				$('#AreaDetailDIV').css('background-color', '#ffffff');
				$('#AreaDetailUL').css('opacity', 1);
				$('#info_text').css('display', 'none');
				$('#AreaDetailUL').css('pointer-events', 'auto');				

				$('#emmaid_select option[value=' + id + ']').prop('selected', true);

				$('#emmaid_select').selectmenu( "refresh" );
				$('#right_area_type').html('');
				$('#right_area_type').append($(tmp_this).clone());

				$.each(lang, function(lindex, ldata){
					if(area_data[id]['desc'][type] !== undefined) if(area_data[id]['desc'][type][lindex] !== undefined) $('#desc_' + lindex).val((area_data[id]['desc'][type][lindex])).trigger('input');
					if(area_data[id]['inst'][type] !== undefined) if(area_data[id]['inst'][type][lindex] !== undefined) $('#inst_' + lindex).val((area_data[id]['inst'][type][lindex])).trigger('input');
				});



				// getDate();

				

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

				if(area_data[id]['date_from'][type] !== undefined)
				{
					// $('#from_date').val(area_data[id]['date_from'][type]).trigger('change');
				}
				else
				{
					var d = new Date();
					// $('#from_date').val(d.yyyy_mm_dd()).trigger('change');
				}
				if(area_data[id]['date_to'][type] !== undefined)
				{
					// $('#to_date').val(area_data[id]['date_to'][type]).trigger('change');
				}
				else
				{
					var d = new Date();
					// $('#to_date').val(d.yyyy_mm_dd()).trigger('change');
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
				$('#emmaid_select option[value=' + id + ']').prop('selected', false);
				$('#emmaid_select').selectmenu( "refresh" );

				if(area_data[id]['identifier'] !== undefined) area_data[id]['identifier'][data['sel_type']] = "";

				area_data[id]['level'][data['sel_type']] = area_data[id]['type'][data['sel_type']];

				area_data[id]['desc'][data['sel_type']] = {};
				area_data[id]['inst'][data['sel_type']] = {};
				$.each(lang, function(lindex, ldata){
					area_data[id]['desc'][data['sel_type']][lindex] = $('#desc_' + lindex).val();
					area_data[id]['inst'][data['sel_type']][lindex] = $('#inst_' + lindex).val();
				});

				area_data[id]['from'][data['sel_type']] = $('#from_0').val();
				area_data[id]['to'][data['sel_type']] = $('#to_0').val();

				// area_data[id]['from'][data['sel_type']] = '12:00:00';
				// area_data[id]['to'][data['sel_type']] = '13:00:00';

				area_data[id]['date_from'][data['sel_type']] = $('#from_date').val();
				area_data[id]['date_to'][data['sel_type']] = $('#to_date').val();

				// var from = document.getElementById("legdatefrom").innerText;
				// var to = document.getElementById("legdateto").innerText;

				// console.log('From: ' + from);
				// console.log('To: ' + to);

				// area_data[id]['date_from'][data['sel_type']] = from;
				// area_data[id]['date_to'][data['sel_type']] = to;


				area_data[id]['sel_type'] = 0;

				// console.log('FROM: ');
				// console.log(area_data[id]['date_from']);
				// console.log('TO: ');
				// console.log(area_data[id]['date_to']);

			}
		});

		// console.log(area_data);

		area_info['sel_type'] = 0;

		$('#right_area_type').html('');
		$.each(lang, function(lindex, ldata){
			$('#desc_' + lindex).val('').trigger('input');
			$('#inst_' + lindex).val('').trigger('input');
		});
		$('#from_0').val('00:00').trigger('input');
		$('#to_0').val('23:59').trigger('input');

		var d = new Date();
		// $('#legdatefrom, #from_date').val(d.yyyy_mm_dd()).trigger('change');
		// $('#legdateto, #to_date').val(d.yyyy_mm_dd()).trigger('change');
		//$('#left_area_name').html(tmp_area_name).trigger('input');
		$('#AreaDetailDIV').css('background-color', '#cccccc');
		$('#AreaDetailUL').css('pointer-events', 'none');
		$('#AreaDetailUL').css('opacity', 0.5);
		$('#info_text').css('display', 'inherit');

		plugin_show_type(false);
		plugin_calc_map();
	}

	function plugin_delete_warning_detail()
	{
		if(confirm($('#del_warn_lang').val()))
		{
			$.each(area_data, function(id, data){
				if(data['sel_type'] == area_info['sel_type'])
				{
					$('#emmaid_select option[value=' + id + ']').prop('selected', false);
					$('#emmaid_select').selectmenu( "refresh" );

					delete(area_data[id]['type'][data['sel_type']]);
					area_data[id]['desc'][area_data[id]['sel_type']] = {};
					area_data[id]['inst'][area_data[id]['sel_type']] = {};
					area_data[id]['from'][area_data[id]['sel_type']] = '00:00';
					area_data[id]['to'][area_data[id]['sel_type']] = '00:00';
					area_data[id]['date_from'][data['sel_type']] = "";
					area_data[id]['date_to'][data['sel_type']] = "";
					area_data[id]['sel_type'] = 0;
				}
			});
			area_info['sel_type'] = 0;
			$('#right_area_type').html('');
			$.each(lang, function(lindex, ldata){
				$('#desc_' + lindex).val('').trigger('input');
				$('#inst_' + lindex).val('').trigger('input');
			});
			$('#from_0').val('00:00').trigger('input');
			$('#to_0').val('00:00').trigger('input');

			var d = new Date();
			// $('#legdatefrom, #from_date').val(d.yyyy_mm_dd()).trigger('change');
			// $('#legdateto, #to_date').val(d.yyyy_mm_dd()).trigger('change');
			$('#AreaDetailDIV').css('background-color', '#cccccc');
			$('#AreaDetailUL').css('pointer-events', 'none');
			$('#AreaDetailUL').css('opacity', 0.5);
			$('#info_text').css('display', 'inherit');

			plugin_show_type(false);
			plugin_calc_map();
		}
	}

	var area_green = {};
	function plugin_get_all_warnings()
	{
		JQ_loader('Loading ...', 'b');
		cap_engine = $('#cap_engine').val(); // webservice uses lib/cap.create.from_js_array.2.php
		plugin_name = $('#plugin_name').val();
		data = $('#day').val();
		if(data == "" || data === undefined) data = 0;

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
		var jsonOb = JSON.stringify(area_data);
		$.post(
			cap_engine,
			{cap_array:jsonOb, data:data, awt:awt_ok_js, use_plugin: plugin_name},
			function(r){
				//your success response
				if($('#plugin_name').val() == 'webservice')
				{
					//console.log(r);
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
							plugin_send_final(r);
						}
					}
					else
					{
						if($('#plugin_name').val() == 'webservice')
						{
							plugin_make_white_areas_green(0);
						}
					}
				}
				else
				{
					alert(r);
					$('#SOAPUL').html(r).trigger('create');
					$('#CAP_SOAP_popupDialog').popup();
					$('#CAP_SOAP_popupDialog').popup( "open" );
				}

				JQ_loader_off();
			}
		);
	}

	function plugin_send_final(r)
	{
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
					content+= '<div class="awt_types" aid="'+data['aid']+'" id="green_div_'+data['aid']+'" style="height: 30px;">';
					tmp_name = data['name'];
					li_bool = true;
			}

			if(data['type'] < 10)
				content+= $('#left_box_type_'+data['type']).closest('div')[0].outerHTML;
			else
				content+= $('#left_box_type_'+data['type']).closest('div')[0].outerHTML;
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
			$('#green_div_'+data['aid']+' div').addClass(data['aid']);
			//if(data['type'] < 10)
			//	$('#green_div_'+data['aid']+' #left_box_type_0'+data['type']).attr('AaidTtype','a'+data['aid']+'t'+data['type']);
			//else
				$('#green_div_'+data['aid']+' #left_box_type_'+data['type']).attr('AaidTtype','a'+data['aid']+'t'+data['type']);
			//console.log(index + ' / ' + r_arr.length);
		});

		$('.green_area_type_sel').on('click', function(){

			var aid = this.className.replace(/[^\d]+/, '');

			if($(this).attr('no_green') != 1)
			{
				$(this).css('background-color', '#ffffff');
				$(this).attr('no_green', 1);

				$('#svg g, polygon#'+aid).each(function(index){
				if($(this).attr('level_1') == 0) {
					id = $(this).attr('id');
					$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
					$(this).css('fill', 'lightgrey');
				}
				if($(this).attr('area_level_0') > 1) {
						var current_level = $(this).attr('area_level_0');
						var current_type = $(this).attr('area_type_0');
						id = $(this).attr('id');
						$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
						$(this).css('fill', 'url(#pattern_l'+current_level+'t'+current_type+')');
					}
				});

				$('#svg g, polygon.pol_'+aid).each(function(index){
				if($(this).attr('level_1') == 0) {
					id = $(this).attr('id');
					$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
					$(this).css('fill', 'lightgrey');
				}
				if($(this).attr('area_level_0') > 1) {
						var current_level = $(this).attr('area_level_0');
						var current_type = $(this).attr('area_type_0');
						id = $(this).attr('id');
						$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
						$(this).css('fill', 'url(#pattern_l'+current_level+'t'+current_type+')');
					}
				});
			}
			else
			{
				$(this).css('background-color', '#29d660');
				$(this).attr('no_green', 0);

				$('#svg g, polygon#'+aid).each(function(index){
				if($(this).attr('level_1') == 0) {
					id = $(this).attr('id');
					$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
					$(this).css('fill', 'url(#pattern_l1t1)');
				}
				if($(this).attr('area_level_0') > 1) {
						var current_level = $(this).attr('area_level_0');
						var current_type = $(this).attr('area_type_0');
						id = $(this).attr('id');
						$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
						$(this).css('fill', 'url(#pattern_l'+current_level+'t'+current_type+')');
					}
				});

				$('#svg g, polygon.pol_'+aid).each(function(index){
				if($(this).attr('level_1') == 0) {
					id = $(this).attr('id');
					$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
					$(this).css('fill', 'url(#pattern_l1t1)');
				}
				if($(this).attr('area_level_0') > 1) {
						var current_level = $(this).attr('area_level_0');
						var current_type = $(this).attr('area_type_0');
						id = $(this).attr('id');
						$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
						$(this).css('fill', 'url(#pattern_l'+current_level+'t'+current_type+')');
					}
				});
			}
		});

		$('.type_collaps .ui-collapsible-content').css('padding','13px');

		$('div .ui-checkbox').css('margin', '-1px 0');

		$.mobile.loading( "hide" );

		$('#CAPpopupDialog').popup();
		$('#CAPpopupDialog').popup( "open" );

		// Painting green warnings for all areas
		$('#svg g, polygon, level_1').each(function(index){
			if($(this).attr('level_1') == 0) {
				id = $(this).attr('id');
				$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
				$(this).css('fill', 'url(#pattern_l1t1)');
			}
			if($(this).attr('area_level_0') > 1) {
				var current_level = $(this).attr('area_level_0');
				var current_type = $(this).attr('area_type_0');
				id = $(this).attr('id');
				$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
				$(this).css('fill', 'url(#pattern_l'+current_level+'t'+current_type+')');
			}
		});
	}

	var area_green_data = {};
	function plugin_make_white_areas_green(yesno)
	{
		if(yesno == 1)
		{
			$.mobile.loading( "hide" );
			JQ_loader('Loading', 'b');

			var date = new Date();
			$.each(area_green, function(index, data){
				aid = data['aid'];
				if(area_green_data[aid] === undefined)
				{
					area_green_data[aid] = {};
				}
				cinfo = 3;

				//aaidttype="a461t1"

				if($('#map_main_div svg').attr('awt_'+data['type']) == 1 && $('[aaidttype='+'a'+data['aid']+'t'+data['type']+']').attr('no_green') != 1)
				{
					area_green_data[aid]['name'] = data['name'];
					level 	= 1;
					type 	= data['type'];
					if(level > 0 && type > 0)
					{
						awtlv = area_data[aid]['type'][type];
						if(awtlv === undefined || awtlv < 1 )
						{
							area_green_data[aid]['emma_id'] 	= data['eid'];
							area_green_data[aid]['exutc'] 	= '+00:00';
							area_green_data[aid]['level'] 	= level;

							if(area_green_data[aid]['type'] === undefined)
							{
								area_green_data[aid]['type'] = {};
							}
							area_green_data[aid]['type'][data['type']] 	= level;

							if(area_green_data[aid]['desc'] === undefined)
							{
								area_green_data[aid]['desc'] = {};
								area_green_data[aid]['desc'][data['type']] = {};
							}
							if(area_green_data[aid]['desc'][data['type']] === undefined)
							{
								area_green_data[aid]['desc'][data['type']] = {};
							}
							area_green_data[aid]['desc'][data['type']][0]	= 'no warning';
							offset = $('#timezone_h').html();

							if(area_green_data[aid]['from'] === undefined)
							{
								area_green_data[aid]['from'] = {};
								area_green_data[aid]['date'] = {};
							}
							area_green_data[aid]['date'][data['type']] = date.yyyymmddH(parseInt($('#data').val()));
							area_green_data[aid]['from'][data['type']] = date.yyyymmdd(parseInt($('#data').val())) + ' ' + $('#st_from').val();

							if(area_green_data[aid]['to'] === undefined)
							{
								area_green_data[aid]['to'] = {};
							}
							area_green_data[aid]['to'][data['type']] 	= date.yyyymmdd(parseInt($('#data').val()) + 1) + ' ' + $('#st_to').val();
						}
					}
				}
			});


			data = $('#day').val();
			if(data == "" || data === undefined) data = 0;
			var jsonOb = JSON.stringify(area_green_data);

			$.post(
				"lib/cap.create.from_js_array.2.php",
				{cap_array:jsonOb, no_del:1, data:data, use_plugin: plugin_name},
				function(r){
					//your success response
					//alert('OK!');
					$.mobile.loading( "hide" );
					area_green_final_resp = r; // shoud be NULL
					//send_final(r);
				}
			);

			$('#CAPpopupDialog').popup( "close" );
		}

		else if(yesno == -1)
		{
			$('.type_collaps').collapsible( "expand" );
		}

		else if(yesno == 0)
		{
         	// No warnings for all areas
            $('#svg g, polygon, level_1').each(function(index){
				if($(this).attr('level_1') == 0) {
					id = $(this).attr('id');
					$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
					$(this).css('fill', 'lightgrey');
				}
				if($(this).attr('area_level_0') > 1) {
					var current_level = $(this).attr('area_level_0');
					var current_type = $(this).attr('area_type_0');
					id = $(this).attr('id');
					$('<g id="' + id + '"</g>').insertBefore('polygon#' + id);
					$(this).css('fill', 'url(#pattern_l'+current_level+'t'+current_type+')');
				}
            });
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

	function plugin_send_all_proce_cap(yesno)
	{
		if(yesno == 1)
		{
			tmp_start_next_calc = new Date().getTime();
			JQ_loader('Loading', 'b');

			$.post(
				"lib/cap.meteoalarm.webservices.multi_import.php",
				{no_del:1},
				function(r){
					$.mobile.loading( "hide" );
					//your success response
					$('#SOAPUL').html(r).trigger('create');

					$('#CAP_Send_popupDialog').popup( "close" );

						$('#CAP_SOAP_popupDialog').popup();
						$('#CAP_SOAP_popupDialog').popup( "open" );
						$( "#CAP_SOAP_popupDialog" ).popup({
							afterclose: function( event, ui ) {
								setTimeout(function(){
									$('#MeteoalarmCalc_popupDialog').popup();
									$('#MeteoalarmCalc_popupDialog').popup( "open" );
								}, 100);
							}
						});

						$("#submit_cap").addClass('ui-disabled');
						mk_pro_interval = setInterval(function(){
							tmp_next_calc = new Date().getTime();
							tmp_final_calc = (tmp_next_calc - tmp_start_next_calc) / 1000;
							next_calc = Math.round(tmp_final_calc * 10) / 10;
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
										// $('#symbol').show();
										//location.reload();
									}
								}
							});
							//next_calc -= 1;
							$('#symbol').show();
						}, 1000);
				}
			);
		}

		var last_connection = 0;
		var next_connection = 0;
		var start = new Date().getTime();

		$('#symbol').mouseenter(function(){
			var countDownDate = new Date("Jan 1, 2021 00:05:25").getTime();

			var x = setInterval(function() {

				var now = new Date().getTime();
				var distance = countDownDate - now;
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);

				next_connection = seconds;
				next_connection -= 20;

				if(next_connection < 1) {
					next_connection = seconds;
				}
				if(next_connection > 1) {}
			});

			var i = 0;
			var timer = setInterval(function() {
				var end = new Date().getTime();
				var tmp_calc = (end - start) / 1000;
				var last_submit = Math.round(tmp_calc);

				i++;

				if(next_connection == 1) {
					if(i > 10) {
						clearInterval(timer);
					}
				}

				document.getElementById('timer').innerHTML = 'The last submit was ' + last_submit + ' sec ago<br />' +
														'The last connection to Meteoalarm was ' + i + ' sec ago<br />' +
														'The next connection to Meteoalarm is planned to be in ' + next_connection  + ' sec';
			}, 1000);

			$('#timer').show();
		});

		$('#symbol').mouseleave(function(){
			$('#timer').hide();
		});

		$('#CAP_Send_popupDialog').popup( "close" );

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

	Date.prototype.yyyy_mm_dd = function() {
		var yyyy = this.getFullYear().toString();
		var dd  = (this.getDate()).toString();
		var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based

		return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
	};

	Date.prototype.yyyymmdd = function(pday) {
		var yyyy = this.getFullYear().toString();
		var dd  = (this.getDate() + pday);
		var mm = (this.getMonth()+1); // getMonth() is zero-based
		if(mm % 2)
		{
			if(dd > 31) var mm = mm + 1; // getMonth() is zero-based
		}
		else if(mm == 2)
		{
			if(dd > 29) var mm = mm + 1; // getMonth() is zero-based
		}
		else
		{
			if(dd > 30) var mm = mm + 1; // getMonth() is zero-based
		}
		mm = mm.toString();
		dd = dd.toString();

		return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
	};

	Date.prototype.yyyymmddH = function(pday) {
		var yyyy = this.getFullYear().toString();
		if(pday > 0) var dd  = (this.getDate() - 1 + pday);
		else var dd  = (this.getDate());
		var mm = (this.getMonth()+1); // getMonth() is zero-based
		if(mm % 2)
		{
			if(dd > 31) var mm = mm + 1; // getMonth() is zero-based
		}
		else if(mm == 2)
		{
			if(dd > 29) var mm = mm + 1; // getMonth() is zero-based
		}
		else
		{
			if(dd > 30) var mm = mm + 1; // getMonth() is zero-based
		}
		mm = mm.toString();
		dd = dd.toString();

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

	function standard_sort(a, b)
	{
		return a-b;
	}

	function get_date() {

		today = $('#today').val();
		// console.log(today);

		// $('#from_date').html($("#today").val());
		// $('#to_date').html($("#today").val());
	}

	// function getDate() {
	// 	today = $('#today').val();
	// 	var from = document.getElementById("legdatefrom").innerText;
	// 	var to = document.getElementById("legdateto").innerText;
	// 	// console.log(today);
	// 	// console.log(from);
	// 	// console.log(to);

	// 	date = new Date();
	// 	console.log(date.yyyy_mm_dd());

	// 	if(today > date.yyyy_mm_dd()) {
	// 		// console.log('FROM should be changed!');
	// 		// console.log('TO should be changed');

	// 		var x = document.getElementById("from_date").value = today;
	// 		var y = document.getElementById("to_date").value = today;

	// 		// console.log(x);
	// 		// console.log(y);

	// 		$('#legdatefrom').html($("#from_date").val());
	// 		$('#legdateto').html($("#to_date").val());

	// 		$( "#from_date, #to_date" ).change(function() {
	// 			$('#legdatefrom').html($("#from_date").val());
	// 			$('#legdateto').html($("#to_date").val());
	// 		});



	// 	}
	// }