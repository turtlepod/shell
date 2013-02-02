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
 * @package shell
 * @subpackage Functions
 * @version 0.1.0
 * @author David Chandra Purnama <david.warna@gmail.com>
 * @copyright Copyright (c) 2013, David Chandra Purnama
 * @copyright Copyright (c) 2010 - 2013, Justin Tadlock
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'shell_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
function shell_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
	
	/* Add theme settings. */
	if ( is_admin() )
		require_once( trailingslashit ( get_template_directory() ) . 'admin/admin.php' );

	/* default settings */
	add_filter( "{$prefix}_default_theme_settings", 'shell_default_settings' );

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'secondary', 'subsidiary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'secondary', 'header', 'subsidiary', 'after-singular' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	add_theme_support( 'hybrid-core-scripts', array( 'drop-downs' ) );
	if ( is_child_theme() ) // child theme
		add_theme_support( 'hybrid-core-styles', array( 'parent', 'media-queries', 'skin', 'style' ) );
	else // parent theme
		add_theme_support( 'hybrid-core-styles', array( 'style', 'media-queries', 'skin' ) );

	/* Add theme support for framework extensions. */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r', '3c-l', '3c-r', '3c-c' ) );
	add_theme_support( 'dev-stylesheet' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );

	/* Add the search form to the secondary menu. */
	add_action( "{$prefix}_close_menu_secondary", 'get_search_form' );

	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'shell_embed_defaults' );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'shell_disable_sidebars' );
	add_action( 'template_redirect', 'shell_one_column' );

	/* Set the content width. */
	hybrid_set_content_width( 600 );

	/* Add additional css */
	add_filter( "{$prefix}_styles", 'shell_styles' );

	/* Enqueue script. */
	add_action('wp_enqueue_scripts', 'shell_script');

	/* Add respond.js and  html5shiv.js for unsupported browsers. */
	add_action( 'wp_head', 'shell_respond_html5shiv' );

	/* Additional css classes for widgets */
	add_filter( 'dynamic_sidebar_params', 'shell_widget_classes' );

	/* Load Sidebar Template Files */
	add_action( "{$prefix}_header", 'shell_get_sidebar_header' ); // sidebar-header.php
	add_action( "{$prefix}_sidebar", 'shell_get_sidebar' ); // sidebar-primary.php and sidebar-secondary.php
	add_action( "{$prefix}_after_main", 'shell_get_sidebar_subsidiary' ); // sidebar-subsidiary.php
	add_action( "{$prefix}_after_singular", 'shell_get_sidebar_after_singular' ); // sidebar-after-singular.php

	/* Load Menu Template Files */
	add_action( "{$prefix}_before_header", 'shell_get_menu_primary' ); // menu-primary.php
	add_action( "{$prefix}_after_header", 'shell_get_menu_secondary' ); // menu-secondary.php
	add_action( "{$prefix}_before_footer", 'shell_get_menu_subsidiary' ); // menu-subsidiary.php

	/* Mobile Menu HTML */
	add_action( "{$prefix}_open_menu_primary", 'shell_mobile_menu' );
	add_action( "{$prefix}_open_menu_secondary", 'shell_mobile_menu' );

	/* Load loop-meta.php Template File */
	add_action( "{$prefix}_open_hfeed", 'shell_get_loop_meta' ); // loop-meta.php

	/* Breadcrumb Trail */
	add_action( "{$prefix}_open_main", 'shell_breadcrumb' );

	/* Thumbnail */
	add_action( "{$prefix}_open_entry", 'shell_thumbnail' );

	/* Entry Summary wp_link_pages */
	add_action( "{$prefix}_close_entry_summary", 'shell_summary_wp_link_pages' );

	/* Attachment Gallery */
	add_action( "{$prefix}_attachment-image_after_entry_content", 'shell_attachment_gallery' );

	/* post format singular template */
	add_filter( 'single_template', 'shell_post_format_singular_template', 11 );

	/* add shortcode */
	add_action( 'init', 'shell_shortcode' );

	/* body class */
	add_filter('body_class','shell_body_class');

	/* Atomic Widget Plugin Context */
	add_filter( 'atomic_widget_context', 'shell_atomic_widgets_context' );

	/* Hybrid Core Context */
	add_filter( 'hybrid_context', 'shell_hybrid_context' );

	/* Skin in Customizer */
	add_action( 'customize_register', 'shell_customizer_register' );

	/* Add editor style */
	add_editor_style();
	add_action( 'admin_head', 'shell_editor_style' );

	/* Modify tinymce */
	add_filter( 'mce_buttons', 'shell_tinymce_1', 1 ); // ist row
	add_filter( 'mce_buttons_2', 'shell_tinymce_2', 1 ); // 2nd row
	add_filter( 'mce_buttons_3', 'shell_tinymce_3', 1 ); // 3rd row
	add_filter( 'tiny_mce_before_init', 'shell_tinymce_style_select', 1 ); //style select settings
}


