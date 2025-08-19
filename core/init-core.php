<?php
/**
 * Core classes.
 *
 * @package review-follow-up-for-woocommerce\core\
 * @author Store Boost Kit <hello@storeboostkit.com>
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( defined( 'STOBOKIT_PATH' ) ) {
	return;
}

define( 'STOBOKIT_VERSION', '1.0' );
define( 'STOBOKIT_PATH', dirname( __FILE__ ) );
define( 'STOBOKIT_URL', plugin_dir_url( __FILE__ ) );

require_once STOBOKIT_PATH . '/class-utils.php';
require_once STOBOKIT_PATH . '/class-license.php';
require_once STOBOKIT_PATH . '/class-admin.php';
require_once STOBOKIT_PATH . '/class-settings.php';
require_once STOBOKIT_PATH . '/class-metabox.php';
