<?php
/**
 * Opcache integration class.
 *
 * @since 2.1.0
 * @package Hummingbird\Core\Integration
 */

namespace Hummingbird\Core\Integration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Opcache
 */
class Opcache {

	/**
	 * Opcache constructor.
	 *
	 * @since 2.1.0
	 */
	public function __construct() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		add_action( 'wphb_clear_cache_url', array( $this, 'purge_cache' ) );
	}

	/**
	 * Check if opcache is enabled on the server.
	 *
	 * @since 2.1.0
	 * @return bool
	 */
	public function is_enabled() {
		if ( ! function_exists( 'opcache_get_status' ) ) {
			return false;
		}

		$opcache = opcache_get_status();
		if ( isset( $opcache['opcache_enabled'] ) ) {
			return $opcache['opcache_enabled'];
		}

		return false;
	}

	/**
	 * Purge cache.
	 *
	 * @since 2.1.0
	 *
	 * @param string $path  Path to purge for.
	 */
	public function purge_cache( $path = '' ) {
		// Only purge when full cache is cleared.
		if ( ! empty( $path ) ) {
			return;
		}

		if ( ! function_exists( 'opcache_reset' ) ) {
			return;
		}

		$status = opcache_reset();
	}

}