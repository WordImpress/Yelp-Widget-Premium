<?php
/*
Plugin Name: Yelp Widget Pro Premium
Plugin URI: http://wordimpress.com/wordpress-plugin-development/yelp-widget-pro/
Description: Easily display Yelp business ratings with a simple and intuitive WordPress widget.
Version: 1.8.4
Author: Devin Walker
Author URI: http://imdev.in/
License: GPLv2
*/

define( 'YELP_PLUGIN_NAME', 'yelp-widget-pro' );
define( 'YELP_PLUGIN_NAME_PLUGIN', plugin_basename( __FILE__ ) );
define( 'YELP_WIDGET_PRO_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'YELP_WIDGET_PRO_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );
define( 'YELP_WIDGET_DEBUG', false );

/**
 * Localize the Plugin for Other Languages
 */
load_plugin_textdomain( 'ywp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Adds Yelp Widget Pro Options Page
 */
require_once( dirname( __FILE__ ) . '/includes/options.php' );

if ( ! class_exists( 'YWPOAuthToken', false ) ) {
	require_once( dirname( __FILE__ ) . '/lib/oauth.php' );
}


/**
 * Logic to check for updated version of Yelp Widget Pro Premium
 * if the user has a valid license key and email
 */
$options = get_option( 'yelp_widget_settings' );
$theme   = wp_get_theme();
if ( isset( $options['yelp_widget_premium_license_status'] ) && $options['yelp_widget_premium_license_status'] == "1" || $theme["Name"] == 'Delicias' ) {

}


/**
 * Debug function.
 *
 * returns handy data
 *
 * @since: 1.5.7
 *
 * @param $what
 */
function ywp_debug_view( $what ) {
	if ( YELP_WIDGET_DEBUG == true ) {
		echo '<pre>';
		if ( is_array( $what ) ) {
			print_r( $what );
		} else {
			var_dump( $what );
		}
		echo '</pre>';
	}
}


/*
 * Get the Widget and Shortcode
 */
if ( ! class_exists( 'Yelp_Widget' ) ) {
	require 'includes/widget-main.php';
	require 'includes/widget-map.php';
	require 'includes/shortcode-main.php';
	require 'includes/shortcode-map.php';
}
