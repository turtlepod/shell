<?php
/**
 * Auto Hosted Theme Updater Class
 *
 * You can rename the class name if needed.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @version 0.1.0
 * @author David Chandra Purnama <david@shellcreeper.com>
 * @link http://shellcreeper.com
 * @link http://autohosted.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright Copyright (c) 2013, David Chandra Purnama
 */
class Shell_Theme_Updater{

	/**
	 * Class Constructor
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		add_action( 'after_setup_theme', array( &$this, 'updater_setup' ), 15 );
	}

	/**
	 * Setup updater
	 * 
	 * @since 0.1.0
	 */
	public function updater_setup(){

		/* Get needed data */
		$updater_data = $this->updater_data();

		/* only do stuff if minimum req pass */
		if ( isset( $updater_data['repo_uri'] ) || !empty( $updater_data['repo_uri'] ) || isset( $updater_data['repo_slug'] ) || !empty( $updater_data['repo_slug'] ) ){

			/* filter theme update transient */
			add_filter( 'pre_set_site_transient_update_themes', array( &$this, 'transient_update_themes' ) );

			/* install theme in correct folder when zip source folder is not the same */
			add_filter( 'upgrader_source_selection', array( &$this, 'theme_source_selection' ), 10, 3);
		}
	}


	/**
	 * Updater Data
	 * 
	 * @since 0.1.0
	 */
	public function updater_data(){

		/* Get the theme support arguements for 'auto-hosted-theme-updater'. */
		$theme_support = get_theme_support( 'auto-hosted-theme-updater' );

		/* get updater config */
		if ( !is_array( $theme_support[0] ) ) $user_config = false;
		else $user_config = $theme_support[0];

		/* default config */
		$defaults = array(
			'repo_uri'	=> '',
			'repo_slug'	=> '',
			'key'		=> '',
			'dashboard'	=> false,
		);

		/* merge configs and defaults */
		$config = wp_parse_args( $user_config, $defaults );

		/* Theme data */
		$theme_data = wp_get_theme( get_template() );

		/* Updater data: Hana Tul Set! */
		$updater_data = array();

		/* Repo URI */
		$repo_uri = '';
		if ( !empty( $config['repo_uri'] ) )
			$repo_uri = trailingslashit( esc_url_raw( $config['repo_uri'] ) );
		$updater_data['repo_uri'] = $repo_uri;

		/* Repo slug */
		$repo_slug = '';
		if ( !empty( $config['repo_slug'] ) )
			$repo_slug = sanitize_title( $config['repo_slug'] );
		$updater_data['repo_slug'] = $repo_slug;

		/* Activation key */
		$key = '';
		if ( $config['key'] ) $key = md5( $config['key'] );
		if ( empty( $key ) && true === $config['dashboard'] ){
			$widget_id = 'aht_' . get_template() . '_activation_key';
			$key_db = get_option( $widget_id );
			$key = ( $key_db['key'] ) ? md5( $key_db['key'] ) : '' ;
		}
		$updater_data['key'] = $key;

		/* Dashboard widget */
		$dashboard = false;
		if ( isset( $config['dashboard'] ) || !empty( $config['dashboard'] ) ) $dashboard = true;
		$updater_data['dashboard'] = $dashboard;

		/* Theme slug */
		$updater_data['slug'] = get_template();

		/* Theme name */
		$updater_data['name'] = esc_attr( $theme_data->get( 'Name' ) );

		/* Theme version */
		$updater_data['version'] = esc_attr( $theme_data->get( 'Version' ) );

		/* Theme URI */
		$theme_uri = $theme_data->get( 'ThemeURI' );
		if ( !empty($theme_uri) ) $theme_uri = esc_url_raw( $theme_uri );
		$updater_data['uri'] = $theme_uri;

		/* Domain */
		$updater_data['domain'] = get_bloginfo('url');

		return $updater_data;
	}

	/**
	 * Check for theme updates
	 * 
	 * @since 0.1.0
	 */
	public function transient_update_themes( $checked_data ) {

		global $wp_version;

		/* only if wp check for updates. */
		if ( empty( $checked_data->checked ) )
			return $checked_data;

		/* Get needed data */
		$updater_data = $this->updater_data();

		/* remote call */
		$remote_url = add_query_arg( array( 'theme_repo' => $updater_data['repo_slug'], 'ahtr_check' => $updater_data['version'] ), $updater_data['repo_uri'] );
		$remote_request = array( 'timeout' => 20, 'body' => array( 'key' => $updater_data['key'] ), 'user-agent' => 'WordPress/' . $wp_version . '; ' . $updater_data['domain'] );
		$raw_response = wp_remote_post( $remote_url, $remote_request );

		/* error check */
		$response = '';
		if ( !is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) )
			$response = maybe_unserialize( trim( wp_remote_retrieve_body( $raw_response ) ) );

		/* check response data */
		if ( is_array( $response ) && !empty( $response ) ){

			/* check if minimum data is available */
			if ( isset( $response['new_version'] ) && !empty( $response['new_version'] ) && isset( $response['package'] ) && !empty( $response['package'] ) ){

				/* create response data array */
				$updates = array();
				$updates['new_version'] = esc_attr( $response['new_version'] );
				$updates['package'] = esc_url_raw( $response['package'] );
				if ( isset( $response['url'] ) && !empty( $response['url'] ) )
					$updates['url'] = esc_url_raw( $response['url'] );
				else
					$updates['url'] = $updater_data['uri'];

				/* if response not exist, create empty. */
				if ( !isset( $checked_data->response ) )
					$checked_data->response = array();

				/* feed data to wp transient */
				$checked_data->response[$updater_data['slug']] = $updates;
			}
		}

		/* close sesame*/
		return $checked_data;
	}


	/**
	 * Move the theme from zip file to correct theme folder
	 * 
	 * @link https://github.com/scarstens/Github-Theme-Updater/blob/master/updater.php
	 * @since 0.1.0
	 */
	public function theme_source_selection( $source, $remote_source, $upgrader ){

		/* if theme name is set */
		if( isset( $upgrader->skin->theme_info->template ) ){

			/* only in current theme */
			if ( $upgrader->skin->theme_info->template == get_template() ){

				/* theme folder */
				$theme_name = $upgrader->skin->theme_info->template;

				/* add notification feedback text */
				$upgrader->skin->feedback( __( 'Executing upgrader_source_selection filter function...', 'shell' ) );

				/* only if everything is set */
				if( isset( $source, $remote_source, $theme_name ) ){

					/* set new source to correct theme folder */
					$new_source = $remote_source . '/' . $theme_name . '/';

					/* rename the folder */
					if(@rename( $source, $new_source ) ){
						$upgrader->skin->feedback( __( 'Renamed theme folder successfully.', 'shell' ) );
						return $new_source;
					}
					/* unable to rename the folder to correct theme folder */
					else{
						$upgrader->skin->feedback( __( '**Unable to rename downloaded theme.', 'shell' ) );
						return new WP_Error();
					}
				}
				/* fallback */
				else
					$upgrader->skin->feedback( __( '**Source or Remote Source is unavailable.', 'shell' ) );
			}
		}
		return $source;
	}
}

new Shell_Theme_Updater;