(function () {

	'use strict';

	(function ( $ ) {

		$( document ).ready(
			function () {

			if ($( google.maps ).length) {

				$( '.fm-map' ).each(
					function () {

					var id        = $( this ).data( 'id' ) + '-box',
						$lat      = $( this ).find( '.fm-map-lat' ).attr( 'readonly', 'readonly' ),
						$lng      = $( this ).find( '.fm-map-lng' ).attr( 'readonly', 'readonly' ),
						mapCenter = {
							lat: parseFloat( $lat.val() || 0 ),
							lng: parseFloat( $lng.val() || 0 )
						},
						$map      = $(
							'<div />',
							{
							"class": "fm-map-box",
							"id":    id,
							"style": "min-height:250px;width:100%;margin:10px 0;"
							} 
						).appendTo( $( this ) ),
						map       = new google.maps.Map(
							$map.get( 0 ),
							{
							center:             mapCenter,
							zoom:               2,
							minZoom:            2,
							backgroundColor:    '#ffffff',
							mapTypeControl:     false,
							streetViewControl:  false,
							fullscreenControl:  false,
							zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_TOP },
							scrollwheel:        false,
							} 
						),
						marker    = new google.maps.Marker(
							{
							position:  mapCenter,
							map:       map,
							title:     "Drag Me",
							visible:   true,
							draggable: true,
							} 
						);

					// Update marker position on click.
					google.maps.event.addListener(
						map,
						'click',
						function ( e ) {
						marker.setPosition( e.latLng );
						// Update inputs.
						$lat.val( e.latLng.lat() );
						$lng.val( e.latLng.lng() );
						} 
					);

					google.maps.event.addListener(
						marker,
						'dragend',
						function ( e ) {
						map.panTo( e.latLng );
						// Update inputs.
						$lat.val( e.latLng.lat() );
						$lng.val( e.latLng.lng() );
						} 
					);

					} 
				);

			}	

			} 
		);

	})( jQuery )

})();
