<?php

/* Display a notice that can be dismissed */

add_action('admin_notices', 'ywp_activation_admin_notice');

function ywp_activation_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	/* Check that the user hasn't already clicked to ignore the message */
	global $pagenow;
	if ( $pagenow == 'plugins.php' ) {
		if (!get_user_meta($user_id, 'ywp_activation_ignore_notice')) { ?>
			<style>
				.updated.ywp {
					border: 0px;
					background: transparent;
					position: relative;
					padding: 0;
					margin: 0;
					overflow: hidden;
					-webkit-box-shadow: 0 0 0 0 rgba(0,0,0,0.1);
					box-shadow: 0 0 0 0 rgba(0,0,0,0.1);
					-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
					-moz-box-sizing: border-box;    /* Firefox, other Gecko */
					box-sizing: border-box;         /* Opera/IE 8+ */
					width: 100%;
				}
				.updated.ywp h3 {
					background: #BF3026;
					padding: 0 1rem 1.5rem 1rem;
					margin: 0;
					color: white;
					position: relative;
				}
				.updated.ywp h3 img {
					position: relative;
					top: 10px;

				}

				.ywp {position: relative;}

				.ywp .dismiss {
					float: right;
					position: relative;
					top: -6px;
					bottom: 0;
					right: -1rem;
					background: rgba(255,255,255,.15);
					padding: 2rem;
					color: white;
				}
				.ywp .dismiss:hover {
					color: #777;
					background: rgba(255,255,255,.5)
				}
				.ywp .dismiss:before {font-family: 'Dashicons'; content: "\f153"; display: inline-block;}
				.ywp-actions {
					display: block;
					width: 100%;
					margin: 0;
					padding: 0;
				}
				.ywp-action {
					width: 30%;
					float: left;
					margin: 0 0 -1rem 0;
				}
				.ywp-action a, .ywp-action a:hover,
				.ywp-action a:before, .ywp-action:hover a:before,
				.dashicons-edit {
					-webkit-transition: all 500ms ease-in-out;
					-moz-transition: all 500ms ease-in-out;
					-ms-transition: all 500ms ease-in-out;
					-o-transition: all 500ms ease-in-out;
					transition: all 500ms ease-in-out;
				}
				.ywp-action a,
				.ywp-action #mc_embed_signup {
					background: #ddd;
					padding: 1.5rem;
					position: relative;
					top: 0;
					bottom: 0;
					right: 0;
					left: 0;
					width: 100%;
					height: 100%;
					display: block;
					text-align: left;
					color: rgba(51,51,51,1);
				}
				.ywp-action a:hover,
				.ywp-action:hover .dashicons-edit {
					background: #cccccc;
					color: rgba(0,0,0,1);
				}
				.ywp-action a:before,
				.ywp-action .dashicons-edit {
					float: left;
					position: relative;
					top: -1.5rem;
					bottom: 0;
					left: -1.5rem;
					background: rgba(163,163,163,.15);
					padding: 1.5rem;
					font-size: 2rem;
					color: #bf3026;
					display: inline-block;
					font-family: 'Dashicons';
				}

				.ywp-action a.settings:before {content: "\f108";}
				.ywp-action a.widget:before {content: "\f111";}

				.ywp-action.mailchimp {
					margin: -12px 0 0 0;
					width: 40%;
					height: 4.1rem;
					overflow: hidden;
				}
				.ywp-action #mc_embed_signup {
					padding: 0 0 0 2rem;
				}
				.ywp-action #mc_embed_signup .mc-field-group,
				.ywp-action #mc_embed_signup .mc-field-group p {
					position: relative;
					top: 0rem;
					margin: 0;
				}

				.ywp-action #mc_embed_signup .dashicons-edit {
					top: 0;
					left: -2rem;
				}
				.ywp-action .dashicons-edit:before {
					con tent: "\f464";
					position: relative;
					top: -0.5rem;
					left: -0.25rem;
				}
			</style>
			<div class="updated ywp">
				<h3><img src="<?php echo YELP_WIDGET_PRO_URL; ?>/includes/images/yelp-logo-transparent-icon.png"  class="yelp-logo"/>Thanks for installing Yelp Widget Pro Premium!<?php printf(__('<a href="%1$s" class="dismiss"></a>'), '?ywp_nag_ignore=0'); ?></h3>
				<div class="ywp-actions">
					<div class="ywp-action"><a href="<?php echo admin_url(); ?>options-general.php?page=yelp_widget" class="settings">Go to the Yelp Reviews Pro Settings Page</a></div>

					<div class="ywp-action"><a href="<?php echo admin_url(); ?>widgets.php" class="widget">Add a Yelp Widget</a></div>

					<div class="ywp-action mailchimp">
						<div id="mc_embed_signup">
							<form action="//wordimpress.us3.list-manage.com/subscribe/post?u=3ccb75d68bda4381e2f45794c&amp;id=68d0636428" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
								<div class="mc-field-group">
									<span class="dashicons dashicons-edit"></span>
									<p><small>Get notified of plugin updates:</small></p>
									<input type="text" value="" name="b_3ccb75d68bda4381e2f45794c_68d0636428" class="required email" id="mce-EMAIL" placeholder="my.email@wordpress.com">
									<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
								</div>
								<div id="mce-responses" class="clear">
									<div class="response" id="mce-error-response" style="display:none"></div>
									<div class="response" id="mce-success-response" style="display:none"></div>
								</div>
								<div style="position: absolute; left: -5000px;">
									<input type="text" name="b_3ccb75d68bda4381e2f45794c_83609e2883" value="">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
	}
}

