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
 * @package Shell
 * @subpackage Functions
 * @version 0.1.0
 * @since 0.1.0
 * @author David Chandra Purnama <david@shellcreeper.com>
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
 * @since  0.1.0
 * @access public
 * @return void
 */
function shell_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
	
	/* Add theme settings. */
	if ( is_admin() )
		require_once( trailingslashit ( get_template_directory() ) . 'admin/admin.php' );

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'secondary', 'subsidiary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'secondary', 'header', 'subsidiary', 'after-singular' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	if ( !wp_is_mobile() ) // only add drop down script in non-mobile device
		add_theme_support( 'hybrid-core-scripts', array( 'drop-downs' ) );

	/* Add theme support for framework extensions. */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r' ) );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );

	/* Add media queries css */
	add_filter( "{$prefix}_styles", 'shell_styles' );

	/* Enqueue script. */
	add_action( 'wp_enqueue_scripts', 'shell_script');

	/* Add respond.js and  html5shiv.js for unsupported browsers. */
	add_action( 'wp_head', 'shell_respond_html5shiv' );

	/* Default settings */
	add_filter( "{$prefix}_default_theme_settings", 'shell_default_settings' );

	/* Skin in Customizer */
	add_action( 'customize_register', 'shell_customizer_register' );

	/* Set the content width. */
	hybrid_set_content_width( 600 );

	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'shell_embed_defaults' );

	/* Filter the sidebar widgets by current page theme layout */
	add_filter( 'sidebars_widgets', 'shell_disable_sidebars' );

	/* Switch to one column layout if no sidebar active or in attachment pages  */
	add_action( 'template_redirect', 'shell_one_column' );

	/* Load Sidebar Template Files */
	add_action( "{$prefix}_header", 'shell_get_sidebar_header' ); // sidebar-header.php
	add_action( "{$prefix}_sidebar", 'shell_get_sidebar' ); // sidebar-primary.php and sidebar-secondary.php
	add_action( "{$prefix}_after_main", 'shell_get_sidebar_subsidiary' ); // sidebar-subsidiary.php
	add_action( "{$prefix}_after_singular", 'shell_get_sidebar_after_singular' ); // sidebar-after-singular.php

	/* Load Menu Template Files */
	add_action( "{$prefix}_before_header", 'shell_get_menu_primary' ); // menu-primary.php
	add_action( "{$prefix}_after_header", 'shell_get_menu_secondary' ); // menu-secondary.php
	add_action( "{$prefix}_before_footer", 'shell_get_menu_subsidiary' ); // menu-subsidiary.php
	add_action( "{$prefix}_before_footer", 'shell_get_menu_mobile_bottom' ); // menu-primary-bottom.php, menu-secondary-bottom.php

	/* Load searchform.php Template File */
	add_action( "{$prefix}_close_menu_secondary", 'get_search_form' );

	/* Add mobile menu */
	add_action( "{$prefix}_open_menu_primary", 'shell_mobile_menu_primary' );
	add_action( "{$prefix}_open_menu_secondary", 'shell_mobile_menu_secondary' );

	/* Add breadcrumb Trail */
	add_action( "{$prefix}_open_main", 'shell_breadcrumb' );
	add_filter( 'breadcrumb_trail_args', 'shell_breadcrumb_trail_args' ); // add edit link

	/* Add thumbnail */
	add_action( "{$prefix}_open_entry", 'shell_thumbnail' );

	/* Add wp_link_pages in entry summary (after excerpt) */
	add_action( "{$prefix}_close_entry_summary", 'shell_summary_wp_link_pages' );

	/* Add gallery in attachment image page */
	add_action( "{$prefix}_attachment-image_after_entry_content", 'shell_attachment_gallery' );

	/* Avatar size in comments: Hybrid Core - Comments Args */
	add_filter( "shell_list_comments_args", 'shell_comments_args' );

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

	/* Add editor style */
	add_editor_style();
	add_filter( 'mce_css', 'shell_theme_layout_editor_style' );

	/* Updater args */
	$updater_args = array(
		'repo_uri' => 'http://repo.shellcreeper.com/',
		'repo_slug' => 'shell',
	);

	/* Add support for updater */
	add_theme_support( 'auto-hosted-theme-updater', $updater_args );
}


