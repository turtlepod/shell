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
<html <?php language_attributes(); ?> class="<?php shell_html_class(); // shell_html_class ?>">
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php shell_document_title(); ?></title>
<!-- Mobile viewport optimized -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); // "wp_head" ?>
</head>

<body <?php shell_body_attributes(); ?>>

	<?php do_atomic( 'open_body' ); // "shell_open_body" ?>

	<div id="container">

		<?php do_atomic( 'before_header' ); // "shell_before_header" ?>

		<div id="header">

			<?php do_atomic( 'open_header' ); // "shell_open_header" ?>

			<div class="wrap">

				<?php do_atomic( 'before_branding' ); // "shell_before_branding" ?>

				<div id="branding">

					<?php do_atomic( 'open_branding' ); // "shell_open_branding" ?>

					<?php shell_site_title(); // "shell_site_title" ?>
					<?php shell_site_description(); // "shell_site_description" ?>

					<?php do_atomic( 'close_branding' ); // "shell_close_branding" ?>

				</div><!-- #branding -->

				<?php do_atomic( 'after_branding' ); // "shell_after_branding" ?>

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

			<?php do_atomic( 'close_header' ); // "shell_close_header" ?>

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

		<?php do_atomic( 'before_main' ); //"shell_before_main" ?>

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