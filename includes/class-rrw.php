<?php
/**
 * Plugin initialization class.
 *
 * @package review-requester-for-woocommerce\includes\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

defined( 'ABSPATH' ) || exit;

/**
 * Core plugin loader.
 */
final class RRW {

	/**
	 * Singleton instance.
	 *
	 * @var RRW|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return RRW
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		$this->define_constants();

		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing.
	 */
	private function __wakeup() {}

	/**
	 * Define plugin constants.
	 */
	private function define_constants() {
		define( 'RRW_VERSION', '1.0.0' );
		define( 'RRW_PATH', plugin_dir_path( dirname( __FILE__ ) ) );
		define( 'RRW_URL', plugin_dir_url( dirname( __FILE__ ) ) );
	}

	/**
	 * Load required files.
	 */
	private function load_dependencies() {
		require_once RRW_PATH . '/includes/class-utils.php';

		require_once RRW_PATH . '/public/class-cron.php';
		require_once RRW_PATH . '/public/class-frontend.php';

		if ( is_admin() ) {
			require_once RRW_PATH . '/admin/class-settings.php';
			include_once RRW_PATH . '/admin/view/settings-page.php';
			include_once RRW_PATH . '/admin/view/email-template-page.php';
			require_once RRW_PATH . '/admin/class-admin.php';
			require_once RRW_PATH . '/admin/class-review-request-list-table.php';
		}
	}

	/**
	 * Hook into WordPress.
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'ensure_table_exists' ) );

		// Create a table when activate the plugin.
		register_activation_hook( RRW_PLUGIN_FILE, array( $this, 'create_email_logs_table' ) );
		add_action( 'before_woocommerce_init', array( $this, 'enable_hpos' ) );
	}

	public function ensure_table_exists() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'rrw_review_requests';

		// Check if table exists.
		if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) {
			$this->create_email_logs_table();
		}
	}

	public function create_email_logs_table() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'rrw_review_requests';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			order_id     BIGINT UNSIGNED NOT NULL,
			customer_id  BIGINT UNSIGNED NOT NULL,
			email        VARCHAR(50)     NOT NULL, 
			status       VARCHAR(50)     NOT NULL DEFAULT 'pending',  /* queued | sent | failed */
			error_msg    TEXT            NULL,
			sent_at      DATETIME        NULL,
			created_at   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY order_id (order_id),
			KEY sent_at  (sent_at)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	public function enable_hpos() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
				'custom_order_tables',
				RRW_PLUGIN_FILE,
				true
			);
		}
	}
}
