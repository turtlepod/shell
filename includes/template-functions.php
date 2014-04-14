<?php
/**
 * Template Functions
 * All functions here is a template functions which is public.
 * It is recommended to use these functions instead using hybrid core function.
 *
 * @package     Shell
 * @subpackage  Includes
 * @since       0.2.0
 * @author      David Chandra Purnama <david@shellcreeper.com>
 * @copyright   Copyright (c) 2013, David Chandra Purnama
 * @link        http://themehybrid.com/themes/shell
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* SHELL TEMPLATE THEME SETUP
=============================================================== */


/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'shell_theme_template_setup' );

/**
 * Theme template setup function.
 * This function handle actions and filters related to templates.
 *
 * @since     0.2.0
 * @access    public
 * @return    void
 */
function shell_theme_template_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* ======== CONTENTS ========= */

	/* Add thumbnail */
	add_action( "{$prefix}_open_entry", 'shell_thumbnail' ); // content/index.php

	/* Add wp_link_pages in entry summary (after excerpt) */
	add_action( "{$prefix}_close_entry_summary", 'shell_summary_wp_link_pages' ); // content/index.php

	/* Add gallery in attachment image page */
	add_action( "{$prefix}_attachment-image_after_entry_content", 'shell_attachment_gallery' ); // content/index.php

	/* ======== NAVIGATIONS ========= */

	/* Load Menu Template Files */
	add_action( "{$prefix}_before_header", 'shell_get_menu_primary' ); // menu/primary.php
	add_action( "{$prefix}_after_header", 'shell_get_menu_secondary' ); // menu/secondary.php
	add_action( "{$prefix}_before_footer", 'shell_get_menu_subsidiary' ); // menu/subsidiary.php
	add_action( "{$prefix}_before_footer", 'shell_get_menu_mobile_bottom' ); // menu/bottom.php

	/* Load searchform.php Template File */
	add_action( "{$prefix}_close_menu_secondary", 'get_search_form' ); // menu/secondary.php

	/* Add mobile menu */
	add_action( "{$prefix}_open_menu_primary", 'shell_mobile_menu_primary' ); // menu/primary.php
	add_action( "{$prefix}_open_menu_secondary", 'shell_mobile_menu_secondary' ); // menu/secondary.php

	/* Add breadcrumb Trail */
	add_action( "{$prefix}_open_main", 'shell_breadcrumb' ); // header.php

	/* ======== SIDEBARS ========= */

	/* Load Sidebar Template Files */
	add_action( "{$prefix}_header", 'shell_get_sidebar_header' ); // sidebar/header.php
	add_action( "{$prefix}_sidebar", 'shell_get_sidebar' ); // sidebar/-primary.php and sidebar/secondary.php
	add_action( "{$prefix}_after_main", 'shell_get_sidebar_subsidiary' ); // sidebar/subsidiary.php
	add_action( "{$prefix}_after_singular", 'shell_get_sidebar_after_singular' ); // sidebar/after-singular.php
}


/* GENERAL TEMPLATE FUNCTIONS
=============================================================== */

/**
 * Advance Get Template by Atomic Context.
 * An easy to use feature for developer to create context based template file.
 * 
 * @param $dir	string	template files directory
 * @param $loop	bool	if it's used in the loop, to give extra template based on post data.
 * @since 0.1.0
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
					$templates[] = "{$dir}/singular_format-" . get_post_format() . ".php";

					// {content}/{singular-post}_format-{gallery}.php
					$templates[] = "{$dir}/singular-" . get_post_type() . "_format-" . get_post_format() . ".php";
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

			/* Front Page and Blog Page */
			if ( is_front_page() && is_singular() ){
				$templates[] = "{$dir}/_home.php";
			}
			elseif( is_home() ){
				$templates[] = "{$dir}/_blog.php";
			}
		}
	}

	/* allow developer to modify template */
	$templates = apply_filters( 'shell_atomic_template',  $templates, $dir, $loop );

	return locate_template( array_reverse( $templates ), true, false );
}


