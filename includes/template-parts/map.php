<?php
/**
 * @description: Display Google Map with Yelp listings
 * @since: 1.2
 */
$jsonArray = '{"results": ['.$jsonArray.']}';
//If user wants to display Google Map
if($displayGoogleMap == 1) { ?>
<div class="ywp-map-container<?php if(!empty($googleMapPosition)) echo " ywp-map-".sanitize_title($googleMapPosition); ?>" data-ywp-json="<?php echo htmlspecialchars($jsonArray, ENT_QUOTES); ?>"<?php if($disableMapScroll == '1') echo " data-map-scroll='false'"; ?>><div class="ywp-map"></div></div>

<?php } ?>