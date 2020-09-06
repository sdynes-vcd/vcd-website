<?php

function wp3cxc2c_clicktotalk_form( $id ) {
	return WP3CXC2C_ClickToTalkForm::get_instance( $id );
}

function wp3cxc2c_get_clicktotalk_form_by_old_id( $old_id ) {
	global $wpdb;

	$q = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_old_cf7_unit_id'"
		. $wpdb->prepare( " AND meta_value = %d", $old_id );

	if ( $new_id = $wpdb->get_var( $q ) ) {
		return wp3cxc2c_clicktotalk_form( $new_id );
	}
}

function wp3cxc2c_get_clicktotalk_form_by_title( $title ) {
	$page = get_page_by_title( $title, OBJECT, WP3CXC2C_ClickToTalkForm::post_type );

	if ( $page ) {
		return wp3cxc2c_clicktotalk_form( $page->ID );
	}

	return null;
}

function wp3cxc2c_get_current_clicktotalk_form() {
	if ( $current = WP3CXC2C_ClickToTalkForm::get_current() ) {
		return $current;
	}
}

function wp3cxc2c_is_posted() {
	if ( ! $clicktotalk_form = wp3cxc2c_get_current_clicktotalk_form() ) {
		return false;
	}

	return $clicktotalk_form->is_posted();
}

function wp3cxc2c_get_hangover( $name, $default = null ) {
	if ( ! wp3cxc2c_is_posted() ) {
		return $default;
	}

	return isset( $_POST[$name] ) ? wp_unslash( $_POST[$name] ) : $default;
}

function wp3cxc2c_get_message( $status ) {
	if ( ! $clicktotalk_form = wp3cxc2c_get_current_clicktotalk_form() ) {
		return '';
	}

	return $clicktotalk_form->message( $status );
}

function wp3cxc2c_form_controls_class( $type, $default = '' ) {
	$type = trim( $type );
	$default = array_filter( explode( ' ', $default ) );

	$classes = array_merge( array( 'wp3cxc2c-form-control' ), $default );

	$typebase = rtrim( $type, '*' );
	$required = ( '*' == substr( $type, -1 ) );

	$classes[] = 'wp3cxc2c-' . $typebase;

	if ( $required ) {
		$classes[] = 'wp3cxc2c-validates-as-required';
	}

	$classes = array_unique( $classes );

	return implode( ' ', $classes );
}

function wp3cxc2c_clicktotalk_form_tag_func( $atts, $content = null, $code = '' ) {
	if ( is_feed() ) {
		return '[3cx-clicktotalk]';
	}

	if ( '3cx-clicktotalk' == $code ) {
		$atts = shortcode_atts(
			array(
				'id' => 0,
				'title' => '',
				'html_id' => '',
				'html_name' => '',
				'html_class' => '',
				'output' => 'form',
			),
			$atts, 'wp3cxc2c'
		);

		$id = (int) $atts['id'];
		$title = trim( $atts['title'] );

		if ( ! $clicktotalk_form = wp3cxc2c_clicktotalk_form( $id ) ) {
			$clicktotalk_form = wp3cxc2c_get_clicktotalk_form_by_title( $title );
		}

	} else {
		if ( is_string( $atts ) ) {
			$atts = explode( ' ', $atts, 2 );
		}

		$id = (int) array_shift( $atts );
		$clicktotalk_form = wp3cxc2c_get_clicktotalk_form_by_old_id( $id );
	}

	if ( ! $clicktotalk_form ) {
		return '[3cx-clicktotalk 404 "Not Found"]';
	}

        wp_enqueue_script( '3cx-clicktotalk',
    	wp3cxc2c_plugin_url( 'includes/js/callus.js' ),
    	array( 'jquery' ), '1.7.1', true );

    return $clicktotalk_form->form_html( $atts );
}

function wp3cxc2c_save_clicktotalk_form( $args = '', $context = 'save' ) {
	$args = wp_parse_args( $args, array(
		'id' => -1,
		'title' => null,
		'locale' => null,
		'form' => null,
		'config' => null,
		'style' => null
	) );

	$args['id'] = (int) $args['id'];

	if ( -1 == $args['id'] ) {
		$clicktotalk_form = WP3CXC2C_ClickToTalkForm::get_template();
	} else {
		$clicktotalk_form = wp3cxc2c_clicktotalk_form( $args['id'] );
	}

	if ( empty( $clicktotalk_form ) ) {
		return false;
	}

	if ( null !== $args['title'] ) {
		$clicktotalk_form->set_title( $args['title'] );
	}

	if ( null !== $args['locale'] ) {
		$clicktotalk_form->set_locale( $args['locale'] );
	}

	$properties = $clicktotalk_form->get_properties();

	$properties['form'] = wp3cxc2c_sanitize_form(
		$args['form'], $properties['form'] );

	$properties['config'] = wp3cxc2c_sanitize_config(
		$args['config'], $properties['config'] );

	$properties['style'] = wp3cxc2c_sanitize_style(
			$args['style'], $properties['style'] );

			

	$properties['config']['active'] = true;

	
	$clicktotalk_form->set_properties( $properties );

	do_action( 'wp3cxc2c_save_clicktotalk_form', $clicktotalk_form, $args, $context );

	if ( 'save' == $context ) {
		$clicktotalk_form->save();
	}

	return $clicktotalk_form;
}

