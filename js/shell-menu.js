// Mobile Menu
jQuery( document ).ready(
	function() {
		jQuery( '.mobile-menu-button' ).click(
			function() {
				jQuery( this ).next().toggleClass( 'mobile-menu-active' );
			}
		);
	}
);
