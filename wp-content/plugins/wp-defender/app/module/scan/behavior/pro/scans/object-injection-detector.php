<?php
/**
 * Author: Hoang Ngo
 */

namespace WP_Defender\Module\Scan\Behavior\Pro\Scans;

use WP_Defender\Module\Scan\Component\Token_Utils;

class Object_Injection_Detector extends Detector_Abstract {
	public function __construct( $index, $token ) {
		parent::__construct( $index, $token );
	}

	public function run() {
		if ( $this->token['code'] == T_STRING && $this->token['content'] == 'unserialize' ) {
			$next = Token_Utils::$tokens[ $this->index + 1 ];
			if ( $next['code'] == T_OPEN_PARENTHESIS ) {
				$params = Token_Utils::findParams( $next['parenthesis_opener'] + 1, $next['parenthesis_closer'] );

				if ( count( $params ) ) {
					foreach ( $params as $param ) {
						if ( $param['code'] == T_VARIABLE ) {
							if ( in_array( $param['content'], $this->getPredefinedVariables() ) ) {
								return array(
									'type'   => 'object_injection',
									'text'   => sprintf( __( 'The function %s line %d column %d unserialize an user inputs ', wp_defender()->domain ), $this->token['content'], $this->token['line'], $this->token['column'] ),
									'offset' => $this->getCodeOffset(),
									'length' => strlen( $this->getCode() ),
									'line'   => $this->token['line'],
									'column' => $this->token['column']
								);
							}
						}
					}
				}
			}
		}

		return false;
	}
}