/**
 * Shell Skins: for skin plugins
 * 
 * @since 0.1.0
 */
function shell_skins(){

	/* theme version */
	$theme = wp_get_theme( get_template() );
	$version = $theme->get( 'Version' );

	/* default skin */
	$skins = array( 'default' => array(
		'name' => 'Default',
		'version' => $version,
		'image' => get_template_directory_uri() . '/screenshot.png',
		'author' => 'David Chandra Purnama',
		'author' => 'David Chandra Purnama',
		'author_uri' => 'http://shellcreeper.com/',
		'description' => __( 'This is default skin for Shell Theme.', 'shell' ),
	));

	/* enable developer to add skins */
	return apply_filters( 'shell_skin', $skins );
}


/**
 * Default Settings
 * 
 * @since 0.1.0
 */
function shell_default_settings( $settings ){

	/* skin option */
	$settings['skin'] = 'default';
	
	return $settings;
}


/**
 * Skin Customizer
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since 0.1.0
 */
function shell_customizer_register( $wp_customize ) {

	/* skin count */
	$skin_count = count( shell_skins() );

	/* add skin to customizer only if additional skin available */
	if ( $skin_count > 1 ){

		/* default settings */
		$default = hybrid_get_default_theme_settings();

		/* get list of available skin */
		$skins = shell_skins();
		$skin_choises = array();
		foreach ( $skins as $skin_id => $skin_data ){
			/* use key as key, cause the value is images. */
			$skin_choises[$skin_id] = $skin_data['name'];
		}

		/* skins */
		$wp_customize->add_section( 'shell_customize_skin', array(
			'title' => _x( 'Skins', 'customizer', 'shell' ),
			'priority' => 30,
		) );
		$wp_customize->add_setting( 'shell_theme_settings[skin]', array(
			'default' => $default['skin'],
			'type' => 'option',
		) );
		$wp_customize->add_control( 'shell_theme_settings[skin]', array(
			'label' =>_x( 'Skins', 'customizer', 'shell' ),
			'section' => 'shell_customize_skin',
			'settings' => 'shell_theme_settings[skin]',
			'type' => 'select',
			'choices' => $skin_choises,
		) );
	}
}


/* function check to enable override/disable custom background from child theme  */
if( !function_exists( 'shell_custom_background' ) ){

	/* setup custom background on the 'after_setup_theme' hook. */
	add_action( 'after_setup_theme', 'shell_custom_background' );

	/**
	 * Custom Background
	 * 
	 * @since 0.1.0
	 */
	function shell_custom_background(){
		add_theme_support( 'custom-background', array( 'default-color' => 'f9f9f9' ) );
	}
}

/**
 * Mobile Menu HTML
 * 
 * @since 0.1.0
 */
function shell_mobile_menu(){?>
<div class="mobile-menu-button"></div><?php
}

/**
 * Add Custom styles
 *
 * Skin plugin need to register style with the name "skin"
 *
 * @since 0.1.0
 */