add_action('admin_init', 'ywp_nag_ignore');

function ywp_nag_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset($_GET['ywp_nag_ignore']) && '0' == $_GET['ywp_nag_ignore'] ) {
		add_user_meta($user_id, 'ywp_activation_ignore_notice', 'true', true);
	}
}


//function detect_plugin_activation(  $plugin ) {
//	if(	$plugin == 'yelp-widget-pro/yelp-widget-pro.php') {
//		wp_redirect("options-general.php?page=yelp_widget");
//		exit;
//	} else {}
//}
//add_action( 'activated_plugin', 'detect_plugin_activation', 10, 2 );


/**
* Adds a simple WordPress pointer to Settings menu
*/
//register_activation_hook( __FILE__, 'ywp_enqueue_pointer_script_style' );
//
//function ywp_enqueue_pointer_script_style( $hook_suffix ) {

// Assume pointer shouldn't be shown
//$enqueue_pointer_script_style = false;

// Get array list of dismissed pointers for current user and convert it to array
//$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

// Check if our pointer is not among dismissed ones
//if( !in_array( 'ywp_settings_pointer', $dismissed_pointers ) ) {
//$enqueue_pointer_script_style = true;

// Add footer scripts using callback function
//add_action( 'admin_print_footer_scripts', 'ywp_pointer_print_scripts' );
//}

// Enqueue pointer CSS and JS files, if needed
//if( $enqueue_pointer_script_style ) {
//wp_enqueue_style( 'wp-pointer' );
//wp_enqueue_script( 'wp-pointer' );
//}
//
//}
//add_action( 'admin_enqueue_scripts', 'ywp_enqueue_pointer_script_style' );
//
//function ywp_pointer_print_scripts() {
//
//$pointer_content = '<h3>' . __( 'Welcome to Yelp Widget Pro Premium', 'ywp' ) . '</h3>';
//$pointer_content .= '<p>' . __( 'Thank you for activating Yelp Widget Pro Premium. To stay up to date on the latest plugin updates, enhancements and news please sign up for our mailing list.', 'ywp' ) . '</p>';
//$pointer_content .= '<div id="mc_embed_signup" style="padding: 0 15px;"><form action="//wordimpress.us3.list-manage.com/subscribe/post?u=3ccb75d68bda4381e2f45794c&amp;id=68d0636428" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate><div class="mc-field-group" style="margin: 0 0 10px;"><input type="text" value="" name="b_3ccb75d68bda4381e2f45794c_68d0636428" class="required email" id="mce-EMAIL" style="margin-right:5px;width:230px;" placeholder="my.email@wordpress.com"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div><div id="mce-responses" class="clear"><div class="response" id="mce-error-response" style="display:none"></div><div class="response" id="mce-success-response" style="display:none"></div></div><div style="position: absolute; left: -5000px;"><input type="text" name="b_3ccb75d68bda4381e2f45794c_83609e2883" value=""></div></form></div>';
//?>

<!--<script type="text/javascript">-->
<!--	//<![CDATA[-->
<!--	jQuery(document).ready( function($) {-->
<!--		$('#menu-settings').pointer({-->
<!--			content:		'<?php //echo $pointer_content; ?>,-->
<!--			position:		{
				edge:	'left', // arrow direction
				align:	'center' // vertical alignment
			},
			pointerWidth:	350,
			close:			function() {
				$.post( ajaxurl, {
					pointer: 'ywp_settings_pointer', // pointer ID
					action: 'dismiss-wp-pointer'
				});
			}
		}).pointer('open');
	});
	//]]>
</script>-->

<?php
//}