/**
 * Shell Get Template
 * Function for loading template with folder/dir support
 * this is a replacement for `get_template_part` and `get_sidebar`
 * To load sidebar use `sidebar` as $slug
 * 
 * @param $slug string directory/slug
 * @param $name file name to load
 * @since 0.2.0
 */
function shell_get_template( $slug, $name = null ){

	/* wp_hook compat */
	if ( 'sidebar' == $slug ){
		do_action( 'get_sidebar', $name );
	}
	else{
		do_action( "get_template_part_{$slug}", $slug, $name );
	}

	/* Start */
	$templates = array();
	$name = (string) $name;

	/* get_template_part compat */
	if ( '' !== $name ){
		$templates[] = "{$slug}-{$name}.php";
	}
	$templates[] = "{$slug}.php";

	/* sub-dir */
	if ( '' !== $name ){
		$templates[] = "{$slug}/{$name}.php";
	}
	$templates[] = "{$slug}/index.php";

	locate_template($templates, true, false);
}



/* DOCUMENTS TITLE, SITE TITLE, AND SITE DESCRIPTION
=============================================================== */

/**
 * shell_document_title()
 * Output current page title, this just a replacement for "wp_title()" function.
 * 
 * Output:
 * {sepatator} default is ": "
 * - front page (first page): "{site title}{separator} {site Description}"
 * - singular pages: "{post type title}"
 * - taxonomy archive pages: "{taxonomy title}"
 * - author archive pages: "{author name}"
 * - post type archive pages: "{post type archive title}"
 * - time based archive pages: "Archive for {time}" time: day, month, etc.
 * -- minute archive pages: "Archive for minute {minute}"
 * -- weekly archive pages: "Archive for week {week} of {year}"
 * - search result pages: 'Search results for "{seach term}"'
 * - 404 pages: "404 Not Found"
 * - paged page: "{current title}{separator} Page {page number}"
 * 
 * >> filter: "shell_document_title" (atomic)
 *            for regular usage, this filter should not be used.
 *            use "wp_title" filter instead.
 *            {@link http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_title }
 * >> filter: "wp_title"
 *            use this filter to modify output
 *
 * @param     none
 * @access    public
 * @uses      hybrid_document_title()  path: library/functions/context.php
 * @since     0.2.0
 */
function shell_document_title(){
	hybrid_document_title();
}


/**
 * shell_site_title()
 * Output the html of site title using dynamic heading tag.
 * Site title is defined on the General Settings page in the admin panel.
 *
 * Tag:
 * - on front page: "h1"
 * - other pages: "div"
 * 
 * To style this via css use the id of elements instead using the element tag:
 * - right: #site-title{}
 * - right: #site-title a{}
 * - wrong: #brading h1{}
 * - wrong: #brading h1 a{}
 * 
 * Output:
 * <{tag} id="site-title">
 *   <a rel="home" title="{site title}" href="{site url}">
 *       <span>{site title}</span>
 *   </a>
 * </{tag}>
 *
 * >> filter: "shell_site_title" (atomic)
 * 
 * @param     none
 * @access    public
 * @uses      hybrid_site_title               path library/functions/utillity.php
 * @since     0.2.0
 */
function shell_site_title(){
	hybrid_site_title();
}


/**
 * shell_site_description()
 * Output the html of site description using dynamic tag.
 * Site description/tag line is defined on the General Settings page in the admin panel.
 *
 * Tag:
 * - on front page: "h2"
 * - other pages: "div"
 * 
 * To style this via css use the id of elements instead using the element tag:
 * - right: #site-description{}
 * - wrong: #brading h2, #branding div{}
 * 
 * Example output:
 * <{tag} id="site-description">
 *    <span>{site-description}</span>
 * </{tag}>
 *
 * >> filter: "shell_site_description" (atomic)
 *
 * @param     none
 * @access    public
 * @uses      hybrid_site_description()       path library/functions/utillity.php
 * @since     0.2.0
 */
function shell_site_description(){
	hybrid_site_description();
}


/* HTML ATTRIBUTES
=============================================================== */