function shell_styles( $styles ) {

	/* css */
	$css = array();

	/* default media queries css */
	$css['file'] = trailingslashit( get_template_directory_uri() ) . 'media-queries.css';

	/* get theme version */
	$theme = wp_get_theme( get_template() );
	$css['version'] = $theme->get( 'Version' );

	/* allow child theme to override media queries css */
	if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'media-queries.css' )){
		$css['file'] = trailingslashit( get_stylesheet_directory_uri() ) . 'media-queries.css';
		$theme = wp_get_theme();
		$css['version'] = $theme->get( 'Version' );
	}

	/* filter: allow skin to filter if needed */
	$css = apply_filters( 'shell_media_queries_css', $css );

	/* add media queries css */
	$styles['media-queries'] = array(
		'src' => $css['file'],
		'media' => 'all',
		'deps' => 'style',
		'version' => $css['version']
	);

	return $styles;
}


/**
 * Add Script
 * 
 * @since 0.1.0
 */
function shell_script(){

	if ( !is_admin() ) {

		/*  Mobile Menu Script */
		wp_enqueue_script( 'shell-menu', get_template_directory_uri() . '/js/shell-menu.js', array('jquery'), false, true );

		/* Enqueue FitVids */
		wp_enqueue_script( 'shell-fitvids', trailingslashit( get_template_directory_uri() ) . 'js/fitvids.js', array( 'jquery' ), '20120625', true );
	}
}


/**
 * Function for help to unsupported browsers understand mediaqueries and html5.
 * @link: https://github.com/scottjehl/Respond
 * @link: http://code.google.com/p/html5shiv/
 * @since 0.1.0
 */
function shell_respond_html5shiv() {
	?><!-- Enables media queries and html5 in some unsupported browsers. -->
	<!--[if (lt IE 9) & (!IEMobile)]>
	<script type="text/javascript" src="<?php echo trailingslashit( get_template_directory_uri() ); ?>js/respond/respond.min.js"></script>
	<script type="text/javascript" src="<?php echo trailingslashit( get_template_directory_uri() ); ?>js/html5shiv/html5shiv.js"></script>
	<![endif]--><?php
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since 0.1.0
 */
function shell_one_column() {

	if ( !is_active_sidebar( 'primary' ) && !is_active_sidebar( 'secondary' ) )
		add_filter( 'get_theme_layout', 'shell_theme_layout_one_column' );

	elseif ( is_attachment() && 'layout-default' == theme_layouts_get_layout() )
		add_filter( 'get_theme_layout', 'shell_theme_layout_one_column' );
}


/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since 0.2.0
 */
function shell_theme_layout_one_column( $layout ) {
	return 'layout-1c';
}


/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since 0.1.0
 */
function shell_disable_sidebars( $sidebars_widgets ) {
	global $wp_query;

	if ( current_theme_supports( 'theme-layouts' ) ) {

		if ( 'layout-1c' == theme_layouts_get_layout() ) {
			$sidebars_widgets['primary'] = false;
			$sidebars_widgets['secondary'] = false;
		}
	}

	return $sidebars_widgets;
}


/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since 0.1.0
 */
function shell_embed_defaults( $args ) {

	if ( current_theme_supports( 'theme-layouts' ) ) {

		$layout = theme_layouts_get_layout();

		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout )
			$args['width'] = 558;
		elseif ( 'layout-1c' == $layout )
			$args['width'] = 930;
		else
			$args['width'] = 600;
	}
	else
		$args['width'] = 600;

	return $args;
}


/**
 * Widget Class Number
 *
 * code from Sukelius Magazine Theme
 * Adding .widget-first and .widget-last classes to widgets.
 * Class .widget-last used to reset margin-right to zero in subsidiary sidebar for the last widget.
 *
 * @link http://themehybrid.com/themes/sukelius-magazine
 * @since 0.1.0
 */
function shell_widget_classes( $params ) {

	global $genbu_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets

	if ( !$genbu_widget_num ) {// If the counter array doesn't exist, create it
		$genbu_widget_num = array();
	}

	if ( !isset( $arr_registered_widgets[$this_id] ) || !is_array( $arr_registered_widgets[$this_id] ) ) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if ( isset($genbu_widget_num[$this_id] ) ) { // See if the counter array has an entry for this sidebar
		$genbu_widget_num[$this_id] ++;
	} else { // If not, create it starting with 1
		$genbu_widget_num[$this_id] = 1;
	}

	$class = 'class="widget widget-' . $genbu_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

	if ( $genbu_widget_num[$this_id] == 1 ) { // If this is the first widget
		$class .= 'widget widget-first ';
	} elseif( $genbu_widget_num[$this_id] == count( $arr_registered_widgets[$this_id] ) ) { // If this is the last widget
		$class .= 'widget widget-last ';
	}

	$params[0]['before_widget'] = str_replace( 'class="widget ', $class, $params[0]['before_widget'] ); // Insert our new classes into "before widget"

	return $params;
}


