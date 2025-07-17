<?php
/**
 * Settings class.
 *
 * @package review-requester-for-woocommerce\admin\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function() {

		// $order = wc_get_order(116);

		// var_dump( $order->get_billing_first_name(), $order->get_billing_last_name() );

		$default_html = "<p>Hi [customer_name],</p>

<p>Thanks again for your recent order! We hope everything arrived in perfect shape and that you're loving your new purchases.</p>

<p>We'd really appreciate it if you could take a moment to review the products you received, your feedback helps us improve and also helps other customers shop with confidence.</p>

<strong>Here's what you ordered:</strong>

[items]

<p>It only takes a minute, and it means a lot to our small team.</p>

<p>Thanks again for choosing us!</p>

<p>Warmly,</p>
<p>The [site_name] Team</p>";

		$fields = array(
			esc_html__( 'Review Email Template', 'review-requester-for-woocommerce' ) => array(
				array(
					'id'      => 'rrw_review_email_subject',
					'label'   => esc_html__( 'Subject', 'product-availability-notifier-for-woocommerce' ),
					'type'    => 'text',
					'default' => esc_html__( 'How was your order? We\'d love your feedback.', 'review-requester-for-woocommerce' ),
				),
				array(
					'id'      => 'rrw_review_email_title',
					'label'   => esc_html__( 'Title', 'product-availability-notifier-for-woocommerce' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'id'             => 'rrw_review_email_template',
					'type'           => 'richtext_editor',
					'options'        => array( 'html', 'css' ),
					'default_editor' => 'html',
					'default'        => array(
						'html' => $default_html,
						'css'  => '',
					),
					'description'    => 'You can use [items], [customer_name], [site_name] in the editor.',
				),
			),
		);

		new Settings(
			'review-requests',          // Parent menu slug.
			'review-email-template',    // menu slug.
			esc_html__( 'Email Template', 'review-requester-for-woocommerce' ),
			esc_html__( 'Email Template', 'review-requester-for-woocommerce' ),
			'manage_options',
			$fields
		);
	}
);
