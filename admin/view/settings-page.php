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
					'label' => esc_html__( 'From Name', 'review-requester-for-woocommerce' ),
					'type'  => 'text',
				),
				array(
					'id'    => 'rrw_from_address',
					'label' => esc_html__( 'From Address', 'review-requester-for-woocommerce' ),
					'type'  => 'text',
				),
				array(
					'id'      => 'rrw_email_subject',
					'label'   => esc_html__( 'From Address', 'review-requester-for-woocommerce' ),
					'type'    => 'text',
					'default' => esc_html__( 'Back in Stock!', 'review-requester-for-woocommerce' ),
				)
			)
		);

		new Settings(
			'email-logs',          // Parent menu slug.
			'email-logs-settings', // menu slug.
			esc_html__( 'Settings', 'review-requester-for-woocommerce' ),
			esc_html__( 'Settings', 'review-requester-for-woocommerce' ),
			'manage_options',
			$fields
		);
	}
);
