<?php
/**
 * Deprecated functions that should be avoided in favor of newer functions. Also handles removed 
 * functions to avoid errors. Developers should not use these functions in their parent themes and users 
 * should not use these functions in their child themes.  The functions below will all be removed at some 
 * point in a future release.  If your theme is using one of these, you should use the listed alternative or 
 * remove it from your theme if necessary.
 *
 * @package    Shell
 * @subpackage Functions
 * @since      0.2.0
 * @author     David Chandra Purnama <david@shellcreeper.com>
 * @copyright  Copyright (c) 2013, David Chandra Purnama
 * @link       http://themehybrid.com/themes/shell
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * @since 0.1.0
 * @deprecated 0.2.0
 */
function shell_tinymce_3(){
	_deprecated_function( __FUNCTION__, '0.2.0' );
	return;
}

/**
 * @since 0.1.0
 * @deprecated 0.2.0
 */
function shell_tinymce_style_select(){
	_deprecated_function( __FUNCTION__, '0.2.0' );
	return;
}


/**
 * @since 0.1.0
 * @deprecated 0.2.0 use shell_mobile_menu_primary() and shell_mobile_menu_secondary()
 */
function shell_mobile_menu(){
	_deprecated_function( __FUNCTION__, '0.2.0', 'shell_mobile_menu_primary() or shell_mobile_menu_secondary()' );?>
<div class="mobile-menu-button" title="navigation">
	<span><?php _ex( 'Navigation', 'mobile-menu', 'shell' ); ?></span>
</div><?php
}


/**
 * @since 0.1.0
 * @deprecated 0.2.0
 */
function shell_theme_layout_meta_box(){
	_deprecated_function( __FUNCTION__, '0.2.0' );
	return;
}

/**
 * @since 0.1.0
 * @deprecated 0.2.0
 */
function shell_breadcrumb_trail_args( $args ){
	_deprecated_function( __FUNCTION__, '0.2.0' );
	return $args;
}

/**
 * @since 0.1.0
 * @deprecated 0.2.2
 */
function shell_tinymce_2( $buttons, $id ){

	_deprecated_function( __FUNCTION__, '0.2.3' );

	/* only add this for content editor */
	if ( 'content' != $id )
		return $buttons;

	/* add horizontal button after indent button */
	array_splice( $buttons, 11, 0, 'hr' );

	return $buttons;
}

/**
 * @since 0.2.1
 * @deprecated 0.2.2
 */
function shell_get_menu_location( $location ){

	_deprecated_function( __FUNCTION__, '0.2.3', 'shell_get_menu_name()' );

	if ( has_nav_menu( $location ) ){
		$locations = get_nav_menu_locations();
		if( ! isset( $locations[$location] ) ) return false;
		$menu_obj = get_term( $locations[$location], 'nav_menu' );
		return $menu_obj->name;
	}
}
