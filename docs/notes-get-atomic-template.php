

/* LET'S CALL IT ATOMIC TEMPLATE */

the function:
shell_get_atomic_template('content');
shell_get_atomic_template('menu-primary');
----
what this do is to get template part (locate template) files based on context and post data.

example: "shell_get_atomic_template('content');"


/* as fallback */
atomic-content/index.php
atomic-content/index_post.php
atomic-content/index_post-sticky.php
atomic-content/index_post-format-video.php
atomic-content/index_page.php
atomic-content/index_product.php
atomic-content/index_product-format-chat.php


/* in "blog" context */
atomic-content/blog.php
atomic-content/blog_post.php
atomic-content/blog_post-sticky.php
atomic-content/blog_post-format-video.php
atomic-content/blog_post-format-image.php
atomic-content/blog_page.php
atomic-content/blog_product.php
atomic-content/blog_product-format-video.php

/* in "tax category" */
atomic-content/archive-taxonomy-category.php
atomic-content/archive-taxonomy-category_post.php
atomic-content/archive-taxonomy-category_product.php

/* in "cpt product" archive */
atomic-content/archive-product.php
atomic-content/archive-product_product.php //the same
atomic-content/archive-product_product-format-video.php //the same
atomic-content/archive-product_product-format-image.php //the same



/* WRAP IT UP: example */
shell_get_atomic_template('content');
---------------------------------------
{content}/{current-context}_post-sticky.php //special
{content}/{current-context}_{post-type}.php
{content}/{current-context}_{post-type}-format-{post-format}.php
{content}/{current-context}_post-sticky.php

get_template_part( 'content', ( post_type_supports( get_post_type(), 'post-formats' ) ? ( false === get_post_format() ? get_post_type() : get_post_type() . '-format-' . get_post_format() ) : get_post_type() ) );
<?
$file = array();
if ( post_type_supports( get_post_type(), 'post-formats' ) ){
	if ( false === get_post_format() ){
		$file =  get_post_type();
	}
	else{
		$file = get_post_type() . '-format-' . get_post_format();
	}
}
else{
	$file = get_post_type();
}
?>
<?php
function shell_get_atomic_template( $dir, $loop = false ) {

	/* array of available templates */
	$templates = array();

	/* get theme path  */
	$theme_dir = trailingslashit( THEME_DIR ) . $template;
	$child_dir = trailingslashit( CHILD_THEME_DIR ) . $template;

	if ( is_dir( $child_dir ) || is_dir( $theme_dir ) ) {

		/* index.php in folder are fallback template */
		$templates[] = "{$dir}/index.php";
	}
	else{
		return null; //null if no dir found
	}

	/* get current page (atomic) contexts */
	foreach ( hybrid_get_context() as $context ){

		/* add context based template */
		$templates[] = "{$dir}/{$context}.php";

		/* if context is in the loop, ( how to check if it's in the loop? ) */
		if ( true === $loop ) ){

			/* file based on post data */
			$files = array();

			/* current post - post type */
			$files[] = get_post_type();

			/* if post type support post-formats */
			if ( post_type_supports( get_post_type(), 'post-formats' ) ){
				$files[] = get_post_type() . '-format-' . get_post_format();
			}

			/* add file based on post type and post format */
			foreach ( $files as $file ){
				$templates[] = "{$dir}/{$file}.php";
				$templates[] = "{$dir}/{$context}_{$file}.php";
			}
		}
	}
	return locate_template( array_reverse( $templates ), true, false );
}




?>

	$templates = array();

	$theme_dir = trailingslashit( THEME_DIR ) . $template;
	$child_dir = trailingslashit( CHILD_THEME_DIR ) . $template;

	if ( is_dir( $child_dir ) || is_dir( $theme_dir ) ) {
		$dir = true;
		$templates[] = "{$template}/index.php";
	}
	else {
		$dir = false;
		$templates[] = "{$template}.php";
	}

	foreach ( hybrid_get_context() as $context )
		$templates[] = ( ( $dir ) ? "{$template}/{$context}.php" : "{$template}-{$context}.php" );

	return locate_template( array_reverse( $templates ), true );
}





?>

atomic-content/index-post.php

in blog (home) content:
atomic-content/blog-index.php

if it's a post (post type):
atomic-content/blog-post.php
atomic-content/blog-page.php (or other post type)
atomic-content/blog-product.php (cpt product)

if it's sticky post:
atomic-content/blog-post-sticky.php

if it has post format:
atomic-content/blog-post-format-image.php
atomic-content/blog-post-format-video.php
atomic-content/blog-product-format-chat.php









the folders:

atomic-template/
=================
this holds all default shelll_get_atomic_template();
-----------------










