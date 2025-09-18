<?php
/**
 * Register cronjobs class.
 *
 * @package plugin-slug\public\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

defined( 'ABSPATH' ) || exit;

/**
 * Register cronjobs class.
 */
class Cron_Pro {

	public static function set_reward_unsubscribe( $args = array(), $order_id = 0 ) {
		Utils::update_status( $args, 'unsubsribed' );
	}
}