function wp3cxc2c_sanitize_form( $input, $default = '' ) {
	if ( null === $input ) {
		return $default;
	}

	$output = trim( $input );
	return $output;
}

function wp3cxc2c_sanitize_config( $input, $defaults = array() ) {
	$defaults = wp_parse_args( $defaults, array(
		'active' => false,
		'aspect' => '',
		'pbxurl' => '',
		'chatboxtitle' => '',
		'phoneboxtitle' => '',
		'welcomemessage' => '',
		'welcomemessagesender' => '',
		'enabledvideo' => false,
		'chatboxwindowicon' => '',
		'operatoricon' => '',
		'popout' => false,
		'enableonmobile' => false,
		'showtypingindicator' => false,
		'autofocus' => false,
		'allowsoundnotifications' => false,
		'showoperatoractualname' => false,
        'authenticationmessage' => '',
        'unavailablemessage' => ''
 		) );

	$input = wp_parse_args( $input, $defaults );

	$output = array();
	$output['active'] = (bool) $input['active'];
	$output['aspect'] = trim(strtolower($input['aspect'] ));
	$output['pbxurl'] = trim(strtolower($input['pbxurl'] ));
	$output['chatboxtitle'] = trim($input['chatboxtitle']);
	$output['authenticationmessage'] = trim($input['authenticationmessage']);
	$output['unavailablemessage'] = trim($input['unavailablemessage']);
	$output['phoneboxtitle'] = trim($input['phoneboxtitle']);
	$output['welcomemessage'] = trim($input['welcomemessage']);
	$output['welcomemessagesender'] = trim($input['welcomemessagesender']);
	$output['enablevideo'] = (trim($input['enablevideo'])) ? true : false;
	$output['popout'] = (trim($input['popout'])) ? true : false;
    $output['allowsoundnotifications'] = (trim($input['allowsoundnotifications'])) ? true : false;
	$output['requireidentity'] = trim(strtolower($input['requireidentity'] ));
	$output['chatboxwindowicon'] = trim($input['chatboxwindowicon']);	
	$output['operatoricon'] = trim($input['operatoricon']);
    $output['enableonmobile'] = (trim($input['enableonmobile'])) ? true : false;
    $output['showoperatoractualname'] = (trim($input['showoperatoractualname'])) ? true : false;
    $output['showtypingindicator'] = (trim($input['showtypingindicator'])) ? true : false;
    $output['autofocus'] = (trim($input['autofocus'])) ? true : false;
    return $output;
}


function wp3cxc2c_sanitize_style( $input, $defaults = array() ) {
	$defaults = wp_parse_args( $defaults, array(	
		'windowposition' => ''	,
        'minimizedstyle' => '',
        'minimized' => false,
        'primarycolor' => '',
        'secondarycolor'=> '',
        'windowwidth' => '',
        'windowheight' => '',
        'enablepoweredby'=> false,
 		) );

	$input = wp_parse_args( $input, $defaults );

	$output = array();	
	$output['windowposition'] = trim($input['windowposition']);
    $output['windowwidth'] = trim(strtolower($input['windowwidth'] ));
    $output['windowheight'] = trim(strtolower($input['windowheight'] ));
    $output['primarycolor'] = trim(strtolower($input['primarycolor'] ));
    $output['secondarycolor'] = trim(strtolower($input['secondarycolor'] ));
    $output['minimized'] = (trim($input['minimized'])) ? true : false;
    $output['enablepoweredby'] = (trim($input['enablepoweredby'])) ? true : false;
    $output['minimizedstyle'] = trim(strtolower($input['minimizedstyle'] ));
    $output['animationstyle'] = trim(strtolower($input['animationstyle'] ));
    $output['emailintegrationurl'] = trim(strtolower($input['emailintegrationurl'] ));
    $output['facebookintegrationurl'] = trim(strtolower($input['facebookintegrationurl'] ));
    $output['twitterintegrationurl'] = trim(strtolower($input['twitterintegrationurl'] ));



    return $output;
}
