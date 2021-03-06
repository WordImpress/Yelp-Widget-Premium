<?php
/**
 * Yelp Settings Admin Options Page
 */

register_activation_hook( __FILE__, 'yelp_widget_activate' );
register_uninstall_hook( __FILE__, 'yelp_widget_uninstall' );
add_action( 'admin_init', 'yelp_widget_init' );
add_action( 'admin_menu', 'yelp_widget_add_options_page' );

include_once( YELP_WIDGET_PRO_PATH . '/licence/licence.php' );

// Include Licensing
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include_once( YELP_WIDGET_PRO_PATH . '/licence/classes/EDD_SL_Plugin_Updater.php' );
}


global $ywplicencing, $store_url, $item_name, $yelp_plugin_meta;
$store_url = 'https://wordimpress.com';
$item_name = 'Yelp Widget Pro';

//Licence Args
$licence_args = array(
	'plugin_basename'     => YELP_PLUGIN_NAME_PLUGIN, //Name of License Option in DB
	'store_url'           => $store_url, //URL of license API
	'item_name'           => $item_name, //Name of License Option in DB
	'settings_page'       => 'settings_page_yelp_widget', //Name of License Option in DB
	'licence_key_setting' => 'ywp_licence_setting', //Name of License Option in DB
	'licence_key_option'  => 'edd_yelp_license_key', //Name of License Option in DB
	'licence_key_status'  => 'edd_yelp_license_status', //Name of License Option in DB
);

$ywplicencing = new Yelp_Widget_Pro_Licensing( $licence_args );


/**
 * Licensing
 */
add_action( 'admin_init', 'yelp_sl_wordimpress_updater' );
function yelp_sl_wordimpress_updater() {
	global $store_url, $item_name;
	$yelp_plugin_meta = get_plugin_data( YELP_WIDGET_PRO_PATH . '/' . YELP_PLUGIN_NAME . '.php', false );
	$options          = get_option( 'edd_yelp_license_key' );
	$licence_key      = ! empty( $options['license_key'] ) ? trim( $options['license_key'] ) : '';

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( $store_url, YELP_PLUGIN_NAME_PLUGIN, array(
			'version'   => $yelp_plugin_meta['Version'], // current version number
			'license'   => $licence_key, // license key (used get_option above to retrieve from DB)
			'item_name' => $item_name, // name of this plugin
			'author'    => 'Devin Walker' // author of this plugin
		)
	);

}


// Delete options when uninstalled
function yelp_widget_uninstall() {
	delete_option( 'yelp_widget_settings' );
	delete_option( 'yelp_widget_consumer_key' );
	delete_option( 'yelp_widget_consumer_secret' );
	delete_option( 'yelp_widget_token' );
	delete_option( 'yelp_widget_token_secret' );
}

// Run function when plugin is activated
function yelp_widget_activate() {
	$options = get_option( 'yelp_widget_settings' );
}

//Yelp Options Page
function yelp_widget_add_options_page() {
	// Add the menu option under Settings, shows up as "Yelp API Settings" (second param)
	$page = add_submenu_page( 'options-general.php', //The parent page of this menu
		__( 'Yelp Widget Pro Settings', 'ywp' ), //The Page Title
		__( 'Yelp Reviews', 'ywp' ), //The Menu Title
		'manage_options', // The capability required for access to this item
		'yelp_widget', // the slug to use for the page in the URL
		'yelp_widget_options_form' ); // The function to call to render the page

	/* Using registered $page handle to hook script load */
	add_action( 'admin_print_scripts-' . $page, 'yelp_options_scripts' );


}

//Add Yelp Widget Pro option scripts to admin head - will only be loaded on plugin options page
function yelp_options_scripts() {

	//register admin JS
	wp_enqueue_script( 'yelp_widget_options_js', plugins_url( 'includes/js/options.js', dirname( __FILE__ ) ) );

	if ( SCRIPT_DEBUG == true ) {
		//register our stylesheet
		wp_register_style( 'yelp_widget_options_css', plugins_url( 'includes/style/options.css', dirname( __FILE__ ) ) );
		// It will be called only on plugin admin page, enqueue our stylesheet here
		wp_enqueue_style( 'yelp_widget_options_css' );
	} else {
		//register our stylesheet
		wp_register_style( 'yelp_widget_options_css_min', plugins_url( 'includes/style/options.min.css', dirname( __FILE__ ) ) );
		// It will be called only on plugin admin page, enqueue our stylesheet here
		wp_enqueue_style( 'yelp_widget_options_css_min' );
	}
}

/**
 * Load Widget JS Script ONLY on Widget page
 */
function yelp_widget_scripts( $hook ) {
	if ( $hook == 'widgets.php' ) {
		wp_enqueue_script( 'yelp_widget_admin_scripts', plugins_url( 'includes/js/admin-widget.js', dirname( __FILE__ ) ) );
		wp_enqueue_style( 'yelp_widget_admin_css', plugins_url( 'includes/style/admin-widget.css', dirname( __FILE__ ) ) );
	} else {
		return;
	}
}

