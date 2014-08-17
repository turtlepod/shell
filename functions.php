<?php
/**
 * Theme Functions
** ---------------------------- */

/* Load text string used in theme */
require_once( trailingslashit( get_template_directory() ) . 'includes/string.php' );

/* Load base theme functionality. */
require_once( trailingslashit( get_template_directory() ) . 'includes/tamatebako.php' );

/* Load theme general setup */
add_action( 'after_setup_theme', 'shell_setup' );

/**
 * General Setup
 * @since 1.0.0
 */
function shell_setup(){

	/* === DEBUG === */
	$debug_args = array(
		'mobile'         => 0,
		'no-js'          => 1,
		'media-queries'  => 1,
	);
	//add_theme_support( 'tamatebako-debug', $debug_args );

	/* === Post Formats === */
	$post_formats_args = array(
		'aside',
		'image',
		'gallery',
		'link',
		'quote',
		'status',
		'video',
		'audio',
		'chat'
	);
	//add_theme_support( 'post-formats', $post_formats_args );

	/* === Theme Layouts === */
	$layouts = array(
		/* One Column */
		'content' => 'Content',
		/* Two Columns */
		'content-sidebar1' => 'Content / Sidebar 1', /* Default */
		'sidebar1-content' => 'Sidebar 1 / Content',
	);
	$layouts_args = array(
		'default'   => 'content-sidebar1',
		'customize' => true,
		'post_meta' => true,
	);
	add_theme_support( 'theme-layouts', $layouts, $layouts_args );

	/* === Register Sidebars === */
	$sidebars_args = array(
		"primary" => array( "name" => shell_string( 'sidebar-primary-name' ), "description" => "" ),
		//"secondary" => array( "name" => shell_string( 'sidebar-secondary-name' ), "description" => "" ),
	);
	add_theme_support( 'tamatebako-sidebars', $sidebars_args );

	/* === Register Menus === */
	$menus_args = array(
		"primary" => shell_string( 'menu-primary-name' ),
		"secondary" => shell_string( 'menu-secondary-name' ),
		"footer" => shell_string( 'menu-footer-name' ),
	);
	add_theme_support( 'tamatebako-menus', $menus_args );

	/* === Load Stylesheet === */

	/* Default stylesheet loaded */
	$style_args = array(
		'theme-open-sans-font',
		'dashicons',
		'theme',
	);
	/* load "style" if it's child theme. */
	if ( is_child_theme() ) {
		$style_args[] = 'style';
	}
	/* While debuging load separate file. */
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ){
		$style_args = array(
			'theme-open-sans-font',
			'dashicons',
			'theme-reset',
			'theme-menus',
			'parent',
			'style',
			'media-queries'
		);
	}
	add_theme_support( 'hybrid-core-styles', $style_args );

	/* === Editor Style === */

	/* Editor Stylesheet loaded */
	$editor_css = array(
		'editor-style.css',
		tamatebako_google_open_sans_font_url()
	);
	/* While debugging load separate file */
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ){
		$editor_css = array(
			'css/reset.css',
			'style.css',
			tamatebako_google_open_sans_font_url()
		);
	}
	add_editor_style( $editor_css );

	/* === Customizer Mobile View === */
	add_theme_support( 'tamatebako-customize-mobile-view' );

	/* === Set Content Width === */
	hybrid_set_content_width( 1200 );
}


do_action( 'shell_after_theme_setup' );