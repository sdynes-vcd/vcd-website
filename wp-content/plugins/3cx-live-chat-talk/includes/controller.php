<?php

add_action( 'parse_request', 'wp3cxc2c_control_init', 20 );

function wp3cxc2c_control_init() {
	if ( isset( $_POST['_wp3cxc2c'] ) ) {
		$clicktotalk_form = wp3cxc2c_clicktotalk_form( (int) $_POST['_wp3cxc2c'] );

		if ( $clicktotalk_form ) {
			$clicktotalk_form->submit();
		}
	}
}

add_filter( 'widget_text', 'wp3cxc2c_widget_text_filter', 9 );

function wp3cxc2c_widget_text_filter( $content ) {
	$pattern = '/\[[\r\n\t ]*clicktotalk-form?[\r\n\t ].*?\]/';

	if ( ! preg_match( $pattern, $content ) ) {
		return $content;
	}

	$content = do_shortcode( $content );

	return $content;
}

add_action( 'wp_enqueue_scripts', 'wp3cxc2c_do_enqueue_scripts' );

function wp3cxc2c_do_enqueue_scripts() {
	if ( wp3cxc2c_load_js() ) {
		wp3cxc2c_enqueue_scripts();
	}

	if ( wp3cxc2c_load_css() ) {
		wp3cxc2c_enqueue_styles();
	}
}

function wp3cxc2c_enqueue_scripts() {
	$in_footer = true;

	if ( 'header' === wp3cxc2c_load_js() ) {
		$in_footer = false;
	}

	//wp_enqueue_script( '3cx-clicktotalk',
	//	wp3cxc2c_plugin_url( 'includes/js/callus.js' ),
	//	array( 'jquery' ), '1.5.0', $in_footer );

	$wp3cxc2c = array(
		'apiSettings' => array(
			'root' => esc_url_raw( rest_url( '3cx-clicktotalk/v1' ) ),
			'namespace' => '3cx-clicktotalk/v1',
		),
	);

	if ( defined( 'WP_CACHE' ) && WP_CACHE ) {
		$wp3cxc2c['cached'] = 1;
	}

	if ( wp3cxc2c_support_html5_fallback() ) {
		$wp3cxc2c['jqueryUi'] = 1;
	}

	wp_localize_script( '3cx-clicktotalk', 'wp3cxc2c', $wp3cxc2c );

	do_action( 'wp3cxc2c_enqueue_scripts' );
}

function wp3cxc2c_script_is() {
	return wp_script_is( '3cx-clicktotalk' );
}

function wp3cxc2c_enqueue_styles() {
	/*
	wp_enqueue_style( '3cx-clicktotalk',
		wp3cxc2c_plugin_url( 'includes/css/styles.css' ),
		array(), WP3CXC2C_VERSION, 'all' );

	if ( wp3cxc2c_is_rtl() ) {
		wp_enqueue_style( '3cx-clicktotalk-rtl',
			wp3cxc2c_plugin_url( 'includes/css/styles-rtl.css' ),
			array(), WP3CXC2C_VERSION, 'all' );
	}
	*/

	do_action( 'wp3cxc2c_enqueue_styles' );
}

function wp3cxc2c_style_is() {
	return wp_style_is( '3cx-clicktotalk' );
}

/* HTML5 Fallback */

add_action( 'wp_enqueue_scripts', 'wp3cxc2c_html5_fallback', 20 );

function wp3cxc2c_html5_fallback() {
	if ( ! wp3cxc2c_support_html5_fallback() ) {
		return;
	}

	if ( wp3cxc2c_script_is() ) {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-spinner' );
	}

	if ( wp3cxc2c_style_is() ) {
		wp_enqueue_style( 'jquery-ui-smoothness',
			wp3cxc2c_plugin_url(
				'includes/js/jquery-ui/themes/smoothness/jquery-ui.min.css' ),
			array(), '1.11.4', 'screen' );
	}
}
