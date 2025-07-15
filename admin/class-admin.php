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
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'order_completed' ) );
	}

	public function order_completed( $order_id = 0 ) {
		// $order = wc_get_order( $order_id );

		// foreach ( $order->get_items() as $item_id => $item ) {
		// 	$product        = $item->get_product();      // WC_Product object
		// 	$product_id     = $item->get_product_id();   // ID without variations
		// 	$variation_id   = $item->get_variation_id(); // Variation ID, 0 if none
		// 	$name           = $item->get_name();         // Product title
		// 	$qty            = $item->get_quantity();     // Quantity purchased
		// 	$line_subtotal  = $item->get_subtotal();     // Price × qty, before tax/discount
		// 	$line_total     = $item->get_total();        // Price × qty, after tax/discount

			// Do whatever you need here…
			// e.g. echo "$name × $qty – ₹$line_total<br>";
		// }

		// Save details to table.
		// Sent an email.

		$email = 'example@example.com';

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject = esc_html__( 'Back in Stock!', 'product-availability-notifier-for-woocommerce' );

		ob_start();
		include RRW_PATH . '/template/email/html-template-email.php';
		$content = ob_get_contents();
		ob_end_clean();

		// CssInliner loads from WooCommerce.
		$html = CssInliner::fromHtml( $content )->inlineCss()->render();

		$result = woocommerce_mail( $email, $subject, $content, $headers );
		if ( ! $result ) {
			esc_html_e( 'Mail failed to sent.', 'product-availability-notifier-for-woocommerce' );
		} else {
			esc_html_e( 'Mail sent successfully.', 'product-availability-notifier-for-woocommerce' );
		}
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
