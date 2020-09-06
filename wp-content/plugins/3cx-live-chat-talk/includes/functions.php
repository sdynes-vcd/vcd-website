<?php

function wp3cxc2c_plugin_path( $path = '' ) {
	return path_join( WP3CXC2C_PLUGIN_DIR, trim( $path, '/' ) );
}

function wp3cxc2c_plugin_url( $path = '' ) {
	$url = plugins_url( $path, WP3CXC2C_PLUGIN );

	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}

function wp3cxc2c_verify_nonce( $nonce, $action = 'wp_rest' ) {
	return wp_verify_nonce( $nonce, $action );
}

function wp3cxc2c_create_nonce( $action = 'wp_rest' ) {
	return wp_create_nonce( $action );
}

function wp3cxc2c_blacklist_check( $target ) {
	$mod_keys = trim( get_option( 'blacklist_keys' ) );

	if ( empty( $mod_keys ) ) {
		return false;
	}

	$words = explode( "\n", $mod_keys );

	foreach ( (array) $words as $word ) {
		$word = trim( $word );

		if ( empty( $word ) || 256 < strlen( $word ) ) {
			continue;
		}

		$pattern = sprintf( '#%s#i', preg_quote( $word, '#' ) );

		if ( preg_match( $pattern, $target ) ) {
			return true;
		}
	}

	return false;
}

function wp3cxc2c_array_flatten( $input ) {
	if ( ! is_array( $input ) ) {
		return array( $input );
	}

	$output = array();

	foreach ( $input as $value ) {
		$output = array_merge( $output, wp3cxc2c_array_flatten( $value ) );
	}

	return $output;
}

function wp3cxc2c_flat_join( $input ) {
	$input = wp3cxc2c_array_flatten( $input );
	$output = array();

	foreach ( (array) $input as $value ) {
		$output[] = trim( (string) $value );
	}

	return implode( ', ', $output );
}

function wp3cxc2c_support_html5() {
	return (bool) apply_filters( 'wp3cxc2c_support_html5', true );
}

function wp3cxc2c_support_html5_fallback() {
	return (bool) apply_filters( 'wp3cxc2c_support_html5_fallback', false );
}


function wp3cxc2c_validate_configuration() {
	return apply_filters( 'wp3cxc2c_validate_configuration',
		WP3CXC2C_VALIDATE_CONFIGURATION );
}

function wp3cxc2c_autop_or_not() {
	return (bool) apply_filters( 'wp3cxc2c_autop_or_not', WP3CXC2C_AUTOP );
}

function wp3cxc2c_load_js() {
	return apply_filters( 'wp3cxc2c_load_js', WP3CXC2C_LOAD_JS );
}

function wp3cxc2c_load_css() {
	return apply_filters( 'wp3cxc2c_load_css', WP3CXC2C_LOAD_CSS );
}

function wp3cxc2c_format_atts( $atts ) {
	$html = '';

	$prioritized_atts = array( 'type', 'name', 'value' );

	foreach ( $prioritized_atts as $att ) {
		if ( isset( $atts[$att] ) ) {
			$value = trim( $atts[$att] );
			$html .= sprintf( ' %s="%s"', $att, esc_attr( $value ) );
			unset( $atts[$att] );
		}
	}

	foreach ( $atts as $key => $value ) {
		$key = strtolower( trim( $key ) );

		if ( ! preg_match( '/^[a-z_:][a-z_:.0-9-]*$/', $key ) ) {
			continue;
		}

		$value = trim( $value );

		if ( '' !== $value ) {
			$html .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
		}
	}

	$html = trim( $html );

	return $html;
}

function wp3cxc2c_link( $url, $anchor_text, $args = '' ) {
	$defaults = array(
		'id' => '',
		'class' => '',
	);

	$args = wp_parse_args( $args, $defaults );
	$args = array_intersect_key( $args, $defaults );
	$atts = wp3cxc2c_format_atts( $args );

	$link = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
		esc_url( $url ),
		esc_html( $anchor_text ),
		$atts ? ( ' ' . $atts ) : '' );

	return $link;
}

function wp3cxc2c_register_post_types() {
	if ( class_exists( 'WP3CXC2C_ClickToTalkForm' ) ) {
		WP3CXC2C_ClickToTalkForm::register_post_type();
		return true;
	} else {
		return false;
	}
}