/**
 * Add Singular Post Format Template
 * 
 * This might be added to Hybrid Core 1.6
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


/**
 * Load Sidebar Header
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_header(){
	get_sidebar( 'header' ); // sidebar-header.php
}


/**
 * Load Sidebar Primary and Secondary
 * 
 * @since 0.1.0
 */
function shell_get_sidebar(){
	get_sidebar( 'primary' ); // sidebar-primary.php
	get_sidebar( 'secondary' ); // sidebar-secondary.php
}


/**
 * Load Sidebar Subsidiary
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_subsidiary(){
	get_sidebar( 'subsidiary' ); // sidebar-subsidiary.php
}


/**
 * Load Sidebar After Singular
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_after_singular(){
	get_sidebar( 'after-singular' ); // sidebar-after-singular.php
}


/**
 * Load Menu Primary
 * 
 * @since 0.1.0
 */
function shell_get_menu_primary(){
	get_template_part( 'menu', 'primary' ); // menu-primary.php
}


/**
 * Load Menu Secondary
 * 
 * @since 0.1.0
 */
function shell_get_menu_secondary(){
	get_template_part( 'menu', 'secondary' ); // menu-secondary.php
}


/**
 * Load Menu Subsidiary
 * 
 * @since 0.1.0
 */
function shell_get_menu_subsidiary(){
	get_template_part( 'menu', 'subsidiary' ); // menu-subsidiary.php
}


/**
 * Breadcrumb Trail
 * 
 * @since 0.1.0
 */
function shell_breadcrumb(){
	if ( current_theme_supports( 'breadcrumb-trail' ) ) 
		breadcrumb_trail( array( 'before' => __( 'You are here:', 'shell' ) ) );
}


/**
 * Load Loop Meta
 * 
 * @since 0.1.0
 */
function shell_get_loop_meta(){

	/* load on blog page, archive, and search result pages */
	if ( ( is_home() && !is_front_page() ) || is_archive() || is_search() )
		get_template_part( 'loop-meta' );
}


/**
 * Thumbnail
 * 
 * @since 0.1.0
 */
function shell_thumbnail(){

	/* add it in archive/blog/search */
	if ( ( is_home() && !is_singular() ) || is_archive() || is_search() ){

		/* check get the image support */
		if ( current_theme_supports( 'get-the-image' ) )
			get_the_image( array( 'meta_key' => 'thumbnail', 'size' => 'thumbnail' ) );
	}
}


/**
 * Summary WP Link Pages
 * 
 * @since 0.1.0
 */
function shell_summary_wp_link_pages(){
	wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'shell' ), 'after' => '</p>' ) );
}


/**
 * Attachment Gallery
 * 
 * Uses custom context
 * 
 * @since 0.1.0
 */
function shell_attachment_gallery(){
	global $post;
	echo do_shortcode( sprintf( '[gallery id="%1$s" exclude="%2$s" columns="8"]', $post->post_parent, $post->ID ) );
}


/**
 * Add Shortcode
 * 
 * @since 0.1.0
 */
function shell_shortcode(){

	/* loop meta title */
	add_shortcode( 'loop-meta-title', 'shell_loop_meta_title' );

	/* loop meta description */
	add_shortcode( 'loop-meta-desc', 'shell_loop_meta_description' );

}


/**
 * Loop Meta Title Shortcode
 * 
 * simplyfy loop meta
 * 
 * @since 0.1.0
 */
