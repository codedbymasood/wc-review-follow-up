<?php
/**
 * Plugin Name: Review Follow Up for WooCommerce
 * Plugin URI: https://github.com/codedbymasood/wc-review-requester
 * Description: Add a "Notify Me" button for out-of-stock products, send back-in-stock alerts and follow-up emails with unique discount codes.
 * Version: 1.0
 * Author: Masood Mohamed
 * Author URI: https://github.com/codedbymasood
 * Text Domain: review-follow-up-for-woocommerce
 * Domain Path: /languages/
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * WC requires at least: 6.0
 * WC tested up to: 9.6
 *
 * @package review-follow-up-for-woocommerce
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'REVIFOUP_PLUGIN_FILE' ) ) {
	define( 'REVIFOUP_PLUGIN_FILE', __FILE__ );
}

// Include the main class.
if ( ! class_exists( 'REVIFOUP', false ) ) {
	include_once dirname( REVIFOUP_PLUGIN_FILE ) . '/includes/class-revifoup.php';
}

/**
 * Returns the main instance of REVIFOUP.
 *
 * @since  1.0
 * @return REVIFOUP
 */
function revifoup() {
	return \REVIFOUP\REVIFOUP::instance();
}

// Global for backwards compatibility.
$GLOBALS['revifoup'] = revifoup();

/**
 * ==========================
 *  Onborading
 * ==========================
 */

// Include the onboarding class.
require_once dirname( REVIFOUP_PLUGIN_FILE ) . '/core/class-onboarding.php';

register_activation_hook( __FILE__, 'revifoup_on_plugin_activation' );

/**
 * Handle plugin activation.
 */
function revifoup_on_plugin_activation() {
	// Set flag that plugin was just activated.
	set_transient( 'revifoup_onboarding_activation_redirect', true, 60 );

	// Set onboarding as pending.
	update_option( 'revifoup_onboarding_completed', false );
	update_option( 'revifoup_onboarding_started', current_time( 'timestamp' ) );

	// Clear any existing onboarding progress.
	delete_option( 'revifoup_onboarding_current_step' );
}

/**
 * Initialize the plugin.
 */
function revifoup_init() {
	$steps = array(
		'welcome'            => 'Welcome',
		'license-activation' => 'Activate License',
		'settings'           => 'General Setup',
		'finish'             => 'Finish',
	);

	new \STOBOKIT\Onboarding(
		array(
			'path'          => REVIFOUP_PATH,
			'plugin_slug'   => 'review-follow-up-for-woocommerce',
			'steps'         => $steps,
			'redirect_page' => 'stobokit-review-follow-up-settings',
			'page_slug'     => 'stobokit-onboarding-revifoup',
			'option_prefix' => 'revifoup_onboarding',
		)
	);

}
add_action( 'plugins_loaded', 'revifoup_init' );
