var trip_stops = {

	"type":"ROADMAP",
	"zoom":"15",
	"styles":"[{\"featureType\":\"water\",\"elementType\":\"all\",\"stylers\":[{\"hue\":\"#e9ebed\"},{\"saturation\":-78},{\"lightness\":67},{\"visibility\":\"simplified\"}]},{\"featureType\":\"landscape\",\"elementType\":\"all\",\"stylers\":[{\"hue\":\"#ffffff\"},{\"saturation\":-100},{\"lightness\":100},{\"visibility\":\"simplified\"}]},{\"featureType\":\"road\",\"elementType\":\"geometry\",\"stylers\":[{\"hue\":\"#bbc0c4\"},{\"saturation\":-93},{\"lightness\":31},{\"visibility\":\"simplified\"}]},{\"featureType\":\"poi\",\"elementType\":\"all\",\"stylers\":[{\"hue\":\"#ffffff\"},{\"saturation\":-100},{\"lightness\":100},{\"visibility\":\"off\"}]},{\"featureType\":\"road.local\",\"elementType\":\"geometry\",\"stylers\":[{\"hue\":\"#e9ebed\"},{\"saturation\":-90},{\"lightness\":-8},{\"visibility\":\"simplified\"}]},{\"featureType\":\"transit\",\"elementType\":\"all\",\"stylers\":[{\"hue\":\"#e9ebed\"},{\"saturation\":10},{\"lightness\":69},{\"visibility\":\"on\"}]},{\"featureType\":\"administrative.locality\",\"elementType\":\"all\",\"stylers\":[{\"hue\":\"#2c2e33\"},{\"saturation\":7},{\"lightness\":19},{\"visibility\":\"on\"}]},{\"featureType\":\"road\",\"elementType\":\"labels\",\"stylers\":[{\"hue\":\"#bbc0c4\"},{\"saturation\":-93},{\"lightness\":31},{\"visibility\":\"on\"}]},{\"featureType\":\"road.arterial\",\"elementType\":\"labels\",\"stylers\":[{\"hue\":\"#bbc0c4\"},{\"saturation\":-93},{\"lightness\":-2},{\"visibility\":\"simplified\"}]}]",
	"itinerary":"1",
	"center":["45.43731","12.33046"],
	"stops":[
		{"label":"Train station","description":"","layout":"full","latitude":"45.44103","longitude":"12.32103","latlong":"45.44103,12.32103","background_appearance":"relative","overlay_display":"","overlay_color":"","overlay_opacity":"","hide_posts":"","mosaic_module":"","mosaic_gutter":"","mosaic_image_size":"large","marker":"","fit_viewport":"","background_color":"#000","background_image":"","stop_pictures":[],"show_gallery":"","id":"52211baa90cecb72759a5d82a53f0209","show_on_map":true,"show_in_page":true},
		{"label":"","description":"","layout":"full","latitude":"45.44365","longitude":"12.32548","latlong":"45.44365,12.32548","background_appearance":"relative","overlay_display":"","overlay_color":"","overlay_opacity":"","hide_posts":"","mosaic_module":"","mosaic_gutter":"","mosaic_image_size":"large","marker":"","fit_viewport":"","background_color":"","background_image":"","stop_pictures":[],"show_gallery":"","id":"391819434b11d60a62d103136a82f3e2","show_on_map":true,"show_in_page":true},
		{"label":"","description":"","layout":"full","latitude":"45.44211","longitude":"12.33282","latlong":"45.44211,12.33282","background_appearance":"relative","overlay_display":"","overlay_color":"","overlay_opacity":"","hide_posts":"","mosaic_module":"","mosaic_gutter":"","mosaic_image_size":"large","marker":"","fit_viewport":"","background_color":"","background_image":"","stop_pictures":[],"show_gallery":"","id":"777a5e3a26bbbe1525cdbcaea742efbb","show_on_map":false,"show_in_page":true},
		{"label":"Rialto bridge","description":"","layout":"full","latitude":"45.43806","longitude":"12.33590","latlong":"45.43806,12.33590","background_appearance":"relative","overlay_display":"","overlay_color":"","overlay_opacity":"","hide_posts":"","mosaic_module":"","mosaic_gutter":"","mosaic_image_size":"large","marker":"","fit_viewport":"","background_color":"","background_image":"","stop_pictures":[],"show_gallery":"","id":"cf9cb12aea26a9b250a433e2ddd33bc8","show_on_map":true,"show_in_page":true},
		{"label":"Santa Maria Formosa","description":"","layout":"full","latitude":"45.43707","longitude":"12.34103","latlong":"45.43707,12.34103","background_appearance":"relative", "overlay_display":"","overlay_color":"","overlay_opacity":"","hide_posts":"","mosaic_module":"","mosaic_gutter":"","mosaic_image_size":"large","marker":"","fit_viewport":"","background_color":"","background_image":"","stop_pictures":[],"show_gallery":"","id":"8948245e116ba0f690e83381b2967fa0","show_on_map":true,"show_in_page":true},
		{"label":"San Marco","description":"","layout":"full","latitude":"45.43455","longitude":"12.33981","latlong":"45.43455,12.33981","background_appearance":"relative","overlay_display":"","overlay_color":"","overlay_opacity":"","hide_posts":"","mosaic_module":"","mosaic_gutter":"","mosaic_image_size":"large","marker":"","fit_viewport":"","background_color":"","background_image":"","stop_pictures":[],"show_gallery":"","id":"bdc7487fd95b766a6a1847e322323ab1","show_on_map":true,"show_in_page":true}
	],
	"geodesic":"0",
	"strokeColor":"#cc3f3f",
	"strokeOpacity":"0.6",
	"strokeWeight":"5",
	"pictures":{
		"52211baa90cecb72759a5d82a53f0209":[],
		"391819434b11d60a62d103136a82f3e2":[],
		"777a5e3a26bbbe1525cdbcaea742efbb":[],
		"cf9cb12aea26a9b250a433e2ddd33bc8":[],
		"8948245e116ba0f690e83381b2967fa0":[],
		"bdc7487fd95b766a6a1847e322323ab1":[]
	}
	
};