function shell_loop_meta_title( $attr ){

	global $wp_query;

	/* Set shortcode attr */
	$attr = shortcode_atts( array(
		'before' => '<h1 class="loop-title">',
		'after' => '</h1>' )
	, $attr );

	/* Set up some default variables. */
	$doctitle = '';
	$separator = ' -';

	/* default */
	$current = '';

	if ( is_home() || is_singular() ) {
		$current = get_post_field( 'post_title', get_queried_object_id() );
	}

	/* If viewing any type of archive page. */
	elseif ( is_archive() ) {

		/* If viewing a taxonomy term archive. */
		if ( is_category() || is_tag() || is_tax() ) {
			if ( is_search() )
				$current = esc_attr( get_search_query() ).' &ndash; '.single_term_title( '', false );
			else
				$current = single_term_title( '', false );
		}

		/* If viewing a post type archive. */
		elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
			$current = $post_type->labels->name;
		}

		/* If viewing an author/user archive. */
		elseif ( is_author() ) {
			$current = get_user_meta( get_query_var( 'author' ), 'Title', true );

			if ( empty( $current ) )
				$current = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
		}

		/* If viewing a date-/time-based archive. */
		elseif ( is_date () ) {
			if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
				$current = sprintf( __( '%1$s', 'shell' ), get_the_time( __( 'g:i a', 'shell' ) ) );

			elseif ( get_query_var( 'minute' ) )
				$current = sprintf( __( 'minute %1$s', 'shell' ), get_the_time( __( 'i', 'shell' ) ) );

			elseif ( get_query_var( 'hour' ) )
				$current = sprintf( __( '%1$s', 'shell' ), get_the_time( __( 'g a', 'shell' ) ) );

			elseif ( is_day() )
				$current = sprintf( __( '%1$s', 'shell' ), get_the_time( __( 'F jS, Y', 'shell' ) ) );

			elseif ( get_query_var( 'w' ) )
				$current = sprintf( __( 'week %1$s of %2$s', 'shell' ), get_the_time( __( 'W', 'shell' ) ), get_the_time( __( 'Y', 'shell' ) ) );

			elseif ( is_month() )
				$current = sprintf( __( '%1$s', 'shell' ), single_month_title( ' ', false) );

			elseif ( is_year() )
				$current = sprintf( __( '%1$s', 'shell' ), get_the_time( __( 'Y', 'shell' ) ) );
		}

		/* For any other archives. */
		else {
			$current = __( 'Archives', 'shell' );
		}
	}

	/* If viewing a search results page. */
	elseif ( is_search() )
		$current = esc_attr( str_replace( '+', ' ', get_search_query( false ) ) );

	/* If viewing a 404 not found page: not really needed */
	elseif ( is_404() )
		$current = __( '404 Not Found', 'shell' );

	/* Trim separator + space from beginning and end in case a plugin adds it. */
	$current = trim( $current, "{$separator} " );

	/* Format title */
	if ( !empty( $current ) ){
		$current = $attr['before'] . $current . $attr['after'] . "\n";
	}

	/* Print the title to the screen. */
	return $current;
}


/**
 * Loop Meta Description Shortcode
 * 
 * simplyfy loop meta
 * 
 * @since 0.1.0
 */
function shell_loop_meta_description( $attr ){

	global $wp_query;

	/* Set shortcode attr */
	$attr = shortcode_atts( array(
		'before' => '<div class="loop-description">',
		'after' => '</div>' )
	, $attr );

	/* Set an empty $description variable. */
	$description = '';

	/* If viewing the home/posts page, get the site's description. */
	if ( is_front_page() && is_home() )
		$description = get_bloginfo( 'description' );

	/* If viewing the posts page or a singular post. */
	elseif ( is_home() || is_singular() ) {

		$description = get_post_meta( get_queried_object_id(), '_yoast_wpseo_metadesc', true );

		if ( empty( $description ) && is_front_page() )
			$description = get_bloginfo( 'description' );

		elseif ( empty( $current ) )
			$description = get_post_field( 'post_excerpt', get_queried_object_id() );
	}

	/* If viewing an archive page. */
	elseif ( is_archive() ) {

		/* If viewing a user/author archive. */
		if ( is_author() ) {

			/* Get the meta value for the 'Description' user meta key. */
			$description = get_user_meta( get_query_var( 'author' ), 'Description', true );

			/* If no description was found, get the user's description (biographical info). */
			if ( empty( $description ) )
				$description = get_the_author_meta( 'description', get_query_var( 'author' ) );
		}

		/* If viewing a taxonomy term archive, get the term's description. */
		elseif ( is_category() || is_tag() || is_tax() )
			$description = term_description( '', get_query_var( 'taxonomy' ) );

		/* If viewing a custom post type archive. */
		elseif ( is_post_type_archive() ) {

			/* Get the post type object. */
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );

			/* If a description was set for the post type, use it. */
			if ( isset( $post_type->description ) )
				$description = $post_type->description;
		}
	}

	/* loop description. */
	if ( !empty( $description ) ){
		$description = $attr['before'] . $description . $attr['after'] . "\n";
	}

	return $description;
}

