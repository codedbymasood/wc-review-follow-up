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
 * Settings class.
 */
class Admin_Pro {

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		add_filter( 'stobokit_product_lists', array( $this, 'add_product' ) );

		add_action( 'revifoup_review_request_sent', array( $this, 'after_review_request_sent' ), 10, 2 );
	}

	public function add_product( $products = array() ) {
		$products['plugin-slug']['name'] = esc_html__( 'Plugin Name', 'plugin-slug' );
		$products['plugin-slug']['id']   = 74;

		return $products;
	}

	public function after_review_request_sent( $email = '', $order = null ) {

		$this->send_followup_email( $email, $order );
		$this->set_unsubscribe( $email, $order );

		/* Instead of eamiler use scheduler so we can add reward period */
		/* 
		 * Send final follow up
		 * After reward period we need to flag unsubscribe
		*/
	}

	public function send_followup_email( $email = '', $order = null ) {

		$schedule_days = (int) get_option( 'revifoup_sent_email_days', 3 );
		$followup_days = (int) get_option( 'revifoup_followup_days', 2 );

		$order_id = $order->get_ID();

		$subject     = get_option( 'revifoup_review_request_order_email_subject', esc_html__( 'Review + Save: {discount} discount waiting for you', 'plugin-slug' ) );
		$heading     = get_option( 'revifoup_review_followup_email_heading', esc_html__( 'Review Your Order & Get {discount} Off Your Next Purchase!', 'plugin-slug' ) );
		$footer_text = get_option( 'revifoup_review_followup_email_footer_text', '' );

		$content = get_option(
			'revifoup_review_followup_email_content',
			array(
				'html' => "Hi{customer_name},

We hope you're enjoying your recent purchase from {site_name}!

We noticed you haven't had a chance to review your order yet. We'd love to hear your thoughts - and as a thank you, we'll send you a {discount} discount code for your next purchase once you leave a review.

Here's what you ordered:
{ordered_items}

Share your honest feedback, We'll email your discount code within 24 hours.

Your reviews help other customers make confident purchases and help us improve our products. Plus, you get rewarded for taking the time!

Thanks for being an amazing customer,
The {site_name} Team",
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

		$result = revifoup()->emailer->send_later(
			$email,
			$subject,
			$html,
			( $schedule_days + $followup_days ),
			array(
				'email'    => $email,
				'order_id' => $order_id,
			),
			'review_followup',
		);
	}

	public function set_unsubscribe( $email = '', $order = null ) {
		$order_id = $order->get_ID();

		revifoup()->cron->create_schedule(
			array(
				'hook_name'     => 'set_reward_unsubscribe_' . $order_id,
				'callback'      => array( 'REVIFOUP\Cron_Pro', 'set_reward_unsubscribe' ),
				'timestamp'     => time() + ( 2 * 60 ), // set expiry time here.
				'callback_args' => array(
					'email'    => $email,
					'order_id' => $order_id,
				),
				'override'      => true,
			)
		);
	}
}

new Admin_Pro();
