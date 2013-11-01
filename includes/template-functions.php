<?php
/**
 * Shell Template Functions
 * This is all functions is public and save to use in template files.
 * Child theme can use this template files savely.
 * this template functions will be maintained for an undetermined amount of time.
 * 
 * Other functions that is save to use are: 
 * - apply_atomic()
 * - do_atomic()
 * 
 * @package Shell
 * @since 0.2.0
 */


/**
 * Get Active Skin ID. Get current active skin. Allow developer to filter active skin.
 * 
 * @access public
 * @since  0.1.0
 */
function shell_active_skin(){

	/* available skins */
	$skins = shell_skins();

	/* get skin ids */
	$skin_ids = array();
	foreach ( $skins as $skin_id => $skin_data ){
		$skin_ids[] = $skin_id;
	}

	/* default skins */
	$active_skin = 'default';

	/* Check support for hybrid core settings */
	if ( current_theme_supports( 'hybrid-core-theme-settings' ) ) {

		$skin_setting = hybrid_get_setting( 'skin' );

		/* check if selected skins is available */
		if ( in_array( $skin_setting, $skin_ids ) )
			$active_skin = $skin_setting;
	}

	/* enable developer to modify it, maybe different skin for different pages (?) */
	$active_skin = apply_atomic( 'active_skin', $active_skin ); // shell_active_skin

	/* sanitize it */
	return wp_filter_nohtml_kses( $active_skin );
}


/**
 * Add Breadcrumb Trail
 * Added in 'shell_open_main' hook in header.php
 * 
 * @access public
 * @since  0.1.0
 */
function shell_breadcrumb(){
	if ( current_theme_supports( 'breadcrumb-trail' ) ) 
		breadcrumb_trail( array( 'before' => __( 'You are here:', 'shell' ) ) );
}

/**
 * Edit Link for singular post and taxonomy
 * Code from wordpress tool bar/admin bar.
 *
 * @access public
 * @since  0.1.0
 */
function shell_edit_link(){
	global $post, $tag, $wp_the_query;
	$current_object = $wp_the_query->get_queried_object();
	if ( empty( $current_object ) )
		return;
	if ( ! empty( $current_object->post_type )
			&& ( $cpt = get_post_type_object( $current_object->post_type ) )
			&& current_user_can( $cpt->cap->edit_post, $current_object->ID )
			&& ( $cpt->show_ui || 'attachment' == $current_object->post_type ) )
		{
			$edit_link = get_edit_post_link( $current_object->ID );
			return ' <a class="edit-link" href="'.$edit_link.'">' . __( ' - Edit', 'shell' ) . '</a>';
	} elseif ( ! empty( $current_object->taxonomy )
			&& ( $tax = get_taxonomy( $current_object->taxonomy ) )
			&& current_user_can( $tax->cap->edit_terms )
			&& $tax->show_ui )
		{
			$edit_link = get_edit_term_link( $current_object->term_id, $current_object->taxonomy );
			return ' <a class="edit-link" href="'.$edit_link.'">' . __( ' - Edit', 'shell' ) . '</a>';
	}
}


/**
 * Shell Footer Content
 * So we can remove support for hybrid core settings if needed.
 * 
 * @access public
 * @return string
 * @since  0.1.0
 */
function shell_footer_content(){

	/* default var */
	$content = '';

	/* If there is a child theme active, add the [child-link] shortcode to the $footer_insert. */
	if ( is_child_theme() )
		$content = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'shell' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link], [theme-link], and [child-link].', 'shell' ) . '</p>';

	/* If no child theme is active, leave out the [child-link] shortcode. */
	else
		$content = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'shell' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link] and [theme-link].', 'shell' ) . '</p>';

	/* Check support for hybrid core settings */
	if ( current_theme_supports( 'hybrid-core-theme-settings' ) ) {

		/* Get theme-supported meta boxes for the settings page. */
		$supports = get_theme_support( 'hybrid-core-theme-settings' );

		/* If the current theme supports the footer meta box and shortcodes, add footer settings input. */
		if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) ) {
			$content = hybrid_get_setting( 'footer_insert' );
		}
	}

	/* Return the $settings array and provide a hook for overwriting the default settings. */
	return $content;
}




/**
 * Advance Get Template by Atomic Context.
 * An easy to use feature for developer to create context based template file.
 * 
 * @param   $dir	string	template files directory
 * @param   $loop	bool	if it's used in the loop, to give extra template based on post data.
 * @access  public
 * @since   0.1.0
 */
function shell_get_atomic_template( $dir, $loop = false ) {

	/* array of available templates */
	$templates = array();

	/* get theme path  */
	$theme_dir = trailingslashit( THEME_DIR ) . $dir;
	$child_dir = trailingslashit( CHILD_THEME_DIR ) . $dir;

	if ( is_dir( $child_dir ) || is_dir( $theme_dir ) ) {

		/* index.php in folder are fallback template */
		$templates[] = "{$dir}/index.php";
	}
	else{
		return ''; // empty string if dir not found
	}

	/* get current page (atomic) contexts */
	$contexts = hybrid_get_context();

	/* for each contexts */
	foreach ( $contexts as $context ){

		/* add context based template */
		$templates[] = "{$dir}/{$context}.php";

		/* if context is in the loop, ( how to check if it's in the loop? ) */
		if ( true === $loop ){

			/* in singular */
			if ( is_singular() ){

				/* if post type support post-formats */
				if ( post_type_supports( get_post_type(), 'post-formats' ) ){

					// {content}/{singular}_format-{gallery}.php
					$templates[] = "{$dir}/singular_format-" . get_post_format() . "php";

					// {content}/{singular-post}_format-{gallery}.php
					$templates[] = "{$dir}/singular-" . get_post_type() . "_format-" . get_post_format() . "php";
				}
			}

			/* if it's blog, archive, or search */
			elseif ( is_home() || is_archive() || is_search() ){

				/* file based on post data */
				$files = array();

				/* current post - post type */
				$files[] = get_post_type();

				/* if post type support post-formats */
				if ( post_type_supports( get_post_type(), 'post-formats' ) ){

					/* current post formats */
					$files[] = 'format-' . get_post_format();

					/* post type and post formats */
					$files[] = get_post_type() . '-format-' . get_post_format();
				}

				/* add file based on post type and post format */
				foreach ( $files as $file ){

					// {content}/{_list-view}_{post}.php: post type template
					// {content}/{_list-view}_format-{gallery}.php: for all post type
					// {content}/{_list-view}_{post}-format-{gallery}.php: only for posts
					$templates[] = "{$dir}/{$context}_{$file}.php";
				}

				/* add sticky post in home page */
				if ( is_home() && !is_paged() ){
					if ( is_sticky( get_the_ID() ) ){
						$templates[] = "{$dir}/_sticky.php";
					}
				}
			}
		}
	}

	/* allow developer to modify template */
	$templates = apply_filters( 'shell_atomic_template',  $templates, $dir, $loop );

	return locate_template( array_reverse( $templates ), true, false );
}


/**
 * Shell Document Title
 * 
 * @since 0.2.0
 */
function shell_document_title(){
	hybrid_document_title();
}

/**
 * Shell Body Attributes
 * 
 * @since 0.2.0
 */
function shell_body_atttibutes(){
	hybrid_body_attributes()
}

/**
 * Shell Post Attributes
 * 
 * 
 */
function shell_post_attributes(){

}


/**
 * Shell Site title
 * 
 * 
 */
function shell_site_title(){

}

/**
 * Shell Site Description
 * 
 * 
 */
function shell_site_description(){

}

/**
 * 
 * 
 * 
 */






