/**
 * Body Class
 * 
 * Add body class: sidebars, menus, theme layout
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

	/* Skins */
	$skin_count = count( shell_skins() );

	/* check if custom skin exist */
	if ( $skin_count > 1 ){

		/* get skin settings */
		$skin_option = hybrid_get_setting( 'skin' );

		/* if custom skin selected */
		if ( $skin_option && $skin_option != 'default' ){

			/* validate */
			if ( in_array( $skin_option, shell_skins() ) )
				$classes[] = $skin_option . '-skin-active';
		}
	}

	/* make it unique */
	$classes = array_unique( $classes );

	return $classes;
}


/**
 * Shell HTML Class
 *
 * Dynamic HTML Class for active theme
 *
 * @since 0.1.0
 */
function shell_html_class( $class = '' ){

	global $wp_query;

	/* default var */
	$classes = array();

	/* Active Theme */
	$classes[] = get_stylesheet();

	/* not singular pages - sometimes i need this */
	if (! is_singular())
		$classes[] = 'not-singular';

	/* theme layout check */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* get current layout */
		$layout = theme_layouts_get_layout();

		/* if current theme layout is 2 column */
		if ( 'layout-default' == $layout || 'layout-2c-l' == $layout || 'layout-2c-r' == $layout )
			$classes[] = 'layout-2c';

		/* if current theme layout is 3 column */
		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout )
			$classes[] = 'layout-3c';
	}

	/* user input */
	if ( ! empty( $class ) ) {

		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );

		$classes = array_merge( $classes, $class );
	}
	else {

		$class = array();
	}

	/* enable filter */
	$classes = apply_atomic( 'html_class', $classes, $class );

	/* sanitize it */
	$classes = array_map( 'esc_attr', $classes );

	/* make it unique */
	$classes = array_unique( $classes );

	/* Join all the classes into one string. */
	$class = join( ' ', $classes );

	/* Print the body class. */
	echo $class;
}


/**
 * Shell Footer Content
 * 
 * So we can remove support for hybrid core settings.
 * 
 * @since 0.1.0
 */
function shell_footer_content(){

	/* default var */
	$content = '';

	/* If there is a child theme active, add the [child-link] shortcode to the $footer_insert. */
	if ( is_child_theme() )
		$content = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'hybrid-core' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link], [theme-link], and [child-link].', 'hybrid-core' ) . '</p>';

	/* If no child theme is active, leave out the [child-link] shortcode. */
	else
		$content = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'hybrid-core' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link] and [theme-link].', 'hybrid-core' ) . '</p>';

	/* Get theme-supported meta boxes for the settings page. */
	$supports = get_theme_support( 'hybrid-core-theme-settings' );

	/* If the current theme supports the footer meta box and shortcodes, add footer settings input. */
	if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) ) {
		$content = hybrid_get_setting( 'footer_insert' );
	}

	/* Return the $settings array and provide a hook for overwriting the default settings. */
	return $content;
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

		/* if current theme layout is 3 column */
		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout )
			$context[] = 'layout-3c';
	}

	return $context;
}


/**
 * Add Context to Hybrid Core Context
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

	/* make it unique */
	$context = array_unique( $context );

	return $context;
}


/**
 * Editor Style
 *
 * Additional Editor Style Based On Theme Layout Selected
 *
 * @since 2.0.0
 */
