<?php
/**
 * Dynamic Context and Classes Functions
 * All functions here is a related to handle dynamic context and classes.
 *
 * @package     Shell
 * @subpackage  Includes
 * @since       0.2.0
 * @author      David Chandra Purnama <david@shellcreeper.com>
 * @copyright   Copyright (c) 2013, David Chandra Purnama
 * @link        http://themehybrid.com/themes/shell
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'shell_theme_context_setup' );


/**
 * Theme Skin setup function.
 * This function handle actions and filters related to theme skin.
 *
 * @since     0.2.0
 * @access    public
 * @return    void
 */
function shell_theme_context_setup() {

	/* Additional Body class */
	add_filter( 'body_class','shell_body_class');

	/* Additional css classes for widgets */
	add_filter( 'dynamic_sidebar_params', 'shell_widget_classes' );

	/* Hybrid Core Context */
	add_filter( 'hybrid_context', 'shell_hybrid_context' );

	/* Atomic Widget Plugin Context */
	add_filter( 'atomic_widget_context', 'shell_atomic_widgets_context' );

	/* Post format singular template */
	add_filter( 'single_template', 'shell_post_format_singular_template', 11 );
}


/**
 * Additional body class for current page active sidebars, menus, theme layout, and skin.
 * 
 * @since 0.1.0
 */
function shell_body_class( $classes ){

	/* get all registered sidebars */
	global $wp_registered_sidebars;

	/* if not empty sidebar */
	if ( ! empty( $wp_registered_sidebars ) ){

		/* foreach widget areas */
		foreach ( $wp_registered_sidebars as $sidebar ){

			/* add active/inactive class */
			$classes[] = is_active_sidebar( $sidebar['id'] ) ? "sidebar-{$sidebar['id']}-active" : "sidebar-{$sidebar['id']}-inactive";
		}
	}

	/* get all registered menus */
	$menus = get_registered_nav_menus();

	/* if not empty menus */
	if ( ! empty( $menus ) ){

		/* for each menus */
		foreach ( $menus as $menu_id => $menu ){

			/* add active/inactive class */
			$classes[] = has_nav_menu( $menu_id ) ? "menu-{$menu_id}-active" : "menu-{$menu_id}-inactive";
		}
	}

	/* theme layout default */
	if ( ! current_theme_supports( 'theme-layouts' ) ) {
		$classes[] = 'layout-default';
	}

	/* make it unique */
	$classes = array_unique( $classes );

	return $classes;
}


/**
 * Additional widget classes with number of each widget position and first/last widget class.
 * This is a modified code from Sukelius Magazine Theme.
 *
 * @link http://themehybrid.com/themes/sukelius-magazine
 * @since 0.1.0
 */
function shell_widget_classes( $params ) {

	/* Global a counter array */
	global $shell_widget_num;

	/* Get the id for the current sidebar we're processing */
	$this_id = $params[0]['id'];

	/* Get registered widgets */
	$arr_registered_widgets = wp_get_sidebars_widgets();

	/* If the counter array doesn't exist, create it */
	if ( !$shell_widget_num ) {
		$shell_widget_num = array();
	}

	/* if current sidebar has no widget, return. */
	if ( !isset( $arr_registered_widgets[$this_id] ) || !is_array( $arr_registered_widgets[$this_id] ) ) {
		return $params;
	}

	/* See if the counter array has an entry for this sidebar */
	if ( isset( $shell_widget_num[$this_id] ) ) {
		$shell_widget_num[$this_id] ++;
	}
	/* If not, create it starting with 1 */
	else {
		$shell_widget_num[$this_id] = 1;
	}

	/* Add a widget number class for additional styling options */
	$class = 'class="widget widget-' . $shell_widget_num[$this_id] . ' '; 

	/* in first widget, add 'widget-first' class */
	if ( $shell_widget_num[$this_id] == 1 ) {
		$class .= 'widget-first ';
	}
	/* in last widget, add 'widget-last' class */
	elseif( $shell_widget_num[$this_id] == count( $arr_registered_widgets[$this_id] ) ) { 
		$class .= 'widget-last ';
	}

	/* str replace before_widget param with new class */
	$params[0]['before_widget'] = str_replace( 'class="widget ', $class, $params[0]['before_widget'] );

	return $params;
}


/**
 * Add Current Post template, Post Format, and Attachment Mime Type to Hybrid Core Context
 * 
 * @since 0.1.0
 */
function shell_hybrid_context( $context ){

	/* Singular post (post_type) classes. */
	if ( is_singular() ) {

		/* Get the queried post object. */
		$post = get_queried_object();

		/* Checks for custom template. */
		$template = str_replace( array ( "{$post->post_type}-template-", "{$post->post_type}-" ), '', basename( get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true ), '.php' ) );
		if ( !empty( $template ) )
			$context[] = "{$post->post_type}-template-{$template}";

		/* Post format. */
		if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) ) {
			$post_format = get_post_format( get_queried_object_id() );
			$context[] = ( ( empty( $post_format ) || is_wp_error( $post_format ) ) ? "{$post->post_type}-format-standard" : "{$post->post_type}-format-{$post_format}" );
		}

		/* Attachment mime types. */
		if ( is_attachment() ) {
			foreach ( explode( '/', get_post_mime_type() ) as $type )
				$context[] = "attachment-{$type}";
		}
	}
	/* Archive type pages */
	else if ( is_home() || is_archive() || is_search() ){
		$context[] = "_list-view";
	}

	/* make it unique */
	$context = array_unique( $context );

	return $context;
}


/**
 * Add Atomic Context for Atomic Widget Plugin
 * 
 * @link http://shellcreeper.com/portfolio/item/atomic-widget/
 * @since 0.1.0
 */
function shell_atomic_widgets_context( $context ){

	/* theme layout check */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* get theme layout */
		$layout = theme_layouts_get_layout();

		/* add theme layout to context */
		$context[] = $layout;

		/* if current theme layout is 2 column */
		if ( 'layout-default' == $layout || 'layout-2c-l' == $layout || 'layout-2c-r' == $layout )
			$context[] = 'layout-2c';

	}

	return $context;
}


/**
 * Add Singular Post Format Template
 * 
 * @link http://themehybrid.com/support/topic/add-post-format-singular-template-in-template-hierarchy#post-75579
 * @since 0.1.0
 */
function shell_post_format_singular_template( $template ){

	/* get queried object */
	$post = get_queried_object();

	/* check supported post type */
	if ( post_type_supports( $post->post_type, 'post-formats' ) ) {

		/* get post format of current object */
		$format = get_post_format( get_queried_object_id() );

		/* template */
		$templates = array(
			"{$post->post_type}-{$post->post_name}.php",
			"{$post->post_type}-{$post->ID}.php",
			"{$post->post_type}-format-{$format}.php"
		);

		/* locate template */
		$has_template = locate_template( $templates );

		if ( $has_template )
			$template = $has_template;
	}

	return $template;
}

