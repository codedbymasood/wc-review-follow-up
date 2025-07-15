<?php
/**
 * Plugin Name: Review requester for WooCommerce
 * Plugin URI: https://github.com/codedbymasood/wc-review-requester
 * Description: Add a "Notify Me" button for out-of-stock products, send back-in-stock alerts and follow-up emails with unique discount codes.
 * Version: 1.0
 * Author: Masood Mohamed
 * Author URI: https://github.com/codedbymasood
 * Text Domain: review-requester-for-woocommerce
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Domain Path: /languages/
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * WC requires at least: 6.0
 * WC tested up to: 9.6
 *
 * @package review-requester-for-woocommerce
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'RRW_PLUGIN_FILE' ) ) {
	define( 'RRW_PLUGIN_FILE', __FILE__ );
}

// Include the main class.
if ( ! class_exists( 'RRW', false ) ) {
	include_once dirname( RRW_PLUGIN_FILE ) . '/includes/class-rrw.php';
}

/**
 * Returns the main instance of RRW.
 *
 * @since  1.0
 * @return RRW
 */
function rrw() {
	return \RRW\RRW::instance();
}

add_action( 
	'woocommerce_loaded',
	function () {
		// Require at least WooCommerce 6.0+.
		if ( version_compare( wc()->version, '6.0', '<' ) ) {
			return;
		}

		// Global for backwards compatibility.
		$GLOBALS['rrw'] = rrw();
	}
);


