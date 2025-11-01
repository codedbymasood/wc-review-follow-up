<?php
/**
 * Register cronjobs class.
 *
 * @package plugin-slug\common\includes\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

/**
 * Register cronjobs class.
 */
class Cron {

	public static function review_requests_unsubscribe( $args = array(), $order_id = 0 ) {
		Utils::update_status( $args, 'unsubscribed' );
	}
}