/**
 * shell_html_class()
 * Output dynamic HTML Class to target context in body class.
 * 
 * This classes is added as utillity class/helper class.
 * This classes is not added in body class for organization purpose.
 * 
 * Classes added:
 * - "not-singular" : all pages except singular pages
 * - "layout-2c" : if using layout 2c-l and 2c-r to easily target all 2 column layout.
 * - "{skin-id}_skin_active" : class to identify active skin 
 * - "js-disabled" : browser with javascript disabled, will be removed by "js/shell.js" javasript.
 * - "js-enabled" : browser with javascript enabled, this added via "js/shell.js" after removing "js-disabled" class.
 * - "shell-is-mobile" : using mobile browser detected by wp_is_mobile()
 * - "shell-is-not-mobile" : using non-mobile browser detected by wp_is_mobile()
 * - "shell-is-opera-mini" : using opera mini browser detected by wp_is_mobile() and Opera Mini user agent.
 * 
 * >> filter: "shell_html_class" (atomic)
 *
 * @param string|array $class additional classes to add; default: none
 * @return string e.g. "not-singular layout-2c js-disabled shell-is-not-mobile"
 * @uses is_singular() {@link http://codex.wordpress.org/Function_Reference/is_singular}
 * @uses current_theme_supports() {@link http://codex.wordpress.org/Function_Reference/current_theme_supports}
 * @uses wp_is_mobile() {@link http://codex.wordpress.org/Function_Reference/wp_is_mobile}
 * @uses theme_layouts_get_layout() {@link http://themehybrid.com/docs/tutorials/theme-layouts}
 * @uses apply_atomic()  {@link http://themehybrid.com/docs/tutorials/creating-custom-hooks}
 * @uses shell_active_skin() to check active skin.
 * @since 0.1.0
 */
function shell_html_class( $class = '' ){

	global $wp_query;

	/* default var */
	$classes = array();

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

	}

	/* Skins */
	$skin_count = count( shell_skins() );

	/* check if custom skin exist */
	if ( $skin_count > 1 ){

		/* get skin settings */
		$active_skin = shell_active_skin();

		/* if custom skin selected */
		if ( $active_skin && $active_skin != 'default' ){
			$classes[] = $active_skin . '-skin-active';
		}
	}

	/* Javascript disabled class, this will be changed to js-enable by theme js/shell.js */
	$classes[] = 'js-disabled';

	/* Mobile visitor */
	if ( wp_is_mobile() ){
		$classes[] = 'shell-is-mobile';

		/* Opera Mini Browser visitor */
		if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false ){
			$classes[] = 'shell-is-opera-mini';
		}
	}
	/* non-mobile visitor */
	else{
		$classes[] = 'shell-is-not-mobile';
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
	$classes = apply_atomic( 'html_class', $classes, $class ); //shell_html_class

	/* sanitize it */
	$classes = array_map( 'esc_attr', $classes );

	/* make it unique */
	$classes = array_unique( $classes );

	/* Join all the classes into one string. */
	$class = join( ' ', $classes );

	/* Print html class. */
	echo $class;
}


/**
 * shell_body_attributes()
 * Outputs the attributes for the <body> element.  By default, this is just the 'class' attribute, but 
 * developers can filter this to add other attributes.
 * 
 * >> filter: "shell_body_attributes" (atomic)
 *            do not use this filter to modify body class. use "body_class" instead
 *            use this filter to modify other other body attributes.
 * >> filter: "body_class"
 *            {@link http://codex.wordpress.org/Plugin_API/Filter_Reference/body_class}
 * 
 * @param     none
 * @access    public
 * @uses      hybrid_body_attributes() in path library/functions/context.php
 * @link      http://themehybrid.com/docs/functions/hybrid_body_attributes
 * @since     0.2.0
 */
function shell_body_attributes(){
	hybrid_body_attributes();
}