function shell_editor_style() {

	global $pagenow;

	/* only if theme layout is supported */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* only in */
		if ( in_array( $pagenow, array( 'page.php', 'page-new.php', 'post.php', 'post-new.php' ) ) ) {

			/* get id */
			if ( isset( $_GET['post'] ) )
				$post_id = $_GET['post'];
			elseif ( isset( $_POST['post_ID'] ) )
				$post_id = $_POST['post_ID'];

			/* after id is set, add editor style */
			if ( isset( $post_id ) ) {

				/* get current post layout */
				$layout = get_post_layout( $post_id );

				/* if current layout is 3 column */
				if ( '3c-l' == $layout || '3c-r' == $layout || '3c-c' == $layout )
					add_editor_style( 'editor-style-3c.css' );

				/* if current layout is 1 column */
				elseif ( '1c' == $layout ) {
					add_editor_style( 'editor-style-1c.css' );
				}
			}
		}
	}
}


/**
 * Modify tinyMCE 1st row
 * Add Strikethrough, UnderScore, and PageBreak in tinyMCE 1st row
 * 
 * @since 0.1.0
 */
function shell_tinymce_1( $buttons ){

	/* add underline after italic button */
	array_splice( $buttons, 2, 0, 'underline' );

	/* add next page after more tag button */
	array_splice( $buttons, 13, 0, 'wp_page' );

	return $buttons;
}


/**
 * Modify tinyMCE 2nd row
 * Add horizontal line and background color
 * 
 * 
 * @since 0.1.0
 */
function shell_tinymce_2( $buttons ){

	/* add background color button after color button */
	array_splice( $buttons, 4, 0, 'backcolorpicker' );

	/* add horizontal button after indent button */
	array_splice( $buttons, 11, 0, 'hr' );

	return $buttons;
}


/**
 * Modify tinyMCE 3rd row
 * Add Style Dropdown in tinyMCE 3rd row
 * 
 * @since 0.1.0
 */
function shell_tinymce_3( $buttons ){

	/* add style select in the first button position */
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}


/**
 * Add Style Options to tinyMCE
 *
 * @link http://tinymce.moxiecode.com/examples/example_24.php
 * @since 1.0.0.
 */
function shell_tinymce_style_select( $settings ) {

	$style_formats = array(
	array( 'title' => 'Code',			'inline' => 'code', ),
	array( 'title' => 'Clear',			'block' => 'div',  'classes' => 'clear' ),

	/* Column */
	array( 'title' => 'Column'),
	array( 'title' => 'Half',			'block' => 'p',  'classes' => 'grid-column-2' ),
	array( 'title' => 'One thirds',		'block' => 'p',  'classes' => 'grid-column-1-3' ),
	array( 'title' => 'Two thirds',		'block' => 'p',  'classes' => 'grid-column-2-3' ),
	array( 'title' => 'Last column*',	'block' => 'p',  'classes' => 'grid-column-last' ),

	/* Info Boxes */
	array( 'title' => 'Box'),
	array( 'title' => 'Note',			'block' => 'div',  'classes' => 'note' ),
	array( 'title' => 'Alert', 			'block' => 'div',  'classes' => 'alert' ),
	array( 'title' => 'Error', 			'block' => 'div',  'classes' => 'error' ),
	array( 'title' => 'Download',		'block' => 'div',  'classes' => 'download' ),

	/* Buttons */
	array( 'title' => 'Button'),
	array( 'title' => 'Button',			'inline' => 'span',  'classes' => 'button' ),
	array( 'title' => 'Green Button',	'inline' => 'span',  'classes' => 'button button-green' ),
	array( 'title' => 'Blue Button',	'inline' => 'span',  'classes' => 'button button-blue' ),
	array( 'title' => 'Black Button',	'inline' => 'span',  'classes' => 'button button-black' ),
	array( 'title' => 'Red Button',		'inline' => 'span',  'classes' => 'button button-red' ),
	array( 'title' => 'Small Button*',	'inline' => 'span',  'classes' => 'button button-small' ),
	);

	$settings['style_formats'] = json_encode( $style_formats );
	return $settings;
}