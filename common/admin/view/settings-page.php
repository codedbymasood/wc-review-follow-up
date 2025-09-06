<?php
/**
 * Settings class.
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
			esc_html__( 'Mail Settings', 'plugin-slug' ) => array(
				array(
					'id'      => 'stobokit_email_from_name',
					'label'   => esc_html__( 'From Name', 'plugin-slug' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'name' ),
				),
				array(
					'id'      => 'stobokit_email_from_address',
					'label'   => esc_html__( 'From Address', 'plugin-slug' ),
					'type'    => 'text',
					'default' => '',
				),
			),
			esc_html__( 'General Settings', 'plugin-slug' ) => array(
				array(
					'id'      => 'revifoup_sent_email_days',
					'label'   => esc_html__( 'Email will be sent in (x) days', 'plugin-slug' ),
					'type'    => 'text',
					'default' => 3,
				),
				array(
					'id'      => 'revifoup_exceed_order_amount',
					'label'   => esc_html__( 'Send email when order total exceeds (x) (optional)', 'plugin-slug' ),
					'type'    => 'text',
					'default' => '',
				),
			),
		);

		new Settings(
			'plugin-slug',
			'stobokit-revifoup-review-requests',          // Parent menu slug.
			'stobokit-revifoup-review-requests-settings', // menu slug.
			esc_html__( 'Settings', 'plugin-slug' ),
			esc_html__( 'Settings', 'plugin-slug' ),
			'manage_options',
			'dashicon-email',
			0,
			$fields
		);
	}
);
