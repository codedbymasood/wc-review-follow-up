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
				array(
					'id'      => 'revifoup_request_type',
					'label'   => esc_html__( 'Request Type', 'plugin-slug' ),
					'type'    => 'select',
					'options' => array(
						'percent'    => esc_html__( 'By Order', 'plugin-slug' ),
						'fixed_cart' => esc_html__( 'By Product', 'plugin-slug' ),
					),
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_exclude_categories',
					'label'   => esc_html__( 'Exclude Product Categories', 'plugin-slug' ),
					'type'    => 'select',
					'options' => array(),
					'pro'     => true,
				),
			),
			esc_html__( 'Followup Email Settings', 'plugin-slug' ) => array(
				array(
					'id'      => 'revifoup_enable_followup',
					'label'   => esc_html__( 'Enable Followup', 'plugin-slug' ),
					'type'    => 'checkbox',
					'default' => '0',
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_followup_days',
					'label'   => esc_html__( 'Followup Days', 'plugin-slug' ),
					'type'    => 'number',
					'default' => 2,
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_enable_discount',
					'label'   => esc_html__( 'Enable Discount', 'plugin-slug' ),
					'type'    => 'checkbox',
					'default' => '0',
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_reward_period_days',
					'label'   => esc_html__( 'Reward Period Days', 'plugin-slug' ),
					'type'    => 'number',
					'default' => 7,
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_discount_type',
					'label'   => esc_html__( 'Discount Type', 'plugin-slug' ),
					'type'    => 'select',
					'options' => array(
						'percent'    => esc_html__( 'Percentage discount', 'plugin-slug' ),
						'fixed_cart' => esc_html__( 'Fixed cart discount', 'plugin-slug' ),
					),
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_discount_amount',
					'label'   => esc_html__( 'Discount Amount', 'plugin-slug' ),
					'type'    => 'number',
					'default' => 20,
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_coupon_expires_in',
					'label'   => esc_html__( 'Coupon Expires In', 'plugin-slug' ),
					'type'    => 'number',
					'default' => 30,
					'pro'     => true,
				),
				array(
					'id'      => 'revifoup_allowed_coupon_limit',
					'label'   => esc_html__( 'Allowed Coupon Limits Per Year', 'plugin-slug' ),
					'type'    => 'number',
					'default' => 3,
					'pro'     => true,
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
