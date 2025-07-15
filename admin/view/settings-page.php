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
		$fields = array(
			esc_html__( 'Mail Settings', 'review-requester-for-woocommerce' ) => array(
				array(
					'id'    => 'rrw_from_name',
					'label' => esc_html__( 'From Name', 'product-availability-notifier-for-woocommerce' ),
					'type'  => 'text',
					'default' => '',
				),
				array(
					'id'    => 'rrw_from_address',
					'label' => esc_html__( 'From Address', 'product-availability-notifier-for-woocommerce' ),
					'type'  => 'text',
					'default' => '',
				),
				array(
					'id'    => 'rrw_sent_email_days',
					'label' => esc_html__( 'When to sent an email (x)days', 'review-requester-for-woocommerce' ),
					'type'  => 'text',
					'default' => '',
				),
			),
		);

		new Settings(
			'review-requests',          // Parent menu slug.
			'review-requests-settings', // menu slug.
			esc_html__( 'Settings', 'review-requester-for-woocommerce' ),
			esc_html__( 'Settings', 'review-requester-for-woocommerce' ),
			'manage_options',
			$fields
		);
	}
);
