<?php
/**
 * Admin class.
 *
 * @package review-requester-for-woocommerce\admin\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

use Pelago\Emogrifier\CssInliner;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin {

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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'order_completed' ) );
	}
	/**
	 * Enqueue scripts.
	 *
	 * @param string $hook Menu hook.
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		wp_enqueue_style( 'rrw-admin', RRW_URL . '/admin/assets/css/admin.css', array(), '1.0' );
	}

	public function order_completed( $order_id = 0 ) {
		$order = wc_get_order( $order_id );
		// Save details to table.
		// Sent an email.

		$email = $order->get_billing_email();

		$this->save_data_in_table( $email, $order );

		// $this->set_cron_job( $email, $order );

		// TODO: Move the email to cronjobs, it only here for testing purposes.
		// $this->send_review_email( $email, $order );
	}

	public function save_data_in_table( $email, $order ) {

		global $wpdb;

		$table_name = $wpdb->prefix . 'rrw_review_requests';

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
					$wpdb->prefix . 'rrw_review_requests',
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

	public function send_review_email( $email, $order ) {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject = esc_html__( 'Back in Stock!', 'review-requester-for-woocommerce' );

		ob_start();
		include RRW_PATH . '/template/email/html-template-email.php';
		$content = ob_get_contents();
		ob_end_clean();

		// CssInliner loads from WooCommerce.
		$html = CssInliner::fromHtml( $content )->inlineCss()->render();

		$result = wp_mail( $email, $subject, $html, $headers ); // we can use `wc_mail` instead.
		if ( ! $result ) {
			esc_html_e( 'Mail failed to sent.', 'review-requester-for-woocommerce' );
		} else {
			esc_html_e( 'Mail sent successfully.', 'review-requester-for-woocommerce' );
		}
	}

	public function set_cron_job( $email, $order ) {
		$schedule = time() + ( 3 * DAY_IN_SECONDS ); // 3 days later.

		wp_schedule_single_event(
			$schedule,
			'rrw_send_review_email',
			array( $email, $order )
		);
	}

	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Review Requests', 'review-requester-for-woocommerce' ),
			esc_html__( 'Review Requests', 'review-requester-for-woocommerce' ),
			'manage_options',
			'review-requests',
			array( $this, 'render_review_request_page' ),
			'dashicons-email',
			26
		);
	}

	public function render_review_request_page() {
		$order = wc_get_order( 90 );

		// var_dump($order->get_items());
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Review Requests', 'review-requester-for-woocommerce' ) . '</h1>';
		$notify_table = new Review_Request_List_Table();
		$notify_table->prepare_items();
		echo '<form method="post">';
		$notify_table->display();
		echo '</form></div>';
	}
}

\RRW\Admin::instance();