add_action( 'admin_enqueue_scripts', 'yelp_widget_scripts' );

/**
 * Add links to Plugin listings view
 *
 * @param $links
 *
 * @return mixed
 */
function ywp_add_plugin_page_links( $links, $file ) {
	if ( $file == YELP_PLUGIN_NAME_PLUGIN ) {
		// Add Widget Page link to our plugin
		$link = ywp_get_options_link();
		array_unshift( $links, $link );

	}

	return $links;
}

function ywp_get_options_link( $linkText = '' ) {
	if ( empty( $linkText ) ) {
		$linkText = __( 'Settings', 'ywp' );
	}

	return '<a href="options-general.php?page=yelp_widget">' . $linkText . '</a>';
}


/**
 * Initiate the Yelp Widget
 */
function yelp_widget_init( $file ) {
	// Register the yelp_widget settings as a group
	register_setting( 'yelp_widget_settings', 'yelp_widget_settings', array( 'sanitize_callback' => 'yelp_widget_clean' ) );

	//call register settings function
	add_action( 'admin_init', 'yelp_widget_options_css' );
	add_action( 'admin_init', 'yelp_widget_options_scripts' );

}


add_filter( 'plugin_action_links', 'ywp_add_plugin_page_links', 10, 2 );

// Output the yelp_widget option setting value
function yelp_widget_option( $setting, $options ) {
	$value = "";
	// If the old setting is set, output that
	if ( get_option( $setting ) != '' ) {
		$value = get_option( $setting );
	} elseif ( is_array( $options ) ) {
		$value = $options[ $setting ];
	}

	return $value;

}

/**
 * Recursively sanitizes a given value.
 *
 * @since 2.0.0
 *
 * @param string|array $value Value to be sanitized.
 * @return string|array Array of clean values or single clean value.
 */
function yelp_widget_clean( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'yelp_widget_clean', $value );
	} else {
		return is_scalar( $value ) ? sanitize_text_field( $value ) : '';
	}
}

