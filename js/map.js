$( document ).ready(function() 
{
	/*
	 		MAP START
	*/
	
		var map = $("#map").geomap();
		
		map.geomap({
		  center: [ 47.227491, 9.565601 ],
		  zoom: 3
		});
		
		$( "#dragCircle" ).bind( "change", function(event, ui) {
			if($('#dragCircle').slider().val() == 1)
			{
				map.geomap( "option", "mode", "dragCircle" );
				$('#drawPolygon').val("0");
				$('#drawPolygon').slider('refresh');
			}
			else
			{
				map.geomap( "option", "mode", "pan" );
			}
		});
		
		$( "#drawPolygon" ).bind( "change", function(event, ui) {
			if($('#drawPolygon').slider().val() == 1)
			{
				map.geomap( "option", "mode", "drawPolygon" );
				$('#dragCircle').val("0");
				$('#dragCircle').slider('refresh');
			}
			else
			{
				map.geomap( "option", "mode", "pan" );
			}
		});
		
		
		
		map.geomap( {
		  shape: function( e, geo ) 
		  {
		  	var allShapes = $( "#map" ).geomap( "find", "*" );
		  	$( "#map" ).geomap( "remove", allShapes );
		  	var geolo = geo;
		  	
		  	if($('#drawPolygon').slider().val() == 1)
		  	{
		  		$( "#polygon" ).val("");
		  		$.each( geolo.coordinates[0], function( keys, values ){
						tmp = $( "#polygon" ).val();							
						$( "#polygon" ).val(tmp + " " + values[1] + "," + values[0]);						
					});
					$('#drawPolygon').val("0");
					$('#drawPolygon').slider('refresh');
					map.geomap( "option", "mode", "pan" );
				}
				else if($('#dragCircle').slider().val() == 1)
				{
					
					var centroid = $.geo.centroid( {
					      type: "Polygon",
					      coordinates: [
					      	geo.coordinates[0]
					      ]
					} )
					
					var distanceBetween = $.geo.distance(
					  { type: "Point", "coordinates": [ centroid.coordinates[0], centroid.coordinates[1] ] },
					  { type: "Point", "coordinates": [ geo.coordinates[0][0][0], geo.coordinates[0][0][1] ] }
					)

					$( "#circle" ).val( centroid.coordinates[1] + "," + centroid.coordinates[0] + " " + (distanceBetween / 1000));
					$('#dragCircle').val("0");
					$('#dragCircle').slider('refresh');
					map.geomap( "option", "mode", "pan" );
				}
			
        var drawStyle = $.extend( { }, map.geomap( "option", "drawStyle" ) );

        var label = $( "#shapeLabels input" ).val( );

        map.geomap( "append", geo, drawStyle, label );
	    }
		});

	/*
			MAP END
	*/
});