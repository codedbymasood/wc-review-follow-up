<?php
/**
 * Hooks class.
 *
 * @package plugin-slug\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

// Include the onboarding class.
if ( ! class_exists( '\STOBOKIT\Onboarding' ) ) {
	include_once dirname( REVIFOUP_PLUGIN_FILE ) . '/core/class-onboarding.php';
}

/**
 * Runs an activate the plugin.
 */
class Install {
	/**
	 * Init activation.
	 *
	 * @return void
	 */
	public static function init() {
		self::maybe_create_table();
		self::init_onboarding();
	}

	/**
	 * Handle plugin activation.
	 */
	public static function init_onboarding() {

		// Set flag that plugin was just activated.
		set_transient( 'revifoup_onboarding_activation_redirect', true, 60 );

		// Set onboarding as pending.
		update_option( 'revifoup_onboarding_completed', false );
		update_option( 'revifoup_onboarding_started', current_time( 'timestamp' ) );

		// Clear any existing onboarding progress.
		delete_option( 'revifoup_onboarding_current_step' );
	}

	/**
	 * Create a alert table.
	 *
	 * @return void
	 */
	public static function maybe_create_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'revifoup_review_requests';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			order_id     BIGINT UNSIGNED NOT NULL,
			customer_id  BIGINT UNSIGNED NOT NULL,
			email        VARCHAR(50)     NOT NULL, 
			status       VARCHAR(50)     NOT NULL DEFAULT 'pending',
			sent_at      DATETIME        NULL,
			created_at   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY order_id (order_id),
			KEY sent_at  (sent_at)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}
