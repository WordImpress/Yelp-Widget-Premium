/**
 *
 */
var infowindow;
var geocoder;

jQuery(function ($) {

	var $ywpMaps = $('.ywp-map');
	/*
	 * Loop through maps and initialize
	 */
	$ywpMaps.each(function (index, value) {

		var jsonArray = JSON.parse(jQuery($ywpMaps[index]).parent().attr('data-ywp-json'));
		var map;
		var icon = null;
		var mapBounds = null;
		var myLatlng;
		var options;
		infowindow = new google.maps.InfoWindow();
		geocoder = new google.maps.Geocoder();

		//Scroll Option

		var scollOption = jQuery($ywpMaps[index]).parent().attr('data-map-scroll');
		if (scollOption == undefined) {
			scrollOption = true;
		} else {
			scrollOption = false;
		}


		/**
		 * Handle API long/lat. results
		 *
		 * Various checks to get the center LatLng for this map from Yelp API JSON results array
		 */
		//First check to see if a region is present
		if (typeof jsonArray.results[0].region !== 'undefined') {

			myLatLng = new google.maps.LatLng(jsonArray.results[0].region.center.latitude, jsonArray.results[0].region.center.longitude);

			//initialize map
			var mapOptions = {
				scrollwheel: scrollOption,
				zoom       : 10,
				center     : myLatLng,
				mapTypeId  : google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map($ywpMaps[index], mapOptions);

			//Event Listener for Markers
			google.maps.event.addDomListener(map, 'idle', function () {
				var mapBounds = map.getBounds();
				updateMap(mapBounds, jsonArray, map);
			});


		}

		//No coordinates in JSON so geocode address
		else {

			//get biz address for geocoding
			var bizAddress = jsonArray.results[0].location.address[0] + ", " + jsonArray.results[0].location.city + ", " + jsonArray.results[0].location.state_code + ", " + jsonArray.results[0].location.country_code;

			//geocode that beast
			geocoder.geocode({ 'address': bizAddress}, function (results, status) {

				//Result is OK
				if (status == google.maps.GeocoderStatus.OK) {

					myLatLng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
					//initialize map
					var mapOptions = {
						scrollwheel: scrollOption,
						zoom       : 10,
						center     : myLatLng,
						mapTypeId  : google.maps.MapTypeId.ROADMAP
					};
					map = new google.maps.Map($ywpMaps[index], mapOptions);

					//Event Listener for Markers
					google.maps.event.addDomListener(map, 'idle', function () {
						var mapBounds = map.getBounds();
						updateMap(mapBounds, jsonArray, map);
					});

				} else {

					console.log('Geocode was not successful for the following reason: ' + status);

				}

			});

		}


		//cleanup DOM
		jQuery($ywpMaps[index]).parent().removeAttr('data-ywp-json');


	});


});


/*
 * Called on the form submission: updates the map by
 * placing markers on it at the appropriate places
 */
var jsonArray;

function updateMap(mapBounds, data, map) {

	if (data != 'undefined') {
		handleResults(data, map);
	}

}

/*
 * If a successful API response is received, place
 * markers on the map.  If not, display an error.
 */
function handleResults(data, map) {

	//Business API
	if (typeof data.results[0].location !== 'undefined') {
		biz = data.results[0];
		bizAddress = biz.location.address[0] + ", " + biz.location.city + ", " + biz.location.state_code + ", " + biz.location.country_code;
		geocodeAddressWidget(bizAddress, 0, map, biz);

	}

	//Search API
	else if (typeof data.results[0].businesses !== 'undefined') {

		for (var i = 0; i < data.results[0].businesses.length; i++) {
			biz = data.results[0].businesses[i];
			bizAddress = biz.location.address[0] + ", " + biz.location.city + ", " + biz.location.state_code + ", " + biz.location.country_code;

			//Get Long/Lat or calculate from address
			if (typeof biz.location.coordinate !== 'undefined') {

				createMarkerWidget(biz, new google.maps.LatLng(biz.location.coordinate.latitude, biz.location.coordinate.longitude), map);

			} else {

				geocodeAddressWidget(bizAddress, i, map, biz);

			}

		}

	} else {

		console.log("Yelp Widget Pro Map Error: " + data.message.text);

	}


}


/**
 * GeoCode Address
 */
function geocodeAddressWidget(address, index, map, biz) {
	geocoder.geocode({
		'address': address
	}, function (results, status) {
		if (status === google.maps.GeocoderStatus.OK) {

			createMarkerWidget(biz, new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()), map);


		} else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
			setTimeout(function () {
				geocodeAddressWidget(address, index, map, biz);
			}, 200);
		} else {
			console.log("Geocode was not successful for " + biz.name + " the following reason: " + status);
		}
	});
}


/*
 * Creates a marker for the given business and point
 */

function createMarkerWidget(biz, point, map) {
	var marker = new google.maps.Marker({
		map     : map,
		icon    : ywpParams.ywpURL + "/includes/images/marker_star.png",
		position: point
	});
	marker.content = generateInfoWindowHtml(biz);

	google.maps.event.addListener(marker, 'click', function () {
		infowindow.setContent(marker.content);
		infowindow.open(map, marker);
	});
}

/*
 * Formats and returns the Info Window HTML
 * (displayed in a balloon when a marker is clicked)
 */
function generateInfoWindowHtml(biz) {

	var text = '<div class="marker">';

	// image and rating
	if (typeof biz.image_url !== 'undefined') {
		text += '<img class="businessimage" src="' + biz.image_url + '" width="60" height="60"/>';
	} else {
		text += '<img class="businessimage" src="' + ywpParams.ywpURL + '/includes/images/blank-biz.png" width="60" height="60"/>';
	}


	// div start
	text += '<div class="businessinfo">';
	// name/url
	text += '<a href="' + biz.url + '" target="_blank" class="marker-business-name">' + biz.name + '</a>';
	// stars
	text += '<img class="ratingsimage" src="' + biz.rating_img_url_small + '"/>';
	// reviews
	text += biz.review_count + '&nbsp;reviews<br/>';
	// categories
	text += formatCategories(biz.categories);
	// neighborhoods
	if (biz.location.neighborhoods)
		text += formatNeighborhoods(biz.location.neighborhoods);
	// address
	text += biz.location.display_address + '<br/>';

	// city, state and zip
	text += biz.location.city + ',&nbsp;' + biz.location.state_code + '&nbsp;' + biz.location.postal_code + '<br/>';
	// phone number
	if (biz.phone !== undefined)
		text += formatPhoneNumber(biz.phone);
	// Read the reviews
	text += '<br/><a href="' + biz.url + '" target="_blank" class="marker-reviews-link">Read the reviews &raquo;</a><br/>';
	// div end
	text += '</div></div>';

	return text;
}

/*
 * Formats the categories HTML
 */
function formatCategories(cats) {
	var s = 'Categories: ';
	for (var i = 0; i < cats.length; i++) {
		s += cats[i][0];
		if (i != cats.length - 1) s += ', ';
	}
	s += '<br/>';
	return s;
}

/*
 * Formats the neighborhoods HTML
 */
function formatNeighborhoods(neighborhoods) {
	s = 'Neighborhoods: ';
	for (var i = 0; i < neighborhoods.length; i++) {
		s += neighborhoods[i];
		if (i != neighborhoods.length - 1) s += ', ';
	}
	s += '<br/>';
	return s;
}

/*
 * Formats the phone number HTML
 */
function formatPhoneNumber(num) {
	if (num.length != 10) return '';
	return '(' + num.slice(0, 3) + ') ' + num.slice(3, 6) + '-' + num.slice(6, 10);
}