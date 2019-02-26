<?php
/**
 * Plugin Name: WP Netlify Build
 * Description: Trigger Netlify Build Hook from your WordPress Admin Dashboard.
 * Author: mmuhsin
 * Author URI: https://muhammadmuhsin.com
 * Version: 0.1.0
 * Plugin URI: https://github.com/m-muhsin/wp-netlify-build
 * License: MIT
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('WP_Netlify_Build')) {

    /**
     * Core class to manage all plugin functionality.
     * Enables:
     * publishing WP content to a Gatsby site hosted by Netlify
     * custom preview URLs
     */
    class WP_Netlify_Build
    {

		const VERSION = '0.3.0';

		private static $refresh = null;

		public static function init() {
			self::includes();
		}

		private static function includes() {
			require_once dirname( __FILE__ ) . '/includes/admin/classes/class-wp-netlify-build-admin.php';
		}

        public static function trigger_netlify_deploy($build_hook) {
      		$client = new Client();
      		$response = $client->post($build_hook);
      		return $response->getStatusCode();
		}
	}

	add_action( 'init', array( 'WP_Netlify_Build', 'init' ) );
}
