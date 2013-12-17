<?php
/**
 * Skins Functions
 * All functions here is a related to handle theme skins.
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
add_action( 'after_setup_theme', 'shell_theme_skin_setup' );


/**
 * Theme Skin setup function.
 * This function handle actions and filters related to theme skin.
 *
 * @since     0.2.0
 * @access    public
 * @return    void
 */
function shell_theme_skin_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Hook to admin menu  */
	add_action( 'admin_menu', 'shell_theme_admin_setup' );

	/* Default settings */
	add_filter( "{$prefix}_default_theme_settings", 'shell_default_settings' );

	/* Skin in Customizer */
	add_action( 'customize_register', 'shell_customizer_register' );
}


/**
 * Admin Setup
 * 
 * @since 0.1.0
 */
function shell_theme_admin_setup() {

	/* only if theme support for hybrid core settings */
	if ( current_theme_supports( 'hybrid-core-theme-settings' ) ) {

		global $theme_settings_page;

		/* Get the theme settings page name. */
		$theme_settings_page = 'appearance_page_theme-settings';

		/* Get the theme prefix. */
		$prefix = hybrid_get_prefix();

		/* Create a settings meta box only on the theme settings page. */
		add_action( 'load-appearance_page_theme-settings', 'shell_theme_settings_meta_boxes' );

		/* Add a filter to validate/sanitize your settings. */
		add_filter( "sanitize_option_{$prefix}_theme_settings", 'shell_theme_validate_settings' );

		/* Enqueue scripts */
		add_action( 'admin_enqueue_scripts', 'shell_admin_scripts' );
	}
}

/**
 * Custom Meta Box in Settings Page
 * 
 * @since 0.1.0
 */
function shell_theme_settings_meta_boxes() {

	/* skin count */
	$skin_count = count( shell_skins() );

	/* add metabox only if additional skin available */
	if ( $skin_count > 1 ){

		/* Add Skin Meta box. */
		add_meta_box(
			'shell-skin-meta-box',				// Name/ID
			_x( 'Skins', 'settings', 'shell' ),	// Label
			'shell_skin_meta_box',				// Callback function
			'appearance_page_theme-settings',	// Page to load on, leave as is
			'normal',							// Which meta box holder?
			'high'								// High/low within the meta box holder
		);
	}
}

/**
 * Skin Meta box
 * 
 * @since 0.1.0
 */
function shell_skin_meta_box() {

	/* Get Theme Skins */
	$skins = shell_skins();
	?>
	<table class="form-table">
		<!-- Skin Option -->
		<tr>
			<td>
				<?php
				foreach ( $skins as $skin_id => $skin_data ) {

					/* selected for image label */
					$selected = '';
					if ( shell_active_skin() == $skin_id ) {
						$selected = ' skin-img-selected';
					}

					/* skin version */
					$version = '';
					if ( isset( $skin_data['version'] ) || !empty( $skin_data['version'] ) )
						$version = ' <span class="skin-version">' . $skin_data['version'] . '</span>';

					/* skin page uri */
					$skin_uri = '';
					if ( isset( $skin_data['skin_uri'] ) || !empty( $skin_data['skin_uri'] ) ){
						$skin_uri = esc_url_raw( $skin_data['skin_uri'] );
						$skin_uri = ' <a class="skin-uri" href="' . $skin_uri . '">' . _x( 'Skin Page &#8594', 'settings', 'shell'  ) . '</a>';
					}

					/* skin image */
					if ( !isset( $skin_data['screenshot'] ) || empty( $skin_data['screenshot'] ) )
						$image = get_template_directory_uri() . '/images/skin-no-image.jpg';
					else
						$image = esc_url_raw( $skin_data['screenshot'] );

					/* skin author */
					$author = '';
					if ( isset( $skin_data['author'] ) || !empty( $skin_data['author'] ) ){
						$author = $skin_data['author'];
						if ( isset( $skin_data['author_uri'] ) || !empty( $skin_data['author_uri'] ) ){
							$author_uri = esc_url_raw( $skin_data['author_uri'] );
							$author = '<a href="' . $author_uri . '">' . $author . '</a>';
						}
					}

					/* skin description */
					$desc = '';
					if ( isset ( $skin_data['description'] ) || !empty( $skin_data['description'] ) ){
						$desc = $skin_data['description'];
					}
					?>
					<div class="skin-input-wrap">

						<input type="radio" name="<?php echo esc_attr( hybrid_settings_field_name( 'skin' ) ); ?>" id="<?php echo esc_attr( $skin_id ); ?>" class="skin-option-input" value="<?php echo esc_attr( $skin_id ); ?>" <?php checked( shell_active_skin(), $skin_id ); ?> />

						<div style="background-image:url(<?php echo esc_url( $image ); ?>);" title="<?php echo esc_attr( $skin_id ); ?>" class="skin-img<?php echo esc_attr( $selected ); ?>" onclick="document.getElementById('<?php echo esc_attr( $skin_id ); ?>').checked=true;" /></div>

						<div class="skin-name"><?php echo esc_html( $skin_data['name'] ); echo $version; echo $skin_uri ?></div>

						<?php if ( !empty( $author ) ){?>
						<div class="skin-author"><?php printf( _x( 'By %s', 'settings', 'shell' ), $author ); ?></div>
						<?php }?>

						<?php if ( !empty( $desc ) ){?>
						<div class="skin-detail-wrap">

							<div class="skin-detail"><?php _ex( 'Details', 'settings', 'shell' ); ?></div>

							<div class="skin-description"><p><?php echo $desc; ?></p></div>

						</div>
						<?php }?>
					</div>
				<?php } ?>
			</td>
		</tr><!-- End custom form elements. -->
	</table><!-- .form-table -->
<?php }


