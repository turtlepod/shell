<?php
/**
 * Scripts Functions
 * All functions here is a related to handle scripts and styles.
 *
 * @package     Shell
 * @subpackage  Includes
 * @since       0.2.0
 * @author      David Chandra Purnama <david@shellcreeper.com>
 * @copyright   Copyright (c) 2013, David Chandra Purnama
 * @link        http://themehybrid.com/themes/shell
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* SHELL SCRIPTS THEME SETUP
=============================================================== */


/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'shell_theme_scripts_setup' );

/**
 * Theme scripts setup function.
 * This function handle actions and filters related to scripts and styles.
 *
 * @since     0.2.0
 * @access    public
 * @return    void
 */
function shell_theme_scripts_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Hybrid Core Styles: Main Theme CSS, Media Queries, Skin Styles, etc */
	if ( is_child_theme() ){ // in child theme
		add_theme_support( 'hybrid-core-styles', array( 'parent', 'media-queries', 'skin', 'style' ) );
	}
	else { // in parent theme
		add_theme_support( 'hybrid-core-styles', array( 'style', 'media-queries', 'skin' ) );
	}

	/* Hybrid Core Scripts: Comment Reply and Drop Down Scripts */
	if ( wp_is_mobile() ){  // mobile user: add only comment reply scripts
		add_theme_support( 'hybrid-core-scripts' );
	} else { // only add drop down script in non-mobile user
		add_theme_support( 'hybrid-core-scripts', array( 'drop-downs' ) );
	}

	/* Add media queries css */
	add_filter( "{$prefix}_styles", 'shell_styles' );

	/* Register scripts */
	add_action( 'wp_enqueue_scripts', 'shell_register_scripts');

	/* Enqueue scripts */
	add_action( 'wp_enqueue_scripts', 'shell_menu_script');
	add_action( 'wp_enqueue_scripts', 'shell_script');
	add_action( 'wp_enqueue_scripts', 'shell_fitvids_script');

	/* Add respond.js and  html5shiv.js for unsupported browsers. */
	add_action( 'wp_head', 'shell_respond_html5shiv' );
}


/* SCRIPTS FUNCTIONS
=============================================================== */

/**
 * Helper function to get the version of theme file using theme version. 
 * 
 * @since 0.1.0
 */
function shell_theme_file_version( $file_names ){

	/* Get Theme */
	$theme = wp_get_theme( get_template() );

	/* Loops through each of the given file names. */
	foreach ( (array) $file_names as $file ) {

		/* If the file exists in the stylesheet (child theme) directory. */
		if ( is_child_theme() && file_exists( trailingslashit( get_stylesheet_directory() ) . $file ) ) {
			$theme = wp_get_theme();
			break;
		}

		/* If the file exists in the template (parent theme) directory. */
		elseif ( file_exists( trailingslashit( get_template_directory() ) . $file ) ) {
			$theme = wp_get_theme( get_template() );
			break;
		}
	}

	return $theme->get( 'Version' );
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

	/* Use the .min script if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/* media queries dependency */
	$deps = null;
	if ( file_exists( get_stylesheet_directory() . "/media-queries{$suffix}.css" ) 
		|| file_exists( get_stylesheet_directory() . "/media-queries.css" ) ){
		$deps = array('style');
	}

	/* Media Queries CSS */
	$styles['media-queries'] = array(
		'src'		=> hybrid_locate_theme_file( array( "media-queries{$suffix}.css", "media-queries.css" ) ),
		'version'	=> shell_theme_file_version( array( "media-queries{$suffix}.css", "media-queries.css" ) ),
		'media'		=> 'all',
		'deps'		=> $deps,
	);

	/* Open Sans Google Fonts */
	$styles['shell-open-sans'] = array(
		'src'		=> 'http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,800,800italic',
		'version'	=> '0.2.0',
		'media'		=> 'all',
		'deps'		=> array(),
	);

	return $styles;
}


/**
 * Register Scripts.
 * 
 * @since  0.2.0
 * @access public
 * @return void
 */
