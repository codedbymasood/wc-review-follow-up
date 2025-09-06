<?php
/**
 * Frontend class.
 *
 * @package plugin-slug\public\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

/**
 * Core plugin loader.
 */
class Frontend {

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
		add_filter( 'wp_mail_from', array( $this, 'mail_from' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'mail_from_name' ) );
	}

	public function mail_from() {
		$from_email = get_option( 'stobokit_email_from_email', '' );
		$from_email = $from_email ? $from_email : get_option( 'admin_email', '' );

		return $from_email;
	}

	public function mail_from_name() {
		$from_name = get_option( 'stobokit_email_from_name', '' );
		$from_name = $from_name ? $from_name : get_option( 'blogname', '' );

		return $from_name;
	}
}

\REVIFOUP\Frontend::instance();
