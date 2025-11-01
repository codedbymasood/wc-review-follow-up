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
	 * Emailer class
	 *
	 * @var \StoboKit\Emailer
	 */
	public $emailer;

	/**
	 * Logger class
	 *
	 * @var \StoboKit\Logger
	 */
	public $logger;

	/**
	 * Cron class
	 *
	 * @var \StoboKit\Cron_Scheduler
	 */
	public $cron;

	/**
	 * Schedule logger class
	 *
	 * @var \StoboKit\Schedule_Logger
	 */
	public $scheduler;

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

		do_action( 'revifoup_initialized' );
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
		require_once REVIFOUP_PATH . '/common/includes/class-hooks.php';
		require_once REVIFOUP_PATH . '/common/public/class-frontend.php';

		if ( is_admin() ) {
			require_once REVIFOUP_PATH . '/common/admin/view/email-templates.php';
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
