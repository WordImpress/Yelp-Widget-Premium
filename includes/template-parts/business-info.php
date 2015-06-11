<?php
/**
 *  Business Information
 *
 * @description: Main Business Information Content
 * @since      : 1.2
 */
?>

<div class="yelp-business-info clearfix">
	<div class="biz-img-wrap"><img class="picture" src="<?php if ( ! empty( $businesses[ $x ]->image_url ) ) {
			echo esc_attr( $businesses[ $x ]->image_url );
		} else {
			echo YELP_WIDGET_PRO_URL . '/includes/images/blank-biz.png';
		}; ?>" alt="<?php echo esc_attr( $businesses[ $x ]->name ); ?>" <?php echo Yelp_Widget::ywp_profile_image_size( $profileImgSize, 'size' ); ?> />
	</div>

	<div class="info clearfix">
		<a class="name" <?php echo $targetBlank . $noFollow; ?> href="<?php echo esc_attr( $businesses[ $x ]->url ); ?>" title="<?php echo esc_attr( $businesses[ $x ]->name ); ?> Yelp page"><?php echo $businesses[ $x ]->name; ?></a>

		<img class="rating" src="<?php echo esc_attr( $businesses[ $x ]->rating_img_url ); ?>" alt="<?php echo $businesses[ $x ]->name; ?>" title="<?php echo $businesses[ $x ]->name; ?> Yelp Rating" />

		<span class="review-count"><?php echo esc_attr( $businesses[ $x ]->review_count ); ?> <?php _e( 'reviews', 'ywp' ); ?></span>

		<a class="yelp-branding" href="<?php echo esc_attr( $businesses[ $x ]->url ); ?>" <?php echo $targetBlank . $noFollow; ?>><img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/yelp.png'; ?>" alt="<?php echo $businesses[ $x ]->name; ?> <?php _e( 'on Yelp', 'ywp' ); ?>" /></a>
	</div>

</div><!--/.yelp-business-info -->

<?php
/**
 * Display Business Address if Set
 */
if ( $address == 1 ) {
	echo Yelp_Widget::display_biz_address( $businesses[ $x ]->location->display_address );
} //Phone
if ( $phone == 1 ) {
	?>

	<p class="ywp-phone"><?php
		//echo pretty display_phone (only avail in biz API)
		if ( ! empty( $businesses[ $x ]->display_phone ) ) {
			echo $businesses[ $x ]->display_phone;
		} else {
			echo $businesses[ $x ]->phone;
		} ?></p>


<?php } //endif phone	?>
