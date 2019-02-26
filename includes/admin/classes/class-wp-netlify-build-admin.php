<?php

if (! defined('ABSPATH')) {
	exit;
}

if (! class_exists('WP_Netlify_Build_Admin')) {

	class WP_Netlify_Build_Admin {

		private static $default = array(
      'netlify' => array(
        'build_hook' => '',
			),
		);

		public static function init() {
			self::hooks();
		}

		private static function hooks() {
			if ( apply_filters( 'wp_netlify_build_show_admin', true ) ) {
				if ( apply_filters( 'wp_netlify_build_show_admin_menu', true ) ) {
					add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
				}
				
				if ( apply_filters( 'wp_netlify_build_show_admin_bar_menu', true ) ) {
					add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar_menu' ), 999 );				
					add_filter('rest_cache_show_admin_bar_menu', function() {
						return false;
					});
				}				
			}
		}

		public static function admin_bar_menu( $wp_admin_bar ) {
			$args = array(
				'id'    => 'wp-netlify-build',
				'title' => __('Trigger Netlify Build', 'wp-netlify-build' ),
				'href'  => self::_publish_to_netlify_url(),
			);
		
			$wp_admin_bar->add_node( $args );
		}

		public static function admin_menu() {
			add_submenu_page( 
				'options-general.php', 
				__( 'WP Netlify Build', 'wp-netlify-build' ), 
				__( 'WP Netlify Build', 'wp-netlify-build' ), 
				'manage_options', 
				'wp-netlify-build', 
				array( __CLASS__, 'render_page' ) 
			);
		}

		public static function render_page() {
			$notice = null;

			if ( isset( $_REQUEST['wp_netlify_build_nonce'] ) && wp_verify_nonce( $_REQUEST['wp_netlify_build_nonce'], 'wp_netlify_build_options' ) ) {
				if ( isset( $_GET['publish'] ) && 1 == $_GET['publish'] ) {
			    $options = self::get_options();
          $deploy_response = WP_Netlify_Build::trigger_netlify_deploy($options['netlify']['build_hook']);
					if ( $deploy_response == 200  ) {
						$type    = 'updated';
						$message = __( 'An updated Netlify site edition is being published. Check the site every 5 minutes.', 'wp-netlify-build' );
					} else {
						$type    = 'error';
						$message = __( 'We were unable to start publishing an updated Netlify site. Check your settings.', 'wp-netlify-build' );
					}
				} elseif ( isset( $_POST['wp_netlify_build_options'] ) && ! empty( $_POST['wp_netlify_build_options'] ) ) {
					if ( self::_update_options( $_POST['wp_netlify_build_options'] ) ) {
						$type    = 'updated';
						$message = __( 'WP Netlify Build options have been successfully updated.', 'wp-netlify-build' );
					} else {
						$type    = 'error';
						$message = __( 'WP Netlify Build options were not updated. Perhaps you saved without making any changes.', 'wp-netlify-build' );
					}
				}
				add_settings_error( 'wp-netlify-build-notice', esc_attr( 'settings_updated' ), $message, $type );
			}

			$options = self::get_options();

			require_once dirname( __FILE__ ) . '/../views/html-options.php';
		}

		private static function _update_options( $options ) {
			$options = apply_filters( 'wp_netlify_build_update_options', $options );

			return update_option( 'wp_netlify_build_options', $options, 'yes' );
		}

		public static function get_options( $key = null ) {
			$options = apply_filters( 'wp_netlify_build_get_options', get_option( 'wp_netlify_build_options', self::$default ) );
			
			if ( is_string( $key ) && array_key_exists( $key, $options ) ) {
				return $options[$key];
			} 

			return $options;
		}

		private static function _publish_to_netlify_url() {
			return wp_nonce_url( admin_url( 'options-general.php?page=wp-netlify-build&publish=1' ), 'wp_netlify_build_options', 'wp_netlify_build_nonce' );
		}

	}

	WP_Netlify_Build_Admin::init();

}
