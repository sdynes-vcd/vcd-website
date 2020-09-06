<?php

function wp3cxc2c_current_action() {
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
		return $_REQUEST['action'];
	}

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
		return $_REQUEST['action2'];
	}

	return false;
}

function wp3cxc2c_admin_has_edit_cap() {
	return current_user_can( 'wp3cxc2c_edit_clicktotalk_forms' );
}
