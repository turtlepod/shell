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

	/* skin count */
	$skin_count = count( shell_skins() );

	/* add metabox only if additional skin added */
	if ( $skin_count > 1 ){

		/* Add Skin Meta box. */
		add_meta_box(
			'shell-skin-meta-box',				// Name/ID
			__( 'Skins', 'shell' ),				// Label
			'shell_skin_meta_box',				// Callback function
			'appearance_page_theme-settings',	// Page to load on, leave as is
			'normal',							// Which meta box holder?
			'high'								// High/low within the meta box holder
		);
	}

	/* add theme layout metabox only if theme support it */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* Add Theme Layout Meta box */
		add_meta_box(
			'shell-theme-layout-meta-box',		// Name/ID
			__( 'Theme Layout', 'shell' ),	// Label
			'shell_theme_layout_meta_box',		// Callback function
			'appearance_page_theme-settings',	// Page to load on, leave as is
			'side',								// Which meta box holder?
			'low'								// High/low within the meta box holder
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
					if ( hybrid_get_setting( 'skin' ) == $skin_id ) {
						$selected = ' skin-img-selected';
					}

					/* skin image */
					if ( !isset( $skin_data['image'] ) )
						$image = get_template_directory_uri() . '/images/skin-no-image.jpg';
					else
						$image = $skin_data['image'];

					/* skin author */
					$author = '';
					if ( isset ( $skin_data['author'] ) ){
						$author = $skin_data['author'];
						if ( isset ( $skin_data['author_uri'] ) )
							$author_uri = esc_url_raw( $skin_data['author_uri'] );
							$author = '<a href="' . $author_uri . '">' . $author . '</a>';
					}

					/* skin description */
					$desc = '';
					if ( isset ( $skin_data['description'] ) ){
						$desc = $skin_data['description'];
					}
					?>
					<div class="skin-input-wrap">

						<input type="radio" name="<?php echo esc_attr( hybrid_settings_field_name( 'skin' ) ); ?>" id="<?php echo esc_attr( $skin_id ); ?>" class="skin-option-input" value="<?php echo esc_attr( $skin_id ); ?>" <?php checked( hybrid_get_setting( 'skin' ), $skin_id ); ?> />

						<div style="background-image:url(<?php echo esc_url( $image ); ?>);" title="<?php echo esc_attr( $skin_id ); ?>" class="skin-img<?php echo esc_attr( $selected ); ?>" onclick="document.getElementById('<?php echo esc_attr( $skin_id ); ?>').checked=true;" /></div>

						<div class="skin-name"><?php echo esc_html( $skin_data['name'] ); ?><span class="skin-version">0.1.0</span></div>

						<?php if ( !empty( $author ) ){?>
						<div class="skin-author"><?php printf( __( 'By %s', 'shell' ), $author ); ?></div>
						<?php }?>

						<?php if ( !empty( $desc ) ){?>
						<div class="skin-detail-wrap">

							<div class="skin-detail"><?php _e( 'Description', 'shell' ); ?></div>

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
 * Theme Layout Meta box
 * 
 * @since 0.1.0
 */
function shell_theme_layout_meta_box(){
?>
	<table class="form-table">
		<!-- Theme Layout -->
		<tr>
			<td>
				<p><?php _e( 'Want to set default global layout?', 'shell' ); ?></p>

				<a href="<?php echo admin_url( 'customize.php' ); ?>" name="reset" class="reset-button button-secondary"><?php esc_html_e( 'Customize', 'shell' ); ?></a>
			</td>
		</tr>

		<!-- End custom form elements. -->
	</table><!-- .form-table -->
<?php }



/**
 * Validate Theme Settings
 * 
 * @since 0.1.0
 */
function shell_theme_validate_settings( $input ) {

	$input['skin'] = wp_filter_nohtml_kses( $input['skin'] );
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