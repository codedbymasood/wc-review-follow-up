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
		add_action( 'stobokit_emailer_review_followup', array( $this, 'followup_email_sent' ), 10, 2 );
	}

	/**
	 * After verified owner status added.
	 *
	 * @return void
	 */
	public function send_coupon_code( $new_status = '', $old_status = '', $comment = null ) {
			error_log( print_r( $comment, true ) );
		if (
			$this->is_approved_review( $new_status, $comment ) &&
			$this->is_verified_purchase( $comment ) &&
			$this->is_within_reward_limit( $comment )
		) {
			$current_year = gmdate( 'Y' );

			
			// $discount_type       = isset( $args['discount_type'] ) ? $args['discount_type'] : 'percent';
			// $amount              = isset( $args['amount'] ) ? $args['amount'] : 20;
			// $coupon_expires_date = isset( $args['coupon_expires_date'] ) ? $args['coupon_expires_date'] : '';

			$coupon = Utils::generate_coupon( $args );
			// generate copuon
			// update coupon count
			// schedule coupon email
			// update in table
			update_user_meta( $comment->user_id, 'revifoup_coupon_count_' . $current_year, $coupon_count + 1 );
			error_log( print_r( $comment, true ) );
		}
	}

	public function is_approved_review( $new_status = '', $comment = null ) {
		if ( 'approved' === $new_status && in_array( $comment->comment_type, array( 'review', 'comment', '' ), true ) ) {
			$is_product = 'product' === get_post_type( $comment->comment_post_ID );
			if ( $is_product ) {
				return true;
			}
		}

		return false;
	}

	public function is_verified_purchase( $comment = null ) {
		if ( $comment && $comment->comment_ID ) {
			return (bool) get_comment_meta( $comment->comment_ID, 'verified', true );
		}

		return false;
	}

	public function is_within_reward_limit( $comment = null ) {
		if ( $comment->user_id ) {

			$allowed_coupon_limit = get_option( 'revifoup_allowed_coupon_limit', 3 );

			$current_year = gmdate( 'Y' );

			$coupon_count = (int) get_user_meta( $comment->user_id, 'revifoup_coupon_count_' . $current_year, true );

			if ( $allowed_coupon_limit > $coupon_count ) {
				return true;
			} else {
				revifoup()->logger->info(
					'Reward coupon limit reached for this user.',
					array(
						'comment_id' => $comment->comment_ID,
						'email'      => $comment->comment_author_email,
					)
				);

				return false;
			}
		} else {
			revifoup()->logger->info(
				'Reward coupon is only for registered users.',
				array(
					'comment_id' => $comment->comment_ID,
					'email'      => $comment->comment_author_email,
				)
			);

			return false;
		}
	}

	public function followup_email_sent( $args = array(), $sent = 0 ) {
		if ( $sent ) {
			Utils::update_status( $args, 'follow-up-sent' );
		} else {
			Utils::update_status( $args, 'follow-up-failed' );
		}
	}
}

new Hooks_Pro();
