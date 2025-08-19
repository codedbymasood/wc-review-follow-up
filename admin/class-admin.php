<?php
/**
 * Admin class.
 *
 * @package review-follow-up-for-woocommerce\admin\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
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
		add_filter( 'stobokit_product_lists', array( $this, 'add_product' ) );
	}

	public function add_product( $products = array() ) {
		$products['review-follow-up']['name'] = esc_html__( 'Review Follow Up for WooCommerce', 'review-follow-up-for-woocommerce' );
		$products['review-follow-up']['id']   = 105;

		return $products;
	}

	public function order_completed( $order_id = 0 ) {
		$order = wc_get_order( $order_id );

		$order_total = (float) $order->get_total();
		$email       = $order->get_billing_email();

		$exceed_order_amount = (int) get_option( 'revifoup_exceed_order_amount', '' );

		if ( empty( $exceed_order_amount ) || $order_total >= $exceed_order_amount ) {
			$this->save_data_in_table( $email, $order );
			$this->set_cron_job( $email, $order );
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

	public function set_cron_job( $email, $order ) {
		$schedule_days = get_option( 'revifoup_sent_email_days', 3 );

		$schedule = time() + ( $schedule_days * DAY_IN_SECONDS ); // 3 days later

		// $schedule = time() + 60; // 2 minutes later

		wp_schedule_single_event(
			$schedule,
			'revifoup_send_review_email',
			array( $email, $order )
		);
	}

	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Review Requests', 'review-follow-up-for-woocommerce' ),
			esc_html__( 'Review Requests', 'review-follow-up-for-woocommerce' ),
			'manage_options',
			'review-requests',
			array( $this, 'render_review_request_page' ),
			'dashicons-email',
			26
		);
	}

	public function render_review_request_page() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Review Requests', 'review-follow-up-for-woocommerce' ) . '</h1>';
		$notify_table = new Review_Request_List_Table();
		$notify_table->prepare_items();
		echo '<form method="post">';
		$notify_table->display();
		echo '</form></div>';
	}
}

\REVIFOUP\Admin::instance();
