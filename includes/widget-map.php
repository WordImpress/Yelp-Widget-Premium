<?php
/**
 * Map Widget Class
 */


/**
 * Adds Yelp Widget Pro Widget
 */
class Yelp_Widget_Map extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'yelp_widget_map', // Base ID
			'Yelp Widget Pro Google Map Search', // Name
			array( 'description' => __( 'Add a Google Map Yelp Search to your website.', 'ywp' ), ) // Args
		);

		//Only Load scripts when widget or shortcode is active
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_yelp_widget_map_frontend_scripts' ) );
		}

	}

	/**
	 *  Adds Yelp Widget Pro Google Maps Widget Scripts
	 */
	public static function add_yelp_widget_map_frontend_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/* Get our options */
		$options                     = get_option( 'yelp_widget_settings' ); // Retrieve settings array, if it exists

		// Token object built using the OAuth library
		$yelp_widget_token        = 'Z3J0Ecxir8c-Vx1_dHDlVnVFOvmWrQ5T';
		$yelp_widget_token_secret = 'qx2cpAUz6UHnAlu53tcWOdH2LNg';

		// Consumer object built using the OAuth library
		$yelp_widget_consumer_key    = 'NLzpDyRu35JeHhOzQAIHuQ';
		$yelp_widget_consumer_secret = '1eQpHwSO38jMSsI37QOjBWuroeQ';

		$cssURL   = plugins_url( '/includes/style/yelp-map-search' . $suffix . '.css', dirname( __FILE__ ) );
		$mapJSurl = plugins_url( '/includes/js/yelp-google-maps-search' . $suffix . '.js', dirname( __FILE__ ) );

		//CSS
		wp_register_style( 'yelp-map-widget-css', $cssURL );
		wp_enqueue_style( 'yelp-map-widget-css' );

		//Map jS
		wp_register_script( 'google_maps_api_ypr', 'https://maps.googleapis.com/maps/api/js?key=' . $options['yelp_widget_maps_api'], null, null, false );
		wp_enqueue_script( 'google_maps_api_ypr' );
		wp_register_script( 'yelp_widget_map_js', $mapJSurl, array( 'jquery' ) );
		wp_enqueue_script( 'yelp_widget_map_js' );

		$params = array(
			'ywpPath'           => YELP_WIDGET_PRO_PATH,
			'ywpURL'            => YELP_WIDGET_PRO_URL,
			'ywp'               => YELP_WIDGET_PRO_URL,
			'consumerKey'       => $yelp_widget_consumer_key,
			'consumerSecret'    => $yelp_widget_consumer_secret,
			'accessToken'       => $yelp_widget_token,
			'accessTokenSecret' => $yelp_widget_token_secret
		);
		wp_localize_script( 'yelp_widget_map_js', 'ywpMapParams', $params );


	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 *
	 * @return widget output html
	 */
	function widget( $args, $instance ) {

		if ( $args ) {
			extract( $args );
		}

		/* Get our options */
		$location    = $instance['map_location'];
		$search_term = ! empty( $instance['map_search_term'] ) ? $instance['map_search_term'] : 'Restaurants';
		?>

		<div id="ywp-map" class="yelp-map-wrap" data-map-location="<?php echo $location; ?>">
			<div class="top">
				<div class="poweredby"><span><?php _e( 'Powered by', 'ywp' ); ?></span>
					<a href="http://www.yelp.com"><img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/yelp-logo-transparent-icon.png' ?>" border="0" /></a>
				</div>
				<div class="searchbox">
					<form class="yelp-search-form">
						<?php _e( 'Search for', 'ywp' ); ?>
						<input type="text" class="yelp-search-term" name="term" placeholder="<?php echo $search_term; ?>" />
						<input value="Search" class="yelp-search-submit" type="submit" />
						<img class="spinner" src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/ajax-loading-1.gif' ?>" />
						<span class="error errorMessage"></span>
					</form>
				</div>
			</div>
			<div class="mapContainer">
				<div class="yelp-map"></div>
			</div>
		</div>


	<?php
	}


	/**
	 * @DESC: Saves the widget options
	 * @SEE WP_Widget::update
	 */
	function update( $new_instance, $old_instance ) {

		$instance                    = $old_instance;
		$instance['title']    		 = strip_tags( $new_instance['title'] );
		$instance['map_location']    = strip_tags( $new_instance['map_location'] );
		$instance['map_search_term'] = strip_tags( $new_instance['map_search_term'] );

		//Return Instance
		return $instance;

	}


	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 */
	function form( $instance ) {

		$title         = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$mapLocation   = empty( $instance['map_location'] ) ? 'San Diego' : esc_attr( $instance['map_location'] );
		$mapSearchTerm = empty( $instance['map_search_term'] ) ? 'Restaurants' : esc_attr( $instance['map_search_term'] );


		/**
		 * @var: Get API Option: either Search or Business
		 */
		$apiOptions = get_option( 'yelp_widget_settings' );

		include( 'widget-map-form.php' );

	} //end form function


}

/*
 * @DESC: Register Twitter Widget Pro widget
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "Yelp_Widget_Map" );' ) );