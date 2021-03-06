/**
 *
 */
var infowindow;
var geocoder;

jQuery( function ( $ ) {

	var $ywpMaps = $( '.ywp-map' );

	/*
	 * Loop through maps and initialize
	 */
	$ywpMaps.each( function ( index, value ) {
		var jsonArray = JSON.parse( jQuery( $ywpMaps[index] ).parent().attr( 'data-ywp-json' ) );
		var map;
		var icon = null;
		var mapBounds = null;
		var myLatlng;
		var options;
		infowindow = new google.maps.InfoWindow( {
			maxWidth: 220 //max-width for containers  https://developers.google.com/maps/documentation/javascript/examples/infowindow-simple-max
		} );
		geocoder = new google.maps.Geocoder();

		//Scroll Option

		var scollOption = jQuery( $ywpMaps[index] ).parent().attr( 'data-map-scroll' );
		if ( scollOption == undefined ) {
			scrollOption = true;
		} else {
			scrollOption = false;
		}

		/**
		 * Handle API long/lat. results
		 *
		 * Various checks to get the center LatLng for this map from Yelp API JSON results array
		 */
		if ( typeof jsonArray.results[0].region !== 'undefined' ) {
			// Widget is in Search mode.
			myLatLng = new google.maps.LatLng( jsonArray.results[0].region.center.latitude, jsonArray.results[0].region.center.longitude );
		} else if ( typeof jsonArray.results[0].coordinates !== 'undefined' ) {
			// Widget is in Business mode.
			myLatLng = new google.maps.LatLng( jsonArray.results[0].coordinates.latitude, jsonArray.results[0].coordinates.longitude );
		}

		// Initialize map.
		var mapOptions = {
			scrollwheel: scrollOption,
			zoom       : 10,
			center     : myLatLng,
			mapTypeId  : google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map( $ywpMaps[index], mapOptions );

		// Add event listener for markers.
		google.maps.event.addDomListener( map, 'idle', function () {
			var mapBounds = map.getBounds();
			updateMap( mapBounds, jsonArray, map );
		} );

		// Clean up DOM.
		jQuery( $ywpMaps[index] ).parent().removeAttr( 'data-ywp-json' );
	} );
} );


/*
 * Called on the form submission: updates the map by
 * placing markers on it at the appropriate places
 */
var jsonArray;

function updateMap( mapBounds, data, map ) {

	if ( data != 'undefined' ) {
		handleResults( data, map );
	}

}

/*
 * If a successful API response is received, place
 * markers on the map.  If not, display an error.
 */
function handleResults( data, map ) {

	//Business API
	if ( typeof data.results[0].coordinates !== 'undefined' ) {
		biz = data.results[0];

		if ( typeof biz.coordinates !== 'undefined' ) {
			createMarkerWidget( biz, new google.maps.LatLng( biz.coordinates.latitude, biz.coordinates.longitude ), map );
		}
	}

	//Search API
	else if ( typeof data.results[0].businesses !== 'undefined' ) {

		for ( var i = 0; i < data.results[0].businesses.length; i++ ) {
			biz = data.results[0].businesses[i];
			bizAddress = biz.location.display_address[0] + ", " + biz.location.display_address[1];

			//Get Long/Lat or calculate from address
			if ( typeof biz.coordinates !== 'undefined' ) {
				createMarkerWidget( biz, new google.maps.LatLng( biz.coordinates.latitude, biz.coordinates.longitude ), map );
			} else {
				geocodeAddressWidget( bizAddress, i, map, biz );
			}

		}

	} else {

		console.log( "Yelp Widget Pro Map Error: " + data.message.text );

	}


}


/**
 * GeoCode Address
 */
function geocodeAddressWidget( address, index, map, biz ) {
	geocoder.geocode( {
		'address': address
	}, function ( results, status ) {
		if ( status === google.maps.GeocoderStatus.OK ) {

			createMarkerWidget( biz, new google.maps.LatLng( results[0].geometry.location.lat(), results[0].geometry.location.lng() ), map );


		} else if ( status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT ) {
			setTimeout( function () {
				geocodeAddressWidget( address, index, map, biz );
			}, 200 );
		} else {
			console.log( "Geocode was not successful for " + biz.name + " the following reason: " + status );
		}
	} );
}


/*
 * Creates a marker for the given business and point
 */

function createMarkerWidget( biz, point, map ) {
	var marker = new google.maps.Marker( {
		map     : map,
		icon    : ywpParams.ywpURL + "/includes/images/marker_star.png",
		position: point
	} );
	marker.content = generateInfoWindowHtml( biz );

	google.maps.event.addListener( marker, 'click', function () {
		infowindow.setContent( marker.content );

		infowindow.open( map, marker );
	} );
}

/*
 * Formats and returns the Info Window HTML
 * (displayed in a balloon when a marker is clicked)
 */
function generateInfoWindowHtml( biz ) {

	var text = '<div class="marker">';

	text += '<div class="ywp-business-top ywp-marker-block clearfix clear">';

	// image and rating
	if ( typeof biz.image_url !== 'undefined' ) {
		text += '<img class="businessimage" src="' + biz.image_url + '" width="60" height="60" />';
	} else {
		text += '<img class="businessimage" src="' + ywpMapParams.ywpURL + '/includes/images/blank-biz.png" width="60" height="60" />';
	}

	// div start
	text += '<div class="ywp-business-info">';

	// name/url
	text += '<a href="' + biz.url + '" target="_blank" class="marker-business-name">' + biz.name + '</a>';
	// reviews
	text += biz.review_count + ' reviews</span></div>';

	//Phone number
	if ( biz.phone !== undefined ) {
		var phone = formatPhoneNumber( biz.phone );
		text += '<a href="tel:' + biz.phone + '" title="Call ' + biz.name + '" class="phone-clickable">' + biz.display_phone + '</a>';
	}

	text += '</div></div>'; //close ywp-business-info & top info div

	// div end
	text += '</div>';

	return text;
}


/*
 * Formats the phone number HTML
 */
function formatPhoneNumber( num ) {
	if ( num.length != 10 ) return '';
	return '(' + num.slice( 0, 3 ) + ') ' + num.slice( 3, 6 ) + '-' + num.slice( 6, 10 );
}