// Mobile Menu
jQuery( document ).ready(
	function() {
		jQuery( '.mobile-menu-button' ).click(
			function() {
				jQuery( this ).next().toggleClass( 'wrap-menu-active' );
				jQuery( this ).toggleClass( 'mobile-menu-button-active' );
			}
		);
	}
);