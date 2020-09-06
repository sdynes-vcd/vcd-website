<?php
/**
 * Manages activation/deactivation and upgrades of Hummingbird
 *
 * @author: WPMUDEV, Ignacio Cruz (igmoweb)
 * @package Hummingbird\Core
 */

namespace Hummingbird\Core;

use Hummingbird\WP_Hummingbird;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Installer
 */
class Installer {

	/**
	 * Plugin activation
	 */
	public static function activate() {
		if ( ! defined( 'WPHB_ACTIVATING' ) ) {
			define( 'WPHB_ACTIVATING', true );
		}

		update_site_option( 'wphb_version', WPHB_VERSION );

		// Add uptime notice.
		update_site_option( 'wphb-notice-uptime-info-show', 'yes' );
	}

	/**
	 * Plugin activation in a blog (if the site is a multisite)
	 */
	public static function activate_blog() {
		update_option( 'wphb_version', WPHB_VERSION );

		do_action( 'wphb_activate' );
	}

	/**
	 * Plugin deactivation
	 */
	public static function deactivate() {
		// Avoid to execute this over an over in same thread execution.
		if ( defined( 'WPHB_SWITCHING_VERSION' ) ) {
			return;
		}

		$settings = Settings::get_settings( 'settings' );
		WP_Hummingbird::flush_cache( $settings['remove_data'], $settings['remove_settings'] );
		do_action( 'wphb_deactivate' );
	}

	/**
	 * Plugin upgrades
	 */
	public static function maybe_upgrade() {
		// Avoid to execute this over an over in same thread execution.
		if ( defined( 'WPHB_ACTIVATING' ) ) {
			return;
		}

		if ( defined( 'WPHB_UPGRADING' ) && WPHB_UPGRADING ) {
			return;
		}

		self::upgrade();
	}

	/**
	 * Upgrade
	 */
	public static function upgrade() {
		$version = get_site_option( 'wphb_version' );

		if ( false === $version ) {
			self::activate();
		}

		if ( is_multisite() ) {
			$blog_version = get_option( 'wphb_version' );
			if ( false === $blog_version ) {
				self::activate_blog();
			}
		}

		if ( false !== $version && WPHB_VERSION !== $version ) {

			if ( ! defined( 'WPHB_UPGRADING' ) ) {
				define( 'WPHB_UPGRADING', true );
			}

			if ( version_compare( $version, '1.9.0', '<' ) ) {
				self::upgrade_1_9_0();
			}

			if ( version_compare( $version, '1.9.2', '<' ) ) {
				self::upgrade_1_9_2();
			}

			if ( version_compare( $version, '1.9.3', '<' ) ) {
				self::upgrade_1_9_3();
			}

			if ( version_compare( $version, '1.9.4', '<' ) ) {
				self::upgrade_1_9_4();
			}

			if ( version_compare( $version, '2.0.0', '<' ) ) {
				self::upgrade_2_0();
			}

			update_site_option( 'wphb_version', WPHB_VERSION );
		}
	}

	/**
	 * Upgrades a single blog in a multisite
	 */
	public static function maybe_upgrade_blog() {
		// 1.3.9 is the first version when blog upgrades are executed
		$version = get_option( 'wphb_version', '1.3.9' );

		if ( WPHB_VERSION === $version ) {
			return;
		}

		if ( version_compare( $version, '1.9.2', '<' ) ) {
			self::upgrade_1_9_2();
		}

		update_option( 'wphb_version', WPHB_VERSION );
	}

	/**
	 * Upgrade to 1.9
	 *
	 * Remove wphb-server-type option, because we are not using it anymore.
	 *
	 * @deprecated 2.1.0
	 */
	private static function upgrade_1_9_0() {
		delete_site_option( 'wphb-server-type' );
		delete_metadata( 'user', '', 'wphb-server-type', '', true );
	}

