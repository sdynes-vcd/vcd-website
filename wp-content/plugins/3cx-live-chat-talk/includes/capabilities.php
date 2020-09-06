<?php

add_filter( 'map_meta_cap', 'wp3cxc2c_map_meta_cap', 10, 4 );

function wp3cxc2c_map_meta_cap( $caps, $cap, $user_id, $args ) {
	$meta_caps = array(
		'wp3cxc2c_edit_clicktotalk_form' => WP3CXC2C_ADMIN_READ_WRITE_CAPABILITY,
		'wp3cxc2c_edit_clicktotalk_forms' => WP3CXC2C_ADMIN_READ_WRITE_CAPABILITY,
		'wp3cxc2c_read_clicktotalk_forms' => WP3CXC2C_ADMIN_READ_CAPABILITY,
		'wp3cxc2c_delete_clicktotalk_form' => WP3CXC2C_ADMIN_READ_WRITE_CAPABILITY,
		'wp3cxc2c_submit' => 'read',
	);

	$meta_caps = apply_filters( 'wp3cxc2c_map_meta_cap', $meta_caps );

	$caps = array_diff( $caps, array_keys( $meta_caps ) );

	if ( isset( $meta_caps[$cap] ) ) {
		$caps[] = $meta_caps[$cap];
	}

	return $caps;
}
