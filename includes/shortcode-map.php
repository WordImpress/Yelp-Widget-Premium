<?php
/**
 * Class Yelp_Map_Shortcode
 *
 * @DESC   : This is the Google Maps Yelp Widget Shortcode
 * @since  : 1.5
 * @created: 5/23/13
 */

class Yelp_Map_Shortcode extends Yelp_Widget_Map {

	static function init() {
		add_shortcode( 'yelp-widget-pro-map', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts ) {
		$yelp_widget_map = new Yelp_Widget_Map();

		//Only Load scripts when widget or shortcode is active
		$yelp_widget_map->add_yelp_widget_map_frontend_scripts();

		//extract shortcode arguments
		extract( shortcode_atts( array(
			'location'    => 'San Diego',
			'search_term' => 'Restaurants',
		), $atts ) );

		$args = array();

		/*
		* Set up our Widget instance array
		*/
		//Business API

		$instance = array(
			'map_location'    => $location,
			'map_search_term' => $search_term,
		);

		//Search API
		//Using ob_start to output shortcode within content appropriately
		ob_start();
		$yelp_widget_map->widget( $args, $instance );
		$shortcode = ob_get_contents();
		ob_end_clean();

		//Output our Widget
		return $shortcode;

	}


}

Yelp_Map_Shortcode::init();