	/**
	 * Upgrade to 1.9.2
	 *
	 * Change the default behavior of AO - do not compress assets by default.
	 *
	 * @deprecated 2.1.0
	 */
	private static function upgrade_1_9_2() {
		/**
		 * Do not compress assets by default.
		 */
		$settings = Settings::get_settings( 'minify' );

		if ( ! isset( $settings['dont_minify'] ) ) {
			return;
		}

		$dont_minify = $settings['dont_minify'];
		unset( $settings['dont_minify'] );

		$collection        = Utils::get_module( 'minify' )->get_resources_collection();
		$options['minify'] = array(
			'styles'  => array(),
			'scripts' => array(),
		);

		foreach ( $dont_minify as $type => $handles ) {
			$settings['minify'][ $type ] = array();
			$type_collection             = wp_list_pluck( $collection[ $type ], 'handle' );
			foreach ( $type_collection as $type_handle ) {
				if ( ! in_array( $type_handle, $handles, true ) ) {
					$options['minify'][ $type ][] = $type_handle;
				}
			}
		}

		Settings::update_settings( $settings, 'minify' );

		/**
		 * Log class has changed. Clear out old log files.
		 */
		Logger::cleanup();
	}

	/**
	 * Upgrade to 1.9.3
	 *
	 * Add option to page caching to hide page cache comments.
	 *
	 * @deprecated 2.1.0
	 */
	private static function upgrade_1_9_3() {
		// Add the new setting cache_identifier to page caching.
		$config_file = WP_CONTENT_DIR . '/wphb-cache/wphb-cache.php';
		if ( file_exists( $config_file ) ) {
			$settings = json_decode( file_get_contents( $config_file ), true );
			if ( ! isset( $settings['settings']['cache_identifier'] ) ) {
				$settings['settings']['cache_identifier'] = 1;
				@file_put_contents( $config_file, json_encode( $settings ) );
			}
		}
	}

	/**
	 * Upgrade to 1.9.4
	 *
	 * Convert the performance reports db data to a new format.
	 *
	 * @deprecated 2.1.0
	 */
	private static function upgrade_1_9_4() {
		wp_clear_scheduled_hook( 'wphb_performance_scan' );

		// Remove wphb_cron_limit option. Now it's a transient.
		delete_site_option( 'wphb_cron_limit' );

		if ( ! Utils::is_member() ) {
			return;
		}

		$options = Settings::get_settings( 'performance' );

		$new_options = $options;

		if ( ! isset( $options['reports'] ) || is_array( $options['reports'] ) ) {
			return;
		}

		unset( $new_options['reports'] );
		$new_options['reports']['enabled'] = $options['reports'];

		$settings = array( 'frequency', 'day', 'time', 'recipients' );
		foreach ( $settings as $setting ) {
			if ( ! isset( $options[ $setting ] ) ) {
				continue;
			}

			/**
			 * Previous version of performance reports had week days (Monday-Sunday) for month schedule,
			 * now it's replaced with 1-28 days.
			 */
			if ( 'frequency' === $setting && 30 === intval( $options[ $setting ] ) ) {
				$new_options['reports'][ $setting ] = wp_rand( 1, 28 );
			}

			unset( $new_options[ $setting ] );
			$new_options['reports'][ $setting ] = $options[ $setting ];
		}

		// Move the last_sent option.
		if ( isset( $options['last_sent'] ) ) {
			unset( $new_options['last_sent'] );
			$new_options['reports']['last_sent'] = $options['last_sent'];
		}

		Settings::update_settings( $new_options, 'performance' );

		// Reschedule reports.
		do_action( 'wphb_activate' );
	}

	/**
	 * Upgrade to 2.0.0.
	 *
	 * @since 2.0.0
	 */
	private static function upgrade_2_0() {
		// Remove old report data.
		Utils::get_module( 'performance' )->clear_cache();

		// Add additional report options.
		$defaults = Settings::get_default_settings();
		$options  = Settings::get_setting( 'reports', 'performance' );

		$new_options = wp_parse_args( $options, $defaults['performance']['reports'] );
		Settings::update_setting( 'reports', $new_options, 'performance' );

		delete_site_option( 'wphb-pro' );
	}

}