<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Behavior;

use Hammer\Base\Behavior;
use Hammer\Helper\HTTP_Helper;
use Hammer\Helper\WP_Helper;
use WP_Defender\Module\Scan\Component\Scan_Api;
use WP_Defender\Module\Scan\Model\Settings;

class Activator extends Behavior {
	public function activateModule() {
		if ( ! Utils::instance()->checkPermission() ) {
			return;
		}

		if ( ! wp_verify_nonce( HTTP_Helper::retrieveGet( '_wpnonce' ), 'activateModule' ) ) {
			return;
		}

		$activator = $_POST;
		$activated = array();
		if ( count( $activator ) ) {
			foreach ( $activator as $item => $status ) {
				if ( $status != true ) {
					continue;
				}
				switch ( $item ) {
					case 'activate_scan':
						$settings               = Settings::instance();
						$settings->notification = true;
						$settings->time         = '4:00';
						$settings->day          = 'monday';
						$settings->frequency    = 7;
						$cronTime               = Utils::instance()->reportCronTimestamp( $settings->time, 'scanReportCron' );
						wp_schedule_event( $cronTime, 'daily', 'scanReportCron' );
						$settings->save();
						//start a new scan
						Scan_Api::createScan();
						$activated[] = $item;
						break;
					case 'activate_audit':
						$settings               = \WP_Defender\Module\Audit\Model\Settings::instance();
						$settings->enabled      = true;
						$settings->notification = true;
						$settings->time         = '4:00';
						$settings->day          = 'monday';
						$settings->frequency    = 7;
						$cronTime               = Utils::instance()->reportCronTimestamp( $settings->time, 'auditReportCron' );
						wp_schedule_event( $cronTime, 'daily', 'auditReportCron' );
						$activated[] = $item;
						$settings->save();
						break;
					case 'activate_blacklist':
						$this->owner->toggleStatus( - 1, false );
						$activated[] = $item;
						break;
					case 'activate_lockout':
						$settings                   = \WP_Defender\Module\IP_Lockout\Model\Settings::instance();
						$settings->detect_404       = true;
						$settings->login_protection = true;
						$settings->report           = true;
						$settings->report_frequency = 7;
						$settings->report_day       = 'monday';
						$settings->report_time      = '4:00';
						$cronTime                   = Utils::instance()->reportCronTimestamp( $settings->report_time, 'lockoutReportCron' );
						wp_schedule_event( $cronTime, 'daily', 'lockoutReportCron' );
						$activated[] = $item;
						$settings->save();
						break;
					default:
						//param not from the button on frontend, log it
						error_log( sprintf( 'Unexpected value %s from IP %s', $item, Utils::instance()->getUserIp() ) );
						break;
				}
			}
		}

		set_site_transient( 'wp_defender_is_activated', 1 );
		wp_send_json_success( array(
			'activated' => $activated,
			//'message'   => __( "" )
		) );
	}

	/**
	 * Check if we should show activator screen
	 * @return bool
	 */
	public function isShowActivator() {
		$cache = WP_Helper::getCache();

		if ( $cache->get( 'isActivated', false ) == 1 ) {
			return 0;
		}

		if ( get_site_transient( 'wp_defender_is_activated' ) == 1 ) {
			return 0;
		}

		if ( $cache->get( 'wdf_isActivated', false ) == 1 ) {
			//this mean user just upgraded from the free
			return 1;
		}

		if ( get_site_transient( 'wp_defender_is_free_activated' ) == 1 ) {
			return 1;
		}
		$keys = [
			'wp_defender',
			'wd_scan_settings',
			'wd_hardener_settings',
			'wd_audit_settings',
			'wd_2auth_settings',
			'wd_masking_login_settings'
		];
		foreach ( $keys as $key ) {
			$option = get_site_option( $key );
			if ( is_array( $option ) ) {
				return 0;
			}
		}

		return 1;
	}
}