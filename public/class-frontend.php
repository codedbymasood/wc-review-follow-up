<?php
/**
 * Frontend class.
 *
 * @package review-requester-for-woocommerce\public\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

defined( 'ABSPATH' ) || exit;

/**
 * Core plugin loader.
 */
class Frontend {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'wp_mail_from', array( $this, 'mail_from' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'mail_from_name' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'rrw-main', RRW_URL . '/public/assets/js/main.js', array( 'jquery' ), '1.0', true );
	}

	public function mail_from() {
		$from_address = get_option( 'rrw_from_address', '' );
		return $from_address;
	}

	public function mail_from_name() {
		$from_name = get_option( 'rrw_from_name', '' );
		return $from_name;
	}

}

\RRW\Frontend::instance();




