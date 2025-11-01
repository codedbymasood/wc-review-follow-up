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
use STOBOKIT\Utils as Core_Utils;

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
		add_filter(
			'stobokit_plugins',
			function ( $plugins = array() ) {
				$plugins[] = 'plugin-slug';

				return $plugins;
			}
		);
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'order_completed' ) );
	}

	public function order_completed( $order_id = 0 ) {
		$order = wc_get_order( $order_id );

		if ( $this->is_allow( $order ) ) {
			$email = $order->get_billing_email();

			$this->save_data_in_table( $email, $order );
			$this->send_review_request_email( $email, $order );
			$this->send_followup_email( $email, $order );
			$this->set_unsubscribe( $email, $order );

			/**
			 * After email sent.
			 */
			do_action( 'revifoup_review_request_sent', $email, $order );
		}
	}

	public function is_allow( $order = null ) {
		$allow = true;

		$order_total = (float) $order->get_total();

		$exceed_order_amount = (int) get_option( 'revifoup_exceed_order_amount', '' );

		if ( $exceed_order_amount && $order_total < $exceed_order_amount ) {
			$allow = false;
		}

		return $allow;
	}

	public function save_data_in_table( $email, $order ) {

		global $wpdb;

		$table_name = $wpdb->prefix . 'revifoup_review_requests';

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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
				$result = $wpdb->insert(
					$wpdb->prefix . 'revifoup_review_requests',
					array(
						'email'    => $email,
						'order_id' => $order_id,
						'status'   => 'scheduled',
					),
					array( '%s', '%d', '%s' )
				);

				if ( false === $result ) {
					revifoup()->logger->warning( 'Can\'t insert review requests log.', array( 'order_id' => $order_id ) );
				}
			} else {
				revifoup()->logger->info(
					'Scheduled already.',
					array(
						'order_id' => $order_id,
						'email'    => $email,
					)
				);
			}
		} else {
			revifoup()->logger->info(
				'Order doesn\'t contain an email address.',
				array(
					'order_id' => $order_id,
				)
			);
		}
	}

	public function send_review_request_email( $email, $order ) {
		$order_id = $order->get_ID();

		$request_type  = get_option( 'revifoup_request_type', 'by_order' );
		$schedule_days = (int) get_option( 'revifoup_sent_email_days', 3 );

		$subject     = get_option( 'revifoup_review_request_email_subject', esc_html__( 'How was your order? We\'d love your feedback.', 'plugin-slug' ) );
		$heading     = get_option( 'revifoup_review_request_email_heading', esc_html__( 'Quick favor? We\'d love your feedback!', 'plugin-slug' ) );
		$footer_text = get_option( 'revifoup_review_request_email_footer_text', esc_html__( 'Thanks again for choosing us!', 'plugin-slug' ) );

		$content = get_option(
			'revifoup_review_request_email_content',
			array(
				'html' => "Hi{customer_name},

Thanks again for your recent order! We hope everything arrived in perfect shape and that you're loving your new purchases.
{% is_order_request_type %}
We'd really appreciate it if you could take a moment to review the products you received, your feedback helps us improve and also helps other customers shop with confidence.
{%}
{% is_product_request_type %}
We'd especially love to hear your thoughts on the {high_value_product_name} and any other items you received. Your feedback helps us improve and also helps other customers shop with confidence.

{product_info}
{%}
Here's what you ordered:
{ordered_items}

It only takes a minute, and it means a lot to our small team.

Warmly,
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
			$schedule_days,
			array(
				'email'                   => $email,
				'order_id'                => $order_id,
				'is_product_request_type' => ( 'by_product' === $request_type ),
				'is_order_request_type'   => ( 'by_order' === $request_type ),
			),
			'review_request',
		);
	}

	public function send_followup_email( $email = '', $order = null ) {
		$enable_followup = (int) get_option( 'revifoup_enable_followup', '0' );

		if ( ! Core_Utils::string_to_bool( $enable_followup ) ) {
			return;
		}

		$enable_discount = get_option( 'revifoup_enable_discount', '0' );

		$schedule_days = (int) get_option( 'revifoup_sent_email_days', 3 );
		$followup_days = (int) get_option( 'revifoup_followup_days', 2 );
		$discount_type = get_option( 'revifoup_discount_type', 'percent' );
		$amount        = (int) get_option( 'revifoup_discount_amount', 20 );

		$order_id = $order->get_ID();

		$subject     = get_option( 'revifoup_review_request_email_subject', esc_html__( 'Review + Save: {discount} discount waiting for you', 'plugin-slug' ) );
		$heading     = get_option( 'revifoup_review_followup_email_heading', esc_html__( 'Review Your Order & Get {discount} Off Your Next Purchase!', 'plugin-slug' ) );
		$footer_text = get_option( 'revifoup_review_followup_email_footer_text', '' );

		$content = get_option(
			'revifoup_review_followup_email_content',
			array(
				'html' => "Hi{customer_name},

We hope you're enjoying your recent purchase from {site_name}!

We noticed you haven't had a chance to review your order yet. We'd love to hear your thoughts{% coupon_enabled %} - and as a thank you, we'll send you a {discount} discount code for your next purchase once you leave a review{%}.

Here's what you ordered:
{ordered_items}

{% coupon_enabled %}Share your honest feedback, We'll email your discount code within 24 hours.{%}

Your reviews help other customers make confident purchases and help us improve our products.{% coupon_enabled %} Plus, you get rewarded for taking the time!{%}

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
				'coupon_enabled' => Core_Utils::string_to_bool( $enable_discount ),
				'email'          => $email,
				'order_id'       => $order_id,
				'discount_type'  => $discount_type,
				'amount'         => $amount,
			),
			'review_followup',
		);
	}

	public function set_unsubscribe( $email = '', $order = null ) {
		$order_id = $order->get_ID();

		$total_days = 0;

		$total_days += (int) get_option( 'revifoup_sent_email_days', 3 );
		$total_days += (int) get_option( 'revifoup_followup_days', 2 );

		$enable_discount = get_option( 'revifoup_enable_discount', '0' );

		if ( Core_Utils::string_to_bool( $enable_discount ) ) {
			$total_days += (int) get_option( 'revifoup_coupon_expires_in', 30 );
		}

		revifoup()->cron->create_schedule(
			array(
				'hook_name'     => 'set_review_requests_unsubscribe_' . $order_id,
				'callback'      => array( 'REVIFOUP\Cron', 'review_requests_unsubscribe' ),
				'timestamp'     => time() + ( $total_days * DAY_IN_SECONDS ),
				'callback_args' => array(
					'email'    => $email,
					'order_id' => $order_id,
				),
				'override'      => true,
			)
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
