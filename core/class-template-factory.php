<?php
/**
 * Factory class for easy template management
 * 
 * @package StoboKit Core
 * @version 1.0.0
 */

namespace STOBOKIT;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'StoboKit\Template_Factory' ) ) {
	
	class Template_Factory {
			
		private static $instances = array();
		
		/**
		 * Get template override instance for a plugin
		 *
		 * @param string $plugin_slug Plugin slug
		 * @param string $plugin_file Main plugin file path
		 * @return Frontend_Template
		 */
		public static function get_instance($plugin_slug, $plugin_file) {
				if (!isset(self::$instances[$plugin_slug])) {
						$plugin_dir = plugin_dir_path($plugin_file);
						$templates_path = $plugin_dir . 'templates';
						
						self::$instances[$plugin_slug] = new Frontend_Template(
								$plugin_slug,
								$templates_path
						);
				}
				
				return self::$instances[$plugin_slug];
		}
		
		/**
		 * Create template override instance with custom path
		 *
		 * @param string $plugin_slug Plugin slug
		 * @param string $templates_path Full path to templates directory
		 * @return Frontend_Template
		 */
		public static function create($plugin_slug, $templates_path) {
				return new Frontend_Template($plugin_slug, $templates_path);
		}
	}
}