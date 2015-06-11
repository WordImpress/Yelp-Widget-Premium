<?php
/**
 *  Admin options page. Creates a page to set your OAuth settings for the Yelp API v2.
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
$store_url = 'http://wordimpress.com';
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
add_action( 'admin_init', 'edd_sl_wordimpress_updater' );

function edd_sl_wordimpress_updater() {
	global $store_url, $item_name;
	$yelp_plugin_meta = get_plugin_data( YELP_WIDGET_PRO_PATH . '/' . YELP_PLUGIN_NAME . '.php', false );
	$options          = get_option( 'edd_yelp_license_key' );
	$licence_key      = ! empty( $options['license_key'] ) ? trim( $options['license_key'] ) : '';

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( $store_url, YELP_PLUGIN_NAME_PLUGIN, array(
			'version'   => $yelp_plugin_meta["Version"], // current version number
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
		__( 'Yelp Widget Pro Settings', 'ywp' ), //The Menu Title
		__( 'Yelp Widget Pro', 'ywp' ), //The Page Title
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

		// Add Support Forum link to our plugin
		$link = ywp_get_support_forum_link();
		array_unshift( $links, $link );
	}

	return $links;
}

function ywp_add_plugin_meta_links( $meta, $file ) {
	if ( $file == YELP_PLUGIN_NAME_PLUGIN ) {
		$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/yelp-widget-pro' target='_blank' title='" . __( 'Rate Yelp Widget Pro', 'ywp' ) . "'>" . __( 'Rate Plugin', 'ywp' ) . "</a>";
		$meta[] = __( 'Premium Version', 'ywp' );
	}

	return $meta;
}

function ywp_get_support_forum_link( $linkText = '' ) {
	if ( empty( $linkText ) ) {
		$linkText = __( 'Support', 'ywp' );
	}

	return '<a href="http://wordimpress.com/support/forum/yelp-widget-pro/" target="_blank" title="Get Support">' . $linkText . '</a>';
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
	register_setting( 'yelp_widget_settings', 'yelp_widget_settings' );

	//call register settings function
	add_action( 'admin_init', 'yelp_widget_options_css' );
	add_action( 'admin_init', 'yelp_widget_options_scripts' );

}


add_filter( 'plugin_row_meta', 'ywp_add_plugin_meta_links', 10, 2 );
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


// Generate the admin form
function yelp_widget_options_form() {
	?>

	<div class="wrap" xmlns="http://www.w3.org/1999/html">

		<!-- Plugin Title -->
		<div id="ywp-title-wrap">
			<div id="icon-yelp" class=""></div>
			<h2><?php _e( 'Yelp Widget Pro Settings', 'ywp' ); ?> </h2>
			<label class="label-success label">Premium Version</label>
		</div>

		<form id="yelp-settings" method="post" action="options.php">

			<div class="metabox-holder">

				<div class="postbox-container" style="width:75%">


					<div id="main-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox" id="yelp-widget-intro">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'Yelp Widget Pro Introductions', 'ywp' ); ?></span></h3>

							<div class="inside">
								<p><?php _e( 'Thanks for choosing Yelp Widget Pro! <strong>To start using Yelp Widget Pro you must have a valid Yelp API key</strong>.  Don\'t worry, it\'s <em>free</em> and very easy to get one! <strong>Having trouble?</strong> Check out the <a href="http://wordimpress.com/docs/yelp-widget-pro/#how-to-request-a-yelp-api-key" target="_blank" class="new-window">How to Request a Yelp API Key</a> screencast.', 'ywp' ); ?></p>

								<p><strong><?php _e( 'Yelp Widget Pro Activation Instructions:', 'ywp' ); ?></strong>
								</p>

								<ol>
									<li><?php _e( 'Sign into Yelp or create an account if you don\'t have one already', 'ywp' ); ?></li>
									<li><?php _e( 'Once logged in, <a href="http://www.yelp.com/developers/getting_started/api_access" target="_blank" class="new-window">sign up for API access', 'ywp' ); ?></a></li>
									<li><?php _e( 'After you have been granted an API key copy-and-paste the API v2.0 information into the appropriate fields below', 'ywp' ); ?></li>
									<li><?php _e( 'Click update to activate and begin using Yelp Widget Pro', 'ywp' ); ?></li>
								</ol>

								<p>
									<strong><?php _e( 'Like this plugin?  Give it a like on Facebook:', 'ywp' ); ?></strong>
								</p>

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

						<div class="postbox" id="api-options">

							<h3 class="hndle"><span><?php _e( 'Yelp API v2.0 Information', 'ywp' ); ?></span></h3>

							<div class="inside">
								<?php
	// Tells WordPress that the options we registered are being handled by this form
	settings_fields( 'yelp_widget_settings' );

	// Retrieve stored options, if any
	$options = get_option( 'yelp_widget_settings' );

	?>
								<div class="api-info">
									<p><?php _e( 'Yelp Widget Pro has our API Key integrated into it automatically. This allows you to show up to 3 reviews on your site at a time. Read about why we did this here.', 'ywp' ); ?></p>

									<p><?php _e( 'There is a small chance that the cumulative amount of API calls made to our integrated API Key will reach the max amount that Yelp allows. If that happens the widget will break regardless of whether you are showing reviews or not.', 'ywp' ); ?></p>

									<p><?php _e( 'To avoid that, we\'re providing you the ability to integrate your own API Key here as a backup. This means that as long as our API is working well, you\'ll be using that. But if you encounter an error that says the API daily limit has been reached, you can enable your backup key for the remainder of the day (remember, the limit Yelp imposes is daily, so you can enable our key again the next day). Keep in mind that if you created your key sometime after June 2014, you will only be able to show one review at a time, instead of 3 -- this is the limit that Yelp imposes on the API, it is restricted by the plugin at all.', 'ywp' ); ?></p>
								</div>
								<div>
									<div class="control-label">
										<label for="enable_backup_key"><?php _e( 'Enable Backup API Key?', 'ywp' ); ?>:</label>
									</div>
									<div class="controls">
										<input type="checkbox" id="enable_backup_key" name="yelp_widget_settings[enable_backup_key]" value="1" <?php
	$backupkey = empty( $options['enable_backup_key'] ) ? '' : $options['enable_backup_key'];
	checked( 1, $backupkey ); ?> />
									</div>
								</div>
								<?php
	if ( $backupkey == 1 ) {
		$display = 'style="display:block"';
	} else {
		$display = 'style="display:none"';
	}
	?>
								<div class="enable-backup" <?php echo $display; ?>>
									<div class="control-group">
										<div class="control-label">
											<label for="yelp_widget_consumer_key"><?php _e( 'Consumer Key', 'ywp' ); ?>: </label>
										</div>
										<div class="controls">
											<input type="text" id="yelp_widget_consumer_key" name="yelp_widget_settings[yelp_widget_consumer_key]" value="<?php echo yelp_widget_option( 'yelp_widget_consumer_key', $options ); ?>" />
										</div>
									</div>

									<div class="control-group">
										<div class="control-label">
											<label for="yelp_widget_consumer_secret"><?php _e( 'Consumer Secret', 'ywp' ); ?>: </label>
										</div>
										<div class="controls">
											<input type="text" id="yelp_widget_consumer_secret" name="yelp_widget_settings[yelp_widget_consumer_secret]" value="<?php echo yelp_widget_option( 'yelp_widget_consumer_secret', $options ); ?>" />
										</div>
									</div>

									<div class="control-group">
										<div class="control-label">
											<label for="yelp_widget_token"><?php _e( 'Token', 'ywp' ); ?>: </label>
										</div>
										<div class="controls">
											<input type="text" id="yelp_widget_token" name="yelp_widget_settings[yelp_widget_token]" value="<?php echo yelp_widget_option( 'yelp_widget_token', $options ); ?>" />
										</div>
									</div>

									<div class="control-group">
										<div class="control-label">
											<label for="yelp_widget_token_secret"><?php _e( 'Token Secret', 'ywp' ); ?>: </label>
										</div>
										<div class="controls">
											<input type="text" id="yelp_widget_token_secret" name="yelp_widget_settings[yelp_widget_token_secret]" value="<?php echo yelp_widget_option( 'yelp_widget_token_secret', $options ); ?>" />
										</div>
									</div>
								</div>
								<!-- end enable-backup -->
							</div>
							<!-- /.inside -->
						</div>
						<!-- /#api-settings -->

						<div class="postbox" id="yelp-widget-options">

							<h3 class="hndle"><span>Yelp Widget Pro Settings</span></h3>

							<div class="inside">
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

						<div id="yelp-widget-pro-support" class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'Need Support?', 'ywp' ); ?></span></h3>

							<div class="inside">
								<p><?php _e( 'If you have any problems with this plugin or ideas for improvements or enhancements, please use the WordImpress support forum: <a href="http://wordimpress.com/support/forum/yelp-widget-pro/" target="_blank" class="new-window">Support Forums</a>. Please note, support is prioritized for <a href="http://wordimpress.com/plugins/yelp-widget-pro/" title="Upgrade to Yelp Widget Premium" target="_blank" class="new-window">Premium Users</a>.', 'ywp' ); ?></p>
							</div>
							<!-- /.inside -->
						</div>
						<!-- /.yelp-widget-pro-support -->

					</div>
					<!-- /.sidebar-sortables -->
					<div class="wip-buttons">
						<a href="https://wordimpress.com/plugins/business-reviews-bundle/?utm_source=wp-admin&utm_medium=Bundle%20Logo&utm_term=bundle-yelp-pro&utm_campaign=bundle-yelp-pro" class="bundle-link" target="_blank"><img src="<?php echo YELP_WIDGET_PRO_URL; ?>/includes/images/bundle-banner-300x300.png" /></a>
						<a href="https://wordimpress.com/" class="wordimpress-link" target="_blank"></a>
					</div>

				</div>
				<!-- /.alignright -->
			</div>



	</div><!-- /#wrap -->

<?php
} //end yelp_widget_options_form
?>