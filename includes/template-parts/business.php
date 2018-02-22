<?php
/**
 * Display Single Yelp Business using the Yelp Business API
 *
 * @api        : http://www.yelp.com/developers/documentation/v2/business
 * @created    : 03/06/13
 * @since      1.2
 */

$x = 0; ?>

<div class="yelp yelp-business <?php echo Yelp_Widget::ywp_profile_image_size( $profileImgSize, 'class' ); ?> yelp-business-api <?php echo "yelp-widget-" . $align; ?>"<?php if ( ! empty( $width ) ) {
	echo "style='width:" . $width . ";'";
} ?>>

	<?php
	//Display Google Map ABOVE Results Option
	if ( $googleMapPosition === 'above' ) {
		include( 'map.php' );
	}

	/**
	 * Display Business information
	 * (if user hasn't checked to not display)
	 */
	if ( $displayBizInfo !== '1' ) {
		include( 'business-info.php' );
	} ?>

	<?php
	/*
	 * Display Reviews
	 */
	if ( $reviewsOption == '1' ) {
		?>

		<div class="yelp-business-reviews<?php if ( $displayBizInfo === '1' ) {
			echo " no-business-info";
		} ?>">


			<?php
			/**
			 * Display Reviews
			 */
			if ( isset( $businesses[0]->review_count ) && isset( $businesses[0]->reviews ) ) {

				foreach ( $businesses[0]->reviews as $review ) {

					//Review Filter
					if ( $reviewFilter == 'none' || $review->rating >= intval( $reviewFilter ) ) :

						$review_avatar = ! empty( $review->user->image_url ) ? $review->user->image_url : YELP_WIDGET_PRO_URL . '/includes/images/yelp-default-avatar.png';
						?>

						<div class="yelp-review yelper-avatar-<?php echo $reviewsImgSize; ?> clearfix">

							<div class="yelp-review-avatar">

								<img src="<?php echo $review_avatar; ?>" <?php
								switch ( $reviewsImgSize ) {
									case '100x100':
										echo "width='100' height='100'";
										break;
									case '80x80':
										echo "width='80' height='80'";
										break;
									case '60x60':
										echo "width='60' height='60'";
										break;
									case '40x40':
										echo "width='40' height='40'";
										break;
									default:
										echo "width='60' height='60'";
								} ?> alt="<?php echo $review->user->name; ?>'s Review" />
								<span class="name"><?php echo $review->user->name; ?></span>
							</div>


							<div class="yelp-review-excerpt">

								<?php if ( $hideRating !== '1' ) { ?>
									<?php yelp_widget_fusion_stars( $review->rating ); ?>
									<time><?php echo date( 'n/j/Y', strtotime( $review->time_created ) ); ?></time>
								<?php } ?>
								<div class="yelp-review-excerpt-text">
									<?php echo wpautop( $review->text ); ?>
								</div>
								<?php
								//Read More Review
								if ( $hideReadMore !== '1' ) {
									$reviewMoreText = ! empty( $customReadMore ) ? $customReadMore : __( 'Read Full Review', 'ywp' );
									?>
									<a href="<?php echo esc_url( $review->url ); ?>"
									   class="ywp-review-read-more" <?php echo $targetBlank . $noFollow; ?>><?php echo $reviewMoreText; ?></a>
								<?php } ?>

							</div>

						</div>

					<?php endif; ?>
				<?php } //end foreach ?>

			<?php } //end if review_count > 0 ?>

		</div>

	<?php } ?>

	<?php //Display Google Map BELOW Results Option
	if ( empty( $googleMapPosition ) || $googleMapPosition === 'below' ) {
		include( 'map.php' );
	}
	?>

</div><!--/.yelp-business -->

