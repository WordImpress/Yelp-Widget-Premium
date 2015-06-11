jQuery( document ).ready( function ( $ ) {
	ywp_open_pointer( 0 );
	function ywp_open_pointer( i ) {
		pointer = wptutsPointer.pointers[i];
		options = $.extend( pointer.options, {
			close: function () {
				$.post( ajaxurl, {
					pointer: pointer.pointer_id,
					action : 'dismiss-wp-pointer'
				} );
			}
		} );

		$( pointer.target ).pointer( options ).pointer( 'open' );
	}
} );