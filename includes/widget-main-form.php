<?php
/*
 *  @description: Widget form options in WP-Admin
 *  @since 1.2.0
 */
?>

<!-- Title -->
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title', 'ywp' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<!-- Listing Options -->
<p class="widget-api-option">

	<label for="<?php echo $this->get_field_id( 'display_option' ); ?>"><?php _e( 'Yelp API Request Method:', 'ywp' ); ?></label><br />
    <span class="yelp-method-span search-api-option-wrap">
    <input type="radio" name="<?php echo $this->get_field_name( 'display_option' ); ?>" class="<?php echo $this->get_field_id( 'display_option' ); ?> search-api-option" value="0" <?php checked( '0', $displayOption ); ?>><span class="yelp-method-label"><?php _e( 'Search Method', 'ywp' ); ?></span><img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Yelp\'s Search API allows you to display results of a specific search term. For more information <a href=\'http://wordimpress.com/docs/yelp-widget-pro/#search-method\' target=\'_blank\'>Click Here</a>', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /><br />
    </span>
    <span class="yelp-method-span business-api-option-wrap">
    <input type="radio" name="<?php echo $this->get_field_name( 'display_option' ); ?>" class="<?php echo $this->get_field_id( 'display_option' ); ?> business-api-option" value="1" <?php checked( '1', $displayOption ); ?>><span class="yelp-method-label"><?php _e( 'Business Method', 'ywp' ); ?></span><img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Yelp’s Business API allows business owners to display their Yelp reviews (up to 3), address, Google Map location, and more (premium version). For more information <a href=\'https://wordimpress.com/documentation/yelp-widget-pro/search-business-request-methods-expalined/\' target=\'_blank\'>Click Here</a>', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" />
    </span>
</p>

