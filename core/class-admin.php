<?php
/**
 * Admin class.
 *
 * @package store-boost-kit\admin\
 * @author Store Boost Kit <hello@storeboostkit.com>
 * @version 1.0
 */

namespace STOBOKIT;

defined( 'ABSPATH' ) || exit;

/**
 * Settings class.
 */
class Admin {

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_ajax_stobokit_save_settings', array( $this, 'save_settings' ) );

	}

	/**
	 * Save settings.
	 *
	 * @return void
	 */
	public function save_settings() {
		if ( ! isset( $_POST['nonce'] ) && ! empty( isset( $_POST['nonce'] ) ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'stobokit_save_settings' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Sorry, not verified.', 'store-boost-kit' ),
				)
			);
		}

		$inputs = isset( $_POST['inputs'] ) && ! empty( $_POST['inputs'] ) ? $_POST['inputs'] : array();

		if ( ! empty( $inputs ) ) {
			foreach ( $inputs as $index => $field ) {
				if ( '_wp_http_referer' !== $index && 'stobokit_save_settings_nonce' !== $index ) {
					update_option( $field['name'], $field['value'] );
				}
			}
		}

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Settings saved!', 'store-boost-kit' ),
			)
		);

		exit();
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param string $hook Menu hook.
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
		wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		wp_enqueue_style( 'stobokit-admin', STOBOKIT_URL . 'assets/css/admin.css', array(), '1.0' );
		wp_enqueue_script( 'stobokit-admin', STOBOKIT_URL . 'assets/js/admin.js', array(), '1.0', true );
	}

	/**
	 * Add menu page hook callback.
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Store Boost Kit', 'store-boost-kit' ),
			esc_html__( 'Store Boost Kit', 'store-boost-kit' ),
			'manage_options',
			'store-boost-kit',
			array( $this, 'dashboard' ),
			'email',
			6
		);

		add_submenu_page(
			'store-boost-kit',
			esc_html__( 'Status', 'store-boost-kit' ),
			esc_html__( 'Status', 'store-boost-kit' ),
			'manage_options',
			'stobokit-status',
			array( $this, 'status' )
		);

		add_submenu_page(
			'store-boost-kit',
			esc_html__( 'License', 'store-boost-kit' ),
			esc_html__( 'License', 'store-boost-kit' ),
			'manage_options',
			'stobokit-license',
			array( $this, 'license' )
		);

	}

	/**
	 * Dashboard page.
	 *
	 * @return void
	 */
	public function dashboard() {

	}

	/**
	 * Status page.
	 *
	 * @return void
	 */
	public function status() {
		include_once STOBOKIT_PATH . '/views/status.php';
	}

	/**
	 * License page.
	 *
	 * @return void
	 */
	public function license() {
		include_once STOBOKIT_PATH . '/views/license.php';
	}
}

new Admin();

