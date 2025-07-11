<?php
/**
 * Register cronjobs class.
 *
 * @package review-requester-for-woocommerce\public\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

defined( 'ABSPATH' ) || exit;

/**
 * Register cronjobs class.
 */
class Cron {

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
	}

}

\RRW\Cron::instance();