(function($) {
	"use strict";

	/**
	 * Trip map.
	 */
	$.fn.tripMap = function( options ) {
		options = $.extend( {
			"zoom": 12,
			"mapTypeId": google.maps.MapTypeId.ROADMAP,
			"center": [ 10, 10 ],
			"styles": [],
			"markers": [], // { latitude: '', longitude: '', title: '', marker: '' }
			"itinerary_enabled": false,
			"itinerary_geodesic": false,
			"itinerary_strokeColor": '#FFFFFF',
			"itinerary_strokeOpacity": 1.0,
			"itinerary_strokeWeight": 10,
			"markerClickCallback": function( marker, info, map, index, instance ) {
				$.scrollTo( $( "#stop-" + index ).position().top, 350, "easeInOutCubic" );
			},
			"markerOutCallback": function( marker, info, map, index, instance ) {
				info.close();
			},
			"markerHoverCallback": function( marker, info, map, index, instance ) {
				$.each( instance._markers, function( i ) {
					if ( instance._infos[i] !== undefined ) {
						instance._infos[i].close();
					}
				} );
				info.open( map, marker );
			}
		}, options );

		return this.each( function() {
			var self = $( this ),
				map = this;

			self._markers = [];
			self._infos = [];

			self.init = function() {
				var is_mobile = $( "body" ).hasClass( "thb-mobile" ),
					center = new google.maps.LatLng( options.center[0], options.center[1] ),
					map_options = {
						"zoom": options.zoom,
						"mapTypeId": options.mapTypeId,
						"center": center,
						"styles": options.styles,
						"scrollwheel": false,
						"draggable": is_mobile ? false : true,
						"panControl": true
						// "disableDefaultUI": true
					};

				if ( options.itinerary_enabled ) {
					map_options.geodesic = options.itinerary_geodesic;
					map_options.strokeColor = options.itinerary_strokeColor;
					map_options.strokeOpacity = options.itinerary_strokeOpacity;
					map_options.strokeWeight = options.itinerary_strokeWeight;
				}

				self.map = new google.maps.Map( map, map_options );

				$('div').on('touchstart', '.gmnoprint div[title^=Pan]', function () {
					$(this).trigger('click');
					return false;
				});

				$( window ).on( "thbShowMap", function() {
					google.maps.event.trigger( self.map, 'resize' );
					// $( window ).trigger( "resize" );
				} );

				$( window ).on( "resize", function() {
					self.map.setCenter( center );
				} );

				self.placeMarkers();

				if ( options.itinerary_enabled ) {
					self.makeItinerary();
				}
			};

			self.placeMarkers = function() {
				$.each( options.markers, function( index, marker ) {
					var latlong = new google.maps.LatLng( marker["latitude"], marker["longitude"] ),
						marker = new google.maps.Marker( {
							position: latlong,
							map: self.map,
							title: marker["title"],
							animation: google.maps.Animation.DROP,
							icon: marker["marker"],
							visible: marker["visible"]
						} );

						if ( marker["title"] != "" ) {
							var info = new InfoBox( {
								content: marker["title"],
								disableAutoPan: false,
								maxWidth: 150,
								pixelOffset: new google.maps.Size(-120, 6),
								zIndex: null,
								boxStyle: {
									width: "240px"
								},
								infoBoxClearance: new google.maps.Size(1, 1)
							} );

							// var info = new google.maps.InfoWindow({
							// 	content: '<div class="content">' + marker["title"] + '</div>'
							// });

							self._infos.push( info );

							google.maps.event.addListener( marker, 'mouseover', function() {
								options.markerHoverCallback( this, info, self.map, index, self );
							} );

							google.maps.event.addListener( marker, 'mouseout', function() {
								options.markerOutCallback( this, info, self.map, index, self );
							} );
						}

						self._markers.push( marker );

						google.maps.event.addListener( marker, 'click', function() {
							options.markerClickCallback( this, info, self.map, index, self );
						} );
				} );
			};

			self.makeItinerary = function() {
				var itinerary = [];

				$.each( options.markers, function( index, stop ) {
					itinerary.push( new google.maps.LatLng( stop["latitude"], stop["longitude"] ) );
				} );

				var itineraryPath = new google.maps.Polyline( {
					path: itinerary,
					geodesic: options.itinerary_geodesic,
					strokeColor: options.itinerary_strokeColor,
					strokeOpacity: options.itinerary_strokeOpacity,
					strokeWeight: options.itinerary_strokeWeight
				} );

				itineraryPath.setMap( self.map );
			};

			self.init();
		} );
	};

	$(document).ready(function() {
	
		/**
		 * Route map
		 */
		 
		if ( $(".map-route-wrapper" ).length ) {
			var options = {
				"styles": $.parseJSON( trip_stops.styles ),
				"zoom": parseInt( trip_stops.zoom, 10 ),
				"mapTypeId": google.maps.MapTypeId[trip_stops.type],
				"itinerary_enabled": parseInt( trip_stops.itinerary, 10 ),
				"itinerary_geodesic": parseInt( trip_stops.geodesic, 10 ),
				"itinerary_strokeColor": trip_stops.strokeColor,
				"itinerary_strokeOpacity": trip_stops.strokeOpacity,
				"itinerary_strokeWeight": trip_stops.strokeWeight,
				"center": trip_stops.center,
				"markers": []
			};

			if ( trip_stops.stops.length > 0 ) {
				$.each( trip_stops.stops, function( index, stop ) {
					// if ( stop.latitude == "" || stop.longitude == "" ) {
					// 	options.itinerary_enabled = false;

					// 	return;
					// }

					if ( index == 0 && options.center.length == 0 ) {
						options.center = [ stop.latitude, stop.longitude ];
					}
					
					options.markers.push( {
						"latitude": stop.latitude,
						"longitude": stop.longitude,
						"title": stop.label,
						"marker": stop.marker,
						"visible": stop.show_on_map
					} );
				} );
			}
			else {
				options.itinerary_enabled = false;
			}

			// Map
			if ( $( "#map-route" ).length ) {
				$( "#map-route" ).tripMap( options );
			}
			
		}

	});

})(jQuery);