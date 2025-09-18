<?php
/**
 * Utils class.
 *
 * @package plugin-slug\admin\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

use STOBOKIT\Utils as Core_Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Utils class.
 */
class Utils {

	/**
	 * Generate coupons.
	 *
	 * @return string
	 */
	public static function generate_coupon( $args = array() ) {
		$code = Core_Utils::generate_random_string();

		$discount_type       = isset( $args['discount_type'] ) ? $args['discount_type'] : 'percent';
		$amount              = isset( $args['amount'] ) ? $args['amount'] : 20;
		$coupon_expires_date = isset( $args['coupon_expires_date'] ) ? $args['coupon_expires_date'] : '';

		$coupon = new \WC_Coupon();

		$coupon->set_code( $code );
		$coupon->set_discount_type( $discount_type );
		$coupon->set_amount( $amount );
		$coupon->set_date_expires( $coupon_expires_date );
		$coupon->set_usage_limit_per_user( 1 );

		$coupon->save();

		return $code;
	}

	public static function update_status( $args = array(), $status = '' ) {

		$order_id = isset( $args['order_id'] ) ? $args['order_id'] : 0;
		$email    = isset( $args['email'] ) ? $args['email'] : '';

		if ( ! $order_id || ! $email ) {
			revifoup()->logger->info(
				'Order ID or Email are not found.',
				array(
					'order_id' => $order_id,
					'email'    => $email,
				)
			);

			return;
		}

		global $wpdb;

		$table = $wpdb->prefix . 'revifoup_review_requests';

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $table WHERE order_id = %d AND email = %s",
				$order_id,
				$email
			)
		);

		if ( ! $exists ) {
			revifoup()->logger->warning(
				'Review request log not found.',
				array(
					'order_id' => $order_id,
					'email'    => $email,
				)
			);

			return;
		}

		$result = $wpdb->update(
			$table,
			array(
				'status' => $status,
			),
			array(
				'order_id' => $order_id,
				'email'    => $email,
			),
			array( '%s' ),
			array( '%d', '%s' )
		);

		if ( false === $result ) {
			revifoup()->logger->warning(
				'Can\'t update review request log.',
				array(
					'order_id' => $order_id,
					'email'    => $email,
				)
			);
		}
	}
}
