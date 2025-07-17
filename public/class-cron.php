<?php
/**
 * Register cronjobs class.
 *
 * @package review-requester-for-woocommerce\public\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

use Pelago\Emogrifier\CssInliner;

defined( 'ABSPATH' ) || exit;

/**
 * Register cronjobs class.
 */
class Cron {

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
		add_action( 'rrw_send_review_email', array( $this, 'send_review_email' ), 10, 2 );
	}

	public function send_review_email( $email, $order ) {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject = get_option( 'rrw_review_email_subject', esc_html__( 'How was your order? We\'d love your feedback.', 'review-requester-for-woocommerce' ) );

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

		// TODO: Change status to `sent` or `failed`.
	}

}

\RRW\Cron::instance();




