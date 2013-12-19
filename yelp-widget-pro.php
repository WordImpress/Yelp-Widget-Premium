<?php
/*
Plugin Name: Yelp Widget Pro Premium
Plugin URI: http://wordimpress.com/wordpress-plugin-development/yelp-widget-pro/
Description: Easily display Yelp business ratings with a simple and intuitive WordPress widget.
Version: 1.6
Author: Devin Walker
Author URI: http://imdev.in/
License: GPLv2
*/


define('YELP_PLUGIN_NAME', 'yelp-widget-pro');
define('YELP_PLUGIN_NAME_PLUGIN', plugin_basename( __FILE__ ));
define('YELP_WIDGET_PRO_PATH', WP_PLUGIN_DIR . '/' . YELP_PLUGIN_NAME);
define('YELP_WIDGET_PRO_URL', WP_PLUGIN_URL . '/' . YELP_PLUGIN_NAME);
define('YELP_WIDGET_DEBUG', true);

/**
 * Localize the Plugin for Other Languages
 */
load_plugin_textdomain('ywp', false, dirname(plugin_basename(__FILE__)) . '/languages/');


/**
 * Adds Yelp Widget Pro Options Page
 */
require_once (dirname(__FILE__) . '/includes/options.php');

if (!class_exists('OAuthToken', false)) {
    require_once (dirname(__FILE__) . '/lib/oauth.php');
}


/**
 * WordImpress Licencing
 */
require_once(dirname(__FILE__) . '/licence/licence.php');
//Licence Args
$licence_args = array(

	'version'                       => '1.6', //Base URL for Website container WooCommerce API
	'wordimpress_api_base'          => 'http://wordimpress.com/', //Base URL for Website container WooCommerce API
	'wordimpress_user_account_page' => 'http://wordimpress.com/my-account/', //used to query API
	'product_id'                    => 'Yelp Widget Pro', //name of product; used to target specific product in WooCommerce
	'settings_page'                 => 'yelp_widget', //used to enqueue JS only for that page
	'settings_options'              => get_option( 'yelp_widget_settings' ), //plugin options settings
	'transient_timeout'             => 60 * 60 * 12, //used to perform plugin update checks
	'textdomain'                    => 'ywp', //used for translations
	'pluginbase'                    => YELP_PLUGIN_NAME_PLUGIN, //used for updates API
);

$licencing = new WordImpress_Licensing( $licence_args );


/**
 * Logic to check for updated version of Yelp Widget Pro Premium
 * if the user has a valid license key and email
 */
$options = get_option('yelp_widget_settings');
$theme = wp_get_theme();
if (isset($options['yelp_widget_premium_license_status']) && $options['yelp_widget_premium_license_status'] == "1" || $theme["Name"] == 'Delicias') {

    /*
     * Adds the Premium Plugin updater
     * @see: https://github.com/YahnisElsts/wp-update-server
     */
    require 'lib/plugin-updates/plugin-update-checker.php';
    $updateChecker = PucFactory::buildUpdateChecker(
    'http://wordimpress.com/wp-update-server/?action=get_metadata&slug=yelp-widget-pro', //Metadata URL.
    __FILE__, //Full path to the main plugin file.
    'yelp-widget-pro' //Plugin slug. Usually it's the same as the name of the directory.
    );

    /* ... Code that initializes the update checker ... */
    //Add the license key to query arguments.
    $updateChecker->addQueryArgFilter('wsh_filter_update_checks');
    function wsh_filter_update_checks($queryArgs) {
        $options = get_option('yelp_widget_settings');
        if ( !empty($options['yelp_widget_premium_license']) ) {
            $queryArgs['license_key'] = $options['yelp_widget_premium_license'];
        }
        return $queryArgs;
    }

}

/**
 * Debug function.
 *
 * returns handy data
 *
 * @since: 1.5.7
 * @param $what
 */
function ywp_debug_view($what) {
    if(YELP_WIDGET_DEBUG == true) {
        echo '<pre>';
            if ( is_array( $what ) )  {
                print_r ( $what );
            } else {
                var_dump ( $what );
            }
            echo '</pre>';
    }
}


/*
 * Get the Widget and Shortcode
 */
if (!class_exists('Yelp_Widget')) {
    require 'includes/widget-main.php';
    require 'includes/widget-map.php';
    require 'includes/shortcode-main.php';
    require 'includes/shortcode-map.php';
}
