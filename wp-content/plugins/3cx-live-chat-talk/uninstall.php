<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

function wp3cxc2c_delete_plugin() {
	global $wpdb;

	delete_option( 'wp3cxc2c' );

	$posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type' => 'wp3cxc2c_c2c_form',
			'post_status' => 'any',
		)
	);

	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}
}

wp3cxc2c_delete_plugin();
