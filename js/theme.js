jQuery( document ).ready( function($) {

	/* === FitVids === */
	$('#content,.entry-content,.entry-summary,.widget').fitVids();

	/* === Accessibility === */

	/* == Menu Toggle == */
	$( '.menu-dropdown' ).find( 'a' ).on( 'focus blur', function() {
		$( this ).parents().toggleClass( 'focus' );
	} );

	/* == Focus input element on Hash "#" change == */
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
					element.tabIndex = -1;

				element.focus();
			}
		}, false );
	}

	/* === Menu Search === */

	/* == Search Toggle == */
	$( ".search-toggle" ).click( function(e) {
		e.preventDefault();
		$( this ).parents( ".menu-search" ).toggleClass( "search-toggle-active" );
		$( this ).siblings( ".search-field" ).focus();
	});

	/* == Display search form on search pages == */
	if ( $("body").hasClass("search") ){
		$( ".search-toggle" ).parents( ".menu-search" ).addClass( "search-toggle-active" )
	}

	/* === Menu Toggle === */

	/* == Mobile submenu toggle on mobile device == */
	if ( $("body").hasClass("wp-is-mobile") ){
		$("body").addClass("mobile-menu-active");
	}
	if ( $("body").hasClass("mobile-menu-active") ){
		$( ".menu-container .menu-item-has-children" ).each( function () {

			/* if this parent menu item have sub-menu available */
			if ( $(this).children( "ul" ).length > 0 ){

				/* Toggle class to open .sub-menu */
				$(this).children( "a" ).click( function(e) {
					e.preventDefault();
					$( this ).parent("li").siblings("li").removeClass( "menu-item-open-children" );
					$( this ).parent("li").toggleClass( "menu-item-open-children" );

					/* Get menu link, and add it as first children */
					if ( !$(this).parent("li").children( ".sub-menu" ).children( "li" ).hasClass("menu-item-parent-link") ){
						/* Only if not linked to "#" */
						if ( $(this).attr("href") != "#" ){
							$(this).parent("li").children( ".sub-menu" ).prepend( '<li class="menu-item menu-item-parent-link">' + $(this).parent("li").html() + '</li>' );
						}
					}
					/* Remove sub menu from this */
					$( ".menu-item-parent-link" ).children( ".sub-menu" ).remove();
				});
			}
		});
	}

	/* == Mobile menu toggle (small screen) == */
	$( ".menu-toggle a" ).click( function(e) {
		e.preventDefault();
		$( this ).parents(".menu-container").toggleClass( "menu-toggle-active" );
	});

});

