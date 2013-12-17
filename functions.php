<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package       Shell
 * @subpackage    Functions
 * @version       0.1.0
 * @since         0.1.0
 * @author        David Chandra Purnama <david@shellcreeper.com>
 * @copyright     Copyright (c) 2013, David Chandra Purnama
 * @copyright     Copyright (c) 2010 - 2013, Justin Tadlock
 * @license       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'shell_theme_setup' );


/**
 * Theme setup function.
 * This function adds support for theme features and defines the default theme actions and filters.
 *
 * @since     0.1.0
 * @access    public
 * @return    void
 */
function shell_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* === HYBRID CORE FEATURES === */

	/* Register Navigation Menus */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'secondary', 'subsidiary' ) );

	/* Register Sidebars */
	add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'secondary', 'header', 'subsidiary', 'after-singular' ) );

	/* Widgets Reloaded */
	add_theme_support( 'hybrid-core-widgets' );

	/* Template Shortcodes */
	add_theme_support( 'hybrid-core-shortcodes' );

	/* Theme Settings */
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );

	/* Better Template Hierarchy */
	add_theme_support( 'hybrid-core-template-hierarchy' );


	/* === HYBRID CORE EXTENSIONS === */

	/* Loop Pagination */
	add_theme_support( 'loop-pagination' );

	/* Get The Image */
	add_theme_support( 'get-the-image' );

	/* Breadcrumb Trail */
	add_theme_support( 'breadcrumb-trail' );

	/* Cleaner Gallery */
	add_theme_support( 'cleaner-gallery' );


	/* === WORDPRESS FEATURES === */

	/* RSS Feed */
	add_theme_support( 'automatic-feed-links' );


	/* === DEFAULT FILTERS === */

	/* Avatar size in comments: Hybrid Core - Comments Args */
	add_filter( "shell_list_comments_args", 'shell_comments_args' );


	/* === THEME UPDATER === */

	/* Updater args */
	$updater_args = array(
		'repo_uri' => 'http://repo.shellcreeper.com/',
		'repo_slug' => 'shell',
	);

	/* Add support for updater */
	add_theme_support( 'auto-hosted-theme-updater', $updater_args );
}

/* function check to enable override/disable custom background feature from child theme  */
if( ! function_exists( 'shell_custom_background' ) ){

	/* Do theme background setup on the 'after_setup_theme' hook. */
	add_action( 'after_setup_theme', 'shell_custom_background' );

	/**
	 * Add custom background theme support.
	 * Child theme can override default by creating 'shell_custom_background' function.
	 * Child theme can disable it by creating blank 'shell_custom_background' function.
	 * 
	 * @since  0.1.0
	 * @deprecated 0.2.0
	 */
	function shell_custom_background(){

		/* Custom Background */
		add_theme_support( 'custom-background', array( 'default-color' => 'f9f9f9' ) );
	}
}

/**
 * Load Updater Class And Activate Updater.
 * @since 0.1.2
 * @link http://autohosted.com/
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/theme-updater.php' );
new Shell_Theme_Updater;

/**
 * Load Template Functions.
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/template-functions.php' );

/**
 * Layouts Functions
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/layouts-functions.php' );

/**
 * Load Scripts Functions
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/scripts-functions.php' );

/**
 * Context Functions
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/context-functions.php' );

/**
 * Skins Functions
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/skins-functions.php' );

/**
 * TinyMCE and Editor Functions
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/editor-functions.php' );

/**
 * Load Deprecated Functions
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/deprecated.php' );



/**
 * Hybrid Comments Args to change avatar size.
 * 
 * @see 	Hybrid::hybrid_list_comments_args()
 * @return	array
 * @since 	0.1.1
 */
function shell_comments_args( $args ) {
	$args['avatar_size'] = 40;
	return $args;
}



/**
 * Shell After Setup Theme
 * This hook is loaded after all functions in Shell is loaded.
 * You can use this hook to "un-hook" shell setup functions.
 * 
 * @since 0.2.0
 */
do_action( 'shell_after_setup_theme' ); ?>