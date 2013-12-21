<?php
/**
 * Editor Functions
 * All functions here is a related to handle TinyMCE and editor styles.
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
add_action( 'after_setup_theme', 'shell_theme_editor_setup' );

/**
 * Theme editor setup function.
 * This function handle actions and filters related to TinyMCE and WP Editor.
 *
 * @since     0.2.0
 * @access    public
 * @return    void
 */
function shell_theme_editor_setup() {

	/* Add editor style */
	add_editor_style();

	/* Modify TinyMCE with WordPress default supported button */
	add_filter( 'mce_buttons', 'shell_tinymce_1', 1, 2 ); // 1st row
	add_filter( 'mce_buttons_2', 'shell_tinymce_2', 1, 2 ); // 2nd row
}


/**
 * Modify tinyMCE 1st row
 * Add Strikethrough, UnderScore, and PageBreak in tinyMCE 1st row
 * 
 * @since 0.1.0
 */
function shell_tinymce_1( $buttons, $id ){

	/* only add this for content editor */
	if ( 'content' != $id )
		return $buttons;

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
 * @since 0.1.0
 */
function shell_tinymce_2( $buttons, $id ){

	/* only add this for content editor */
	if ( 'content' != $id )
		return $buttons;

	/* add horizontal button after indent button */
	array_splice( $buttons, 11, 0, 'hr' );

	return $buttons;
}