/**
 * shell_post_attributes()
 * Outputs the attributes for the main content <article> element. 
 * By default, this is just the 'class' attribute, but 
 * developers can filter this to add other attributes.
 * 
 * >> filter: "shell_post_attributes" (atomic)
 *            it's possible that this filter might changed in the future.
 *            do not use this filter to modify body class. use "body_class" instead
 * >> filter: "body_class"
 *            {@link http://codex.wordpress.org/Plugin_API/Filter_Reference/body_class}
 * 
 * @param     none
 * @access    public
 * @uses      hybrid_post_attributes()        path library/functions/context.php
 * @link      http://themehybrid.com/docs/functions/hybrid_body_attributes
 * @since     0.2.0
 */
function shell_post_attributes(){

	/* no error pages */
	if ( get_post() ){

		/* hybrid post attributes do not have $post check. */
		hybrid_post_attributes();
	}

	/* not found pages */
	else{

		$attributes = array();
		$output     = '';

		/* use post id = "0" */
		$attributes['id']    = 'post-0';
		$attributes['class'] = join( ' ', hybrid_get_post_class() );

		$attributes = apply_atomic( 'post_attributes', $attributes );

		foreach( $attributes as $attr => $value ){
			$output .= !empty( $value ) ? " {$attr}='{$value}'" : " {$attr}";
		}
		echo $output;
	}
}


/**
 * shell_comment_attributes()
 * Outputs the attributes for the comment wrapper.  By default, this is the 'class' and 'id' attributes, 
 * but developers can filter this to add other attributes.
 * 
 * >> filter: "shell_comment_attributes" (atomic)
 * 
 * @param     none
 * @access    public
 * @uses      hybrid_comment_attributes      path library/functions/context.php
 * @since     0.2.0
 */
function shell_comment_attributes(){
	hybrid_comment_attributes();
}




/* CONTENT
=============================================================== */

/**
 * Loop Meta Title.
 * 
 * @since 0.1.0
 */
function shell_loop_meta_title(){

	global $wp_query;

	/* attr */
	$attr = array(
		'before' => '<h1 class="loop-title">',
		'after' => '</h1>' );

	/* default var */
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
			$current = post_type_archive_title( '', false );
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
		$current = esc_attr( get_search_query() );

	/* Filter it */
	$current = apply_atomic( 'loop_meta_title', $current );

	/* Format title */
	if ( !empty( $current ) ){
		$current = $attr['before'] . $current . $attr['after'] . "\n";
	}

	/* Print the title to the screen. */
	return $current;
}


/**
 * Loop Meta Description.
 * 
 * @since 0.1.0
 */
function shell_loop_meta_description(){

	global $wp_query;

	/* attr */
	$attr = array(
		'before' => '<div class="loop-description">',
		'after' => '</div>' );

	/* Set an empty $description variable. */
	$description = '';

	/* If viewing the posts page or a singular post. */
	if ( is_home() && !is_front_page()  ) {

		$description = get_post_field( 'post_excerpt', get_queried_object_id() );

		if ( !empty( $description ) )
			$description = '<p>' . $description . '</p>';
	}

	/* If viewing an archive page. */
	elseif ( is_archive() ) {

		/* If viewing a taxonomy term archive, get the term's description. */
		if ( is_category() || is_tag() || is_tax() )
			$description = term_description( '', get_query_var( 'taxonomy' ) );

		/* If viewing a custom post type archive. */
		elseif ( is_post_type_archive() ) {

			/* Get the post type object. */
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );

			/* If a description was set for the post type, use it. */
			if ( isset( $post_type->description ) )
				$description = '<p>' . $post_type->description . '</p>';
		}
	}

	/* Filter it */
	$description = apply_atomic( 'loop_meta_description', $description );

	/* loop description. */
	if ( !empty( $description ) ){
		$description = $attr['before'] . $description . $attr['after'] . "\n";
	}

	return $description;
}

/**
 * Add Thumbnail in archive type pages.
 * Added in 'shell_open_entry' hook in content area, all files is in "content" folder.
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
 * Add WP Link Pages
 * Added in 'shell_close_entry_summary' hook in archive content area, all files is in "content" folder.
 * 
 * @since 0.1.0
 */
function shell_summary_wp_link_pages(){
	wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'shell' ), 'after' => '</p>' ) );
}

/**
 * More link text.
 * Used as Args in the_content() function
 * 
 * @since 0.2.0
 */
