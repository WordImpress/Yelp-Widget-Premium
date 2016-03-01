<?php

/**
 * Class Yelp_Shortcode
 *
 * @description: Yelp Main Shortcode Class
 * @since      : 1.2
 * @created    : 3/20/13
 */
class Yelp_Shortcode extends Yelp_Widget {
	static function init() {
		add_shortcode( 'yelp-widget-pro', array( __CLASS__, 'handle_shortcode' ) );

	}

	static function handle_shortcode( $atts ) {
		//Only Load scripts when widget or shortcode is active
		parent::add_yelp_widget_frontend_scripts();

		//extract shortcode arguments
		extract( shortcode_atts( array(
			'location'           => 'San Diego',
			'limit'              => '4',
			'address'            => '0',
			'phone'              => '0',
			'map'                => '0',
			'map_disable_scroll' => 'false',
			'map_placement'      => 'below',
			'reviews'            => '0',
			'review_filter'      => '',
			'avatar'             => '60x60',
			'target_blank'       => '1',
			'no_follow'          => '1',
			'profile_image_size' => '',
			'align'              => 'right',
			'width'              => '250px',
			'cache'              => '1 Day',
			'hide_read_more'     => '0',
			'custom_read_more'   => 'Read Full Review'
		), $atts ) );

		$args = array();


		//Display Address if true
		$address = check_shortcode_value( $address );

		//Display Phone if true
		$phone = check_shortcode_value( $phone );

		//Display Google Map if true
		$map = check_shortcode_value( $map );

		//Display Google Map if true
		$map_disable_scroll = check_shortcode_value( $map_disable_scroll );

		//Display Reviews if true
		$reviews = check_shortcode_value( $reviews );

		//Filter Reviews if specified
		$review_filter = check_shortcode_value( $review_filter );

		//Hide More Review if specified
		$hide_read_more = check_shortcode_value( $hide_read_more );

		//Handle links opening
		$target_blank = check_shortcode_value( $target_blank );

		//Handle No Follow
		$no_follow = check_shortcode_value( $no_follow );

		/**
		 * Set up our Widget instance array
		 */
		//Business API
		if ( ! empty( $atts['id'] ) ) {

			$instance = array(
				'id'                  => $atts['id'],
				'display_option'      => '1',
				'display_address'     => empty( $address ) ? '' : $address,
				'display_phone'       => empty( $phone ) ? '' : $phone,
				'display_google_map'  => empty( $map ) ? '' : $map,
				'disable_map_scroll'  => empty( $map_disable_scroll ) ? '' : $map_disable_scroll,
				'google_map_position' => empty( $atts['map_position'] ) ? '' : $atts['map_position'],
				'target_blank'        => empty( $target_blank ) ? '' : $target_blank,
				'no_follow'           => empty( $no_follow ) ? '' : $no_follow,
				'display_reviews'     => empty( $reviews ) ? '' : $reviews,
				'review_filter'       => empty( $review_filter ) ? '' : $review_filter,
				'review_avatar_size'  => empty( $avatar ) ? '' : $avatar,
				'profile_img_size'  => empty( $profile_image_size ) ? '' : $profile_image_size,
				'align'               => empty( $align ) ? '' : $align,
				'width'               => empty( $width ) ? '' : $width,
				'cache'               => empty( $cache ) ? '' : $cache,
				'hide_read_more'      => empty( $hide_read_more ) ? '' : $hide_read_more,
				'custom_read_more'    => empty( $custom_read_more ) ? '' : $custom_read_more
			);

		} //Search API
		else if ( ! empty( $atts['term'] ) ) {

			$instance = array(
				'term'                => empty( $atts['term'] ) ? '' : $atts['term'],
				'limit'               => empty( $limit ) ? '' : $limit,
				'location'            => empty( $location ) ? '' : $location,
				'sort'                => '0',
				'target_blank'        => empty( $target_blank ) ? '' : $target_blank,
				'no_follow'           => empty( $no_follow ) ? '' : $no_follow,
				'display_option'      => '0',
				'display_address'     => empty( $address ) ? '' : $address,
				'display_phone'       => empty( $phone ) ? '' : $phone,
				'display_google_map'  => empty( $map ) ? '' : $map,
				'disable_map_scroll'  => empty( $map_disable_scroll ) ? '' : $map_disable_scroll,
				'google_map_position' => empty( $atts['map_position'] ) ? '' : $atts['map_position'],
				'profile_img_size'    => empty( $profile_image_size ) ? '' : $profile_image_size,
				'align'               => empty( $align ) ? '' : $align,
				'width'               => empty( $width ) ? '' : $width,
				'cache'               => empty( $cache ) ? '' : $cache
			);

		}

		// actual shortcode handling here
		//Using ob_start to output shortcode within content appropriatly
		ob_start();
		$shortcode_widget = new Yelp_Widget();
		$shortcode_widget->widget( $args, $instance );
		$shortcode = ob_get_contents();
		ob_end_clean();

		//Output our Widget
		return $shortcode;

	}

}

Yelp_Shortcode::init();

/*
 * Check Value
 *
 * Helper Function
 */
function check_shortcode_value( $attr ) {

	if ( $attr === "true" || $attr === "1" ) {
		$attr = "1";
	} else {
		$attr = '0';
	}

	return $attr;

}