/**
 * Load Updater Class
 * @since 0.1.2
 * @link http://autohosted.com/
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/theme-updater.php' );
new Shell_Theme_Updater;


/**
 * Load Deprecated Functions
 * @since 0.2.0
 */
require_once( trailingslashit( get_template_directory() ) . 'includes/deprecated.php' );


/* function check to enable override/disable custom background feature from child theme  */
if( !function_exists( 'shell_custom_background' ) ){

	/* setup custom background on the 'after_setup_theme' hook. */
	add_action( 'after_setup_theme', 'shell_custom_background' );

	/**
	 * Add custom background theme support.
	 * Child theme can override default by creating 'shell_custom_background' function.
	 * Child theme can disable it by creating blank 'shell_custom_background' function.
	 * 
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	function shell_custom_background(){
		add_theme_support( 'custom-background', array( 'default-color' => 'f9f9f9' ) );
	}
}


/* Shell load style */
add_action( 'after_setup_theme', 'shell_load_style', 11 );

/**
 * Shell Core Style
 * Add in priority 11 for easier child theme filter
 * 
 * @since 0.2.0
 */
function shell_load_style(){

	// Hybrid Core Style
	if ( is_child_theme() && apply_filters( 'shell_parent_css', true ) ){ // in child theme
		add_theme_support( 'hybrid-core-styles', array( 'parent', 'media-queries', 'skin', 'style' ) );
	}
	else { // in parent theme
		add_theme_support( 'hybrid-core-styles', array( 'style', 'media-queries', 'skin' ) );
	}
}


/**
 * Add Media Queries Stylesheet to 'hybrid-core-styles' feature. 
 * Child theme can override media queries css file by creating media-queries.css file in root folder.
 *
 * @since  0.1.0
 * @param  array $styles Hybrid Core Styles
 * @return array
 */
function shell_styles( $styles ) {

	/* media queries dependency */
	$deps = null;
	if ( file_exists( get_stylesheet_directory() . '/media-queries.css' ))
		$deps = array('style');

	/* Media Queries CSS */
	$styles['media-queries'] = array(
		'src'		=> hybrid_locate_theme_file( 'media-queries.css' ),
		'version'	=> shell_theme_file_version( 'media-queries.css' ),
		'media'		=> 'all',
		'deps'		=> $deps,
	);

	return $styles;
}


/**
 * Get the version of theme file using theme version. 
 * 
 * @since 0.1.0
 */
function shell_theme_file_version( $file ){
	$theme = wp_get_theme( get_template() );
	if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $file )){
		$theme = wp_get_theme();
	}
	return $theme->get( 'Version' );
}


/**
 * Register and Enqueue Mobile Menu Script and Fitvids.
 * 
 * @since  0.1.0
 * @access public
 * @return void
 */
function shell_script(){

	/* do not load on admin */
	if ( !is_admin() ) {

		/*  Mobile Menu Script */
		$shell_menu_file = hybrid_locate_theme_file( 'js/shell-menu.js' );
		$shell_menu_version = shell_theme_file_version( 'js/shell-menu.js' );
		wp_enqueue_script( 'shell-menu', $shell_menu_file, array('jquery'), $shell_menu_version, true );

		/*  Theme Script */
		$shell_js_file = hybrid_locate_theme_file( 'js/shell.js' );
		$shell_js_version = shell_theme_file_version( 'js/shell.js' );
		wp_enqueue_script( 'shell-js', $shell_js_file, array('jquery'), $shell_js_version, true );

		/* Enqueue FitVids */
		$fitvids_file = hybrid_locate_theme_file( 'js/fitvids.min.js' );
		$fitvids_version = shell_theme_file_version( 'js/fitvids.min.js' );
		wp_enqueue_script( 'shell-fitvids', $fitvids_file, array( 'jquery' ), $fitvids_version, true );
	}
}


