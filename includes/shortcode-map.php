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

		//Only Load scripts when widget or shortcode is active
		parent::add_yelp_widget_map_frontend_scripts();


		//extract shortcode arguments
		extract( shortcode_atts( array(
			'location' => 'San Diego',

		), $atts ) );


		$args = array();

		/*
		* Set up our Widget instance array
		*/
		//Business API
		if ( ! empty( $atts['location'] ) ) {

			$instance = array(
				'map_location' => $atts['location'],
			);

		}


		//Search API
		//Using ob_start to output shortcode within content appropriatly
		ob_start();
		parent::widget( $args, $instance );
		$shortcode = ob_get_contents();
		ob_end_clean();

		//Output our Widget
		return $shortcode;

	}


}

Yelp_Map_Shortcode::init();