/**
 * Validate Theme Settings
 * 
 * @since 0.1.0
 */
function shell_theme_validate_settings( $input ) {

	/* available skins */
	$skins = shell_skins();

	/* get skin ids */
	$skin_ids = array();
	foreach ( $skins as $skin_id => $skin_data ){
		$skin_ids[] = $skin_id;
	}

	/* check if selected skins is available */
	if ( in_array( $input['skin'], $skin_ids ) ){
		$input['skin'] = wp_filter_nohtml_kses( $input['skin'] );
	}
	/* if not, save it as default */
	else{
		$input['skin'] = 'default';
	}

	return $input;
}


/**
 * Theme Settings Script and Style
 * 
 * @since 0.1.0
 */
function shell_admin_scripts( $hook_suffix ) {

	/* skin count */
	$skin_count = count( shell_skins() );

	/* script and style only needed if skin options available */
	if ( $skin_count > 1 ){

		global $theme_settings_page;

		if ( $theme_settings_page == $hook_suffix ) {

			/* Enqueue Scripts */
			wp_enqueue_script( 'shell-skin-settings', get_template_directory_uri() . '/admin/skin-settings.js', array( 'jquery' ), '0.1.0', false );

			/* Enqueue Styles */
			wp_enqueue_style( 'shell-skin-settings', get_template_directory_uri() . '/admin/skin-settings.css', false, '0.1.0', 'screen' );
		}
	}
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

	/* enable developer to modify it, maybe different skin for different pages/context */
	$active_skin = apply_atomic( 'active_skin', $active_skin ); // shell_active_skin

	/* sanitize it */
	return wp_filter_nohtml_kses( $active_skin );
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

		/* Get list of available skin */
		$skins = shell_skins();
		$skin_choices = array();
		foreach ( $skins as $skin_id => $skin_data ){
			$skin_choices[$skin_id] = $skin_data['name'];
		}

		/* Section */
		$wp_customize->add_section( 'shell_theme_skin_section', array(
			'title'           => _x( 'Skins', 'customizer', 'shell' ),
			'priority'        => 30,
		));

		/* Settings */
		$wp_customize->add_setting( 'shell_theme_settings[skin]', array(
			'default'         => 'default',
			'type'            => 'option',
		));

		/* Control: Select Skin */
		$wp_customize->add_control( new WP_Customize_Control(
				$wp_customize,
				'shell_theme_settings[skin]',
				array(
					'label'          => _x( 'Select Skin', 'customizer', 'shell' ),
					'section'        => 'shell_theme_skin_section',
					'settings'       => 'shell_theme_settings[skin]',
					'type'           => 'select',
					'choices'        => $skin_choices,
				)
		));
	}
}
