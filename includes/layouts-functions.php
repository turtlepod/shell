<?php
/**
 * Theme Layouts Functions
 * All functions here related to theme layout handling.
 * Including content width, and MCE CSS for layouts.
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
add_action( 'after_setup_theme', 'shell_theme_layouts_setup' );

/**
 * Theme layouts setup function.
 * This function handle actions and filters related to layouts.
 *
 * @since     0.2.0
 * @access    public
 * @return    void
 */
function shell_theme_layouts_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Set the content width. */
	hybrid_set_content_width( 600 );

	/* Theme Support for Hybrid Core Theme Layouts Ext. */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r', '3c-l', '3c-r', '3c-c' ) );

	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'shell_embed_defaults' );

	/* Filter the sidebar widgets by current page theme layout */
	add_filter( 'sidebars_widgets', 'shell_disable_sidebars' );

	/* Switch to one column layout if no sidebar active or in attachment pages  */
	add_action( 'template_redirect', 'shell_one_column' );

	/* Theme Layout Editor Style */
	add_filter( 'mce_css', 'shell_theme_layout_editor_style' );
}


/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since 0.1.0
 */
function shell_embed_defaults( $args ) {

	/* only if theme layouts is supported */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* get current page theme layout */
		$layout = theme_layouts_get_layout();

		/* width in pixel based on current page theme layout */
		if ( 'layout-1c' == $layout ){
			$args['width'] = 930;
		}
		elseif ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout ){
			$args['width'] = 558;
		}
		else{
			$args['width'] = 600;
		}
	}
	else{
		$args['width'] = 600;
	}

	return $args;
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
 * @since 0.1.0
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
 * Additional Editor Style Based On Theme Layout Selected
 *
 * @since 0.1.0
 */
function shell_theme_layout_editor_style( $mce_css ) {

	global $pagenow;

	/* only if theme layout is supported */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* only in post and page edit screen */
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

				/* if current layout is 1 column */
				if ( '1c' == $layout ) {
					$mce_css .= ', ' . hybrid_locate_theme_file( 'editor-style-1c.css' );
				}

				/* if current layout is 3 column */
				elseif ( '3c-l' == $layout || '3c-r' == $layout || '3c-c' == $layout ){
					$mce_css .= ', ' . hybrid_locate_theme_file( 'editor-style-3c.css' );
				}
			}
		}
	}
	return $mce_css;
}


/* Theme Layout Customize: use Refresh
=============================================================== */

/* Remove and create custom layout option in customizer
--------------------------------------------------------------- */

/* Override theme layout customize */
add_action( 'after_setup_theme', 'shell_override_theme_layouts_customize_setup', 14 );

/**
 * Shell Override Theme Layouts Customize
 * 
 * @since 0.2.0
 */
function shell_override_theme_layouts_customize_setup(){

	/* Check theme support */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* Remove default theme layout customize function */
		remove_action( 'customize_register', 'theme_layouts_customize_register' );

		/* Add custom setting for layout in customizer */
		add_action( 'customize_register', 'shell_theme_layouts_customize_register' );

	}

}

/**
 * Theme Layouts Customize Register
 * 
 * @since 0.2.0
 */
function shell_theme_layouts_customize_register( $wp_customize ){

	/* Get supported theme layouts. */
	$layouts = theme_layouts_get_layouts();
	$args = theme_layouts_get_args();

	if ( true === $args['customize'] ) {

		/* Add the layout section. */
		$wp_customize->add_section(
			'layout',
			array(
				'title'      => esc_html__( 'Layout', 'theme-layouts' ),
				'priority'   => 190,
				'capability' => 'edit_theme_options'
			)
		);

		/* Add the 'layout' setting. */
		$wp_customize->add_setting(
			'theme_layout',
			array(
				'default'           => get_theme_mod( 'theme_layout', $args['default'] ),
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_html_class',
				'transport'         => 'refresh' /* USE REFRESH */
			)
		);

		/* Set up an array for the layout choices and add in the 'default' layout. */
		$layout_choices = array();

		/* Only add 'default' if it's the actual default layout. */
		if ( 'default' == $args['default'] )
			$layout_choices['default'] = theme_layouts_get_string( 'default' );

		/* Loop through each of the layouts and add it to the choices array with proper key/value pairs. */
		foreach ( $layouts as $layout )
			$layout_choices[$layout] = theme_layouts_get_string( $layout );

		/* Add the layout control. */
		$wp_customize->add_control(
			'theme-layout-control',
			array(
				'label'    => esc_html__( 'Global Layout', 'theme-layouts' ),
				'section'  => 'layout',
				'settings' => 'theme_layout',
				'type'     => 'radio',
				'choices'  => $layout_choices
			)
		);
	}
}