// Generate the admin form
function yelp_widget_options_form() {
	?>

	<div class="wrap" xmlns="http://www.w3.org/1999/html">

		<!-- Plugin Title -->
		<div id="ywp-title-wrap">
			<div id="icon-yelp" class=""></div>
			<h1><?php _e( 'Yelp Widget Pro Settings', 'ywp' ); ?> </h1>
			<label class="label-success label">Premium Version</label>
		</div>

		<form id="yelp-settings" method="post" action="options.php">

			<?php
			// Tells WordPress that the options we registered are being handled by this form
			settings_fields( 'yelp_widget_settings' );

			// Retrieve stored options, if any
			$options = get_option( 'yelp_widget_settings' );

			?>

			<div class="metabox-holder">

				<div class="postbox-container" style="width:75%">


					<div id="main-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox" id="yelp-widget-intro">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'Yelp Widget Pro Introduction', 'ywp' ); ?></span></h3>

							<div class="inside">
								<h3><?php _e( 'Thanks for choosing Yelp Widget Pro!', 'ywp' ); ?></h3>
								<p>
									<strong><?php _e( 'To get started, follow the steps below:', 'ywp' ); ?></strong>
								</p>

								<ol>

									<li><?php _e( 'First, <a href="https://www.yelp.com/developers/v3/manage_app" target="_blank" rel="noopener noreferrer">create your own Yelp app</a>. The app is required to access your reviews.', 'ywp' ); ?></li>
									<li><?php _e( 'Once you\'ve created the app, copy the API Key from the <a href="https://www.yelp.com/developers/v3/manage_app" target="_blank" rel="noopener noreferrer">My App</a> page. Save it in the Yelp API Key field below.', 'ywp' ); ?></li>
									<li><?php _e( 'To optionally display maps alongside your reviews, follow the docs to <a href="https://wordimpress.com/documentation/yelp-widget-pro/create-maps-api-key/" target="_blank" rel="noopener noreferrer">create your own Google Maps API Key</a>.', 'ywp' ); ?></li>
									<li><?php _e( 'Activate your plugin license in the sidebar to the right. Check out our <a href="https://wordimpress.com/frequent-customer-questions/" target="_blank" rel="noopener noreferrer">FAQ</a> if you have questions about that.', 'ywp' ); ?></li>
									<li><?php _e( 'Learn the difference between <a href="https://wordimpress.com/documentation/yelp-widget-pro/search-business-request-methods-expalined/" target="_blank" rel="noopener noreferrer">Search and Business display methods</a>.', 'ywp' ); ?></a></li>
									<li><?php _e( 'Head over to your Widgets area, or read about how to use <a href="https://wordimpress.com/documentation/yelp-widget-pro/shortcode-explanation-and-usage/" target="_blank" rel="noopener noreferrer">the Shortcode</a> to integrate your Yelp Reviews now.', 'ywp' ); ?></li>
								</ol>

								<div class="social-items-wrap">

									<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FWordImpress%2F353658958080509&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=220596284639969" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>

									<a href="https://twitter.com/wordimpress" class="twitter-follow-button" data-show-count="false">Follow @wordimpress</a>
									<script>!function ( d, s, id ) {
											var js, fjs = d.getElementsByTagName( s )[0], p = /^http:/.test( d.location ) ? 'http' : 'https';
											if ( !d.getElementById( id ) ) {
												js = d.createElement( s );
												js.id = id;
												js.src = p + '://platform.twitter.com/widgets.js';
												fjs.parentNode.insertBefore( js, fjs );
											}
										}( document, 'script', 'twitter-wjs' );</script>
									<div class="google-plus">
										<!-- Place this tag where you want the +1 button to render. -->
										<div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://plus.google.com/117062083910623146392"></div>


										<!-- Place this tag after the last +1 button tag. -->
										<script type="text/javascript">
											(function () {
												var po = document.createElement( 'script' );
												po.type = 'text/javascript';
												po.async = true;
												po.src = 'https://apis.google.com/js/plusone.js';
												var s = document.getElementsByTagName( 'script' )[0];
												s.parentNode.insertBefore( po, s );
											})();
										</script>
									</div>
									<!--/.google-plus -->
								</div>
								<!--/.social-items-wrap -->

							</div>
							<!-- /.inside -->
						</div>
						<!-- /#yelp-widget-intro -->

						<div class="postbox" id="yelp-widget-options">

							<h3 class="hndle"><span>Yelp Widget Pro Settings</span></h3>

							<div class="inside">
									<div class="control-group">
									<div class="control-label">
										<label for="yelp_widget_fusion_api">Yelp API Key:<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is necessary to get reviews from Yelp.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
									</div>
									<div class="controls">
										<?php $ywpFusionAPI = empty( $options['yelp_widget_fusion_api'] ) ? '' : $options['yelp_widget_fusion_api']; ?>
										<p><input type="text" id="yelp_widget_fusion_api" name="yelp_widget_settings[yelp_widget_fusion_api]" value="<?php echo $ywpFusionAPI; ?>" size="45"/><br />
										<small><a href="https://www.yelp.com/developers/v3/manage_app" target="_blank" rel="noopener noreferrer">Get a Yelp API Key by creating your own Yelp App</a></small></p>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label for="yelp_widget_maps_api">Google Maps API Key:<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is necessary to embed Google Maps in your widgets.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
									</div>
									<div class="controls">
										<?php $ywpMapsAPI = empty( $options['yelp_widget_maps_api'] ) ? '' : $options['yelp_widget_maps_api']; ?>
										<p><input type="text" id="yelp_widget_maps_api" name="yelp_widget_settings[yelp_widget_maps_api]" value="<?php echo $ywpMapsAPI; ?>" size="45"/><br />
										<small><a href="https://wordimpress.com/documentation/yelp-widget-pro/create-maps-api-key/" target="_blank" rel="noopener noreferrer">Read our doc on creating your Google Maps API Key here</a></small></p>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label for="yelp_widget_disable_css">Disable Plugin CSS Output:<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Disabling the widget\'s CSS output is useful for more complete control over customizing the widget styles. Helpful for integration into custom theme designs.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
									</div>
									<div class="controls">
										<input type="checkbox" id="yelp_widget_disable_css" name="yelp_widget_settings[yelp_widget_disable_css]" value="1" <?php
										$cssOption = empty( $options['yelp_widget_disable_css'] ) ? '' : $options['yelp_widget_disable_css'];
										checked( 1, $cssOption ); ?> />
									</div>
								</div>
								<!--/.control-group -->

							</div>
							<!-- /.inside -->
						</div>
						<!-- /#yelp-widget-options -->

						<div class="control-group">
							<div class="controls">
								<input class="button-primary" type="submit" name="submit-button" value="<?php _e( 'Update', 'ywp' ); ?>" />
							</div>
						</div>

						<!-- /.metabox-holder -->
		</form>

	</div>
	<!-- /#main-sortables -->
	</div>
	<!-- /.postbox-container -->
	<div class="alignright" style="width:24%">
		<div id="sidebar-sortables" class="meta-box-sortables ui-sortable">

			<div id="yelp-licence" class="postbox">
				<?php
				/**
				 * Output Licensing Fields
				 */
				global $ywplicencing;
				if ( class_exists( 'Yelp_Widget_Pro_Licensing' ) ) {
					$ywplicencing->edd_wordimpress_license_page();
				}
				?>
			</div>

		</div>
		<!-- /.sidebar-sortables -->
	</div>
	<!-- /.alignright -->
	</div>


	</div><!-- /#wrap -->

	<?php
} //end yelp_widget_options_form
?>