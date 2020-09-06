<?php
/**
 * Class Pro manages the premium side of Hummingbird
 *
 * @since 1.5.0
 * @package Hummingbird\Core\Pro
 */

namespace Hummingbird\Core\Pro;

use Hummingbird\Core\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Pro
 */
class Pro {

	/**
	 * Class instance
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Saves the modules object instances
	 *
	 * @var array
	 * @since 1.5.0
	 */
	public $modules = array();

	/**
	 * Admin instance
	 *
	 * @var null|Admin\Pro_Admin
	 */
	public $admin;

	/**
	 * Return the plugin instance
	 *
	 * @return Pro
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the class
	 *
	 * @since 1.5.0
	 */
	public function init() {
		// Load dashboard notice.
		global $wpmudev_notices;
		$wpmudev_notices[] = array(
			'id'      => 1081721,
			'name'    => 'Hummingbird',
			'screens' => array(
				'toplevel_page_wphb',
				'hummingbird_page_wphb-performance',
				'hummingbird_page_wphb-caching',
				'hummingbird_page_wphb-gzip',
				'hummingbird_page_wphb-minification',
				'hummingbird_page_wphb-advanced',
				'hummingbird_page_wphb-uptime',
				'hummingbird-pro_page_wphb-performance',
				'hummingbird-pro_page_wphb-caching',
				'hummingbird-pro_page_wphb-gzip',
				'hummingbird-pro_page_wphb-minification',
				'hummingbird-pro_page_wphb-advanced',
				'hummingbird-pro_page_wphb-uptime',
			),
		);

		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {
			include_once ABSPATH . 'wp-includes/plugin.php';
		}

		/* @noinspection PhpIncludeInspection */
		include_once WPHB_DIR_PATH . 'core/pro/externals/dash-notice/wpmudev-dash-notification.php';

		if ( is_admin() ) {
			$this->admin = new Admin\Pro_Admin();
			$this->admin->init();
		}

		$this->load_ajax();
		$this->load_modules();
	}

	/**
	 * Load AJAX functionality
	 *
	 * @since 1.5.0
	 */
	private function load_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new Pro_AJAX();
		}
	}

	/**
	 * Load WP Hummingbird Pro modules
	 *
	 * @since 1.5.0
	 */
	private function load_modules() {
		$modules = apply_filters(
			'wp_hummingbird_modules',
			array(
				'reporting-cron' => __( 'Cron', 'wphb' ),
				'cleanup-cron'   => __( 'Database Cleanup', 'wphb' ),
				'uptime-reports' => __( 'Reports', 'wphb' ),
			)
		);

		array_walk( $modules, array( $this, 'load_module' ), true );
	}

	/**
	 * Load a single module
	 *
	 * @param string $name    Module name.
	 * @param string $module  Module slug.
	 *
	 * @since 1.5.0
	 */
	public function load_module( $name, $module ) {
		$parts = explode( '-', $module );
		$parts = array_map( 'ucfirst', $parts );
		$class = implode( '_', $parts );

		$class_name = 'Hummingbird\\Core\\Pro\\Modules\\' . $class;

		/**
		 * Module.
		 *
		 * @var Module $module_obj
		 */
		$module_obj = new $class_name( $module, $name );

		if ( $module_obj instanceof $class_name ) {
			if ( $module_obj->is_active() ) {
				$module_obj->run();
			}

			$this->modules[ $module ] = $module_obj;
		}
	}

}