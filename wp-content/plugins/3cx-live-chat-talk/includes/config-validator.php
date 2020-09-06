<?php

class WP3CXC2C_ConfigValidator {

	const error = 100;
	const error_maybe_empty = 101;
	const error_deprecated_settings = 109;
	const error_pbx_url_invalid = 111;

	private $clicktotalk_form;
	private $errors = array();

	public function __construct( WP3CXC2C_ClickToTalkForm $clicktotalk_form ) {
		$this->clicktotalk_form = $clicktotalk_form;
	}

	public function clicktotalk_form() {
		return $this->clicktotalk_form;
	}

	public function is_valid() {
		return ! $this->count_errors();
	}

	public function count_errors( $args = '' ) {
		$args = wp_parse_args( $args, array(
			'section' => '',
			'code' => '',
		) );

		$count = 0;

		foreach ( $this->errors as $key => $errors ) {
			if ( preg_match( '/^mail_[0-9]+\.(.*)$/', $key, $matches ) ) {
				$key = sprintf( 'mail.%s', $matches[1] );
			}

			if ( $args['section']
			&& $key != $args['section']
			&& preg_replace( '/\..*$/', '', $key, 1 ) != $args['section'] ) {
				continue;
			}

			foreach ( $errors as $error ) {
				if ( empty( $error ) ) {
					continue;
				}

				if ( $args['code'] && $error['code'] != $args['code'] ) {
					continue;
				}

				$count += 1;
			}
		}

		return $count;
	}

	public function collect_error_messages() {
		$error_messages = array();

		foreach ( $this->errors as $section => $errors ) {
			$error_messages[$section] = array();

			foreach ( $errors as $error ) {
				if ( empty( $error['args']['message'] ) ) {
					$message = $this->get_default_message( $error['code'] );
				} elseif ( empty( $error['args']['params'] ) ) {
					$message = $error['args']['message'];
				} else {
					$message = $this->build_message(
						$error['args']['message'],
						$error['args']['params'] );
				}

				$link = '';

				if ( ! empty( $error['args']['link'] ) ) {
					$link = $error['args']['link'];
				}

				$error_messages[$section][] = array(
					'message' => $message,
					'link' => esc_url( $link ),
				);
			}
		}

		return $error_messages;
	}

	public function build_message( $message, $params = '' ) {
		$params = wp_parse_args( $params, array() );

		foreach ( $params as $key => $val ) {
			if ( ! preg_match( '/^[0-9A-Za-z_]+$/', $key ) ) { // invalid key
				continue;
			}

			$placeholder = '%' . $key . '%';

			if ( false !== stripos( $message, $placeholder ) ) {
				$message = str_ireplace( $placeholder, $val, $message );
			}
		}

		return $message;
	}

	public function get_default_message( $code ) {
		switch ( $code ) {
			case self::error_maybe_empty:
				return __( "There is a possible empty field.", '3cx-clicktotalk' );
			case self::error_deprecated_settings:
				return __( "Deprecated settings are used.", '3cx-clicktotalk' );
			case self::error_pbx_url_invalid:
				return __( "PBX URL is not valid.", '3cx-clicktotalk' );
			default:
				return '';
		}
	}

	public function add_error( $section, $code, $args = '' ) {
		$args = wp_parse_args( $args, array(
			'message' => '',
			'params' => array(),
		) );

		if ( ! isset( $this->errors[$section] ) ) {
			$this->errors[$section] = array();
		}

		$this->errors[$section][] = array( 'code' => $code, 'args' => $args );

		return true;
	}

	public function remove_error( $section, $code ) {
		if ( empty( $this->errors[$section] ) ) {
			return;
		}

		foreach ( (array) $this->errors[$section] as $key => $error ) {
			if ( isset( $error['code'] ) && $error['code'] == $code ) {
				unset( $this->errors[$section][$key] );
			}
		}
	}

	public function validate() {
		$this->errors = array();

		$this->validate_config();

		do_action( 'wp3cxc2c_config_validator_validate', $this );
		
		return $this->is_valid();
	}

	public function save() {
		if ( $this->clicktotalk_form->initial() ) {
			return;
		}

		delete_post_meta( $this->clicktotalk_form->id(), '_config_errors' );

		if ( $this->errors ) {
			update_post_meta( $this->clicktotalk_form->id(), '_config_errors',
				$this->errors );
		}
	}

	public function restore() {
		$config_errors = get_post_meta(
			$this->clicktotalk_form->id(), '_config_errors', true );

		foreach ( (array) $config_errors as $section => $errors ) {
			if ( empty( $errors ) ) {
				continue;
			}

			if ( ! is_array( $errors ) ) { // for back-compat
				$code = $errors;
				$this->add_error( $section, $code );
			} else {
				foreach ( (array) $errors as $error ) {
					if ( ! empty( $error['code'] ) ) {
						$code = $error['code'];
						$args = isset( $error['args'] ) ? $error['args'] : '';
						$this->add_error( $section, $code, $args );
					}
				}
			}
		}
	}
	
	public function validate_config() {
		$template = 'config';
		$components = (array) $this->clicktotalk_form->prop( $template );

		if ( ! $components ) {
			return;
		}

		$components = wp_parse_args( $components, array(
		'pbxurl' => '',
		'aspect' => '',
		'welcomemessage' => '',
		'welcomemessagesender' => '',
		'chatboxtitle' => '',
		'authenticationmessage' => '',
		'unavailablemessage' => '',
		'phoneboxtitle' => '',
		'enablevideo' => false,
		'minimized'=> false,
		'popout'=> false,
		'enablepoweredby'=> false,
		'requireidentity' => 'none',
            'minimizedstyle' => 'bubble',
            'animationstyle' => 'none'


			) );

		$pbxurl = trim($components['pbxurl']);
		
		$aspect = trim(strtolower($components['aspect']));
		if (!in_array($aspect,array('both','chat','phone'))) {
			$aspect='both';
		}
		
		$chatboxtitle = trim($components['chatboxtitle']);
		$authenticationmessage = trim($components['authenticationmessage']);
		$unavailablemessage = trim($components['unavailablemessage']);
		$welcomemessage = trim($components['welcomemessage']);
		$welcomemessagesender = trim($components['welcomemessagesender']);
		$enablevideo = ($components['enablevideo']) ? true : false;
		$minimized = ($components['minimized']) ? true : false;
		$popout = ($components['popout']) ? true : false;
		$requireidentity = trim(strtolower($components['requireidentity']));
		if (!in_array($requireidentity,array('none','name','email','both'))) {
			$requireidentity='none';
		}
		
		$phoneboxtitle = trim($components['phoneboxtitle']);

		if (!filter_var($pbxurl, FILTER_VALIDATE_URL)) {
			$this->add_error( sprintf( '%s.apitoken', $template ),
				self::error_pbx_url_invalid, array(
				)
			);
		}
		
	}	

	public function detect_maybe_empty( $section, $content ) {
		if ( '' === $content ) {
			return $this->add_error( $section,
				self::error_maybe_empty, array(
				)
			);
		}

		return false;
	}

}
