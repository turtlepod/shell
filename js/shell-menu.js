// Mobile Menu
jQuery( document ).ready( function($) {

	/**
	 * Add sub-menu indicator
	 * ----------------------------------
	 * compatibility with previous version of superfish drop down
	 */
	$( '.menu-item-has-children' ).children('a').append( '<span class="sf-sub-indicator"> »</span>' );


	/**
	 * Mobile menu toggle
	 * ----------------------------------
	 * This is for device with width < 480px
	 * menu display is controlled by stylesheet
	 */
	$( '.mobile-menu-button' ).click( function(e) {
		e.preventDefault();

		/* Close/deactivate other menu, only one menu should be open */
		$( this ).parent().siblings(".menu-container").find( ".mobile-menu-button-active" ).removeClass("mobile-menu-button-active");
		$( this ).parent().siblings(".menu-container").find( ".wrap-menu-active" ).removeClass("wrap-menu-active");

		/* Add active class to current menu, to display via css */
		$( this ).next().toggleClass( 'wrap-menu-active' );

		/* Add active class to toggle button to style */
		$( this ).toggleClass( 'mobile-menu-button-active' );
	});


	/**
	 * Mobile Sub Navigation toggle
	 * ----------------------------------
	 * it's only active in mobile device
	 */
	if ( $( "html" ).hasClass("shell-is-mobile") ) {

		/* sub-menu indicator only to first level parent menu item, remove from other level */
		$( '.sub-menu .sf-sub-indicator' ).remove();

		/* Add sub-menu toggle class for easy styling */
		$( ".sf-sub-indicator" ).addClass( "sub-menu-toggle");

		/* sub-menu-toggle click event */
		$( '.sub-menu-toggle' ).unbind('click').click( function(e) {
			e.preventDefault();

			/* Hide other active sub-menu in the same menu */
			$( this ).parent().parent().siblings( ".parent-menu-active" ).removeClass("parent-menu-active");

			/* Hide other active sub-menu in other menu  */
			$( this ).parents( ".menu-container" ).siblings( ".menu-container" ).find(".parent-menu-active").removeClass("parent-menu-active");

			/* Open the current sub-menu */
			$( this ).parent().parent( ".menu-item-has-children" ).toggleClass( "parent-menu-active" );
		});
	}
});