<div class="toggle-api-option-1 toggle-item <?php if ( $displayOption == "0" ) {
	echo 'toggled';
} ?>">
	<!-- Search Term -->
	<p>
		<label for="<?php echo $this->get_field_id( 'term' ); ?>"><?php _e( 'Search Term:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The term you would like to display results for, ie: \'Bars\', \'Daycare\', \'Restaurants\'.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'term' ); ?>" name="<?php echo $this->get_field_name( 'term' ); ?>" type="text" value="<?php echo $term; ?>" />
	</p>


	<!-- Location -->
	<p>
		<label for="<?php echo $this->get_field_id( 'location' ); ?>"><?php _e( 'Location:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The city name you would like to to search, ie \'San Diego\', \'New York\', \'Miami\'.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'location' ); ?>" name="<?php echo $this->get_field_name( 'location' ); ?>" type="text" value="<?php echo $location; ?>" />
	</p>

	<!-- Limit -->
	<p>
		<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of Items:', 'ywp' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'limit' ); ?>" id="<?php echo $this->get_field_id( 'limit' ); ?>" class="widefat">
			<?php
			$options = array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' );
			foreach ( $options as $option ) {
				?>

				<option value="<?php echo $option; ?>" id="<?php echo $option; ?>" <?php if ( $limit == $option || empty( $limit ) && $option == '4' ) {
					echo 'selected="selected"';
				} ?>><?php echo $option; ?></option>

			<?php } ?>
		</select>
	</p>

	<!-- Sort -->
	<p>
		<label for="<?php echo $this->get_field_id( 'sort' ); ?>"><?php _e( 'Sorting:', 'ywp' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'sort' ); ?>" id="<?php echo $this->get_field_id( 'sort' ); ?>" class="widefat">
			<?php
			$options = array( __( 'Best Match', 'ywp' ), __( 'Distance', 'ywp' ), __( 'Highest Rated', 'ywp' ) );
			//Counter for Option Values
			$counter = 0;

			foreach ( $options as $option ) {
				echo '<option value="' . $counter . '" id="' . $option . '"', $sort == $counter ? ' selected="selected"' : '', '>', $option, '</option>';
				$counter ++;
			}
			?>
		</select>
	</p>

</div><!-- /.toggle-api-option-1 -->


<div class="toggle-api-option-2 toggle-item  <?php if ( $displayOption == "1" ) {
	echo 'toggled';
} ?>">
	<!-- Business ID -->
	<p>
		<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Business ID:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The Business ID is the portion of the Yelp url after the \'http://www.yelp.com/biz/\' portion. For example, the following business\'s URL on Yelp is \'http://www.yelp.com/biz/the-barbeque-pit-seattle-2\' and the Business ID is \'the-barbeque-pit-seattle-2\'. For more information <a href=\'https://wordimpress.com/documentation/yelp-widget-pro/search-business-request-methods-expalined/\' target=\'_blank\'>Click Here</a>', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo $id; ?>" />
	</p>

	<!-- Display Reviews -->
	<p>
		<input id="<?php echo $this->get_field_id( 'display_reviews' ); ?>" class="reviews-toggle" name="<?php echo $this->get_field_name( 'display_reviews' ); ?>" type="checkbox" value="1" <?php checked( '1', $reviewsOption ); ?>/>
		<label for="<?php echo $this->get_field_id( 'display_reviews' ); ?>"><?php _e( 'Display Business Reviews', 'ywp' ); ?></label>
	</p>

	<div class="reviews-toggle-container <?php if ( $reviewsOption == "1" ) {
		echo 'toggled';
	} ?>">

		<h4 class="yelp-toggler"><?php _e( 'Review Options:', 'ywp' ); ?><span></span></h4>

		<div class="display-options toggle-item">


			<!-- Filter Reviews -->
			<p>
				<label for="<?php echo $this->get_field_id( 'review_filter' ); ?>"><?php _e( 'Minimum Review Rating:', 'ywp' ); ?>
					<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Filter bad reviews to prevent them from displaying. Please note that the Yelp API only allows for 3 total reviews pulled. This will limit the total number further.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>

				<select id="<?php echo $this->get_field_id( 'review_filter' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'review_filter' ); ?>">

					<option value="none" <?php if ( empty( $reviewFilter ) || $reviewFilter == 'No filter' ) {
						echo "selected='selected'";
					} ?>><?php _e( 'No filter', 'ywp' ); ?>
					</option>
					<option value="5" <?php if ( $reviewFilter == '5' ) {
						echo "selected='selected'";
					} ?>><?php _e( '5 Stars', 'ywp' ); ?>
					</option>
					<option value="4" <?php if ( $reviewFilter == '4' ) {
						echo "selected='selected'";
					} ?>><?php _e( '4 Stars', 'ywp' ); ?>
					</option>
					<option value="3" <?php if ( $reviewFilter == '3' ) {
						echo "selected='selected'";
					} ?>><?php _e( '3 Stars', 'ywp' ); ?>
					</option>
					<option value="2" <?php if ( $reviewFilter == '2' ) {
						echo "selected='selected'";
					} ?>><?php _e( '2 Stars', 'ywp' ); ?>
					</option>
					<option value="1" <?php if ( $reviewFilter == '1' ) {
						echo "selected='selected'";
					} ?>><?php _e( '1 Star', 'ywp' ); ?>
					</option>

				</select>

			</p>

			<!-- Reviewers Avatar Size -->
			<p>
				<label for="<?php echo $this->get_field_id( 'review_avatar_size' ); ?>"><?php _e( 'Reviewer\'s Avatar Size:', 'ywp' ); ?></label>

				<select id="<?php echo $this->get_field_id( 'review_avatar_size' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'review_avatar_size' ); ?>">

					<?php
					$options = array( '40x40', '60x60', '80x80', '100x100' );
					foreach ( $options as $option ) {
						?>

						<option value="<?php echo $option; ?>" id="<?php echo $option; ?>" <?php if ( $reviewsImgSize == $option || empty( $reviewsImgSize ) && $option == '60x60' ) {
							echo 'selected="selected"';
						} ?>><?php echo $option; ?></option>

					<?php } ?>
				</select>

			</p>


			<!-- Disable rating and time output -->
			<p>
				<input id="<?php echo $this->get_field_id( 'hide_rating' ); ?>" name="<?php echo $this->get_field_name( 'hide_rating' ); ?>" type="checkbox" value="1" <?php checked( '1', $hideRating ); ?>/>
				<label for="<?php echo $this->get_field_id( 'hide_rating' ); ?>"><?php _e( 'Hide Star Rating and Date', 'ywp' ); ?>
					<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Disable the individual star rating and date per each review. Useful for certain feeds and output situations.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
			</p>


			<!-- Disable Read More Link -->
			<p>
				<input id="<?php echo $this->get_field_id( 'hide_read_more' ); ?>" name="<?php echo $this->get_field_name( 'hide_read_more' ); ?>" type="checkbox" value="1" <?php checked( '1', $hideReadMore ); ?>/>
				<label for="<?php echo $this->get_field_id( 'hide_read_more' ); ?>"><?php _e( 'Hide "Read Full Review" link', 'ywp' ); ?>
					<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This option allows you to hide the link below each review snippet to the full review on Yelp.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>

			</p>


			<!-- Custom Read More text -->
			<p>
				<label for="<?php echo $this->get_field_id( 'custom_read_more' ); ?>"><?php _e( 'Custom "Read More" text:', 'ywp' ); ?>
					<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Customize the \'Read More\' link text. Useful for non-English languages and personalization.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'custom_read_more' ); ?>" name="<?php echo $this->get_field_name( 'custom_read_more' ); ?>" type="text" value="<?php echo $customReadMore; ?>" placeholder="Read Full Review" />
			</p>


		</div>

	</div>

