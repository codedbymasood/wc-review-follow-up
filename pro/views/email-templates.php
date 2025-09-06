<?php
/**
 * Email template settings.
 *
 * @package plugin-slug\admin\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace STOBOKIT;

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		$review_request_email_html = "Hi{customer_name},

Thanks again for your recent order! We hope everything arrived in perfect shape and that you're loving your new purchases

We'd really appreciate it if you could take a moment to review the products you received, your feedback helps us improve and also helps other customers shop with confidence.

Here's what you ordered:

{ordered_items}

It only takes a minute, and it means a lot to our small team.

Warmly,
The {site_name} Team";

		$fields = array(
			esc_html__( 'Review Request Email', 'plugin-slug' ) => array(
				array(
					'id'      => 'revifoup_review_request_email_subject',
					'label'   => esc_html__( 'Subject', 'plugin-slug' ),
					'type'    => 'text',
					'default' => esc_html__( 'How was your order? We\'d love your feedback.', 'plugin-slug' ),
				),
				array(
					'id'      => 'revifoup_review_request_email_heading',
					'label'   => esc_html__( 'Heading', 'plugin-slug' ),
					'type'    => 'text',
					'default' => esc_html__( 'Quick favor? We\'d love your feedback!', 'plugin-slug' ),
				),
				array(
					'id'             => 'revifoup_review_request_email_content',
					'label'          => esc_html__( 'Email Content', 'plugin-slug' ),
					'type'           => 'richtext_editor',
					'options'        => array( 'html' ),
					'default_editor' => 'html',
					'default'        => array(
						'html' => $review_request_email_html,
						'css'  => '',
					),
					'description'    => 'You can use {ordered_items}, {customer_name}, {site_name}, {site_url} in the editor.',
				),
				array(
					'id'      => 'revifoup_review_request_email_footer_text',
					'label'   => esc_html__( 'Footer Text', 'plugin-slug' ),
					'type'    => 'textarea',
					'default' => esc_html__( 'Thanks again for choosing us!', 'plugin-slug' ),
				),
			),
		);

		new Settings(
			'plugin-slug',
			'stobokit-revifoup-review-requests', // Parent menu slug.
			'stobokit-revifoup-email-templates', // menu slug.
			esc_html__( 'Email Templates', 'plugin-slug' ),
			esc_html__( 'Email Templates', 'plugin-slug' ),
			'manage_options',
			'',
			0,
			$fields
		);
	}
);
