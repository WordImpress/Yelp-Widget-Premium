<?php
/**
 * widget-main.php
 * @Description: Main Yelp widget class
 */


/**
 * Adds Yelp Widget Pro Widget
 */

class Yelp_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'yelp_widget', // Base ID
			'Yelp Widget Pro', // Name
			array( 'description' => __( 'Display Yelp business ratings and reviews on your website.', 'ywp' ), ) // Args
		);

		//Only Load scripts when widget or shortcode is active
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_yelp_widget_frontend_scripts' ) );
		}

	}


	/**
	 * Adds Yelp Widget Pro Scripts
	 */

	public function add_yelp_widget_frontend_scripts() {

		$params = array(
			'ywpPath' => YELP_WIDGET_PRO_PATH,
			'ywpURL'  => YELP_WIDGET_PRO_URL,
		);

		//Load Google Maps API
		wp_enqueue_script(
			'google_maps_api',
			'https://maps.googleapis.com/maps/api/js?sensor=false'
		);

		wp_localize_script( 'google_maps_api', 'ywpParams', $params );


		$mapJSurl = plugins_url( '/includes/js/yelp-google-maps.min.js', dirname( __FILE__ ) );

		wp_register_script( 'yelp-widget-js', $mapJSurl, array( 'jquery' ) );
		wp_enqueue_script( 'yelp-widget-js' );

		//Yelp Widget Pro JS

		//Yelp Widget Pro CSS
		$cssOption = get_option( 'yelp_widget_settings' );
		if ( ! isset( $cssOption["yelp_widget_disable_css"] ) || $cssOption["yelp_widget_disable_css"] == 0 ) {

			$cssURL = plugins_url( '/includes/style/yelp.min.css', dirname( __FILE__ ) );
			wp_register_style( 'yelp-widget-css', $cssURL );
			wp_enqueue_style( 'yelp-widget-css' );

		}


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

		$yelp = new Yelp_Widget();

		extract( $args );

		/* Get our options */
		$options = get_option( 'yelp_widget_settings' ); // Retrieve settings array, if it exists

		// Base unsigned URL
		$unsigned_url = "http://api.yelp.com/v2/";

		// Token object built using the OAuth library
		$yelp_widget_token        = $options['yelp_widget_token'];
		$yelp_widget_token_secret = $options['yelp_widget_token_secret'];

		$token = new OAuthToken( $yelp_widget_token, $yelp_widget_token_secret );

		// Consumer object built using the OAuth library
		$yelp_widget_consumer_key    = $options['yelp_widget_consumer_key'];
		$yelp_widget_consumer_secret = $options['yelp_widget_consumer_secret'];

		$consumer = new OAuthConsumer( $yelp_widget_consumer_key, $yelp_widget_consumer_secret );

		// Yelp uses HMAC SHA1 encoding
		$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

		//Yelp Widget Options
		$title             = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$displayOption     = empty( $instance['display_option'] ) ? '' : $instance['display_option'];
		$term              = empty( $instance['term'] ) ? '' : $instance['term'];
		$id                = empty( $instance['id'] ) ? '' : $instance['id'];
		$location          = empty( $instance['location'] ) ? '' : $instance['location'];
		$address           = empty( $instance['display_address'] ) ? '' : $instance['display_address'];
		$displayBizInfo    = empty( $instance['disable_business_info'] ) ? '' : $instance['disable_business_info'];
		$limit             = empty( $instance['limit'] ) ? '' : $instance['limit'];
		$profileImgSize    = empty( $instance['profile_img_size'] ) ? '' : $instance['profile_img_size'];
		$sort              = empty( $instance['sort'] ) ? '0' : $instance['sort'];
		$reviewsOption     = empty( $instance['display_reviews'] ) ? '' : $instance['display_reviews'];
		$reviewFilter      = empty( $instance['review_filter'] ) ? '' : $instance['review_filter'];
		$hideRating        = empty( $instance['hide_rating'] ) ? '' : $instance['hide_rating'];
		$hideReadMore      = empty( $instance['hide_read_more'] ) ? '' : $instance['hide_read_more'];
		$customReadMore    = empty( $instance['custom_read_more'] ) ? '' : $instance['custom_read_more'];
		$reviewsImgSize    = empty( $instance['review_avatar_size'] ) ? '' : $instance['review_avatar_size'];
		$displayGoogleMap  = empty( $instance['display_google_map'] ) ? '' : $instance['display_google_map'];
		$googleMapPosition = empty( $instance['google_map_position'] ) ? '' : $instance['google_map_position'];
		$disableMapScroll  = empty( $instance['disable_map_scroll'] ) ? '' : $instance['disable_map_scroll'];
		$titleOutput       = empty( $instance['disable_title_output'] ) ? '' : $instance['disable_title_output'];
		$targetBlank       = empty( $instance['target_blank'] ) ? '' : $instance['target_blank'];
		$noFollow          = empty( $instance['no_follow'] ) ? '' : $instance['no_follow'];
		$cache             = empty( $instance['cache'] ) ? '' : $instance['cache'];
		$align             = empty( $instance['align'] ) ? '' : $instance['align'];
		$width             = empty( $instance['width'] ) ? '' : $instance['width'];

		//Build URL Parameters
		$urlparams = array(
			'term'     => $term,
			'id'       => $id,
			'location' => $location,
			'limit'    => $limit,
			'sort'     => $sort
		);


		// Use appropriate API depending on API Request Method option
		if ( $displayOption == '1' ) {
			$urlparams['method'] = 'business/' . $urlparams['id'];
			unset( $urlparams['term'], $urlparams['location'], $urlparams['id'], $urlparams['sort'] );
		} else {
			$urlparams['method'] = 'search';
			unset( $urlparams['id'] );
		}


		// Set method
		$unsigned_url = $unsigned_url . $urlparams['method'];

		unset( $urlparams['method'] );

		// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
		$oauthrequest = OAuthRequest::from_consumer_and_token( $consumer, $token, 'GET', $unsigned_url, $urlparams );

		// Sign the request
		$oauthrequest->sign_request( $signature_method, $consumer, $token );

		// Get the signed URL
		$signed_url = $oauthrequest->to_url();

		ywp_debug_view( $signed_url );


		// Cache: cache option is enabled
		if ( $cache != 'None' ) {
			$transient = $displayOption . $term . $id . $location . $limit . $sort;

			// Check for an existing copy of our cached/transient data
			if ( ( $response = get_transient( $transient ) ) == false ) {

				//Get Time to Cache Data
				$expiration = $cache;

				//Assign Time to appropriate Math
				switch ( $expiration ) {

					case "1 Hour":
						$expiration = 3600;
						break;
					case "3 Hours":
						$expiration = 3600 * 3;
						break;
					case "6 Hours":
						$expiration = 3600 * 6;
						break;
					case "12 Hours":
						$expiration = 60 * 60 * 12;
						break;
					case "1 Day":
						$expiration = 60 * 60 * 24;
						break;
					case "2 Days":
						$expiration = 60 * 60 * 48;
						break;
					case "1 Week":
						$expiration = 60 * 60 * 168;
						break;
				}

				// Cache data wasn't there, so regenerate the data and save the transient
				$response = yelp_widget_curl( $signed_url );
				set_transient( $transient, $response, $expiration );


			}


		} else {

			//No Cache option enabled;
			$response = yelp_widget_curl( $signed_url );

		}

		/*
		 * Output Yelp Widget Pro
		 */

		// Instantiate output var
		$output = '';

		//Widget Output
		echo empty( $before_widget ) ? '' : $before_widget;

		// if the title is set & the user hasn't disabled title output
		if ( $title && $titleOutput != 1 ) {
			echo $before_title . $title . $after_title;
		}

		//check for business response
		if ( isset( $response->businesses ) ) {
			$businesses = $response->businesses;
		} else {
			$businesses = array( $response );
		}

		if ( isset( $response->error ) ) {

			$output .= $yelp->handle_yelp_api_error( $response );

		} else {
			if ( ! isset( $businesses[0] ) ) {

				$output .= '<div class="yelp-error">' . __( 'No results', 'ywp' ) . '</div>';

			} //API Returned valid results; no errors
			else {


				// Open link in new window if set
				if ( $targetBlank == '1' ) {
					$targetBlank = 'target="_blank" ';
				} else {
					$targetBlank = '';
				}

				// Add nofollow relation if set
				if ( $noFollow == '1' ) {
					$noFollow = 'rel="nofollow" ';
				} else {
					$noFollow = '';
				}


				//Used for Google Maps
				$jsonArray = json_encode( $response );

				//Display Appropriate View per API selection
				if ( $displayOption == "1" ) {

					$output .= include( 'template-parts/business.php' );

				} else {

					$output .= include( 'template-parts/search.php' );

				}

			}
		}

		echo empty( $after_widget ) ? '' : $after_widget;

		//Output Widget Contents
		return $output;

	}


	/**
	 * @DESC: Saves the widget options
	 * @SEE WP_Widget::update
	 */
	function update( $new_instance, $old_instance ) {
		$instance                          = $old_instance;
		$instance['title']                 = strip_tags( $new_instance['title'] );
		$instance['display_option']        = strip_tags( $new_instance['display_option'] );
		$instance['term']                  = strip_tags( $new_instance['term'] );
		$instance['id']                    = strip_tags( $new_instance['id'] );
		$instance['location']              = strip_tags( $new_instance['location'] );
		$instance['display_address']       = strip_tags( $new_instance['display_address'] );
		$instance['disable_business_info'] = strip_tags( $new_instance['disable_business_info'] );
		$instance['limit']                 = strip_tags( $new_instance['limit'] );
		$instance['profile_img_size']      = strip_tags( $new_instance['profile_img_size'] );
		$instance['sort']                  = strip_tags( $new_instance['sort'] );
		$instance['display_reviews']       = strip_tags( $new_instance['display_reviews'] );
		$instance['review_filter']         = strip_tags( $new_instance['review_filter'] );
		$instance['hide_rating']           = strip_tags( $new_instance['hide_rating'] );
		$instance['hide_read_more']        = strip_tags( $new_instance['hide_read_more'] );
		$instance['custom_read_more']      = strip_tags( $new_instance['custom_read_more'] );
		$instance['review_avatar_size']    = strip_tags( $new_instance['review_avatar_size'] );
		$instance['display_google_map']    = strip_tags( $new_instance['display_google_map'] );
		$instance['google_map_position']   = strip_tags( $new_instance['google_map_position'] );
		$instance['disable_map_scroll']    = strip_tags( $new_instance['disable_map_scroll'] );
		$instance['disable_title_output']  = strip_tags( $new_instance['disable_title_output'] );
		$instance['target_blank']          = strip_tags( $new_instance['target_blank'] );
		$instance['no_follow']             = strip_tags( $new_instance['no_follow'] );
		$instance['cache']                 = strip_tags( $new_instance['cache'] );

		return $instance;
	}


	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 */
	function form( $instance ) {

		$title             = esc_attr( $instance['title'] );
		$displayOption     = esc_attr( $instance['display_option'] );
		$term              = esc_attr( $instance['term'] );
		$id                = esc_attr( $instance['id'] );
		$location          = esc_attr( $instance['location'] );
		$address           = esc_attr( $instance['display_address'] );
		$displayBizInfo    = esc_attr( $instance['disable_business_info'] );
		$limit             = esc_attr( $instance['limit'] );
		$profileImgSize    = esc_attr( $instance['profile_img_size'] );
		$sort              = esc_attr( $instance['sort'] );
		$reviewsOption     = esc_attr( $instance['display_reviews'] );
		$reviewFilter      = esc_attr( $instance['review_filter'] );
		$hideRating        = esc_attr( $instance['hide_rating'] );
		$hideReadMore      = esc_attr( $instance['hide_read_more'] );
		$customReadMore    = esc_attr( $instance['custom_read_more'] );
		$reviewsImgSize    = esc_attr( $instance['review_avatar_size'] );
		$displayGoogleMap  = esc_attr( $instance['display_google_map'] );
		$googleMapPosition = esc_attr( $instance['google_map_position'] );
		$disableMapScroll  = esc_attr( $instance['disable_map_scroll'] );
		$titleOutput       = esc_attr( $instance['disable_title_output'] );
		$targetBlank       = esc_attr( $instance['target_blank'] );
		$noFollow          = esc_attr( $instance['no_follow'] );
		$cache             = esc_attr( $instance['cache'] );

		/**
		 * @var: Get API Option: either Search or Business
		 */
		$apiOptions = get_option( 'yelp_widget_settings' );

		//Verify that the API values have been inputed prior to output
		if ( empty( $apiOptions["yelp_widget_consumer_key"] ) || empty( $apiOptions["yelp_widget_consumer_secret"] ) || empty( $apiOptions["yelp_widget_token"] ) || empty( $apiOptions["yelp_widget_token_secret"] ) ) {
			//the user has not properly configured plugin so display a warning
			?>
			<div class="alert alert-red"><?php _e( 'Please input your Yelp API information in the <a href="options-general.php?page=yelp_widget">plugin settings</a> page prior to enabling Yelp Widget Pro.', 'ywp' ); ?></div>
		<?php
		} //The user has properly inputted Yelp API info so output widget form so output the widget contents
		else {

			include( 'widget-main-form.php' );

		} //endif check for Yelp API key inputs

	} //end form function

	/**
	 *  display_biz_address
	 *
	 * @description: Displays the business address from Yelp
	 * @since      1.2.0
	 * @created    : 03/06/13
	 */
	public static function display_biz_address( $address ) {
		$x      = 0;
		$output = '<div class="yelp-address-wrap"><address>';
		//Itterate through Address Array
		foreach ( $address as $addressItem ) {

			$output .= $addressItem . "<br/>";
		}
		$output .= '<address></div>';

		return $output;

	}

	/**
	 *  ywp_profile_image_size
	 *
	 * @description:
	 * @since      1.2.0
	 * @created    : 03/06/13
	 */
	public static function ywp_profile_image_size( $profileImgSize, $choice ) {
		if ( $choice == 'size' ) {
			//Set profile image size
			switch ( $profileImgSize ) {
				case '40x40':
					$output = "width='40' height='40'";
					break;
				case '60x60':
					$output = "width='60' height='60'";
					break;
				case '80x80':
					$output = "width='80' height='80'";
					break;
				case '100x100':
					$output = "width='100' height='100'";
					break;
				default:
					$output = "width='60' height='60'";
			}
		} else {
			//Set profile image size
			switch ( $profileImgSize ) {

				case '40x40':
					$output = "ywp-size-40";
					break;
				case '60x60':
					$output = "ywp-size-60";
					break;
				case '80x80':
					$output = "ywp-size-80";
					break;
				case '100x100':
					$output = "ywp-size-100";
					break;
				default:
					$output = "ywp-size-60";
			}
		}

		return $output;

	}

	/*
	 * Handle Yelp Error Messages
	 */
	public function handle_yelp_api_error( $response ) {

		$output = '<div class="yelp-error">';
		if ( $response->error->id == 'EXCEEDED_REQS' ) {
			$output .= __( 'Yelp API is exhausted (Contact Yelp to increase your API call limit)', 'ywp' );
		} elseif ( $response->error->id == 'BUSINESS_UNAVAILABLE' ) {
			$output .= __( '<strong>Error:</strong> Business information is unavailable. Either you mistyped the Yelp biz ID or the business does not have any reviews.', 'ywp' );
		} //output standard error
		else {
			if ( ! empty( $response->error->id ) ) {
				$output .= $response->error->id . ": ";
			}
			if ( ! empty( $response->error->field ) ) {
				$output .= $response->error->field . " - ";
			}
			$output .= $response->error->text;
		}
		$output .= '</div>';

		echo $output;

	}

}

/*
 * @DESC: Register Twitter Widget Pro widget
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "Yelp_Widget" );' ) );

/**
 * @DESC: CURLs the Yelp API with our url parameters and returns JSON response
 */
function yelp_widget_curl( $signed_url ) {

	// Send Yelp API Call using WP's HTTP API
	$data = wp_remote_get( $signed_url );

	//Use curl only if necessary
	if ( empty( $data['body'] ) ) {

		$ch = curl_init( $signed_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		$data = curl_exec( $ch ); // Yelp response
		curl_close( $ch );
		$response = json_decode( $data );

	} else {
		$response = json_decode( $data['body'] );
	}

	// Handle Yelp response data
	return $response;

}