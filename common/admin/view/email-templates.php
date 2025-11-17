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
The {site_name} Team";

		$review_followup_email_html = "Hi{customer_name},

We hope you're enjoying your recent purchase from {site_name}!

We noticed you haven't had a chance to review your order yet. We'd love to hear your thoughts{% coupon_enabled %} - and as a thank you, we'll send you a {discount} discount code for your next purchase once you leave a review{%}.

Here's what you ordered:
{ordered_items}

{% coupon_enabled %}Share your honest feedback, We'll email your discount code within 24 hours.{%}

Your reviews help other customers make confident purchases and help us improve our products.{% coupon_enabled %} Plus, you get rewarded for taking the time!{%}

Thanks for being an amazing customer,
The {site_name} Team";

		$review_reward_email_html = 'Hi{customer_name},

Thank you so much for taking the time to review your recent purchase! Your feedback means the world to us and helps other customers shop with confidence.

{% coupon_enabled %}
Discount Code: {coupon_code}
Discount: {discount} off your next order
Expires: {coupon_expiry_date}

How to use:
Simply enter code {coupon_code} at checkout to save {discount} on your next purchase.
{%}

We truly appreciate customers like you who take the time to share their experiences. Your review helps our small business grow and helps other shoppers make informed decisions.

Happy shopping!
The {site_name} Team';

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
					'description'    => 'You can use {product_info}, {ordered_items}, {customer_name}, {site_name}, {site_url} in the editor. Also you can use this conditional block {% is_order_request_type %}, {% is_product_request_type %}.',
				),
				array(
					'id'      => 'revifoup_review_request_email_footer_text',
					'label'   => esc_html__( 'Footer Text', 'plugin-slug' ),
					'type'    => 'textarea',
					'default' => esc_html__( 'Thanks again for choosing us!', 'plugin-slug' ),
				),
			),
			esc_html__( 'Final Followup Email', 'plugin-slug' ) => array(
				array(
					'id'      => 'revifoup_review_followup_email_subject',
					'label'   => esc_html__( 'Subject', 'plugin-slug' ),
					'type'    => 'text',
					'default' => esc_html__( 'Review + Save: {discount} discount waiting for you', 'plugin-slug' ),
				),
				array(
					'id'      => 'revifoup_review_followup_email_heading',
					'label'   => esc_html__( 'Heading', 'plugin-slug' ),
					'type'    => 'text',
					'default' => esc_html__( 'Review Your Order & Get {discount} Off Your Next Purchase!', 'plugin-slug' ),
				),
				array(
					'id'             => 'revifoup_review_followup_email_content',
					'label'          => esc_html__( 'Email Content', 'plugin-slug' ),
					'type'           => 'richtext_editor',
					'options'        => array( 'html' ),
					'default_editor' => 'html',
					'default'        => array(
						'html' => $review_followup_email_html,
						'css'  => '',
					),
					'description'    => 'You can use {discount}, {ordered_items}, {customer_name}, {site_name}, {site_url} in the editor. Also you can use this conditional block {% coupon_enabled %}.',
				),
				array(
					'id'      => 'revifoup_review_followup_email_footer_text',
					'label'   => esc_html__( 'Footer Text', 'plugin-slug' ),
					'type'    => 'textarea',
					'default' => '',
				),
			),
			esc_html__( 'Review Reward Email', 'plugin-slug' ) => array(
				array(
					'id'      => 'revifoup_review_reward_email_subject',
					'label'   => esc_html__( 'Subject', 'plugin-slug' ),
					'type'    => 'text',
					'default' => esc_html__( 'Your {discount} discount code is here! 🎉', 'plugin-slug' ),
				),
				array(
					'id'      => 'revifoup_review_reward_email_heading',
					'label'   => esc_html__( 'Heading', 'plugin-slug' ),
					'type'    => 'text',
					'default' => esc_html__( 'Thank You for Your Review! Here\'s Your {discount} Discount Code', 'plugin-slug' ),
				),
				array(
					'id'             => 'revifoup_review_reward_email_content',
					'label'          => esc_html__( 'Email Content', 'plugin-slug' ),
					'type'           => 'richtext_editor',
					'options'        => array( 'html' ),
					'default_editor' => 'html',
					'default'        => array(
						'html' => $review_reward_email_html,
						'css'  => '',
					),
					'description'    => 'You can use {coupon_code}, {discount}, {coupon_expiry_date}, {customer_name}, {site_name}, {site_url} in the editor. Also you can use this conditional block {% coupon_enabled %}.',
				),
				array(
					'id'      => 'revifoup_review_reward_email_footer_text',
					'label'   => esc_html__( 'Footer Text', 'plugin-slug' ),
					'type'    => 'textarea',
					'default' => '',
				),
			),
		);

		new Settings(
			'plugin-slug',
			'stobokit-revifoup-review-requests', // Parent menu slug.
			'stobokit-revifoup-email-templates', // menu slug.
			esc_html__( 'Email Templates', 'plugin-slug' ),
			esc_html__( 'Email Templates', 'plugin-slug' ),
			'manage_woocommerce',
			'',
			0,
			$fields
		);
	}
);