/**
 * Function for help to unsupported browsers understand mediaqueries and html5.
 * This is added in 'head' using 'wp_head' hook.
 *
 * @link: https://github.com/scottjehl/Respond
 * @link: http://code.google.com/p/html5shiv/
 * @since 0.1.0
 */
function shell_respond_html5shiv() {
	?><!-- Enables media queries and html5 in some unsupported browsers. -->
	<!--[if (lt IE 9) & (!IEMobile)]>
	<script type="text/javascript" src="<?php echo hybrid_locate_theme_file( 'js/respond.min.js' ); ?>"></script>
	<script type="text/javascript" src="<?php echo hybrid_locate_theme_file( 'js/html5shiv.min.js' ); ?>"></script>
	<![endif]-->
<?php
}


/**
 * Shell Skins 
 * Created to give developers an easy way to create multiple skins for Shell theme from plugins or Child Theme.
 * Developers can register skin using 'shell_skins' filter.
 * Available data for skins:
 * - 'name'			Name of skins registered (required)
 * - 'version'		Version of skins (optional)
 * - 'screenshot'	URI of screenshot or thumbnail image file, 300px x 225px (optional)
 * - 'author'		Author name (optional)
 * - 'author_uri'	URI for author website (optional)
 * - 'description'	Short description of skins (optional)
 * 
 * @since  0.1.0
 * @param  none
 * @return array of skins
 */
function shell_skins(){

	/* Theme version */
	$theme = wp_get_theme( get_template() );
	$version = $theme->get( 'Version' );

	/* Default skin / no skin selected */
	$skins = array( 'default' => array(
		'name' => 'Default',
		'version' => $version,
		'screenshot' => get_template_directory_uri() . '/screenshot.png',
		'author' => 'David Chandra Purnama',
		'author_uri' => 'http://shellcreeper.com/',
		'description' => __( 'This is default skin for Shell Theme.', 'shell' ),
	));

	/* enable developer to add skins */
	return apply_filters( 'shell_skins', $skins );
}


/**
 * Get Active Skin ID. Get current active skin. Allow developer to filter active skin.
 * 
 * @since 0.1.0
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
 * Default Skin Settings for Hybrid Core Settings.
 * 
 * @since  0.1.0
 * @param  array $settings Hybrid Core Settings
 * @return array
 */
function shell_default_settings( $settings ){
	$settings['skin'] = 'default';
	return $settings;
}


/**
 * Add Skin Settings in Customize.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since 0.1.0
 */
function shell_customizer_register( $wp_customize ) {

	/* Skin Count */
	$skin_count = count( shell_skins() );

	/* Add skin to customizer only if additional skin available */
	if ( $skin_count > 1 ){

		/* Default settings, no need to call hybrid core default setting. */
		$default = 'default';

		/* Get list of available skin */
		$skins = shell_skins();
		$skin_choices = array();
		foreach ( $skins as $skin_id => $skin_data ){
			$skin_choises[$skin_id] = $skin_data['name'];
		}

		/* Section */
		$wp_customize->add_section( 'shell_customize_skin', array(
			'title' => _x( 'Skins', 'customizer', 'shell' ),
			'priority' => 30,
		));
		/* Settings */
		$wp_customize->add_setting( 'shell_theme_settings[skin]', array(
			'default' => $default['skin'],
			'type' => 'option',
		));
		/* Control */
		$wp_customize->add_control( 'shell_theme_settings[skin]', array(
			'label' =>_x( 'Skins', 'customizer', 'shell' ),
			'section' => 'shell_customize_skin',
			'settings' => 'shell_theme_settings[skin]',
			'type' => 'select',
			'choices' => $skin_choices,
		));
	}
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
		if ( 'layout-1c' == $layout )
			$args['width'] = 930;
		else
			$args['width'] = 600;
	}
	else
		$args['width'] = 600;

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
 * Load Sidebar Header
 * Loaded in 'shell_header' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_header(){
	get_sidebar( 'header' ); // sidebar-header.php
}


