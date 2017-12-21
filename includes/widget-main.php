<?php

/**
 * Yelp_Widget
 * @description: Main Yelp widget class that adds Yelp Widget Pro Widget
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

		add_action( 'wp_ajax_clear_widget_cache', array( $this, 'ywp_clear_widget_cache' ) );

	}


	/**
	 * AJAX Clear Widget Cache
	 */
	// Same handler function...

	function ywp_clear_widget_cache() {

		if ( isset( $_POST['transient_id'] ) ) {

			delete_transient( $_POST['transient_id'] );
			echo "Cache cleared";

		} else {
			echo "Error: Transient ID not set. Cache not cleared.";
		}

		die();

	}

	//Load Widget JS Script ONLY on Widget page
	function yelp_widget_scripts( $hook ) {
		if ( $hook == 'widgets.php' ) {

			if ( SCRIPT_DEBUG == true ) {

				wp_enqueue_script( 'yelp_widget_admin_scripts', plugins_url( 'includes/js/admin-widget.js', dirname( __FILE__ ) ) );
				wp_enqueue_style( 'yelp_widget_admin_css', plugins_url( 'includes/style/admin-widget.css', dirname( __FILE__ ) ) );

			} else {

				wp_enqueue_script( 'yelp_widget_admin_scripts', plugins_url( 'includes/js/admin-widget.min.js', dirname( __FILE__ ) ) );
				wp_enqueue_style( 'yelp_widget_admin_css', plugins_url( 'includes/style/admin-widget.min.css', dirname( __FILE__ ) ) );

			}

		} else {
			return;
		}
	}


	/**
	 * Adds Yelp Widget Pro Scripts
	 */

	public static function add_yelp_widget_frontend_scripts() {

		$settings = get_option( 'yelp_widget_settings' );
		$suffix   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$params = array(
			'ywpPath' => YELP_WIDGET_PRO_PATH,
			'ywpURL'  => YELP_WIDGET_PRO_URL,
		);

		// Register the Maps API for conditional use in the Widget
		wp_register_script( 'google_maps_api_ypr', 'https://maps.googleapis.com/maps/api/js?key=' . $settings['yelp_widget_maps_api'], null, null, false );

		//Yelp Widget Pro JS
		$mapJSurl = plugins_url( '/includes/js/yelp-google-maps' . $suffix . '.js', dirname( __FILE__ ) );

		wp_register_script( 'yelp_widget_js', $mapJSurl, array( 'jquery' ) );
		wp_enqueue_script( 'yelp_widget_js' );
		wp_localize_script( 'yelp_widget_js', 'ywpParams', $params );


		//Yelp Widget Pro CSS
		if ( ! isset( $settings["yelp_widget_disable_css"] ) || $settings["yelp_widget_disable_css"] == 0 ) {

			//Determine which version of the CSS to dish out
			$cssURL = plugins_url( '/includes/style/yelp' . $suffix . '.css', dirname( __FILE__ ) );

			//Register and enqueue the styles
			wp_register_style( 'yelp-widget-css', $cssURL );
			wp_enqueue_style( 'yelp-widget-css' );

		}

	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 *
	 * @return  output html
	 */
	public function widget( $args, $instance ) {

		$yelp = new Yelp_Widget();

		extract( $args );

		// Get plugin options.
		$options = get_option( 'yelp_widget_settings' );

		// As of v2.0.0, the Yelp API transitioned from v2 to v3. To ensure upgraded plugins continue to function,
		// a backup API key has been included below. It is still highly recommended that each user set up their
		// own Yelp app and use their own API key.
		$fusion_api_key = ! empty( $options['yelp_widget_fusion_api'] ) ? $options['yelp_widget_fusion_api'] : 'u6iiKEMVJzF8hpqAaxajY-pf0bWxltr4etYBs6jo6HDpgZHQErXP8JkIGWA2ISKI2HUE9-E-3MBiYK14YXCq3fZmGPKFFjPVouU4HhQONe4AlEIct9MTVf97ZOs5WnYx';
		$maps_api_key   = isset( $options['yelp_widget_maps_api'] ) ? $options['yelp_widget_maps_api'] : '';

		// Get widget options.
		$title             = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$displayOption     = ! isset( $instance['display_option'] ) ? 0 : $instance['display_option'];
		$term              = empty( $instance['term'] ) ? '' : $instance['term'];
		$id                = empty( $instance['id'] ) ? '' : $instance['id'];
		$location          = empty( $instance['location'] ) ? '' : $instance['location'];
		$address           = empty( $instance['display_address'] ) ? '' : $instance['display_address'];
		$phone             = empty( $instance['display_phone'] ) ? '' : $instance['display_phone'];
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

		// If cache option is enabled, attempt to get response from transient.
		if ( strtolower( $cache ) != 'none' ) {

			$transient = urlencode( $displayOption . $term . $id . $location . $limit . $sort . $displayGoogleMap . $reviewsImgSize . $reviewsOption );

			$response = get_transient( $transient );

			// Check for an existing copy of our cached/transient data
			if ( $response === false ) {
				// It wasn't there, so regenerate the data and save the transient

				//Get Time to Cache Data
				$expiration = $cache;

				//Assign Time to appropriate Math
				switch ( $expiration ) {
					case '1 Hour':
						$expiration = 3600;
						break;
					case '3 Hours':
						$expiration = 3600 * 3;
						break;
					case '6 Hours':
						$expiration = 3600 * 6;
						break;
					case '12 Hours':
						$expiration = 60 * 60 * 12;
						break;
					case '1 Day':
						$expiration = 60 * 60 * 24;
						break;
					case '2 Days':
						$expiration = 60 * 60 * 48;
						break;
					case '1 Week':
						$expiration = 60 * 60 * 168;
						break;
				}

				// Cache data wasn't there, so regenerate the data and save the transient
				if ( $displayOption == '1' ) {
					$response = yelp_widget_fusion_get_business( $fusion_api_key, $id, $reviewsOption );
				} else {
					$response = yelp_widget_fusion_search( $fusion_api_key, $term, $location, $limit, $sort );
				}

				set_transient( $transient, $response, $expiration );
			}

		} else {
			// No Cache option enabled.
			if ( $displayOption == '1' ) {
				// Widget is in Business mode.
				$response = yelp_widget_fusion_get_business( $fusion_api_key, $id, $reviewsOption );
			} else {
				// Widget is in Search mode.
				$response = yelp_widget_fusion_search( $fusion_api_key, $term, $location, $limit, $sort );
			}
		}

		//Load Google Maps API only if option to disable is NOT set
		if ( $instance['display_google_map'] ) {
			wp_enqueue_script( 'google_maps_api_ypr' );
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
	 * Update Widget
	 *
	 * @description: Saves the widget options
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']                 = strip_tags( $new_instance['title'] );
		$instance['display_option']        = strip_tags( $new_instance['display_option'] );
		$instance['term']                  = strip_tags( $new_instance['term'] );
		$instance['id']                    = strip_tags( $new_instance['id'] );
		$instance['location']              = strip_tags( $new_instance['location'] );
		$instance['display_address']       = ! empty( $new_instance['display_address'] ) ? strip_tags( $new_instance['display_address'] ) : '';
		$instance['display_phone']         = ! empty( $new_instance['display_phone'] ) ? strip_tags( $new_instance['display_phone'] ) : '';
		$instance['disable_business_info'] = ! empty( $new_instance['disable_business_info'] ) ? strip_tags( $new_instance['disable_business_info'] ) : '';
		$instance['limit']                 = strip_tags( $new_instance['limit'] );
		$instance['profile_img_size']      = strip_tags( $new_instance['profile_img_size'] );
		$instance['sort']                  = strip_tags( $new_instance['sort'] );
		$instance['display_reviews']       = ! empty( $new_instance['display_reviews'] ) ? strip_tags( $new_instance['display_reviews'] ) : '';
		$instance['review_filter']         = strip_tags( $new_instance['review_filter'] );
		$instance['hide_rating']           = ! empty( $new_instance['hide_rating'] ) ? strip_tags( $new_instance['hide_rating'] ) : '';
		$instance['hide_read_more']        = ! empty( $new_instance['hide_read_more'] ) ? strip_tags( $new_instance['hide_read_more'] ) : '';
		$instance['custom_read_more']      = strip_tags( $new_instance['custom_read_more'] );
		$instance['review_avatar_size']    = strip_tags( $new_instance['review_avatar_size'] );
		$instance['display_google_map']    = ! empty( $new_instance['display_google_map'] ) ? strip_tags( $new_instance['display_google_map'] ) : '';
		$instance['google_map_position']   = strip_tags( $new_instance['google_map_position'] );
		$instance['disable_map_scroll']    = ! empty( $new_instance['disable_map_scroll'] ) ? strip_tags( $new_instance['disable_map_scroll'] ) : '';
		$instance['disable_title_output']  = ! empty( $new_instance['disable_title_output'] ) ? strip_tags( $new_instance['disable_title_output'] ) : '';
		$instance['target_blank']          = ! empty( $new_instance['target_blank'] ) ? strip_tags( $new_instance['target_blank'] ) : '';
		$instance['no_follow']             = ! empty( $new_instance['no_follow'] ) ? strip_tags( $new_instance['no_follow'] ) : '';
		$instance['cache']                 = strip_tags( $new_instance['cache'] );


		return $instance;
	}


	/**
	 * Widget Form
	 *
	 * Back-end widget form
	 *
	 * @param array $instance
	 *
	 * @return string
	 */
	function form( $instance ) {

		$title             = ! isset( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$displayOption     = ! isset( $instance['display_option'] ) ? 0 : esc_attr( $instance['display_option'] );
		$term              = ! isset( $instance['term'] ) ? '' : esc_attr( $instance['term'] );
		$id                = ! isset( $instance['id'] ) ? '' : esc_attr( $instance['id'] );
		$location          = ! isset( $instance['location'] ) ? '' : esc_attr( $instance['location'] );
		$address           = ! isset( $instance['display_address'] ) ? '' : esc_attr( $instance['display_address'] );
		$phone             = ! isset( $instance['display_phone'] ) ? '' : esc_attr( $instance['display_phone'] );
		$displayBizInfo    = ! isset( $instance['disable_business_info'] ) ? '' : esc_attr( $instance['disable_business_info'] );
		$limit             = ! isset( $instance['limit'] ) ? '' : esc_attr( $instance['limit'] );
		$profileImgSize    = ! isset( $instance['profile_img_size'] ) ? '' : esc_attr( $instance['profile_img_size'] );
		$sort              = ! isset( $instance['sort'] ) ? '' : esc_attr( $instance['sort'] );
		$reviewsOption     = ! isset( $instance['display_reviews'] ) ? '' : esc_attr( $instance['display_reviews'] );
		$reviewFilter      = ! isset( $instance['review_filter'] ) ? '' : esc_attr( $instance['review_filter'] );
		$hideRating        = ! isset( $instance['hide_rating'] ) ? '' : esc_attr( $instance['hide_rating'] );
		$hideReadMore      = ! isset( $instance['hide_read_more'] ) ? '' : esc_attr( $instance['hide_read_more'] );
		$customReadMore    = ! isset( $instance['custom_read_more'] ) ? '' : esc_attr( $instance['custom_read_more'] );
		$reviewsImgSize    = ! isset( $instance['review_avatar_size'] ) ? '' : esc_attr( $instance['review_avatar_size'] );
		$displayGoogleMap  = ! isset( $instance['display_google_map'] ) ? '' : esc_attr( $instance['display_google_map'] );
		$googleMapPosition = ! isset( $instance['google_map_position'] ) ? '' : esc_attr( $instance['google_map_position'] );
		$disableMapScroll  = ! isset( $instance['disable_map_scroll'] ) ? '' : esc_attr( $instance['disable_map_scroll'] );
		$titleOutput       = ! isset( $instance['disable_title_output'] ) ? '' : esc_attr( $instance['disable_title_output'] );
		$targetBlank       = ! isset( $instance['target_blank'] ) ? '' : esc_attr( $instance['target_blank'] );
		$noFollow          = ! isset( $instance['no_follow'] ) ? '' : esc_attr( $instance['no_follow'] );
		$cache             = ! isset( $instance['cache'] ) ? '' : esc_attr( $instance['cache'] );
		$transient         = urlencode( $displayOption . $term . $id . $location . $limit . $sort . $displayGoogleMap . $reviewsImgSize . $reviewsOption );


		/**
		 * @var: Get API Option: either Search or Business
		 */
		$apiOptions = get_option( 'yelp_widget_settings' );

		//Verify that the API values have been inputed prior to output

		if ( ( ! empty( $apiOptions["enable_backup_key"] ) ) && ( empty( $apiOptions["yelp_widget_consumer_key"] ) || empty( $apiOptions["yelp_widget_consumer_secret"] ) || empty( $apiOptions["yelp_widget_token"] ) || empty( $apiOptions["yelp_widget_token_secret"] ) ) ) {
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
		if ( ! empty( $choice ) && $choice == 'size' ) {
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
		if ( $response->error->code == 'EXCEEDED_REQS' ) {
			$output .= __( 'The default Yelp API has exhausted its daily limit. Please enable your own API Key in your Yelp Widget Pro settings.', 'ywp' );
		} elseif ( $response->error->code == 'BUSINESS_UNAVAILABLE' ) {
			$output .= __( '<strong>Error:</strong> Business information is unavailable. Either you mistyped the Yelp biz ID or the business does not have any reviews.', 'ywp' );
		} elseif ( $response->error->code == 'TOKEN_MISSING' ) {
			$output .= sprintf(
				__( '%1$sSetup Required:%2$s Enter a Yelp Fusion API Key in the %3$splugin settings screen.%4$s', 'ywp' ),
				'<strong>',
				'</strong>',
				'<a href="' . YWP_SETTINGS_URL . '">',
				'</a>'
			);
		} //output standard error
		else {
			if ( ! empty( $response->error->code ) ) {
				$output .= $response->error->code . ": ";
			}
			if ( ! empty( $response->error->description ) ) {
				$output .= $response->error->description;
			}
		}
		$output .= '</div>';

		echo $output;

	}

}

add_action( 'widgets_init', create_function( '', 'register_widget( "Yelp_Widget" );' ) );

/**
 * Yelp Widget cURL
 *
 * @description: CURLs the Yelp API with our url parameters and returns JSON response
 *
 * @param $signed_url
 *
 * @return array|bool|mixed|object
 */
function yelp_widget_curl( $signed_url ) {

	// Send Yelp API Call using WP's HTTP API
	$data = wp_remote_get( $signed_url );

	// make sure the response came back okay
	if ( is_wp_error( $data ) ) {
		return false;
	}

	//Use curl only if necessary
	if ( empty( $data['body'] ) ) {
		$ch = curl_init( $signed_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		$data = curl_exec( $ch ); // Yelp response
		curl_close( $ch );
		$data     = yelp_update_http_for_ssl( $data );
		$response = json_decode( $data );
	} else {
		$data     = yelp_update_http_for_ssl( $data );
		$response = json_decode( $data['body'] );
	}

	// Handle Yelp response data
	return apply_filters( 'yelp_api_response', $response );

}

/**
 * Retrieves search results based on a search term and location.
 *
 * @since 2.0.0
 *
 * @param string $key      Yelp Fusion API Key.
 * @param string $term     The search term, usually a business name.
 * @param string $location The location within which to search.
 * @param string $limit    Number of businesses to return.
 * @param string $sort_by  Optional. Sort the results by one of the these modes:
 *                         best_match, rating, review_count or distance. Defaults to best_match.
 * @return array Associative array containing the response body.
 */
function yelp_widget_fusion_search( $key, $term, $location, $limit, $sort_by ) {
	switch ( $sort_by ) {
		case '0':
			$sort_by = 'best_match';
			break;
		case '1':
			$sort_by = 'distance';
			break;
		case '2':
			$sort_by = 'rating';
			break;
		default:
			$sort_by = 'best_match';
	}

	$url = add_query_arg(
		array(
			'term'     => $term,
			'location' => $location,
			'limit'    => $limit,
			'sort_by'  => $sort_by,
		),
		'https://api.yelp.com/v3/businesses/search'
	);

	$args = array(
		'user-agent'     => '',
		'headers' => array(
			'authorization' => 'Bearer ' . $key,
		),
	);

	$response = yelp_widget_fusion_get( $url, $args );

	return $response;
}

/**
 * Retrieves business details based on Yelp business ID.
 *
 * @since 2.0.0
 *
 * @param string $key             Yelp Fusion API Key.
 * @param string $id              The Yelp business ID.
 * @param int    $reviews_option  1 if reviews should be displayed. 0 otherwise.
 * @return array Associative array containing the response body.
 */
function yelp_widget_fusion_get_business( $key, $id, $reviews_option = 0 ) {
	$url = 'https://api.yelp.com/v3/businesses/' . $id;

	$args = array(
		'user-agent'     => '',
		'headers' => array(
			'authorization' => 'Bearer ' . $key,
		),
	);

	$response = yelp_widget_fusion_get( $url, $args );

	if ( $reviews_option ) {
		$reviews_response = yelp_fusion_get_reviews( $key, $id );

		if ( ! empty( $reviews_response ) and isset( $reviews_response->reviews[0] ) ) {
			$response->reviews = $reviews_response->reviews;
		}
	}

	return $response;
}

/**
 * Retrieves reviews based on Yelp business ID.
 *
 * @since 2.0.0
 *
 * @param string $key Yelp Fusion API Key.
 * @param string $id  The Yelp business ID.
 * @return array Associative array containing the response body.
 */
function yelp_fusion_get_reviews( $key, $id ) {
	$url = 'https://api.yelp.com/v3/businesses/' . $id . '/reviews';

	$args = array(
		'user-agent'     => '',
		'headers' => array(
			'authorization' => 'Bearer ' . $key,
		),
	);

	$response = yelp_widget_fusion_get( $url, $args );

	return $response;
}

/**
 * Retrieves a response from a safe HTTP request using the GET method.
 *
 * @since 2.0.0
 *
 * @see wp_safe_remote_get()
 *
 * @return array Associative array containing the response body.
 */
function yelp_widget_fusion_get( $url, $args = array() ) {
	$response = wp_safe_remote_get( $url, $args );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$body = json_decode( $response['body'] );


	$response = yelp_update_http_for_ssl( $response );
	$response = json_decode( $response['body'] );

	/**
	 * Filters the Yelp Fusion API response.
	 *
	 * @since 2.0.0
	 */
	return apply_filters( 'yelp_fusion_api_response', $response );
}

/**
 * Generates a star image based on numerical rating.
 *
 * @since 2.0.0
 *
 * @param int|float $rating Numerical rating between 0 and 5 in increments of 0.5.
 * @return string Responsive image element.
 */
function yelp_widget_fusion_stars( $rating = 0 ) {
	$ext          = '.png';
	$floor_rating = floor( $rating );

	if ( $rating != $floor_rating ) {
		$image_name = $floor_rating . '_half';
	} else {
		$image_name = $floor_rating;
	}

	$uri_image_name = YELP_WIDGET_PRO_URL . '/includes/images/stars/regular_' . $image_name;
	$single         = $uri_image_name . $ext;
	$double         = $uri_image_name . '@2x' . $ext;
	$triple         = $uri_image_name . '@3x' . $ext;
	$srcset         = "{$single}, {$double} 2x, {$triple} 3x";
	$decimal_rating = number_format( $rating, 1, '.', '' );

	echo '<img class="rating" srcset="' . esc_attr( $srcset ) . '" src="' . esc_attr( $single ) . '" title="' . $decimal_rating . ' star rating" alt="' . $decimal_rating . ' star rating">';
}

/**
 * Displays responsive Yelp logo.
 *
 * @since 2.0.0
 *
 * @return string Responsive image element.
 */
function yelp_widget_fusion_logo() {
	$image_name     = 'yelp-widget-logo';
	$ext            = '.png';
	$uri_image_name = YELP_WIDGET_PRO_URL . '/includes/images/' . $image_name;
	$single         = $uri_image_name . $ext;
	$double         = $uri_image_name . '@2x' . $ext;
	$srcset         = "{$single}, {$double} 2x";

	echo '<img class="ywp-logo" srcset="' . esc_attr( $srcset ) . '" src="' . esc_attr( $single ) . '" alt="Yelp logo">';
}

/**
 * Function update http for SSL
 *
 * @param $data
 *
 * @return mixed
 */
function yelp_update_http_for_ssl( $data ) {

	if ( ! empty( $data['body'] ) && is_ssl() ) {
		$data['body'] = str_replace( 'http:', 'https:', $data['body'] );
	} elseif ( is_ssl() ) {
		$data = str_replace( 'http:', 'https:', $data );
	}
	$data = str_replace( 'http:', 'https:', $data );

	return $data;

}