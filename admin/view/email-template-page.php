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
		$default_html = "Hi [customer_name],

Thanks again for your recent order! We hope everything arrived in perfect shape and that you're loving your new purchases.

We'd really appreciate it if you could take a moment to review the products you received, your feedback helps us improve and also helps other customers shop with confidence.

Here's what you ordered:

[items]

It only takes a minute, and it means a lot to our small team.
Thanks again for choosing us!

Warmly,
The [store_name] Team";

		$fields = array(
			esc_html__( 'Review Email Template', 'review-requester-for-woocommerce' ) => array(
				array(
					'id'      => 'rrw_review_email_subject',
					'label'   => esc_html__( 'Subject', 'product-availability-notifier-for-woocommerce' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'id'      => 'rrw_review_email_title',
					'label'   => esc_html__( 'Title', 'product-availability-notifier-for-woocommerce' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'id'             => 'rrw_review_email_template3',
					'type'           => 'richtext_editor',
					'options'        => array( 'html', 'css' ),
					'default_editor' => 'html',
					'default'        => array(
						'html' => $default_html,
						'css'  => '',
					),
					'description'    => 'You can use [items], [customer_name], [store_name] in the editor.',
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
