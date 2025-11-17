<?php
/**
 * Plugin Name: Review Follow Up Development
 * Description: Development version - switches between Lite and Pro versions for testing
 * Version: 1.0.0
 * Author: Store Boost Kit
 * Text Domain: review-follow-up-dev
 * Requires PHP: 7.4
 *
 * This is the DEVELOPMENT entry point. Use this during development to test both versions.
 *
 * @package   Review Follow Up
 * @author    Store Boost Kit
 * @copyright Copyright (c) Store Boost Kit
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'REVIFOUP_PLUGIN_FILE' ) ) {
	define( 'REVIFOUP_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'REVIFOUP_VERSION' ) ) {
	define( 'REVIFOUP_VERSION', '1.0.0' );
}

if ( ! defined( 'REVIFOUP_PATH' ) ) {
	define( 'REVIFOUP_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'REVIFOUP_URL' ) ) {
	define( 'REVIFOUP_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Development version switcher
 * Use ?revifoup_version=pro or ?revifoup_version=lite in URL to switch versions
 */
function revifoup_development_init() {
	// Check URL parameter or saved option.
	$force_version = isset( $_GET['revifoup_version'] ) ? $_GET['revifoup_version'] : get_option( 'revifoup_dev_version', 'lite' );

	// Save the preference.
	if ( isset( $_GET['revifoup_version'] ) ) {
		update_option( 'revifoup_dev_version', sanitize_text_field( wp_unslash( $_GET['revifoup_version'] ) ) );
	}

	// Load appropriate version.
	if ( 'pro' === $force_version && file_exists( __DIR__ . '/pro/class-revifoup.php' ) ) {
		add_filter(
			'stobokit_frontend_template_file',
			function ( $template_file = '' ) {
				return ( strpos( $template_file, '/pro/' ) !== false || strpos( $template_file, 'templates/pro' ) !== false )
					? $template_file
					: str_replace( 'templates/', 'templates/pro/', $template_file );
			}
		);

		require_once __DIR__ . '/pro/class-revifoup.php';

		// Show pro version notice.
		if ( is_admin() ) {
			add_action(
				'admin_notices',
				function () {
					echo '<div class="notice notice-success is-dismissible">';
					echo '<p><strong>🚀 REVIEW FOLLOW UP PRO VERSION ACTIVE</strong> - Development Mode | ';
					echo '<a href="' . esc_url( add_query_arg( 'revifoup_version', 'lite' ) ) . '">Switch to Lite</a>';
					echo '</p></div>';
				}
			);
		}
	} else {
		add_filter(
			'stobokit_frontend_template_file',
			function ( $template_file = '' ) {
				return ( strpos( $template_file, '/lite/' ) !== false || strpos( $template_file, 'templates/lite' ) !== false )
					? $template_file
					: str_replace( 'templates/', 'templates/lite/', $template_file );
			}
		);

		require_once __DIR__ . '/lite/class-revifoup.php';

		// Show lite version notice.
		if ( is_admin() ) {
			add_action(
				'admin_notices',
				function () {
					echo '<div class="notice notice-info is-dismissible">';
					echo '<p><strong>💡REVIEW FOLLOW UP LITE VERSION ACTIVE</strong> - Development Mode | ';
					echo '<a href="' . esc_url( add_query_arg( 'revifoup_version', 'pro' ) ) . '">Switch to Pro</a>';
					echo '</p></div>';
				}
			);
		}
	}

	/**
	 * Returns the main instance of REVIFOUP.
	 *
	 * @since  1.0
	 * @return REVIFOUP
	 */
	function revifoup() {
		return \REVIFOUP\REVIFOUP::instance();
	}

	// Global for backwards compatibility.
	$GLOBALS['revifoup'] = revifoup();
}

/**
 * Initialize the development plugin
 */
add_action( 'plugins_loaded', 'revifoup_development_init', 0 );

require_once dirname( REVIFOUP_PLUGIN_FILE ) . '/install.php';

register_activation_hook( __FILE__, array( 'REVIFOUP\Install', 'init' ) );

/**
 * Add development tools to admin bar
 *
 * @param object $wp_admin_bar Admin bar.
 * @return void
 */
function revifoup_dev_admin_bar( $wp_admin_bar ) {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	$current_version = get_option( 'revifoup_dev_version', 'lite' );

	$wp_admin_bar->add_node(
		array(
			'id'    => 'stobokit-dev',
			'title' => 'Store Boost Kit Dev',
			'href'  => '#',
		)
	);

	$wp_admin_bar->add_node(
		array(
			'parent' => 'stobokit-dev',
			'id'     => 'revifoup-dev-switch',
			'title'  => 'Review Follow Up: ' . strtoupper( $current_version ),
			'href'   => '#',
		)
	);

	$wp_admin_bar->add_node(
		array(
			'parent' => 'revifoup-dev-switch',
			'id'     => 'revifoup-switch-lite',
			'title'  => 'Switch to Lite',
			'href'   => add_query_arg( 'revifoup_version', 'lite' ),
		)
	);

	$wp_admin_bar->add_node(
		array(
			'parent' => 'revifoup-dev-switch',
			'id'     => 'revifoup-switch-pro',
			'title'  => 'Switch to Pro',
			'href'   => add_query_arg( 'revifoup_version', 'pro' ),
		)
	);
}
add_action( 'admin_bar_menu', 'revifoup_dev_admin_bar', 100 );

/**
 * Plugin activation hook
 */
register_activation_hook(
	__FILE__,
	function () {
		// Set default to lite version.
		update_option( 'revifoup_dev_version', 'lite' );

		// Show welcome message.
		set_transient( 'revifoup_dev_welcome', true, 60 );
	}
);

/**
 * Show welcome message after activation
 */
add_action(
	'admin_notices',
	function () {
		if ( get_transient( 'revifoup_dev_welcome' ) ) {
			delete_transient( 'revifoup_dev_welcome' );
			?>
			<div class="notice notice-success is-dismissible">
				<h3>🎉 Review Follow Up Development Plugin Activated!</h3>
				<p>
					<strong>Development Mode:</strong> You can switch between Lite and Pro versions for testing.<br>
					<strong>Current Version:</strong> <?php echo esc_html( strtoupper( get_option( 'revifoup_dev_version', 'lite' ) ) ); ?><br>
					<strong>Switch Versions:</strong> Use the admin bar menu or URL parameters (?revifoup_version=pro)
				</p>
				<p>
					<a href="<?php echo esc_url( add_query_arg( 'revifoup_version', 'lite' ) ); ?>" class="button">Test Lite Version</a>
					<a href="<?php echo esc_url( add_query_arg( 'revifoup_version', 'pro' ) ); ?>" class="button button-primary">Test Pro Version</a>
				</p>
			</div>
			<?php
		}
	}
);

/**
 * Add settings link to plugins page
 */
add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	function ( $links = '' ) {
		$current_version = get_option( 'revifoup_dev_version', 'lite' );

		$dev_links = array(
			'<a href="' . esc_url( add_query_arg( 'revifoup_version', 'lite' ) ) . '">Lite</a>',
			'<a href="' . esc_url( add_query_arg( 'revifoup_version', 'pro' ) ) . '">Pro</a>',
			'<strong>Current: ' . esc_html( strtoupper( $current_version ) ) . '</strong>',
		);

		return array_merge( $dev_links, $links );
	}
);