function shell_the_content_more(){
	return apply_atomic( 'the_content_more', __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'shell' ) );
}


/**
 * Entry Meta
 * a helper function to return shortcode with all taxonomy/term attach to a post.
 * Usage:
 * echo apply_atomic_shortcode( 'entry_meta', shell_entry_meta() );
 * 
 * @since 0.2.1
 */
function shell_entry_meta(){

	/* Default */
	$entry_meta = '';

	/* Entry Taxonomies */
	$entry_taxonomies = array();

	/* Get Taxonomies Object */
	$entry_taxonomies_obj = get_object_taxonomies( get_post_type(), 'object' );
	foreach ( $entry_taxonomies_obj as $entry_tax_id => $entry_tax_obj ){

		/* Only for public taxonomy */
		if ( 1 == $entry_tax_obj->public ){
			$entry_taxonomies[$entry_tax_id] = array(
				'taxonomy' => $entry_tax_id,
				'text' => $entry_tax_obj->labels->name,
			);
		}
	}

	/* If taxonomies not empty */
	if ( !empty( $entry_taxonomies ) ){

		$entry_meta .= '<div class="entry-meta">';
		foreach ( $entry_taxonomies as $entry_tax ){
			$sep = "<span class='term-sep'>, </span>";
			$entry_meta .= '[entry-terms taxonomy="' . $entry_tax['taxonomy'] . '" before="' . $entry_tax['text'] . ': " separator="' . $sep . '"] ';
		}
		$entry_meta .= '</div><!-- .entry-meta -->';

	} //end empty check

	return $entry_meta;
}


/**
 * Attachment Gallery
 * Added in 'shell_after_entry_content' hook in content/attachment-image.php
 * Hook added with atomic hook 'attachment-image' using 'shell_attachment-image_after_entry_content' hook.
 * 
 * @since 0.1.0
 */
function shell_attachment_gallery(){
	global $post;
	echo do_shortcode( sprintf( '[gallery id="%1$s" exclude="%2$s" columns="8"]', $post->post_parent, $post->ID ) );
}



/**
 * shell_attachment()
 * Loads the correct function for handling attachments.  Checks the attachment mime type to call 
 * correct function. Image attachments are not loaded with this function.
 * 
 * >> filter: "shell_{$type}_attachment" (atomic)
 * >> filter: "shell_attachment" (atomic)
 * 
 * @param     none
 * @access    public
 * @uses      hybrid_attachment()            path library/functions/media.php
 * @since     0.2.0
 */
function shell_attachment(){
	hybrid_attachment();
}

/* COMMENTS
--------------------------------------------------------------- */

/**
 * shell_avatar()
 * Displays the avatar for the comment author and wraps it in the comment author's URL if it is
 * available.
 * 
 * >> filter: "shell_avatar" (atomic)
 * 
 * @param     none
 * @access    public
 * @uses      hybrid_avatar()                path library/functions/comments.php
 * @since     0.2.0
 */
function shell_avatar(){
	hybrid_avatar();
}


/**
 * shell_list_comments_args()
 * Arguments for the wp_list_comments_function() used in comments.php.
 * 
 * >> filter: "shell_list_comments_args" atomic
 * 
 * @param     none
 * @access    public
 * @uses      hybrid_list_comments_args()    path library/functions/comments.php
 * @since     0.2.0
 */
function shell_list_comments_args(){
	return hybrid_list_comments_args();
}



/* NAVIGATIONS
=============================================================== */

/* MENUS
--------------------------------------------------------------- */


/**
 * Load Menu Primary
 * Loaded in 'shell_before_header' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_get_menu_primary(){
	shell_get_template( 'menu', 'primary' ); // menu-primary.php
}


/**
 * Load Menu Secondary
 * Loaded in 'shell_after_header' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_get_menu_secondary(){
	shell_get_template( 'menu', 'secondary' ); // menu-secondary.php
}


/**
 * Load Menu Primary and Secondary in footer
 * this is for mobile device < 480px browser width and js-disabled
 * Loaded in 'shell_before_footer' hook in footer.php
 * 
 * @since 0.2.0
 */