/**
 * Load Sidebar Primary and Secondary
 * Loaded in 'shell_sidebar' hook in footer.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar(){
	get_sidebar( 'primary' ); // sidebar-primary.php
	get_sidebar( 'secondary' ); // sidebar-secondary.php
}


/**
 * Load Sidebar Subsidiary
 * Loaded in 'shell_after_main' hook in footer.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_subsidiary(){
	get_sidebar( 'subsidiary' ); // sidebar-subsidiary.php
}


/**
 * Load Sidebar After Singular
 * Loaded in 'shell_after_singular' hook in singular.php
 * 
 * @since 0.1.0
 */
function shell_get_sidebar_after_singular(){
	get_sidebar( 'after-singular' ); // sidebar-after-singular.php
}


/**
 * Load Menu Primary
 * Loaded in 'shell_before_header' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_get_menu_primary(){
	get_template_part( 'menu', 'primary' ); // menu-primary.php
}


/**
 * Load Menu Secondary
 * Loaded in 'shell_after_header' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_get_menu_secondary(){
	get_template_part( 'menu', 'secondary' ); // menu-secondary.php
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

		get_template_part( 'menu', 'bottom' ); // load menu-bottom.php
	}
}

/**
 * Load Menu Subsidiary
 * Loaded in 'shell_before_footer' hook in footer.php
 * 
 * @since 0.1.0
 */
function shell_get_menu_subsidiary(){
	get_template_part( 'menu', 'subsidiary' ); // menu-subsidiary.php
}


/**
 * Mobile Menu Primary HTML.
 * Added in 'shell_open_menu_primary' hook in menu-primary.php
 * 
 * @since 0.2.0
 */
function shell_mobile_menu_primary(){?>
<div class="mobile-menu-button" title="navigation">
	<span><a href="#menu-primary-bottom"><?php _ex( 'Navigation', 'mobile-menu', 'shell' ); ?></a></span>
</div><?php
}


/**
 * Mobile Menu Secondary HTML.
 * Added in 'shell_open_menu_secondary' hook in menu-secondary.php
 * 
 * @since 0.2.0
 */
function shell_mobile_menu_secondary(){?>
<div class="mobile-menu-button" title="navigation">
	<span><a href="#menu-secondary-bottom"><?php _ex( 'Navigation', 'mobile-menu', 'shell' ); ?></a></span>
</div><?php
}


/**
 * Add Breadcrumb Trail
 * Added in 'shell_open_main' hook in header.php
 * 
 * @since 0.1.0
 */
function shell_breadcrumb(){
	if ( current_theme_supports( 'breadcrumb-trail' ) ) 
		breadcrumb_trail( array( 'before' => __( 'You are here:', 'shell' ) ) );
}


/**
 * Shell Breadcrumb Trail Args.
 * Add edit link for singular pages and taxonomy term archive pages in breadcrumb trail.
 * 
 * @since 0.1.0
 */
function shell_breadcrumb_trail_args( $args ){
	$args['after'] = shell_edit_link();
	return $args;
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
 * Hybrid Comments Args to change avatar size.
 * 
 * @see 	Hybrid::hybrid_list_comments_args()
 * @return	array
 * @since 	0.1.1
 */
function shell_comments_args( $args ) {
	$args['avatar_size'] = 40;
	return $args;
}


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
 * Dynamic HTML Class to target context in body class.
 * Can be modified using filter hook "shell_html_class"
 *
 *
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
			}
		}
	}
	return $mce_css;
}