function shell_register_scripts(){

	/* Use the .min script if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/*  Mobile Menu Script */
	$shell_menu_file = hybrid_locate_theme_file( array( "js/shell-menu{$suffix}.js", "js/shell-menu.js" ) );
	$shell_menu_version = shell_theme_file_version( array( "js/shell-menu{$suffix}.js", "js/shell-menu.js" ) );
	wp_register_script( 'shell-menu', $shell_menu_file, array('jquery'), $shell_menu_version, true );

	/*  Theme Script */
	$shell_theme_file = hybrid_locate_theme_file( array( "js/shell-theme{$suffix}.js", "js/shell-theme.js" ) );
	$shell_theme_version = shell_theme_file_version( array( "js/shell-theme{$suffix}.js", "js/shell-theme.js" ) );
	wp_register_script( 'shell-js', $shell_theme_file, array('jquery'), $shell_theme_version, true );

	/* Enqueue FitVids */
	$fitvids_file = hybrid_locate_theme_file( array( "js/fitvids{$suffix}.js", "js/fitvids.js" ) );
	$fitvids_version = shell_theme_file_version( array( "js/fitvids{$suffix}.js", "js/fitvids.js" ) );
	wp_register_script( 'shell-fitvids', $fitvids_file, array( 'jquery' ), $fitvids_version, true );
}

/**
 * Enqueue Menu Script.
 * @since  0.2.0
 */
function shell_menu_script(){

	/*  Mobile Menu Script */
	wp_enqueue_script( 'shell-menu' );
}

/**
 * Enqueue Theme Script.
 * @since 0.1.0
 */
function shell_script( $args ){

	/*  Theme Script */
	wp_enqueue_script( 'shell-js' );
}

/**
 * Enqueue FitVids Script.
 * @since 0.1.0
 */
function shell_fitvids_script( $args ){

	/* Enqueue FitVids */
	wp_enqueue_script( 'shell-fitvids' );
}


/**
 * Function for help to unsupported browsers understand mediaqueries and html5.
 * This is added in 'head' using 'wp_head' hook.
 *
 * @link   https://github.com/scottjehl/Respond
 * @link   https://github.com/aFarkas/html5shiv
 * @since  0.1.0
 */
function shell_respond_html5shiv() {

	/* Use the .min script if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	?><!-- Enables media queries and html5 in some unsupported browsers. -->
	<!--[if (lt IE 9) & (!IEMobile)]>
	<script type="text/javascript" src="<?php echo hybrid_locate_theme_file( array( "js/respond{$suffix}.js", "js/respond.js" ) ); ?>"></script>
	<script type="text/javascript" src="<?php echo hybrid_locate_theme_file( array( "js/html5shiv{$suffix}.js", "js/html5shiv.js" ) ); ?>"></script>
	<![endif]-->
<?php
}



/* Hybrid COre Style: Load all registered stylesheet
=============================================================== */

/* Override Hybrid Sore Style */
add_action( 'after_setup_theme', 'shell_theme_core_style_setup', 14 );

/**
 * Shell Override Hybrid Core Style.
 * 
 * @since 0.2.0
 */
function shell_theme_core_style_setup(){

	/* Check theme support */
	if ( current_theme_supports( 'hybrid-core-styles' ) ) {

		/* Remove Loading Hybrid Core styles. */
		remove_action( 'wp_enqueue_scripts', 'hybrid_enqueue_styles', 5 );

		/* Add custom setting for layout in customizer */
		add_action( 'wp_enqueue_scripts', 'shell_hybrid_enqueue_styles', 5 );
	}
}

/**
 * Tells WordPress to load the styles needed for the framework using the wp_enqueue_style() function.
 * Modified function from Hybrid Core Style Feature.
 * This function might be removed in the future, already inplemented in HC.2.0
 *
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @since 1.5.0
 * @access private
 * @return void
 */
function shell_hybrid_enqueue_styles(){

	/* Get the theme-supported stylesheets. */
	$supports = get_theme_support( 'hybrid-core-styles' );

	/* If the theme doesn't add support for any styles, return. */
	if ( !is_array( $supports[0] ) )
		return;

	/* Loop through each of the core styles and enqueue them. */
	foreach ( $supports[0] as $style ) {
		wp_enqueue_style( $style );
	}
}
