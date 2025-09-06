<?php
/**
 * Admin class.
 *
 * @package plugin-slug\admin\
 * @author Store Boost Kit <storeboostkit@gmail.com>
 * @version 1.0
 */

namespace STOBOKIT;

defined( 'ABSPATH' ) || exit;

$args = array(
	'file'      => REVIFOUP_PLUGIN_FILE,
	'slug'      => 'plugin-slug',
	'version'   => REVIFOUP_VERSION,
	'license'   => get_option( 'plugin-slug_license_key', '' ),
	'item_name' => 'Plugin Name',
	'item_id'   => 105,
);

new Update_Handler( $args );
