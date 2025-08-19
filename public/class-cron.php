<?php
/**
 * Register cronjobs class.
 *
 * @package review-follow-up-for-woocommerce\public\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace REVIFOUP;

use Pelago\Emogrifier\CssInliner;

defined( 'ABSPATH' ) || exit;

/**
 * Register cronjobs class.
 */
class Cron {

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
		add_action( 'revifoup_send_review_email', array( $this, 'send_review_email' ), 10, 2 );
	}

	public function send_review_email( $email, $order ) {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject = get_option( 'revifoup_review_email_subject', esc_html__( 'How was your order? We\'d love your feedback.', 'review-follow-up-for-woocommerce' ) );

		ob_start();
		include REVIFOUP_PATH . '/template/email/html-template-email.php';
		$content = ob_get_contents();
		ob_end_clean();

		// CssInliner loads from WooCommerce.
		$html = CssInliner::fromHtml( $content )->inlineCss()->render();

		$result = wp_mail( $email, $subject, $html, $headers ); // we can use `wc_mail` instead.
		if ( ! $result ) {
			esc_html_e( 'Mail failed to sent.', 'review-follow-up-for-woocommerce' );
		} else {
			esc_html_e( 'Mail sent successfully.', 'review-follow-up-for-woocommerce' );
		}

		// TODO: Change status to `sent` or `failed`.
	}

}

\REVIFOUP\Cron::instance();




