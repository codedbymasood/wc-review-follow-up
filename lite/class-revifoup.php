<?php
/**
 * Plugin initialization class.
 *
 * @package plugin-slug\includes\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

/**
 * Core plugin loader.
 */
final class REVIFOUP {

	/**
	 * Logger class
	 *
	 * @var \StoboKit\Logger
	 */
	public $logger;

	/**
	 * Template override class.
	 *
	 * @var \StoboKit\Template_Factory
	 */
	public $templates;

	/**
	 * Singleton instance.
	 *
	 * @var REVIFOUP|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return REVIFOUP
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
		$this->init_core();

		// Assign template override.
		$this->templates = \StoboKit\Template_Factory::get_instance(
			'plugin-slug',
			REVIFOUP_PLUGIN_FILE
		);

		// Emailer.
		$this->emailer = \StoboKit\Emailer::get_instance();

		// Logger.
		$this->logger = new \StoboKit\Logger();

		// Cron scheduler.
		$this->cron = new \StoboKit\Cron_Scheduler();

		// Schedule logger.
		$this->scheduler = new \StoboKit\Schedule_Logger();

		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Load core.
	 */
	private function init_core() {
		require_once REVIFOUP_PATH . '/core/init-core.php';
	}

	/**
	 * Load required files.
	 */
	private function load_common() {
		require_once REVIFOUP_PATH . '/common/includes/class-utils.php';
		require_once REVIFOUP_PATH . '/common/public/class-frontend.php';

		if ( is_admin() ) {
			include_once REVIFOUP_PATH . '/common/admin/view/settings-page.php';
			require_once REVIFOUP_PATH . '/common/admin/class-admin.php';
			require_once REVIFOUP_PATH . '/common/admin/class-review-request-list-table.php';
		}
	}

	/**
	 * Load required files.
	 */
	private function load_dependencies() {
		$this->load_common();
	}

	/**
	 * Hook into WordPress.
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'init_onboarding' ) );
		add_action( 'plugins_loaded', array( $this, 'ensure_table_exists' ) );

		// Create a table when activate the plugin.
		register_activation_hook( REVIFOUP_PLUGIN_FILE, array( $this, 'maybe_create_table' ) );
		add_action( 'before_woocommerce_init', array( $this, 'enable_hpos' ) );
	}

	public function init_onboarding() {
		static $onboarding_initialized = false;
		if ( $onboarding_initialized ) {
			return;
		}
		$onboarding_initialized = true;

		$steps = array(
			'welcome'  => 'Welcome',
			'settings' => 'General Setup',
			'finish'   => 'Finish',
		);

		new \STOBOKIT\Onboarding(
			array(
				'path'          => REVIFOUP_PATH,
				'plugin_slug'   => 'plugin-slug',
				'steps'         => $steps,
				'page_slug'     => 'stobokit-onboarding-revifoup',
				'option_prefix' => 'revifoup_onboarding',
			)
		);
	}

	/**
	 * Make sure the table exists, otherwise create the required table.
	 *
	 * @return void
	 */
	public function ensure_table_exists() {
		global $wpdb;

		$table = $wpdb->prefix . 'revifoup_review_requests';

    // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_exists = $wpdb->get_var(
			$wpdb->prepare(
				'SHOW TABLES LIKE %s',
				$table
			)
		);

		if ( $table_exists !== $table ) {
			$this->maybe_create_table();
		}
	}

	/**
	 * Create a table.
	 *
	 * @return void
	 */
	public function maybe_create_table() {

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

	/**
	 * Enable HPOS
	 *
	 * @return void
	 */
	public function enable_hpos() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
				'custom_order_tables',
				REVIFOUP_PLUGIN_FILE,
				true
			);
		}
	}
}
