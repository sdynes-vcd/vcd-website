<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Module\Hardener\Component;

use Hammer\Helper\WP_Helper;
use Hammer\Helper\Log_Helper;
use WP_Defender\Behavior\Utils;
use WP_Defender\Component\Error_Code;
use WP_Defender\Module\Hardener\IRule_Service;
use WP_Defender\Module\Hardener\Rule_Service;

class Prevent_PHP_Service extends Rule_Service implements IRule_Service {

	/**
	 * @return bool
	 */
	public function check() {
		$cache = WP_Helper::getArrayCache()->get( 'Prevent_PHP_Service', null );
		if ( $cache === null ) {
			//init upload dir and a php file
			Utils::instance()->getDefUploadDir();
			$url = WP_Helper::getUploadUrl();
			$url = $url . '/wp-defender/index.php';
			$ssl_verify = apply_filters( 'defender_ssl_verify', true ); //most hosts dont really have valid ssl or ssl still pending
			$status = wp_remote_head( $url, array(
				'user-agent' => $_SERVER['HTTP_USER_AGENT'],
				'timeout'    => 3,
				'sslverify'  => $ssl_verify
			) );
			if ( is_wp_error( $status ) ) {
				//General error
				return false;
			} else {
				if ( 200 == wp_remote_retrieve_response_code( $status ) ) {
					WP_Helper::getArrayCache()->set( 'Prevent_PHP_Service', false );

					return false;
				}
				WP_Helper::getArrayCache()->set( 'Prevent_PHP_Service', true );

				return true;
			}
		} else {
			return $cache;
		}
	}

	/**
	 * Return the nginx rules use to put in site-enabled config files
	 * @return string
	 */
	public function getNginxRules() {
		if ( DIRECTORY_SEPARATOR == '\\' ) {
			//Windows
			$wp_includes = str_replace( ABSPATH, '', WPINC );
			$wp_content  = str_replace( ABSPATH, '', WP_CONTENT_DIR );
		} else {
			$wp_includes = str_replace( $_SERVER['DOCUMENT_ROOT'], '', ABSPATH . WPINC );
			$wp_content  = str_replace( $_SERVER['DOCUMENT_ROOT'], '', WP_CONTENT_DIR );
		}

		$rules = "# Stop php access except to needed files in wp-includes
location ~* ^$wp_includes/.*(?<!(js/tinymce/wp-tinymce))\.php$ {
  internal; #internal allows ms-files.php rewrite in multisite to work
}

# Specifically locks down upload directories in case full wp-content rule below is skipped
location ~* /(?:uploads|files)/.*\.php$ {
  deny all;
}

# Deny direct access to .php files in the /wp-content/ directory (including sub-folders).
#  Note this can break some poorly coded plugins/themes, replace the plugin or remove this block if it causes trouble
location ~* ^$wp_content/.*\.php$ {
  deny all;
}
";

		return $rules;
	}

	/**
	 * @return bool|\WP_Error
	 */
	public function process() {
		return new \WP_Error( Error_Code::INVALID, __( "Process is not possible on your current server", wp_defender()->domain ) );
	}

	/**
	 * @return bool|\WP_Error
	 */
	public function revert() {
		return new \WP_Error( Error_Code::INVALID, __( "Revert is not possible on your current server", wp_defender()->domain ) );
	}
}