<?php
/**
 * Admin class.
 *
 * @package plugin-slug\admin\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

use STOBOKIT\Utils as Core_Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Settings class.
 */
class Hooks_Pro {

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		add_action( 'transition_comment_status', array( $this, 'send_coupon_code' ), 10, 3 );
	}

	/**
	 * After verified owner status added.
	 *
	 * @return void
	 */
	public function send_coupon_code() {
		error_log( 'verified' );
	}
}

new Hooks_Pro();
