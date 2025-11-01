<?php
/**
 * Hooks class.
 *
 * @package plugin-slug\common\includes\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

use Pelago\Emogrifier\CssInliner;
use STOBOKIT\Utils as Core_Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Settings class.
 */
class Hooks {

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->register_mail_tags();
		add_action( 'transition_comment_status', array( $this, 'send_coupon_code' ), 10, 3 );
		add_action( 'stobokit_emailer_review_followup', array( $this, 'after_follow_up_email_sent' ), 10, 2 );
	}

	public function register_mail_tags() {

		revifoup()->emailer->register_shortcode(
			'ordered_items',
			function ( $args ) {
				$order_id = isset( $args['order_id'] ) ? $args['order_id'] : 0;

				$order = wc_get_order( $order_id );

				if ( ! $order ) {
					revifoup()->logger->info(
						'Order not found.',
						array(
							'order_id' => $order_id,
						)
					);
					return;
				}

				$items = $order->get_items();

				$html = '';
				foreach ( $items as $item_id => $item ) {
					$product = $item->get_product();

					if ( ! $product ) {
						revifoup()->logger->info(
							'Product not found in this order.',
							array(
								'order_id' => $order_id,
							)
						);
						continue;
					}

					$excluded_category = array_map( 'intval', get_option( 'revifoup_exclude_categories', array() ) );

					if ( ! empty( $excluded_category ) && array_intersect( $excluded_category, $product->get_category_ids() ) ) {
						continue;
					}

					if ( apply_filters( 'revifoup_exclude_product', false, $product ) ) {
						continue;
					}

					$product_name = $item->get_name();
					$product_url  = $product->get_permalink();

					$html .= '<div class="order-item">';
					$html .= '<h4>' . esc_html( $product_name ) . '</h4>';
					$html .= '<a href="' . esc_url( $product_url . '#tab-reviews' ) . '">' . esc_html__( 'Leave a Review', 'plugin-slug' ) . '</a>';
					$html .= '</div>';
				}

				return $html;
			}
		);

		revifoup()->emailer->register_shortcode(
			'discount',
			function ( $args ) {
				$discount_type = isset( $args['discount_type'] ) ? $args['discount_type'] : '';
				$amount        = isset( $args['amount'] ) ? $args['amount'] : '';

				return ( 'percent' === $discount_type ) ? $amount . '%' : $amount;
			}
		);

		revifoup()->emailer->register_shortcode(
			'coupon_code',
			function ( $args ) {
				return isset( $args['coupon'] ) ? $args['coupon'] : '';
			}
		);

		revifoup()->emailer->register_shortcode(
			'coupon_expires',
			function ( $args ) {
				return isset( $args['coupon_expires_in'] ) ? $args['coupon_expires_in'] : '';
			}
		);

		revifoup()->emailer->register_shortcode(
			'coupon_expiry_date',
			function ( $args ) {
				return isset( $args['coupon_expires_date'] ) ? $args['coupon_expires_date'] : '';
			}
		);

		revifoup()->emailer->register_shortcode(
			'coupon_expires',
			function ( $args ) {
				return isset( $args['coupon_expires_in'] ) ? $args['coupon_expires_in'] : '';
			}
		);
	}

	/**
	 * After verified owner status added.
	 *
	 * @return void
	 */
	public function send_coupon_code( $new_status = '', $old_status = '', $comment = null ) {
		if (
			$this->is_approved_review( $new_status, $comment )
			&& $this->is_verified_purchase( $comment )
			&& $this->is_within_reward_limit( $comment )
		) {
			$subscribe_details = $this->get_subscribe_details( $comment );

			if ( $subscribe_details ) {
				$current_year = gmdate( 'Y' );

				$coupon_count = (int) get_user_meta( $comment->user_id, 'revifoup_coupon_count_' . $current_year, true );

				update_user_meta( $comment->user_id, 'revifoup_coupon_count_' . $current_year, $coupon_count + 1 );

				$this->send_reward_email( $subscribe_details );
			}
		}
	}

	public function send_reward_email( $row = array() ) {
		$email = isset( $row['email'] ) ? $row['email'] : '';

		$enable_discount = get_option( 'revifoup_enable_discount', '0' );

		$discount_type     = get_option( 'revifoup_discount_type', 'percent' );
		$amount            = get_option( 'revifoup_discount_amount', 20 );
		$coupon_expires_in = get_option( 'revifoup_coupon_expires_in', 30 );

		$coupon_expires      = time() + ( $coupon_expires_in * DAY_IN_SECONDS ); // Add 30 days.
		$coupon_expires_date = gmdate( 'd-m-Y', $coupon_expires );

		$coupon = false;

		if ( Core_Utils::string_to_bool( $enable_discount ) ) {
			$args = array(
				'discount_type'       => $discount_type,
				'amount'              => $amount,
				'coupon_expires_date' => $coupon_expires_date,
			);

			$coupon = Utils::generate_coupon( $args );
		}

		$subject     = get_option( 'revifoup_review_reward_email_subject', esc_html__( 'Your {discount} discount code is here! 🎉', 'plugin-slug' ) );
		$heading     = get_option( 'revifoup_review_reward_email_heading', esc_html__( 'Thank You for Your Review! Here\'s Your {discount} Discount Code', 'plugin-slug' ) );
		$footer_text = get_option( 'revifoup_review_reward_email_footer_text', '' );

		$content = get_option(
			'revifoup_review_reward_email_content',
			array(
				'html' => 'Hi{customer_name},

Thank you so much for taking the time to review your recent purchase! Your feedback means the world to us and helps other customers shop with confidence.

{% coupon_enabled %}
Discount Code: {coupon_code}
Discount: {discount} off your next order
Expires: {coupon_expiry_date}

How to use:
Simply enter code {coupon_code} at checkout to save {discount} on your next purchase.
{%}

We truly appreciate customers like you who take the time to share their experiences. Your review helps our small business grow and helps other shoppers make informed decisions.

Happy shopping!
The {site_name} Team',
			)
		);

		$content = revifoup()->templates->get_template(
			'email/email-content.php',
			array(
				'heading'     => $heading,
				'content'     => $content['html'],
				'footer_text' => $footer_text,
			)
		);

		// CssInliner loads from WooCommerce.
		$html = CssInliner::fromHtml( $content )->inlineCss()->render();

		$result = revifoup()->emailer->send_now(
			$email,
			$subject,
			$html,
			array(
				'coupon_enabled'      => Core_Utils::string_to_bool( $enable_discount ),
				'coupon'              => $coupon,
				'discount_type'       => $discount_type,
				'amount'              => $amount,
				'coupon_expires_in'   => $coupon_expires_in,
				'coupon_expires_date' => $coupon_expires_date,
			)
		);
	}

	public function get_subscribe_details( $comment = null ) {
		$email      = 'masood@example.com'; // $comment->comment_author_email;
		$product_id = (int) $comment->comment_post_ID;

		global $wpdb;

		$table = $wpdb->prefix . 'revifoup_review_requests';

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table WHERE status = %s AND email = %s",
				'unsubscribed',
				$email
			),
			ARRAY_A
		);

		foreach ( $results as $key => $row ) {
			$order_id = isset( $row['order_id'] ) ? $row['order_id'] : 0;

			$order = wc_get_order( $order_id );

			foreach ( $order->get_items() as $item ) {

				if ( $product_id === $item->get_product_id() ) {
					return $row;
				}
			}
		}
	}

	public function is_approved_review( $new_status = '', $comment = null ) {
		return true;
		if ( 'approved' === $new_status && in_array( $comment->comment_type, array( 'review', 'comment', '' ), true ) ) {
			$is_product = 'product' === get_post_type( $comment->comment_post_ID );
			if ( $is_product ) {
				return true;
			}
		}

		return false;
	}

	public function is_verified_purchase( $comment = null ) {
		return true;
		if ( $comment && $comment->comment_ID ) {
			return (bool) get_comment_meta( $comment->comment_ID, 'verified', true );
		}

		return false;
	}

	public function is_within_reward_limit( $comment = null ) {
		return true;
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

	public function after_follow_up_email_sent( $args = array(), $sent = 0 ) {
		if ( $sent ) {
			Utils::update_status( $args, 'follow-up-sent' );
		} else {
			Utils::update_status( $args, 'follow-up-failed' );
		}
	}
}

new Hooks();