</div>


<h4 class="yelp-toggler"><?php _e( 'Display Options:', 'ywp' ); ?><span></span></h4>

<div class="display-options toggle-item">

	<!-- Profile Image Size -->
	<p>
		<label for="<?php echo $this->get_field_id( 'profile_img_size' ); ?>"><?php _e( 'Profile Image Size:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Customize the width and height of the business Yelp profile image.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<select name="<?php echo $this->get_field_name( 'profile_img_size' ); ?>" id="<?php echo $this->get_field_id( 'profile_img_size' ); ?>" class="widefat">
			<?php
			$options = array( '40x40', '60x60', '80x80', '100x100' );
			foreach ( $options as $option ) {
				?>

				<option value="<?php echo $option; ?>" id="<?php echo $option; ?>" <?php if ( $profileImgSize == $option || empty( $profileImgSize ) && $option == '60x60' ) {
					echo 'selected="selected"';
				} ?>><?php echo $option; ?></option>

			<?php } ?>
		</select>
	</p>

	<!-- Disable title output checkbox -->
	<p>
		<input id="<?php echo $this->get_field_id( 'display_address' ); ?>" name="<?php echo $this->get_field_name( 'display_address' ); ?>" type="checkbox" value="1" <?php checked( '1', $address ); ?>/>
		<label for="<?php echo $this->get_field_id( 'display_address' ); ?>"><?php _e( 'Display Business Address', 'ywp' ); ?></label>
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'display_phone' ); ?>" name="<?php echo $this->get_field_name( 'display_phone' ); ?>" type="checkbox" value="1" <?php checked( '1', $phone ); ?>/>
		<label for="<?php echo $this->get_field_id( 'display_phone' ); ?>"><?php _e( 'Display Business Phone Number', 'ywp' ); ?></label>
	</p>
	<!-- Display phone -->

	<!-- Disable Business Name and Rating Information -->
	<p>
		<input id="<?php echo $this->get_field_id( 'disable_business_info' ); ?>" name="<?php echo $this->get_field_name( 'disable_business_info' ); ?>" type="checkbox" value="1" <?php checked( '1', $displayBizInfo ); ?> />
		<label for="<?php echo $this->get_field_id( 'disable_business_info' ); ?>"><?php _e( 'Hide Business Information', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Disable the main business information profile image, name, rating and address (if enabled). Useful for displaying only maps and ratings in the widget.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>

	</p>


</div>

<h4 class="yelp-toggler"><?php _e( 'Google Map Options:', 'ywp' ); ?><span></span></h4>

<div class="advanced-options toggle-item">

	<?php $options = get_option( 'yelp_widget_settings' );
		// Only Display if a Google Maps API key is present in Yelp Widget Pro Settings
		if (!empty($options['yelp_widget_maps_api'])) {
	?>
	<!-- Display Google Map checkbox -->
	<p>
		<input id="<?php echo $this->get_field_id( 'display_google_map' ); ?>" name="<?php echo $this->get_field_name( 'display_google_map' ); ?>" type="checkbox" value="1" <?php checked( '1', $displayGoogleMap ); ?>/>
		<label for="<?php echo $this->get_field_id( 'display_google_map' ); ?>"><?php _e( 'Display Google Map', 'ywp' ); ?></label>
	</p>


	<!-- Google Map Scrollability -->
	<p>
		<input id="<?php echo $this->get_field_id( 'disable_map_scroll' ); ?>" name="<?php echo $this->get_field_name( 'disable_map_scroll' ); ?>" type="checkbox" value="1" <?php checked( '1', $disableMapScroll ); ?>/>
		<label for="<?php echo $this->get_field_id( 'disable_map_scroll' ); ?>"><?php _e( 'Disable Map Scroll', 'ywp' ); ?></label>
	</p>


	<!--- Google Map Position -->
	<p>
		<label id="<?php echo $this->get_field_id( 'google_map_position' ); ?>" for="<?php echo $this->get_field_id( 'google_map_position' ); ?>" class="ywp-radio-label"><?php _e( 'Map Position', 'ywp' ); ?>:</label>

		<input type="radio" name="<?php echo $this->get_field_name( 'google_map_position' ); ?>" value="below" <?php checked( 'below', $googleMapPosition );
		if ( empty( $googleMapPosition ) ) {
			echo "checked='checked'";
		} ?>><span class="yelp-method-label"><?php _e( 'Below Results', 'ywp' ); ?></span><br>
		<input type="radio" name="<?php echo $this->get_field_name( 'google_map_position' ); ?>" value="above" <?php checked( 'above', $googleMapPosition ); ?>><span class="yelp-method-label"><?php _e( 'Above Results', 'ywp' ); ?></span>
	</p>
	<?php } else {
		// If Maps API Key is NOT present, inform the user where to go: ?>
		<div class="ywp-notice warning">
			<p><strong>Warning: No Maps API Key Present.</strong></p>
			<p>To use this feature, you have to have a Google Maps API Key in your Yelp Settings.</p>
			<p><a href="<?php echo admin_url( 'options-general.php?page=yelp_widget' );?>" class="button">Go to Settings</a></p>
		</div>

	<?php }?>
</div>

<h4 class="yelp-toggler"><?php _e( 'Advanced Options:', 'ywp' ); ?><span></span></h4>

<div class="advanced-options toggle-item">

	<!-- Transient / Cache -->
	<p>
		<label for="<?php echo $this->get_field_id( 'cache' ); ?>"><?php _e( 'Cache Data:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Caching data will save Yelp data to your database in order to speed up response times and conserve API requests. The suggested settings is 1 Day. ', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>

		<select name="<?php echo $this->get_field_name( 'cache' ); ?>" id="<?php echo $this->get_field_id( 'cache' ); ?>" class="widefat">
			<?php $options = array(
				__( 'None', 'ywp' ),
				__( '1 Hour', 'ywp' ),
				__( '3 Hours', 'ywp' ),
				__( '6 Hours', 'ywp' ),
				__( '12 Hours', 'ywp' ),
				__( '1 Day', 'ywp' ),
				__( '2 Days', 'ywp' ),
				__( '1 Week', 'ywp' )
			);
			/**
			 * Output Cache Options (set 2 Days as default for new widgets)
			 */
			foreach ( $options as $option ) {
				?>
				<option value="<?php echo $option; ?>" id="<?php echo $option; ?>" <?php if ( $cache == $option || empty( $cache ) && $option == '1 Day' ) {
					echo ' selected="selected" ';
				} ?>>
					<?php echo $option; ?>
				</option>
				<?php $counter ++;
			} ?>
		</select>


	</p>

	<!-- Clear Cache Button -->
	<p class="clearfix">
		<span class="cache-message"></span>
		<a href="#" class="button ywp-clear-cache" title="Clear" data-transient-id="<?php echo $transient; ?>">Clear Cache</a>
		<span class="cache-clearing-loading spinner"></span>
	</p>


	<!-- Disable title output checkbox -->
	<p>
		<input id="<?php echo $this->get_field_id( 'disable_title_output' ); ?>" name="<?php echo $this->get_field_name( 'disable_title_output' ); ?>" type="checkbox" value="1" <?php checked( '1', $titleOutput ); ?>/>
		<label for="<?php echo $this->get_field_id( 'disable_title_output' ); ?>"><?php _e( 'Disable Title Output', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The title output is content within the \'Widget Title\' field above. Disabling the title output may be useful for some themes.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p>

	<!-- Open Links in New Window -->
	<p>
		<input id="<?php echo $this->get_field_id( 'target_blank' ); ?>" name="<?php echo $this->get_field_name( 'target_blank' ); ?>" type="checkbox" value="1" <?php checked( '1', $targetBlank ); ?> />
		<label for="<?php echo $this->get_field_id( 'target_blank' ); ?>"><?php _e( 'Open Links in New Window', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This option will add target=\'_blank\' to the widget\'s links. This is useful to keep users on your website.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p>
	<!-- No Follow Links -->
	<p>
		<input id="<?php echo $this->get_field_id( 'no_follow' ); ?>" name="<?php echo $this->get_field_name( 'no_follow' ); ?>" type="checkbox" value="1" <?php checked( '1', $noFollow ); ?> />
		<label for="<?php echo $this->get_field_id( 'no_follow' ); ?>"><?php _e( 'No Follow Links', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This option will add rel=\'nofollow\' to the widget\'s outgoing links. This option may be useful for SEO.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p>

</div>
