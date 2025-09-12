<?php
/**
 * Admin class.
 *
 * @package plugin-slug\admin\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

use Pelago\Emogrifier\CssInliner;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin {

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
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'order_completed' ) );
	}

	public function order_completed( $order_id = 0 ) {
		$order = wc_get_order( $order_id );

		$order_total = (float) $order->get_total();
		$email       = $order->get_billing_email();

		$exceed_order_amount = (int) get_option( 'revifoup_exceed_order_amount', '' );

		if ( empty( $exceed_order_amount ) || $order_total >= $exceed_order_amount ) {
			$this->save_data_in_table( $email, $order );
			$this->send_review_request_email( $email, $order );
		}
	}

	public function save_data_in_table( $email, $order ) {

		global $wpdb;

		$table_name = $wpdb->prefix . 'revifoup_review_requests';

		if ( ! empty( $email ) && null !== $order ) {
			$order_id = $order->get_ID();

			$exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM $table_name WHERE email = %s AND order_id = %d",
					$email,
					$order_id
				)
			);

			if ( ! $exists ) {
				$wpdb->insert(
					$wpdb->prefix . 'revifoup_review_requests',
					array(
						'email'    => $email,
						'order_id' => $order_id,
						'status'   => 'queued',
					),
					array( '%s', '%d', '%s' )
				);
			} else {
				die( esc_html__( 'Scheduled already.', 'product-availability-notifier-for-woocommerce' ) );
			}
		} else {
			die( esc_html__( 'Order doesn\'t contain an email address.', 'product-availability-notifier-for-woocommerce' ) );
		}
	}

	public function send_review_request_email( $email, $order ) {
		$schedule_days = (int) get_option( 'revifoup_sent_email_days', 3 );

		$subject     = get_option( 'revifoup_review_request_email_subject', esc_html__( 'How was your order? We\'d love your feedback.', 'plugin-slug' ) );
		$heading     = get_option( 'revifoup_review_request_email_heading', esc_html__( 'Quick favor? We\'d love your feedback!', 'plugin-slug' ) );
		$footer_text = get_option( 'revifoup_review_request_email_footer_text', esc_html__( 'Thanks again for choosing us!', 'plugin-slug' ) );

		$content = get_option(
			'revifoup_review_request_email_content',
			"Hi{customer_name},

Thanks again for your recent order! We hope everything arrived in perfect shape and that you're loving your new purchases

We'd really appreciate it if you could take a moment to review the products you received, your feedback helps us improve and also helps other customers shop with confidence.

Here's what you ordered:

{ordered_items}

It only takes a minute, and it means a lot to our small team.

Warmly,
The {site_name} Team"
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

		$result = revifoup()->emailer->send_later(
			$email,
			$subject,
			$html,
			$schedule_days,
			array(
				'email' => $email,
				'order' => $order,
			),
			'review_followup'
		);
	}

	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Review Requests', 'plugin-slug' ),
			esc_html__( 'Review Requests', 'plugin-slug' ),
			'manage_options',
			'stobokit-revifoup-review-requests',
			array( $this, 'render_review_request_page' ),
			'dashicons-email',
			50
		);
	}

	public function render_review_request_page() {
		$args = array(
			'title'      => esc_html__( 'Review Requests', 'plugin-slug' ),
			'singular'   => 'request',
			'plural'     => 'requests',
			'table_name' => 'revifoup_review_requests',
			'id'         => 'revifoup_review_requests',
		);

		$table = new Review_Request_List_Table( $args );
		$table->display_table();
	}
}

\REVIFOUP\Admin::instance();
