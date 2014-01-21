<?php
/**
 * DESC: Display Yelp Business listings using the Yelp Search API
 */
?>
<div class="yelp-widget-search-api <?php echo Yelp_Widget::ywp_profile_image_size( $profileImgSize, 'class' ); ?> <?php if ( ! empty( $align ) ) {
	echo "yelp-widget-" . $align;
} ?>" <?php if ( ! empty( $width ) ) {
	echo "style='width:" . $width . ";'";
} ?>>
	<?php
	//Display Google Map ABOVE Results Option
	if ( $googleMapPosition === 'above' ) {
		include( 'map.php' );
	}
	if ( $displayBizInfo !== '1' ) {


//Begin Setting Output Variable by Looping Data from Yelp
		for ( $x = 0; $x < count( $businesses ); $x ++ ) {
			?>

			<div class="yelp yelp-business">


				<?php include( 'business-info.php' ); ?>


			</div><!-- /.yelp-business -->

		<?php
		} //end foreach

	} //endif display
	//Display Google Map BELOW Results Option
	if ( empty( $googleMapPosition ) || $googleMapPosition === 'below' ) {
		include( 'map.php' );
	}
	?>

</div>