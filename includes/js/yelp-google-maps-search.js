/**
 * Yelp Widget Pro Google Maps Search
 *
 * @Desc: Used for Yelp Google Maps API Search shortcode and widget
 *
 */
function hex_sha1(e) {
	return binb2hex(core_sha1(str2binb(e), e.length * chrsz))
}
function b64_sha1(e) {
	return binb2b64(core_sha1(str2binb(e), e.length * chrsz))
}
function str_sha1(e) {
	return binb2str(core_sha1(str2binb(e), e.length * chrsz))
}
function hex_hmac_sha1(e, t) {
	return binb2hex(core_hmac_sha1(e, t))
}
function b64_hmac_sha1(e, t) {
	return binb2b64(core_hmac_sha1(e, t))
}
function str_hmac_sha1(e, t) {
	return binb2str(core_hmac_sha1(e, t))
}
function sha1_vm_test() {
	return hex_sha1("abc") == "a9993e364706816aba3e25717850c26c9cd0d89d"
}
function core_sha1(e, t) {
	e[t >> 5] |= 128 << 24 - t % 32;
	e[(t + 64 >> 9 << 4) + 15] = t;
	var n = Array(80);
	var r = 1732584193;
	var i = -271733879;
	var s = -1732584194;
	var o = 271733878;
	var u = -1009589776;
	for (var a = 0; a < e.length; a += 16) {
		var f = r;
		var l = i;
		var c = s;
		var h = o;
		var p = u;
		for (var d = 0; d < 80; d++) {
			if (d < 16)n[d] = e[a + d]; else n[d] = rol(n[d - 3] ^ n[d - 8] ^ n[d - 14] ^ n[d - 16], 1);
			var v = safe_add(safe_add(rol(r, 5), sha1_ft(d, i, s, o)), safe_add(safe_add(u, n[d]), sha1_kt(d)));
			u = o;
			o = s;
			s = rol(i, 30);
			i = r;
			r = v
		}
		r = safe_add(r, f);
		i = safe_add(i, l);
		s = safe_add(s, c);
		o = safe_add(o, h);
		u = safe_add(u, p)
	}
	return Array(r, i, s, o, u)
}
function sha1_ft(e, t, n, r) {
	if (e < 20)return t & n | ~t & r;
	if (e < 40)return t ^ n ^ r;
	if (e < 60)return t & n | t & r | n & r;
	return t ^ n ^ r
}
function sha1_kt(e) {
	return e < 20 ? 1518500249 : e < 40 ? 1859775393 : e < 60 ? -1894007588 : -899497514
}
function core_hmac_sha1(e, t) {
	var n = str2binb(e);
	if (n.length > 16)n = core_sha1(n, e.length * chrsz);
	var r = Array(16), i = Array(16);
	for (var s = 0; s < 16; s++) {
		r[s] = n[s] ^ 909522486;
		i[s] = n[s] ^ 1549556828
	}
	var o = core_sha1(r.concat(str2binb(t)), 512 + t.length * chrsz);
	return core_sha1(i.concat(o), 512 + 160)
}
function safe_add(e, t) {
	var n = (e & 65535) + (t & 65535);
	var r = (e >> 16) + (t >> 16) + (n >> 16);
	return r << 16 | n & 65535
}
function rol(e, t) {
	return e << t | e >>> 32 - t
}
function str2binb(e) {
	var t = Array();
	var n = (1 << chrsz) - 1;
	for (var r = 0; r < e.length * chrsz; r += chrsz)t[r >> 5] |= (e.charCodeAt(r / chrsz) & n) << 32 - chrsz - r % 32;
	return t
}
function binb2str(e) {
	var t = "";
	var n = (1 << chrsz) - 1;
	for (var r = 0; r < e.length * 32; r += chrsz)t += String.fromCharCode(e[r >> 5] >>> 32 - chrsz - r % 32 & n);
	return t
}
function binb2hex(e) {
	var t = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
	var n = "";
	for (var r = 0; r < e.length * 4; r++) {
		n += t.charAt(e[r >> 2] >> (3 - r % 4) * 8 + 4 & 15) + t.charAt(e[r >> 2] >> (3 - r % 4) * 8 & 15)
	}
	return n
}
function binb2b64(e) {
	var t = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
	var n = "";
	for (var r = 0; r < e.length * 4; r += 3) {
		var i = (e[r >> 2] >> 8 * (3 - r % 4) & 255) << 16 | (e[r + 1 >> 2] >> 8 * (3 - (r + 1) % 4) & 255) << 8 | e[r + 2 >> 2] >> 8 * (3 - (r + 2) % 4) & 255;
		for (var s = 0; s < 4; s++) {
			if (r * 8 + s * 6 > e.length * 32)n += b64pad; else n += t.charAt(i >> 6 * (3 - s) & 63)
		}
	}
	return n
}
var OAuth;
if (OAuth == null)OAuth = {};
OAuth.setProperties = function (t, n) {
	if (t != null && n != null) {
		for (var r in n) {
			t[r] = n[r]
		}
	}
	return t
};
OAuth.setProperties(OAuth, {percentEncode: function (t) {
	if (t == null) {
		return""
	}
	if (t instanceof Array) {
		var n = "";
		for (var r = 0; r < t.length; ++t) {
			if (n != "")n += "&";
			n += OAuth.percentEncode(t[r])
		}
		return n
	}
	t = encodeURIComponent(t);
	t = t.replace(/\!/g, "%21");
	t = t.replace(/\*/g, "%2A");
	t = t.replace(/\'/g, "%27");
	t = t.replace(/\(/g, "%28");
	t = t.replace(/\)/g, "%29");
	return t
}, decodePercent                         : function (t) {
	if (t != null) {
		t = t.replace(/\+/g, " ")
	}
	return decodeURIComponent(t)
}, getParameterList                      : function (t) {
	if (t == null) {
		return[]
	}
	if (typeof t != "object") {
		return OAuth.decodeForm(t + "")
	}
	if (t instanceof Array) {
		return t
	}
	var n = [];
	for (var r in t) {
		n.push([r, t[r]])
	}
	return n
}, getParameterMap                       : function (t) {
	if (t == null) {
		return{}
	}
	if (typeof t != "object") {
		return OAuth.getParameterMap(OAuth.decodeForm(t + ""))
	}
	if (t instanceof Array) {
		var n = {};
		for (var r = 0; r < t.length; ++r) {
			var i = t[r][0];
			if (n[i] === undefined) {
				n[i] = t[r][1]
			}
		}
		return n
	}
	return t
}, getParameter                          : function (t, n) {
	if (t instanceof Array) {
		for (var r = 0; r < t.length; ++r) {
			if (t[r][0] == n) {
				return t[r][1]
			}
		}
	} else {
		return OAuth.getParameterMap(t)[n]
	}
	return null
}, formEncode                            : function (t) {
	var n = "";
	var r = OAuth.getParameterList(t);
	for (var i = 0; i < r.length; ++i) {
		var s = r[i][1];
		if (s == null)s = "";
		if (n != "")n += "&";
		n += OAuth.percentEncode(r[i][0]) + "=" + OAuth.percentEncode(s)
	}
	return n
}, decodeForm                            : function (t) {
	var n = [];
	var r = t.split("&");
	for (var i = 0; i < r.length; ++i) {
		var s = r[i];
		if (s == "") {
			continue
		}
		var o = s.indexOf("=");
		var u;
		var a;
		if (o < 0) {
			u = OAuth.decodePercent(s);
			a = null
		} else {
			u = OAuth.decodePercent(s.substring(0, o));
			a = OAuth.decodePercent(s.substring(o + 1))
		}
		n.push([u, a])
	}
	return n
}, setParameter                          : function (t, n, r) {
	var i = t.parameters;
	if (i instanceof Array) {
		for (var s = 0; s < i.length; ++s) {
			if (i[s][0] == n) {
				if (r === undefined) {
					i.splice(s, 1)
				} else {
					i[s][1] = r;
					r = undefined
				}
			}
		}
		if (r !== undefined) {
			i.push([n, r])
		}
	} else {
		i = OAuth.getParameterMap(i);
		i[n] = r;
		t.parameters = i
	}
}, setParameters                         : function (t, n) {
	var r = OAuth.getParameterList(n);
	for (var i = 0; i < r.length; ++i) {
		OAuth.setParameter(t, r[i][0], r[i][1])
	}
}, completeRequest                       : function (t, n) {
	if (t.method == null) {
		t.method = "GET"
	}
	var r = OAuth.getParameterMap(t.parameters);
	if (r.oauth_consumer_key == null) {
		OAuth.setParameter(t, "oauth_consumer_key", n.consumerKey || "")
	}
	if (r.oauth_token == null && n.token != null) {
		OAuth.setParameter(t, "oauth_token", n.token)
	}
	if (r.oauth_version == null) {
		OAuth.setParameter(t, "oauth_version", "1.0")
	}
	if (r.oauth_timestamp == null) {
		OAuth.setParameter(t, "oauth_timestamp", OAuth.timestamp())
	}
	if (r.oauth_nonce == null) {
		OAuth.setParameter(t, "oauth_nonce", OAuth.nonce(6))
	}
	OAuth.SignatureMethod.sign(t, n)
}, setTimestampAndNonce                  : function (t) {
	OAuth.setParameter(t, "oauth_timestamp", OAuth.timestamp());
	OAuth.setParameter(t, "oauth_nonce", OAuth.nonce(6))
}, addToURL                              : function (t, n) {
	newURL = t;
	if (n != null) {
		var r = OAuth.formEncode(n);
		if (r.length > 0) {
			var i = t.indexOf("?");
			if (i < 0)newURL += "?"; else newURL += "&";
			newURL += r
		}
	}
	return newURL
}, getAuthorizationHeader                : function (t, n) {
	var r = 'OAuth realm="' + OAuth.percentEncode(t) + '"';
	var i = OAuth.getParameterList(n);
	for (var s = 0; s < i.length; ++s) {
		var o = i[s];
		var u = o[0];
		if (u.indexOf("oauth_") == 0) {
			r += "," + OAuth.percentEncode(u) + '="' + OAuth.percentEncode(o[1]) + '"'
		}
	}
	return r
}, correctTimestampFromSrc               : function (t) {
	t = t || "oauth_timestamp";
	var n = document.getElementsByTagName("script");
	if (n == null || !n.length)return;
	var r = n[n.length - 1].src;
	if (!r)return;
	var i = r.indexOf("?");
	if (i < 0)return;
	parameters = OAuth.getParameterMap(OAuth.decodeForm(r.substring(i + 1)));
	var s = parameters[t];
	if (s == null)return;
	OAuth.correctTimestamp(s)
}, correctTimestamp                      : function (t) {
	OAuth.timeCorrectionMsec = t * 1e3 - (new Date).getTime()
}, timeCorrectionMsec                    : 0, timestamp: function () {
	var t = (new Date).getTime() + OAuth.timeCorrectionMsec;
	return Math.floor(t / 1e3)
}, nonce                                 : function (t) {
	var n = OAuth.nonce.CHARS;
	var r = "";
	for (var i = 0; i < t; ++i) {
		var s = Math.floor(Math.random() * n.length);
		r += n.substring(s, s + 1)
	}
	return r
}});
OAuth.nonce.CHARS = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
OAuth.declareClass = function (t, n, r) {
	var i = t[n];
	t[n] = r;
	if (r != null && i != null) {
		for (var s in i) {
			if (s != "prototype") {
				r[s] = i[s]
			}
		}
	}
	return r
};
OAuth.declareClass(OAuth, "SignatureMethod", function () {
});
OAuth.setProperties(OAuth.SignatureMethod.prototype, {sign: function (t) {
	var n = OAuth.SignatureMethod.getBaseString(t);
	var r = this.getSignature(n);
	OAuth.setParameter(t, "oauth_signature", r);
	return r
}, initialize                                             : function (t, n) {
	var r;
	if (n.accessorSecret != null && t.length > 9 && t.substring(t.length - 9) == "-Accessor") {
		r = n.accessorSecret
	} else {
		r = n.consumerSecret
	}
	this.key = OAuth.percentEncode(r) + "&" + OAuth.percentEncode(n.tokenSecret)
}});
OAuth.setProperties(OAuth.SignatureMethod, {sign: function (t, n) {
	var r = OAuth.getParameterMap(t.parameters).oauth_signature_method;
	if (r == null || r == "") {
		r = "HMAC-SHA1";
		OAuth.setParameter(t, "oauth_signature_method", r)
	}
	OAuth.SignatureMethod.newMethod(r, n).sign(t)
}, newMethod                                    : function (t, n) {
	var r = OAuth.SignatureMethod.REGISTERED[t];
	if (r != null) {
		var i = new r;
		i.initialize(t, n);
		return i
	}
	var s = new Error("signature_method_rejected");
	var o = "";
	for (var u in OAuth.SignatureMethod.REGISTERED) {
		if (o != "")o += "&";
		o += OAuth.percentEncode(u)
	}
	s.oauth_acceptable_signature_methods = o;
	throw s
}, REGISTERED                                   : {}, registerMethodClass: function (t, n) {
	for (var r = 0; r < t.length; ++r) {
		OAuth.SignatureMethod.REGISTERED[t[r]] = n
	}
}, makeSubclass                                 : function (t) {
	var n = OAuth.SignatureMethod;
	var r = function () {
		n.call(this)
	};
	r.prototype = new n;
	r.prototype.getSignature = t;
	r.prototype.constructor = r;
	return r
}, getBaseString                                : function (t) {
	var n = t.action;
	var r = n.indexOf("?");
	var i;
	if (r < 0) {
		i = t.parameters
	} else {
		i = OAuth.decodeForm(n.substring(r + 1));
		var s = OAuth.getParameterList(t.parameters);
		for (var o = 0; o < s.length; ++o) {
			i.push(s[o])
		}
	}
	return OAuth.percentEncode(t.method.toUpperCase()) + "&" + OAuth.percentEncode(OAuth.SignatureMethod.normalizeUrl(n)) + "&" + OAuth.percentEncode(OAuth.SignatureMethod.normalizeParameters(i))
}, normalizeUrl                                 : function (t) {
	var n = OAuth.SignatureMethod.parseUri(t);
	var r = n.protocol.toLowerCase();
	var i = n.authority.toLowerCase();
	var s = r == "http" && n.port == 80 || r == "https" && n.port == 443;
	if (s) {
		var o = i.lastIndexOf(":");
		if (o >= 0) {
			i = i.substring(0, o)
		}
	}
	var u = n.path;
	if (!u) {
		u = "/"
	}
	return r + "://" + i + u
}, parseUri                                     : function (t) {
	var n = {key: ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"], parser: {strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@\/]*):?([^:@\/]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/}};
	var r = n.parser.strict.exec(t);
	var i = {};
	var s = 14;
	while (s--)i[n.key[s]] = r[s] || "";
	return i
}, normalizeParameters                          : function (t) {
	if (t == null) {
		return""
	}
	var n = OAuth.getParameterList(t);
	var r = [];
	for (var i = 0; i < n.length; ++i) {
		var s = n[i];
		if (s[0] != "oauth_signature") {
			r.push([OAuth.percentEncode(s[0]) + " " + OAuth.percentEncode(s[1]), s])
		}
	}
	r.sort(function (e, t) {
		if (e[0] < t[0])return-1;
		if (e[0] > t[0])return 1;
		return 0
	});
	var o = [];
	for (var u = 0; u < r.length; ++u) {
		o.push(r[u][1])
	}
	return OAuth.formEncode(o)
}});
OAuth.SignatureMethod.registerMethodClass(["PLAINTEXT", "PLAINTEXT-Accessor"], OAuth.SignatureMethod.makeSubclass(function (t) {
	return this.key
}));
OAuth.SignatureMethod.registerMethodClass(["HMAC-SHA1", "HMAC-SHA1-Accessor"], OAuth.SignatureMethod.makeSubclass(function (t) {
	b64pad = "=";
	var n = b64_hmac_sha1(this.key, t);
	return n
}));
try {
	OAuth.correctTimestampFromSrc()
} catch (e) {
}
var hexcase = 0;
var b64pad = "";
var chrsz = 8;

/**
 * Yelp Widget Google Map
 */
var auth = {
	//From localize params
	consumerKey      : ywpMapParams.consumerKey,
	consumerSecret   : ywpMapParams.consumerSecret,
	accessToken      : ywpMapParams.accessToken,
	accessTokenSecret: ywpMapParams.accessTokenSecret,
	serviceProvider  : {
		signatureMethod: "HMAC-SHA1"
	}
};

var map;
var icon;
var markersArray = [];

jQuery.noConflict();

(function ($) {

	$(function () {

		//Default Term

		var $ywpSearchMaps = $('.yelp-map');
		var infowindow = new google.maps.InfoWindow({
			maxWidth: 220 //max-width for containers  https://developers.google.com/maps/documentation/javascript/examples/infowindow-simple-max
		});

		/*
		 * Loop through maps and initialize
		 */
		$ywpSearchMaps.each(function (index, value) {
			geocoder = new google.maps.Geocoder();

			var ywpSearchMapsParent = $ywpSearchMaps.parent().parent();
			var defaultTerm = $(ywpSearchMapsParent).find('.yelp-search-term').attr('placeholder');
			var mapBounds = null;
			var location = ywpSearchMapsParent.attr('data-map-location');


			//Get Lat Long from Address
			geocoder.geocode({ 'address': location}, function (results, status) {

				if (status == google.maps.GeocoderStatus.OK) {

					var geoLoc = results[0].geometry.location;
					var myLatitude = geoLoc.lat();
					var myLongitude = geoLoc.lng();


					var mapOptions = {
						zoom     : 10,
						center   : new google.maps.LatLng(myLatitude, myLongitude),
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};


					map = new google.maps.Map($ywpSearchMaps[index], mapOptions);

					//When Search Button is Clicked
					$('.yelp-search-form').on('submit', function (e) {
						e.preventDefault();
						var newTerm = $(this).children('.yelp-search-term').val();
						yelpMapDataInit(newTerm, location, map);

					});

					//Initiate Map Data on Load
					yelpMapDataInit(defaultTerm, location, map);

				} else {
					console.log('Geocode was not successful for the following reason: ' + status);
				}
			});


		});
	});

})(jQuery);

/**
 * Handles the search feature for the Google map
 * @param term
 * @param location
 * @param map
 */
function yelpMapDataInit(term, location, map) {

	//oAuth Goodness
	var accessor = {
		consumerSecret: auth.consumerSecret,
		tokenSecret   : auth.accessTokenSecret
	};

	parameters = [];
	parameters.push(['term', term]);
	parameters.push(['location', location]);
	parameters.push(['callback', 'cb']);
	parameters.push(['oauth_consumer_key', auth.consumerKey]);
	parameters.push(['oauth_consumer_secret', auth.consumerSecret]);
	parameters.push(['oauth_token', auth.accessToken]);
	parameters.push(['oauth_signature_method', 'HMAC-SHA1']);

	var message = {
		'action'    : 'http://api.yelp.com/v2/search',
		'method'    : 'GET',
		'parameters': parameters
	};

	OAuth.setTimestampAndNonce(message);
	OAuth.SignatureMethod.sign(message, accessor);

	var parameterMap = OAuth.getParameterMap(message.parameters);
	parameterMap.oauth_signature = OAuth.percentEncode(parameterMap.oauth_signature);


	jQuery.ajax({
		url          : message.action,
		data         : parameterMap,
		cache        : true,
		dataType     : 'jsonp',
		jsonpCallback: 'cb',
		success      : function (data, textStats, XMLHttpRequest) {
			clearOverlays();
			handleSearchResults(data, map);
		}
	});


}

/**
 * If a successful API response is received, place
 * markers on the map.  If not, display an error.
 */
function handleSearchResults(data, map) {
	// turn off spinner animation
	jQuery(".spinner").css('visibility', 'hidden');

	//No Businesses found
	if (data.businesses.length == 0) {
		alert("Error: No businesses were found near that location");
		return;
	}
	//Business found:
	for (var i = 0; i < data.businesses.length; i++) {

		biz = data.businesses[i];

		bizAddress = biz.location.address[0] + ", " + biz.location.city + ", " + biz.location.state_code + ", " + biz.location.country_code;
		//Get Long/Lat or calculate from address
		if (typeof biz.location.coordinate !== 'undefined') {

			createSearchMarker(biz, new google.maps.LatLng(biz.location.coordinate.latitude, biz.location.coordinate.longitude), i, map);

		} else {

			geocodeAddress(bizAddress, i, map, biz);

		}


	}

}

/**
 * GeoCode Address
 */
function geocodeAddress(address, index, map, biz) {

	geocoder.geocode({
		'address': address
	}, function (results, status) {
		if (status === google.maps.GeocoderStatus.OK) {

			var lat = results[0].geometry.location.lat().toString().substr(0, 12);
			var lng = results[0].geometry.location.lng().toString().substr(0, 12);

			createSearchMarker(biz, new google.maps.LatLng(lat, lng), index, map);

		} else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
			setTimeout(function () {
				geocodeAddress(address, index, map, biz);
			}, 200);
		} else {
			console.log("Geocode was not successful for " + biz.name + " the following reason: " + status);
		}
	});
}


/**
 * Creates a marker for the given business and point
 */
function createSearchMarker(biz, point, markerNum, map) {
	//Set Markers
	var marker = new google.maps.Marker({
		map     : map,
		icon    : ywpMapParams.ywpURL + "/includes/images/marker_star.png",
		position: point
	});

	//Set Info Windows
	marker.content = generateSearchInfoWindowHtml(biz);
	markersArray.push(marker);
	google.maps.event.addListener(marker, 'click', function () {
		infowindow.setContent(marker.content);
		infowindow.open(map, marker);
	});

	//Open First Marker by Default
	if (markerNum == 0) {
		infowindow.setContent(marker.content);
		infowindow.open(map, marker);
	}

}


/**
 * Formats and returns the Info Window HTML
 * (displayed in a balloon when a marker is clicked)
 */
function generateSearchInfoWindowHtml(biz) {

	var text = '<div class="marker">';

	text += '<div class="ywp-business-top ywp-marker-block clearfix clear">';

	// image and rating
	if (typeof biz.image_url !== 'undefined') {
		text += '<img class="businessimage" src="' + biz.image_url + '" width="60" height="60" />';
	} else {
		text += '<img class="businessimage" src="' + ywpMapParams.ywpURL + '/includes/images/blank-biz.png" width="60" height="60" />';
	}

	// div start
	text += '<div class="ywp-business-info">';

	// name/url
	text += '<a href="' + biz.url + '" target="_blank" class="marker-business-name">' + biz.name + '</a>';
	// stars
	text += '<div class="review-rating"><img class="ratingsimage" src="' + biz.rating_img_url_small + '"/> <span class="ywp-review-text">';
	// reviews
	text += biz.review_count + ' reviews</span></div>';

	//Phone number
	if (biz.phone !== undefined) {
		var phone = formatPhoneNumber(biz.phone);
		text += '<a href="tel:' + biz.phone + '" title="Call ' + biz.name + '" class="phone-clickable">' + biz.display_phone + '</a>';
	}

	text += '</div></div>'; //close ywp-business-info info div

// Review Snipped
	text += '<div class="ywp-read-review-snippet"><p class="snippet-text">' + biz.snippet_text + '</p><img class="snippet-img" src="' + biz.snippet_image_url + '" width="25" height="25" /></div>';
// Read the reviews
	text += '<div class="ywp-read-reviews"><a href="' + biz.url + '" target="_blank" class="marker-business-name marker-business-link">Read the reviews »</a></div>';

	text += '<div class="businessinfo"><table border="0" class="marker-info-table" width="220" style="width:220px"><tr>';

	// categories
	if (biz.categories !== undefined) {
		text += '<td>' + formatCategories(biz.categories) + '</td>';
	}

	//Neighborhoods
	if (biz.location.neighborhoods !== undefined) {
		text += '<td>' + formatNeighborhoods(biz.location.neighborhoods) + '</td>';
	}

	text += '<tr>';

	//Display Address
	if (biz.location.display_address[0] !== undefined) {
		text += '<td colspan="2">' + formatAddress(biz.location.display_address) + '</td>';
	}

	text += '</tr>';


	// div end
	text += '</table></div></div>';

	return text;
}

/**
 * Formats the categories HTML
 */
function formatCategories(cats) {
	var output = '<div class="ywp-marker-categories-wrap ywp-marker-block"><p class="ywp-marker-cat-title"><strong>Categories:</strong></p><p class="ywp-marker-categories">';
	for (var i = 0; i < cats.length; i++) {
		output += '<span class="ywp-marker-cat">' + cats[i][0] + '</span>';
		if (i != cats.length - 1) {
			output += ', ';
		}
	}
	output += '</p></div>';
	return output;
}

/**
 * Formats the neighborhoods HTML
 */
function formatNeighborhoods(neighborhoods) {
	var output = '<div class="ywp-marker-neighborhoods-wrap ywp-marker-block"><p class="ywp-marker-neighborhoods-title"><strong>Neighborhoods:</strong></p><p class="ywp-marker-neighborhoods">';
	for (var i = 0; i < neighborhoods.length; i++) {
		output += '<span class="ywp-neighborhood">' + neighborhoods[i] + '</span>';
		if (i != neighborhoods.length - 1) {
			output += ', ';
		}
	}
	output += '</p></div>';
	return output;
}

/**
 * Format Address
 * @param address array
 * @returns formatted string
 */

function formatAddress(address) {

	output = '<address class="ywp-marker-block"><strong>Address:</strong><br/><div class="ywp-map-address">';
	for (var i = 0; i < address.length; i++) {
		output += address[i] + '<br>';
		if (i != address.length - 1) {
			output += '';
		}
	}
	output += '</div></address>';

	return output;

}


/*
 * Formats the phone number HTML
 */
function formatPhoneNumber(num) {
	if (num.length != 10) return '';
	return '(' + num.slice(0, 3) + ') ' + num.slice(3, 6) + '-' + num.slice(6, 10) + '<br/>';
}

/**
 * Clear all Markers
 */

function clearOverlays() {
	for (var i = 0; i < markersArray.length; i++) {
		markersArray[i].setMap(null);
	}
	markersArray = [];
}