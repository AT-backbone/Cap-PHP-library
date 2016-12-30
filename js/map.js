// This example creates a simple polygon representing the Bermuda Triangle.
// When the user clicks on the polygon an info window opens, showing
// information about the polygon's coordinates.

var map;
var infoWindow;
var polygon = null;
var circle = null;


function initMap() {

	google.maps.Polygon.prototype.getBounds = function() {
	    var bounds = new google.maps.LatLngBounds();
	    var paths = this.getPaths();
	    var path;        
	    for (var i = 0; i < paths.getLength(); i++) {
	        path = paths.getAt(i);
	        for (var ii = 0; ii < path.getLength(); ii++) {
	            bounds.extend(path.getAt(ii));
	        }
	    }
	    return bounds;
	}

  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 53, lng: 9},
    zoom: 3
  });

  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.POLYGON,
        google.maps.drawing.OverlayType.CIRCLE
      ]
    }
  });
  drawingManager.setMap(map);

	google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
		if (event.type == google.maps.drawing.OverlayType.POLYGON) {
			if(polygon != null) polygon.setMap(null);
			polygon = event.overlay;
			var vertices = polygon.getPath();
			// Iterate over the vertices.
			contentString = "";
			for (var i =0; i < vertices.getLength(); i++) {
				var xy = vertices.getAt(i);
				if(i == 0) save_xy = xy.lat() + ',' + xy.lng() + ' ';
				contentString += xy.lat() + ',' + xy.lng() + ' ';
			}
			contentString += save_xy;
			$('#polygon').val(contentString.trim());
			drawingManager.setDrawingMode(null);
		} else if(event.type == google.maps.drawing.OverlayType.CIRCLE) {
			if(circle != null) circle.setMap(null);
			circle = event.overlay;
			$('#circle').val(circle.getCenter().lat()+','+circle.getCenter().lng()+' '+(circle.getRadius() / 1000));
			drawingManager.setDrawingMode(null);
		}
		updateCapXML();
	});

	google.maps.event.addListener(drawingManager, "drawingmode_changed", function() {
    	if ((drawingManager.getDrawingMode() == google.maps.drawing.OverlayType.POLYGON) &&  (polygon != null))
        polygon.setMap(null);
	});

	google.maps.event.addListener(drawingManager, "drawingmode_changed", function() {
    if ((drawingManager.getDrawingMode() == google.maps.drawing.OverlayType.CIRCLE) && (circle != null))
        circle.setMap(null);
	});

	if($('#polygon').val() != ""){
		poly_arr = [];
		$.each($('#polygon').val().trim().split(' '), function(index, data){
			arr_xy = [];
			$.each(data.split(','), function(index, xy){
				arr_xy[index] = parseFloat(xy);
			});
			poly_arr[index] = {lat: arr_xy[0], lng:arr_xy[1]};
		});

		// Construct the polygon.
		polygon = new google.maps.Polygon({
			paths: poly_arr,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#FF0000',
			fillOpacity: 0.35
		});
		polygon.setMap(map);
		map.fitBounds(polygon.getBounds());
		console.log($('#polygon').val());
	}

	if($('#circle').val() != ""){
		circle_data = $('#circle').val().trim().split(' ');

		circle_xy = circle_data[0].split(',');

		circle = new google.maps.Circle({
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			map: map,
			center: {lat: parseFloat(circle_xy[0]), lng: parseFloat(circle_xy[1])},
			radius: circle_data[1] * 1000
		});
		circle.setMap(map);
		map.setCenter({lat: parseFloat(circle_xy[0]), lng: parseFloat(circle_xy[1])});
		console.log($('#circle').val());
	}

	//// Define the LatLng coordinates for the polygon.
	//var triangleCoords = [
	//	{lat: 25.774, lng: -80.190},
	//	{lat: 18.466, lng: -66.118},
	//	{lat: 32.321, lng: -64.757}
	//];
//
	//// Construct the polygon.
	//var bermudaTriangle = new google.maps.Polygon({
	//	paths: triangleCoords,
	//	strokeColor: '#FF0000',
	//	strokeOpacity: 0.8,
	//	strokeWeight: 3,
	//	fillColor: '#FF0000',
	//	fillOpacity: 0.35
	//});
	//bermudaTriangle.setMap(map);

	$("#polygon").bind( "change", function(event, ui) {
		poly_arr = [];
		$.each($(this).val().split(' '), function(index, data){
			arr_xy = [];
			$.each(data.split(','), function(index, xy){
				arr_xy[index] = parseFloat(xy);
			});
			poly_arr[index] = {lat: arr_xy[0], lng:arr_xy[1]};
		});

		// Construct the polygon.
		polygon = new google.maps.Polygon({
			paths: poly_arr,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#FF0000',
			fillOpacity: 0.35
		});
		polygon.setMap(map);
		map.fitBounds(polygon.getBounds());
	});

	$("#circle").bind( "change", function(event, ui) {
		circle_data = $(this).val().split(' ');

		circle_xy = circle_data[0].split(',');

		circle = new google.maps.Circle({
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			map: map,
			center: {lat: parseFloat(circle_xy[0]), lng: parseFloat(circle_xy[1])},
			radius: circle_data[1] * 1000
		});
		circle.setMap(map);
		map.setCenter({lat: parseFloat(circle_xy[0]), lng: parseFloat(circle_xy[1])});
	});

}

$( document ).ready(function() {
	$( document ).on( "pagechange", function( event, to_page ) { 
		if(typeof google == 'undefined'){
			
		}else{
			initMap();
		}
	});
});
/* OLD
$( document ).ready(function() 
{
	
		var map = $("#map").geomap();
		
		map.geomap({
		  center: [ 47.227491, 9.565601 ],
		  zoom: 3
		});

		map.geomap( 'refresh' );

		//$("#polygon").bind( "change", function(event, ui) {
		//	poly_arr = [];
		//	$.each($(this).val().split(' '), function(index, data){
		//		arr_xy = [];
		//		$.each(data.split(','), function(index, xy){
		//			arr_xy[index] = parseFloat(xy);
		//		});
		//		poly_arr[index] = [arr_xy[0], arr_xy[1]];
		//	});
		//	// 14.833358730212113,-10.95610274998453 30.652140944162902,-1.112352749987173 9.682041896854297,30.528272250004438 -1.1699034566484328,2.7548347500118178 14.833358730212113,-10.95610274998453  
		//	map.geomap( "append", {
		//	  type: "Polygon",
		//	  coordinates: [ poly_arr ]
		//	}, { stroke: "#11117f", strokeWidth: "3px" } );
		//});
		
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

});
*/