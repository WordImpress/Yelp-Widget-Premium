<?php
/*
 *  @description: Widget form options in WP-Admin
 *  @since 1.2.0
 *  @created: 03/10/13
 */
?>

<!-- Title -->
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title', 'ywp' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p>


<h4 class="yelp-toggler"><?php _e( 'Display Options:', 'ywp' ); ?><span></span></h4>

<div class="display-options toggle-item">

	<!-- Map Location -->
	<p>
		<label for="<?php echo $this->get_field_id( 'map_location' ); ?>"><?php _e( 'Map Location:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The location this map will search. You can either use a full address or city name.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'map_location' ); ?>" name="<?php echo $this->get_field_name( 'map_location' ); ?>" type="text" value="<?php echo $mapLocation; ?>" />
	</p>

	<!-- Map Search Term -->
	<p>
		<label for="<?php echo $this->get_field_id( 'map_search_term' ); ?>"><?php _e( 'Default Search Term:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is the default search term that appears in the search input.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'map_search_term' ); ?>" name="<?php echo $this->get_field_name( 'map_search_term' ); ?>" type="text" value="<?php echo $mapSearchTerm; ?>" />
	</p>


</div>

<p>
	<a href="http://wordimpress.com/tag/yelp-widget-pro/" target="_blank" class="new-window"><?php _e( 'Plugin Documentation', 'ywp' ); ?></a><br /><a href="https://wordimpress.com/support/" target="_blank" class="new-window"><?php _e( 'Priority Support', 'ywp' ); ?></a>
</p>