function shell_get_menu_mobile_bottom(){

	/* Check mobile user agent */
	if ( wp_is_mobile() ){

		shell_get_template( 'menu', 'bottom' ); // load menu-bottom.php
	}
}

/**
 * Load Menu Subsidiary
 * Loaded in 'shell_before_footer' hook in footer.php
 * 
 * @since 0.1.0
 */
function shell_get_menu_subsidiary(){
	shell_get_template( 'menu', 'subsidiary' ); // menu-subsidiary.php
}


/**
 * Mobile Menu Primary HTML.
 * Added in 'shell_open_menu_primary' hook in menu-primary.php
 * 
 * @since 0.2.0
 */
function shell_mobile_menu_primary(){?>
<div id="mobile-menu-button-primary" class="mobile-menu-button" title="navigation">
	<span><a href="#menu-primary-bottom"><?php echo shell_get_menu_name( 'primary' ); ?></a></span>
</div><?php
}


/**
 * Mobile Menu Secondary HTML.
 * Added in 'shell_open_menu_secondary' hook in menu-secondary.php
 * 
 * @since 0.2.0
 */
function shell_mobile_menu_secondary(){?>
<div id="mobile-menu-button-secondary" class="mobile-menu-button" title="navigation">
	<span><a href="#menu-secondary-bottom"><?php echo shell_get_menu_name( 'secondary' ); ?></a></span>
</div><?php
}


/**
 * Get Menu Location
 * Helper function to get menu location and use it as mobile toggle.
 *
 * @link http://wordpress.stackexchange.com/questions/45700
 * @since 0.2.1
 */
function shell_get_menu_name( $location ){
	if ( has_nav_menu( $location ) ){
		$locations = get_nav_menu_locations();
		if( ! isset( $locations[$location] ) ) return false;
		$menu_obj = get_term( $locations[$location], 'nav_menu' );
		return $menu_obj->name;
	}
}


/* BREADCRUMBS
--------------------------------------------------------------- */


/**
 * Add Breadcrumb Trail
 * Added in 'shell_open_main' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_breadcrumb(){
	if ( current_theme_supports( 'breadcrumb-trail' ) ){
		breadcrumb_trail( array(
			'before'           => __( 'You are here:', 'shell' ),
			'show_browse'      => false,
			'after'            => shell_edit_link()
		));
	}
}




/**
 * Edit Link for singular post and taxonomy
 * Code from wordpress tool bar/admin bar.
 *
 * @since 0.1.0
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



/* SIDEBARS
=============================================================== */

/**
 * Load Sidebar Header
 * Loaded in 'shell_header' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_header(){
	shell_get_template( 'sidebar', 'header' ); // sidebar-header.php
}


/**
 * Load Sidebar Primary and Secondary
 * Loaded in 'shell_sidebar' hook in footer.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar(){
	shell_get_template( 'sidebar', 'primary' ); // sidebar-primary.php
	shell_get_template( 'sidebar', 'secondary' ); // sidebar-secondary.php
}


/**
 * Load Sidebar Subsidiary
 * Loaded in 'shell_after_main' hook in footer.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_subsidiary(){
	shell_get_template( 'sidebar', 'subsidiary' ); // sidebar-subsidiary.php
}


/**
 * Load Sidebar After Singular
 * Loaded in 'shell_after_singular' hook in singular.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_after_singular(){
	shell_get_template( 'sidebar', 'after-singular' ); // sidebar-after-singular.php
}




/* FOOTER
=============================================================== */

/**
 * Shell Footer Content
 * So we can remove support for hybrid core settings if needed.
 * 
 * @since 0.1.0
 */
function shell_footer_content(){

	/* default var */
	$content = '';

	/* If there is a child theme active, add the [child-link] shortcode to the $footer_insert. */
	if ( is_child_theme() ){
		$content = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'shell' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link], [theme-link], and [child-link].', 'shell' ) . '</p>';
	}

	/* If no child theme is active, leave out the [child-link] shortcode. */
	else{
		$content = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'shell' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link] and [theme-link].', 'shell' ) . '</p>';
	}

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
