<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the 
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins. 
 *
 * @package Shell
 * @subpackage Template
 * @since 0.1.0
 */
?>
<!DOCTYPE html>
<?php
/**
 * language_attributes()
 * Output language attributes for the <html> tag
 *
 * Added inside the HTML <html> tag, and outputs various HTML
 * language attributes, such as language and text-direction.
 *
 * >> filter: "language_attributes"
 *
 * @param null
 * @return string e.g. dir="ltr" lang="en-US"
 *
 * @author WordPress
 * @link http://codex.wordpress.org/Function_Reference/language_attributes
 */

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
 * 
 * @uses is_singular() {@link http://codex.wordpress.org/Function_Reference/is_singular}
 * @uses current_theme_supports() {@link http://codex.wordpress.org/Function_Reference/current_theme_supports}
 * @uses wp_is_mobile() {@link http://codex.wordpress.org/Function_Reference/wp_is_mobile}
 *
 * @uses theme_layouts_get_layout() {@link http://themehybrid.com/docs/tutorials/theme-layouts}
 * @uses apply_atomic()  {@link http://themehybrid.com/docs/tutorials/creating-custom-hooks}
 *
 * @uses shell_active_skin() to check active skin.
 * 
 * @package Shell
 * @since 0.1.0
 */
?>
<html <?php language_attributes(); ?> class="<?php shell_html_class(); // shell_html_class ?>">
<head>
<?php
/**
 * bloginfo( 'html_type' )
 * Output the site HTML type.
 *
 * The 'html_type' parameter is the document HTML type
 *  - Defined on the General Settings page in the administration panel
 *  - Usually "text/html"
 *
 * @param string $show e.g. 'html_type'; default: none
 * @return string e.g. "text/html"
 *
 * @package WordPress
 * @link http://codex.wordpress.org/Template_Tags/bloginfo
 * @link http://codex.wordpress.org/Function_Reference/get_bloginfo
 */

/**
 * bloginfo( 'charset' )
 * Output the site Character Set.
 *
 * The 'charset' parameter is the document character set
 *  - Defined in wp-config.php
 *  - Usually "UTF-8"
 *
 * @param string $show e.g. 'html_type'; default: none
 * @return string e.g. "text/html"
 *
 * @package WordPress
 * @link http://codex.wordpress.org/Template_Tags/bloginfo
 * @link http://codex.wordpress.org/Function_Reference/get_bloginfo
 * @link http://codex.wordpress.org/Function_Reference/language_attributes
 */
?>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<?php
/**
 * hybrid_document_title()
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
 * >> file: library/functions/context.php
 * >> filter: "shell_document_title" (atomic)
 *            this filter should not be used. use "wp_title" filter instead.
 *            it's possible that this filter might changed in the future.
 * >> filter: "wp_title"
 *            use this filter to modify output
 *
 * @return string current page title.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_title
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_title
 * 
 * @package HybridCore
 */
?>
<title><?php hybrid_document_title(); ?></title>
<!-- Mobile viewport optimized -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php
/**
 * bloginfo( 'pingback_url' )
 * Displays the Pingback XML-RPC file URL (xmlrpc.php). 
 * 
 * @package WordPress
 * @link http://codex.wordpress.org/Template_Tags/bloginfo
 * @link http://codex.wordpress.org/Function_Reference/get_bloginfo
 */
?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
/**
 * wp_head()
 * Fire the 'wp_head' action hook
 * 
 * This hook is used by WordPress core, Themes, and Plugins to 
 * add scripts, CSS styles, meta tags, etc. to the document head.
 * 
 * MUST come immediately before the closing </head> tag
 * 
 * >> action: "wp_head"
 * 
 * @param null
 * @return mixed any output hooked into 'wp_head'
 * 
 * @package WordPress
 * @link http://codex.wordpress.org/Hook_Reference/wp_head
 */
wp_head(); ?>
</head>

<?php
/**
 * hybrid_body_attributes()
 * Outputs the attributes for the <body> element.  By default, this is just the 'class' attribute, but 
 * developers can filter this to add other attributes.
 * 
 * >> filter: "shell_body_attributes" (atomic)
 *            it's possible that this filter might changed in the future.
 *            do not use this filter to modify body class. use "body_class" instead
 * >> filter: "body_class"
 *            {@link http://codex.wordpress.org/Plugin_API/Filter_Reference/body_class}
 * 
 * @param none
 * @return string
 * 
 * @package HybridCore
 * @link http://themehybrid.com/docs/functions/hybrid_body_attributes
 */
?>
<body <?php hybrid_body_attributes(); ?>>

	<?php
	/**
	 * "shell_open_body" (atomic action hook)
	 * @since 0.1.0
	 */
	do_atomic( 'open_body' ); ?>

	<div id="container">

		<?php
		/**
		 * "shell_before_header" (atomic action hook)
		 * menu-primary.php is loaded in this hook.
		 * 
		 * @see shell_get_menu_primary()
		 * @since 0.1.0
		 */
		do_atomic( 'before_header' ); ?>

		<div id="header">

			<?php
			/**
			 * "shell_open_header" (atomic action hook)
			 * @since 0.1.0
			 */
			do_atomic( 'open_header' ); ?>

			<div class="wrap">

				<?php
				/**
				 * "shell_before_branding" (atomic action hook)
				 * @since 0.1.0
				 */
				do_atomic( 'before_branding' ); ?>

				<div id="branding">

					<?php
					/**
					 * "shell_open_branding" (atomic action hook)
					 * @since 0.1.0
					 */
					do_atomic( 'open_branding' ); ?>

					<?php
					/**
					 * hybrid_site_title()
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
					 * Example output:
					 * <{tag} id="site-title">
					 *   <a rel="home" title="{site title}" href="{site url}">
					 *       <span>{site title}</span>
					 *   </a>
					 * </{tag}>
					 *
					 * >> file: library/functions/utillity.php
					 * >> filter: "shell_site_title" (atomic)
					 * 
					 * @package HybridCore
					 */
					hybrid_site_title(); ?>
					<?php
					/**
					 * hybrid_site_description()
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
					 * >> file: library/functions/utillity.php
					 * >> filter: "shell_site_description" (atomic)
					 *
					 * @package HybridCore
					 */
					hybrid_site_description(); //  ?>

					<?php
					/**
					 * "shell_close_branding" (atomic action hook)
					 * @since 0.1.0
					 */
					do_atomic( 'close_branding' ); ?>

				</div><!-- #branding -->

				<?php do_atomic( 'after_branding' ); // shell_after_branding ?>

				<?php
				/**
				 * "shell_header" (atomic action hook)
				 * sidebar-header.php is loaded in this hook
				 * 
				 * @see shell_get_sidebar_header()
				 * @since 0.1.0
				 */
				do_atomic( 'header' ); //  ?>

			</div><!-- .wrap -->

			<?php
			/**
			 * "shell_close_header" (atomic action hook)
			 * @since 0.1.0
			 */
			do_atomic( 'close_header' ); ?>

		</div><!-- #header -->

		<?php
		/**
		 * "shell_after_header"
		 * menu-secondary.php is loaded in this hook.
		 * 
		 * @see shell_get_menu_secondary()
		 * @since 0.1.0
		 */
		do_atomic( 'after_header' ); ?>

		<?php
		/**
		 * "shell_before_main"
		 * @since 0.1.0
		 */
		do_atomic( 'before_main' ); ?>

		<div id="main">

			<div class="wrap">

			<?php
			/**
			 * "shell_open_main"
			 * Breadcrumb trail is added in this hook
			 * 
			 * @see shell_breadcrumb()
			 * @since 0.1.0
			 */
			do_atomic( 'open_main' ); ?>