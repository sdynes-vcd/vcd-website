<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Module\Advanced_Tools\Controller;

use Hammer\Helper\HTTP_Helper;
use Hammer\Helper\WP_Helper;
use WP_Defender\Behavior\Utils;
use WP_Defender\Controller;
use WP_Defender\Module\Advanced_Tools\Component\Auth_API;
use WP_Defender\Module\Advanced_Tools\Component\Auth_Listener;
use WP_Defender\Module\Advanced_Tools\Component\Mask_Api;
use WP_Defender\Module\Advanced_Tools\Component\Mask_Login_Listener;
use WP_Defender\Module\Advanced_Tools\Model\Auth_Settings;
use WP_Defender\Module\Advanced_Tools\Model\Mask_Settings;

class Main extends Controller {
	protected $slug = 'wdf-advanced-tools';

	/**
	 * @return array
	 */
	public function behaviors() {
		$behaviors = array(
			'utils'     => '\WP_Defender\Behavior\Utils',
			'endpoints' => '\WP_Defender\Behavior\Endpoint',
			'wpmudev'   => '\WP_Defender\Behavior\WPMUDEV'
		);

		return $behaviors;
	}

	public function __construct() {
		if ( $this->isNetworkActivate( wp_defender()->plugin_slug ) ) {
			$this->addAction( 'network_admin_menu', 'adminMenu' );
		} else {
			$this->addAction( 'admin_menu', 'adminMenu' );
		}
		$this->addAction( 'defender_enqueue_assets', 'scripts', 12 );

		new Auth_Listener();
		new Mask_Login_Listener();
	}

	/**
	 * Add submit admin page
	 */
	public function adminMenu() {
		$cap = is_multisite() ? 'manage_network_options' : 'manage_options';
		add_submenu_page( 'wp-defender', esc_html__( "Advanced Tools", wp_defender()->domain ), esc_html__( "Advanced Tools", wp_defender()->domain ), $cap, $this->slug, array(
			&$this,
			'actionIndex'
		) );
	}

	/**
	 * a simple router
	 */
	public function actionIndex() {
		$this->render( 'main' );
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		if ( $this->isInPage() ) {
			wp_enqueue_style( 'wpmudev-sui' );
			wp_enqueue_media();
			wp_enqueue_style( 'defender' );
			wp_register_script( 'defender-adtools', wp_defender()->getPluginUrl() . 'assets/app/advanced-tools.js', array(
				'vue',
				'defender',
				'wp-i18n'
			), wp_defender()->version, true );
			wp_localize_script( 'defender-adtools', 'advanced_tools', $this->_scriptsData() );
			Utils::instance()->createTranslationJson( 'defender-adtools' );
			wp_set_script_translations( 'defender-adtools', 'wpdef', wp_defender()->getPluginPath() . 'languages' );
			wp_enqueue_script( 'defender-adtools' );
			wp_enqueue_script( 'wpmudev-sui' );
		}
	}

	/**
	 * @return array
	 */
	public function _scriptsData() {
		if ( ! $this->checkPermission() ) {
			return [];
		}
		$settings    = Auth_Settings::instance();
		$allRoles    = get_editable_roles();
		$ml_settings = Mask_Settings::instance();

		return [
			'misc'      => [
				'all_roles'     => $allRoles,
				'compatibility' => isset( wp_defender()->global['compatibility'] ) && is_array( wp_defender()->global['compatibility'] ) ? wp_defender()->global['compatibility'] : false,
				'new_login_url' => Mask_Api::getNewLoginUrl()
			],
			'model'     => [
				'two_factor' => $settings->export(),
				'mask_login' => $ml_settings->export( [ 'otp' ] )
			],
			'nonces'    => [
				'updateSettings' => wp_create_nonce( 'updateSettings' ),
				'sendTestEmail'  => wp_create_nonce( 'sendTestEmail' )
			],
			'endpoints' => $this->getAllAvailableEndpoints( \WP_Defender\Module\Advanced_Tools::getClassName() ),
		];
	}
}