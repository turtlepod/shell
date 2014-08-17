<?php
/**
 * Text String / Translatable string used in tamatebako
 * @since 1.0.0
 */
function tamatebako_string( $context ){

	/* Open Sesame ! */
	$text = array();

	/* Paged Title Tag
	 * Translators: 1 is the page title and separator. 2 is the page number.
	 * Example Output: "{post title} | Page {page number}"
	 */
	$text['paged'] = _x( '%1$s Page %2$s', 'paged title tag', 'shell' );

	/* Skip to content (accessibility) */
	$text['skip-to-content'] = _x( 'Skip to content', 'skip to content (accessibility)', 'shell' );

	/* Read More */
	$text['read-more'] = _x( 'Continue reading', 'content read more', 'shell' );

	/* Entry Permalink */
	$text['permalink'] = _x( 'Permalink', 'entry permalink', 'shell' );

	/* Next, Previous */
	$text['next'] = _x( 'Next', 'next in pagination and navigation (accessibility)', 'shell' );
	$text['previous'] = _x( 'Previous', 'previous in pagination and navigation (accessibility)', 'shell' );

	/* Search */
	$text['search'] = _x( 'Search&hellip;', 'search text', 'shell' );
	$text['search-button'] = _x( 'Search', 'search button (accessibility)', 'shell' );
	$text['expand-search-form'] = _x( 'Expand Search Form', 'expand search form button (accessibility)', 'shell' );

	/* Comments error */
	$text['comments-closed-pings-open'] = _x( 'Comments are closed, but trackbacks and pingbacks are open.', 'comments notice', 'shell' );
	$text['comments-closed'] = _x( 'Comments are closed.', 'comments notice', 'shell' );

	/* Content error */
	$text['error'] = _x( '404 Not Found', '404 title', 'shell' );
	$text['error-msg'] = _x( 'Apologies, but no entries were found.', '404 content', 'shell' );

	/* Theme Layout */
	$text['global-layout'] = _x( 'Global Layout', 'theme layouts', 'shell' );
	$text['layout'] = _x( 'Layout', 'theme layouts', 'shell' );

	$text = apply_filters( 'tamatebako_string', $text );

	/* Close Sesame ! */
	if ( isset( $text[$context] ) ){
		return $text[$context];
	}
	return '';
}


/**
 * Text String / Translatable string used in this theme
 * To keep track on language usage and separate from Hybrid Core.
 * @since 1.0.0
 */
function shell_string( $context ){

	/* Open Sesame ! */
	$text = array();

	/* Register Menus */
	$text['menu-primary-name'] = _x( 'Top Navigation', 'nav menu location', 'shell' );
	$text['menu-secondary-name'] = _x( 'Header Navigation', 'nav menu location', 'shell' );
	$text['menu-footer-name'] = _x( 'Footer Links', 'nav menu location', 'shell' );

	/* Register Sidebar */
	$text['sidebar-primary-name'] = _x( 'Sidebar 1', 'sidebar name', 'shell' );
	$text['sidebar-secondary-name'] = _x( 'Sidebar 2', 'sidebar name', 'shell' );

	/* Custom Content Portfolio Project Link */
	$text['ccp-view-project'] = _x( 'View Project', 'custom content portfolio link', 'shell' );

	$text = apply_filters( 'genbu_string', $text );

	/* Close Sesame ! */
	if ( isset( $text[$context] ) ){
		return $text[$context];
	}
	return '';
}
