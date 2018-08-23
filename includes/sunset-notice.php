<?php


/**
 * Activation admin notice
 */
function ywp_sunset_admin_notice() {

	global $pagenow;
	$ignored = get_transient( 'ywp_nag_ignore' );

	// Check that the user hasn't already clicked to ignore the message
	if (
		( 'plugins.php' === $pagenow && empty( $ignored ) )
		|| ( isset( $_GET['page'] ) && 'ywp' === $_GET['page'] )
	) : ?>
		<style>
			div.updated.wpbr {
				border-left: 4px solid #3498db;
				background: #FFF;
				margin: 1rem 0 2rem 0;
				-webkit-box-shadow: 0 1px 1px 1px rgba(0, 0, 0, 0.1);
				box-shadow: 0 1px 1px 1px rgba(0, 0, 0, 0.1);
				overflow: hidden;
			}

			div.updated.wpbr header {
				position: relative;
			}

			div.updated.wpbr header h2 {
				display: inline-block;
				margin: 0 0 10px;
			}

			div.updated.wpbr header img.wpbr-logo {
				max-width: 80px;
				margin: 1rem;
				position: absolute;
				top: 0;
				left: 0;
			}

			.wpbr-actions {
				margin: 20px 0 22px 120px;
				padding: 0;
				float: left;
			}

			.wpbr-actions h2 {
				font-size: 24px;
			}

			.wpbr-actions p {
				font-size: 16px;
			}

			.wpbr-action {
				float: left;
				padding: 4px 20px 0 0;
				width: auto;
			}

			/* Dismiss button */
			div.updated.wpbr a {
				outline: none;
			}

			div.updated.wpbr a.dismiss {
				display: block;
				position: absolute;
				left: auto;
				top: 10px;
				right: 0;
				color: #cacaca;
				text-align: center;
			}

			.wpbr a.dismiss:before {
				font-family: 'Dashicons';
				content: "\f153";
				font-size: 20px;
				display: inline-block;
			}

			div.updated.wpbr a.dismiss:hover {
				color: #777;
			}

			.settings_page_facebook-reviews-pro a.dismiss {
				display: none !important;
			}

			@media (max-width: 930px) {

				.wpbr-actions {
					width: auto;
				}

				.wpbr-intro-text br {
					display: none;
				}
			}

		</style>
		<div class="updated wpbr">
			<header>
				<img src="<?php echo FB_WIDGET_PRO_URL . '/assets/images/platform-icon-wpbr.png'; ?>"
					 class="wpbr-logo"/>
				<?php printf( __( '<a href="%1$s" class="dismiss"></a>', 'ywp' ), '?ywp_nag_ignore=1' ); ?>

				<div class="wpbr-actions">

					<h2>Yelp Widget Premium is now WP Business Reviews</h2>

					<p>We recently launched a brand new way to embed your business reviews in your WordPress website called <a
								href="https://wpbusinessreviews.com" target="_blank">WP Business Reviews</a>. This new product is a significant enhancement compared to Yelp Widget Premium and we're excited to offer you a <a
								href="https://wpbusinessreviews.com/freetrial18">free year</a> for being an existing customer. <strong>To consolidate our focus on making the best reviews platform available for WordPress we will be discontinuing support and updates of Yelp Widget Premium on December 31st, 2018.</strong></p>

					<p>You will continue to receive Priority Support for Yelp Widget Premium until the product's end of life on 12/31/18. However we urge all customers to
						upgrade at <em>no cost</em> to WP Business Reviews before the product's end of life. If you have any questions or need help migrating we're here
						to help!</p>

					<div class="wpbr-action">
						<a href="https://wpbusinessreviews.com/freetrial18"
						   class="button button-primary" target="_blank">
							<?php _e( 'Upgrade to WP Business Reviews', 'ywp' ); ?>
						</a>
					</div>

					<div class="wpbr-action">
						<a href="https://wpbusinessreviews.com/contact" class="button" target="_blank">Questions? Contact Support</a>
					</div>

				</div>

			</header>
		</div>
	<?php
	endif;
}


add_action( 'admin_notices', 'ywp_sunset_admin_notice' );

/**
 * Nag Ignore
 */
function ywp_nag_ignore() {

	// If user clicks to ignore the notice, add that to their user meta.
	if ( isset( $_GET['ywp_nag_ignore'] ) && '1' == $_GET['ywp_nag_ignore'] ) {
		set_transient( 'ywp_nag_ignore', '1', 48 * HOUR_IN_SECONDS );
	}
}

add_action( 'admin_init', 'ywp_nag_ignore' );
