<?php
/**
 * Preload caache files.
 *
 * @since 2.1.0
 * @package Hummingbird\Core\Modules\Caching
 */

namespace Hummingbird\Core\Modules\Caching;

use Hummingbird\Core\Modules\Minify\Scanner;
use Hummingbird\Core\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Preload
 */
class Preload extends Background_Process {

	/**
	 * Database row prefix.
	 *
	 * @since 2.1.0
	 * @var string $prefix
	 */
	protected $prefix = 'wphb';

	/**
	 * Unique process ID.
	 *
	 * @since 2.1.0
	 * @var string $action
	 */
	protected $action = 'cache_preload';

	/**
	 * Task that does the preloading of each item (url).
	 *
	 * @param mixed $item  Queue item to iterate over.
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		$args = array(
			'timeout'    => 0.01,
			'blocking'   => false,
			'user-agent' => 'Hummingbird ' . WPHB_VERSION . '/Cache Preloader',
			'sslverify'  => false,
		);
		wp_remote_get( esc_url_raw( $item ), $args );

		$interval = Settings::get_setting( 'preload_interval', 'page_cache' );
		if ( ! isset( $interval ) ) {
			$interval = 500;
		}

		sleep( $interval / 1000 );

		return false;
	}

	/**
	 * Fires on complete.
	 *
	 * @since 2.1.0
	 */
	protected function complete() {
		parent::complete();

		delete_transient( 'wphb-preloading' );
	}

	/**
	 * Populate the queue for preloading with the provided URL, or preload all pages.
	 *
	 * @since 2.1.0
	 *
	 * @param string $url  URL of the page to preload. Leave blank to preload all.
	 */
	public function preload( $url = '' ) {
		set_transient( 'wphb-preloading', true, 3600 );

		$scanner = new Scanner();

		// Preload a single URL.
		if ( $url ) {
			$this->push_to_queue( $url );
		} else {
			// Batch preload all URLs.
			$urls = $scanner->get_scan_urls( '-1' );
			$urls = array_unique( $urls );

			foreach ( $urls as $url ) {
				$this->push_to_queue( $url );
			}
		}

		$this->save()->dispatch();
	}

	/**
	 * Callback function after clearing cache for a page/post.
	 *
	 * @since 2.1.0
	 *
	 * @param string $path  Path to page.
	 */
	public function preload_page( $path ) {
		$preload = Settings::get_setting( 'preload', 'page_cache' );
		if ( ! $preload ) {
			$this->cancel();
			return;
		}

		$url = get_option( 'home' ) . $path;
		$this->preload( $url );
	}

	/**
	 * Cancel cache preloading.
	 *
	 * @since 2.1.0
	 */
	public function cancel() {
		delete_transient( 'wphb-preloading' );
		$this->cancel_process();
	}

}