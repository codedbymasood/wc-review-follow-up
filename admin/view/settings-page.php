<?php
/**
 * Settings class.
 *
 * @package review-follow-up-for-woocommerce\admin\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace STOBOKIT;

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function() {
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
			esc_html__( 'Mail Settings', 'review-follow-up-for-woocommerce' ) => array(
				array(
					'id'      => 'stobokit_email_from_name',
					'label'   => esc_html__( 'From Name', 'review-follow-up-for-woocommerce' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'name' ),
				),
				array(
					'id'      => 'stobokit_email_from_address',
					'label'   => esc_html__( 'From Address', 'review-follow-up-for-woocommerce' ),
					'type'    => 'text',
					'default' => '',
				),
			),
			esc_html__( 'Review Email', 'review-follow-up-for-woocommerce' ) => array(
				array(
					'id'      => 'revifoup_review_email_subject',
					'label'   => esc_html__( 'Subject', 'review-follow-up-for-woocommerce' ),
					'type'    => 'text',
					'default' => esc_html__( 'How was your order? We\'d love your feedback.', 'review-follow-up-for-woocommerce' ),
				),
				array(
					'id'      => 'revifoup_review_email_title',
					'label'   => esc_html__( 'Title', 'review-follow-up-for-woocommerce' ),
					'type'    => 'text',
					'default' => esc_html__( 'Quick favor? We\'d love your feedback!', 'review-follow-up-for-woocommerce' ),
				),
				array(
					'id'      => 'revifoup_sent_email_days',
					'label'   => esc_html__( 'Email will be sent in (x) days', 'review-follow-up-for-woocommerce' ),
					'type'    => 'text',
					'default' => 3,
				),
				array(
					'id'      => 'revifoup_exceed_order_amount',
					'label'   => esc_html__( 'Send email when order total exceeds (x) (optional)', 'review-follow-up-for-woocommerce' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'id'             => 'revifoup_review_email_template',
					'label'          => esc_html__( 'Email Template', 'review-follow-up-for-woocommerce' ),
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
			'stobokit-review-requests',          // Parent menu slug.
			'stobokit-review-requests-settings', // menu slug.
			esc_html__( 'Settings', 'review-follow-up-for-woocommerce' ),
			esc_html__( 'Settings', 'review-follow-up-for-woocommerce' ),
			'manage_options',
			0,
			$fields
		);
	}
);
