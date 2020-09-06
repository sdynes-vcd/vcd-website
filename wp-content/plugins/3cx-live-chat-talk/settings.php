<?php

require_once WP3CXC2C_PLUGIN_DIR . '/includes/functions.php';
require_once WP3CXC2C_PLUGIN_DIR . '/includes/l10n.php';
require_once WP3CXC2C_PLUGIN_DIR . '/includes/formatting.php';
require_once WP3CXC2C_PLUGIN_DIR . '/includes/capabilities.php';
require_once WP3CXC2C_PLUGIN_DIR . '/includes/clicktotalk-form.php';
require_once WP3CXC2C_PLUGIN_DIR . '/includes/clicktotalk-form-functions.php';
require_once WP3CXC2C_PLUGIN_DIR . '/includes/config-validator.php';
require_once WP3CXC2C_PLUGIN_DIR . '/includes/clicktotalk.php';

if ( is_admin() ) {
	require_once WP3CXC2C_PLUGIN_DIR . '/admin/admin.php';
} else {
	require_once WP3CXC2C_PLUGIN_DIR . '/includes/controller.php';
}

class WP3CXC2C {

	public static function load_modules() {
		self::load_module( 'listo' );
	}

	protected static function load_module( $mod ) {
		$dir = WP3CXC2C_PLUGIN_MODULES_DIR;

		if ( empty( $dir ) || ! is_dir( $dir ) ) {
			return false;
		}

		$file = path_join( $dir, $mod . '.php' );

		if ( file_exists( $file ) ) {
			include_once $file;
		}
	}

	public static function get_option( $name, $default = false ) {
		$option = get_option( 'wp3cxc2c' );

		if ( false === $option ) {
			return $default;
		}

		if ( isset( $option[$name] ) ) {
			return $option[$name];
		} else {
			return $default;
		}
	}

	public static function update_option( $name, $value ) {
		$option = get_option( 'wp3cxc2c' );
		$option = ( false === $option ) ? array() : (array) $option;
		$option = array_merge( $option, array( $name => $value ) );
		update_option( 'wp3cxc2c', $option );
	}
}

add_action( 'plugins_loaded', 'wp3cxc2c' );

function wp3cxc2c() {
	wp3cxc2c_load_textdomain();
	WP3CXC2C::load_modules();

	/* Shortcodes */
	add_shortcode( '3cx-clicktotalk', 'wp3cxc2c_clicktotalk_form_tag_func' );
	add_shortcode( 'clicktotalk-form', 'wp3cxc2c_clicktotalk_form_tag_func' );
}

add_action( 'init', 'wp3cxc2c_init' );


// UPLOAD ENGINE
function load_wp_media_files() {
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );

function wp3cxc2c_init() {
	wp3cxc2c_register_post_types();
	do_action( 'wp3cxc2c_init' );
	do_action('load_media_uploader');
}

add_action( 'admin_init', 'wp3cxc2c_upgrade' );

function wp3cxc2c_upgrade() {
	$old_ver = WP3CXC2C::get_option( 'version', '0' );
	$new_ver = WP3CXC2C_VERSION;

	if ( $old_ver == $new_ver ) {
		return;
	}

	do_action( 'wp3cxc2c_upgrade', $new_ver, $old_ver );

	WP3CXC2C::update_option( 'version', $new_ver );
}

/* Install and default settings */

add_action( 'activate_' . WP3CXC2C_PLUGIN_BASENAME, 'wp3cxc2c_install' );

function wp3cxc2c_install() {
	if ( $opt = get_option( 'wp3cxc2c' ) ) {
		return;
	}

	wp3cxc2c_load_textdomain();
	wp3cxc2c_register_post_types();
	wp3cxc2c_upgrade();

	if ( get_posts( array( 'post_type' => 'wp3cxc2c_c2c_form' ) ) ) {
		return;
	}

	$clicktotalk_form = WP3CXC2C_ClickToTalkForm::get_template(
		array(
			'title' =>
				/* translators: title of your first Live Chat & Talk item. %d: number fixed to '1' */
				sprintf( __( 'Live Chat & Talk item %d', '3cx-clicktotalk' ), 1 ),
		)
	);

	$clicktotalk_form->save();

	WP3CXC2C::update_option( 'bulk_validate',
		array(
			'timestamp' => current_time( 'timestamp' ),
			'version' => WP3CXC2C_VERSION,
			'count_valid' => 1,
			'count_invalid' => 0,
		)
	);
}
