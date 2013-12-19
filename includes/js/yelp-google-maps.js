/**
 *
 */

jQuery(function ($) {

    var $ywpMaps = $('.ywp-map');
    infowindow = new google.maps.InfoWindow();

    /*
     * Loop through maps and initialize
     */
    $ywpMaps.each(function (index, value) {
        var jsonArray = JSON.parse(jQuery($ywpMaps[index]).parent().attr('data-ywp-json'));
        var map;
        var infowindow;
        var icon = null;
        var mapBounds = null;
        var myLatlng;
        geocoder = new google.maps.Geocoder();

        //Scroll Option
        var scrollOption = jQuery($ywpMaps[index]).parent().attr('data-map-scroll');
        if (scrollOption == undefined) {
            scrollOption = true;
        } else {
            scrollOption = false;
        }

        //Handle API long/lat. results

        if (typeof jsonArray.results[0].region !== 'undefined') {
            myLatlng = new google.maps.LatLng(jsonArray.results[0].region.center.latitude, jsonArray.results[0].region.center.longitude);
        } else if (typeof jsonArray.results[0].location.coordinate !== 'undefined') {
            myLatlng = new google.maps.LatLng(jsonArray.results[0].location.coordinate.latitude, jsonArray.results[0].location.coordinate.longitude);
        } else {
            bizAddress = jsonArray.results[0].location.address[0] + ", " + jsonArray.results[0].location.city + ", " + jsonArray.results[0].location.state_code + ", " + jsonArray.results[0].location.country_code;
            geocoder.geocode({ 'address': bizAddress}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    myLatlng = new google.maps.LatLng(results[0].geometry.location.ob, results[0].geometry.location.pb);



                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }

            });


        }


        var mapOptions = {
            scrollwheel: scrollOption,
            zoom: 10,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map($ywpMaps[index], mapOptions);

        google.maps.event.addDomListener(window, "load", function () {
            var mapBounds = map.getBounds();
            updateMap(mapBounds, jsonArray, map);
        });

        infowindow = new google.maps.InfoWindow({
            content: ''
        });

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
        createMarkerWidget(biz, new google.maps.LatLng(biz.location.coordinate.latitude, biz.location.coordinate.longitude), i, map);

    }
    //Search API
    else if (typeof data.results[0].businesses !== 'undefined') {

        for (var i = 0; i < data.results[0].businesses.length; i++) {
            biz = data.results[0].businesses[i];
            bizAddress = biz.location.address[0] + ", " + biz.location.city + ", " + biz.location.state_code + ", " + biz.location.country_code;


            //Get Long/Lat or calculate from address
            if (typeof biz.location.coordinate !== 'undefined') {
                createMarkerWidget(biz, new google.maps.LatLng(biz.location.coordinate.latitude, biz.location.coordinate.longitude), i, map);

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

            createMarkerWidget(biz, new google.maps.LatLng(results[0].geometry.location.ob, results[0].geometry.location.pb), index, map);


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

function createMarkerWidget(biz, point, markerNum, map) {
    var marker = new google.maps.Marker({
        map: map,
        icon: ywpParams.ywpURL + "/includes/images/marker_star.png",
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
        text += '<img class="businessimage" src="' + biz.image_url + '"/>';
    } else {
        text += '<img class="businessimage" src="' + ywpParams.ywpURL + '/includes/images/blank-biz.png"/>';
    }


    // div start
    text += '<div class="businessinfo">';
    // name/url
    text += '<a href="' + biz.url + '" target="_blank" class="marker-business-name">' + biz.name + '</a><br/>';
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
    text += '<br/><a href="' + biz.url + '" target="_blank">Read the reviews &raquo;</a><br/>';
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