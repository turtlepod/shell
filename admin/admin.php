<?php
/*
 * Theme Settings
 * 
 * @package shell
 * @subpackage Template
 * @since 0.1.0
 */
 
add_action( 'admin_menu', 'shell_theme_admin_setup' );

function shell_theme_admin_setup() {
    
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

/**
 * Custom Meta Box in Settings Page
 * 
 * @since 0.1.0
 */
function shell_theme_settings_meta_boxes() {

	/* Add a custom meta box. */
	add_meta_box(
		'shell-theme-meta-box-skin',		// Name/ID
		__( 'Skins', 'shell' ),				// Label
		'shell_theme_meta_box_skin',		// Callback function
		'appearance_page_theme-settings',	// Page to load on, leave as is
		'normal',								// Which meta box holder?
		'high'								// High/low within the meta box holder
	);
}

/**
 * Display Skin Options
 * 
 * @since 0.1.0
 */
function shell_theme_meta_box_skin() {

	/* Get theme layouts. */
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
					if ( hybrid_get_setting( 'skin' ) == $skin_id ) {
						$selected = ' skin-img-selected';
					}
					/* skin image */
					if ( !isset( $skin_data['image'] ) || empty( $skin_data['image'] ) )
						$image = get_template_directory_uri() . '/images/skin-no-image.jpg';
					else
						$image = $skin_data['image'];
					?>
					<div class="skin-input-wrap">
						<input type="radio" name="<?php echo esc_attr( hybrid_settings_field_name( 'skin' ) ); ?>" id="<?php echo esc_attr( $skin_id ); ?>" class="skin-option-input" value="<?php echo esc_attr( $skin_id ); ?>" <?php checked( hybrid_get_setting( 'skin' ), $skin_id ); ?> />

						<span class="skin-name"><?php echo esc_html( $skin_data['name'] ); ?></span>

						<img title="<?php echo esc_attr( $skin_id ); ?>" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $skin_id ); ?>" class="skin-img<?php echo esc_attr( $selected ); ?>" onclick="document.getElementById('<?php echo esc_attr( $skin_id ); ?>').checked=true;" />
					</div>
				<?php } ?>
			</td>
		</tr>

		<!-- End custom form elements. -->
	</table><!-- .form-table -->
	
<?php }

/* Validate theme settings. */
function shell_theme_validate_settings( $input ) {

	$input['skin'] = wp_filter_nohtml_kses( $input['skin'] );
	return $input;
}

/* Enqueue scripts (and related stylesheets) */
function shell_admin_scripts( $hook_suffix ) {
	global $theme_settings_page;

	if ( $theme_settings_page == $hook_suffix ) {

		/* Enqueue Scripts */
		wp_enqueue_script( 'shell_functions-admin', get_template_directory_uri() . '/admin/admin.js', array( 'jquery' ), '0.1.0', false );

		/* Enqueue Styles */
		wp_enqueue_style( 'shell_functions-admin', get_template_directory_uri() . '/admin/admin.css', false, '0.1.0', 'screen' );
	}
}