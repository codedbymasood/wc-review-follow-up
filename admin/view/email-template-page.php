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
			esc_html__( 'Review Email Template', 'review-requester-for-woocommerce' ) => array(
				array(
					'id'             => 'rrw_review_email_template',
					'type'           => 'richtext_editor',
					'options'        => array( 'html', 'css' ),
					'default_editor' => 'css',
					'default'        => array(
						'html' => '',
						'css'  => '',
					),
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
