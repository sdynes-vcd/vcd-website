<?php

require_once WP3CXC2C_PLUGIN_DIR . '/admin/includes/admin-functions.php';
require_once WP3CXC2C_PLUGIN_DIR . '/admin/includes/help-tabs.php';
//require_once WP3CXC2C_PLUGIN_DIR . '/admin/includes/tag-generator.php';

add_action( 'admin_init', 'wp3cxc2c_admin_init' );

function wp3cxc2c_admin_init() {
	do_action( 'wp3cxc2c_admin_init' );
}

add_action( 'admin_menu', 'wp3cxc2c_admin_menu', 9 );

function wp3cxc2c_admin_menu() {
	global $_wp_last_object_menu;

	$_wp_last_object_menu++;

	add_menu_page( __( '3CX', '3cx-clicktotalk' ),
		__( '3CX Live Chat & Talk', '3cx-clicktotalk' ),
		'wp3cxc2c_read_clicktotalk_forms', 'wp3cxc2c',
		'wp3cxc2c_admin_management_page', ' data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMjU2LjAwMDAwMHB0IiBoZWlnaHQ9IjI1Ni4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDI1Ni4wMDAwMDAgMjU2LjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgcG90cmFjZSAxLjE1LCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxNwo8L21ldGFkYXRhPgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwyNTYuMDAwMDAwKSBzY2FsZSgwLjEwMDAwMCwtMC4xMDAwMDApIgpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPgo8cGF0aCBkPSJNNjk3IDIxMzkgYy05MSAtMjEgLTE2OSAtODcgLTIwOCAtMTc0IC0yNyAtNTggLTMxIC0xNjggLTEwIC0yMjggMjAKLTU4IDgyIC0xMjkgMTQxIC0xNjEgNzIgLTM5IDE4MCAtNDcgMjU1IC0xOSA0OCAxOCA1MSAyMSA3MiA4MSAyNSA2OSA3MyAxNDYKMTA0IDE2NSAzOCAyNCAxOCAxMzkgLTM4IDIyMCAtMzYgNTIgLTEwNSA5OCAtMTcyIDExNCAtNjMgMTQgLTkwIDE1IC0xNDQgMnoiLz4KPHBhdGggZD0iTTE3MTUgMjEzNyBjLTk3IC0yNSAtMTc1IC05OCAtMjEwIC0xOTYgLTI1IC03MyAtMjEgLTg5IDQxIC0xNDkgNTcKLTU2IDk2IC0xMjYgMTA5IC0xOTUgNiAtMzEgMTIgLTM2IDUxIC00NyA3MCAtMjAgMTcxIC04IDIzNCAyNiA1OSAzMiAxMjEgMTAzCjE0MSAxNjEgMjEgNjAgMTYgMTY5IC0xMCAyMjkgLTU4IDEzMyAtMjEzIDIwNyAtMzU2IDE3MXoiLz4KPHBhdGggZD0iTTEyMzMgMTgzMCBjLTU4IC0xMiAtMTEyIC00MiAtMTU1IC04NiAtMTQzIC0xNDMgLTEwMyAtMzkyIDc5IC00ODQKNDAgLTIxIDYyIC0yNSAxMzggLTI1IDc2IDAgOTggNCAxMzggMjUgMTgxIDkyIDIyMiAzMzkgODAgNDg1IC02NyA2OSAtMTg1CjEwNCAtMjgwIDg1eiIvPgo8cGF0aCBkPSJNNjA1IDE1MjMgYy0xMDYgLTEzIC0xODQgLTUyIC0yNTQgLTEyOSAtODMgLTkxIC05NCAtMTMzIC05OSAtMzc1CmwtNCAtMjA2IDk5IC0yNiBjMTIxIC0zMiAyNjMgLTU3IDMyNiAtNTcgbDQ3IDAgMCA4NSBjMCAxODggOTUgMzM5IDI2NSA0MjAKMzAgMTQgNTUgMjkgNTUgMzIgMCA0IC0xMSAxOCAtMjQgMzIgLTM1IDM4IC02NCA5NyAtNzggMTYwIC02IDMxIC0xNyA1OSAtMjIKNjMgLTExIDggLTI1MyA4IC0zMTEgMXoiLz4KPHBhdGggZD0iTTE2NTMgMTQ3MiBjLTEzIC03MCAtNDYgLTE0MiAtODAgLTE3NCAtMTMgLTEyIC0yMyAtMjUgLTIzIC0yOSAwIC01CjI1IC0yMCA1NSAtMzQgMTcxIC04MSAyNjUgLTIzMSAyNjUgLTQyNCBsMCAtODkgNjMgNSBjOTIgNyAyNTQgNDEgMzE3IDY2IGw1NQoyMiAwIDIwNSBjLTEgMTg5IC0zIDIwOSAtMjQgMjYyIC0yNiA2OCAtMTA2IDE2MCAtMTY1IDE5MSAtNzcgNDAgLTExOCA0OAotMjg2IDU0IGwtMTY1IDYgLTEyIC02MXoiLz4KPHBhdGggZD0iTTExMzUgMTIxNCBjLTE1MSAtMjIgLTI1OSAtOTcgLTMxOSAtMjI0IGwtMzEgLTY1IC0zIC0yMTEgLTMgLTIxMgo3OCAtMjAgYzIyMSAtNjAgNDIyIC04MSA1OTcgLTYzIDEyMyAxMiAyNDAgMzkgMzEyIDcwIGw0NSAyMCAtMyAyMDggLTMgMjA4Ci0zMSA2NSBjLTQ4IDEwMSAtMTI4IDE3MSAtMjM5IDIwOCAtNDAgMTMgLTMzNyAyNSAtNDAwIDE2eiIvPgo8L2c+Cjwvc3ZnPgo=',
		$_wp_last_object_menu );

	$edit = add_submenu_page( 'wp3cxc2c',
		__( 'Edit Live Chat & Talk', '3cx-clicktotalk' ),
		__( 'Live Chat & Talk items', '3cx-clicktotalk' ),
		'wp3cxc2c_read_clicktotalk_forms', 'wp3cxc2c',
		'wp3cxc2c_admin_management_page' );

	add_action( 'load-' . $edit, 'wp3cxc2c_load_clicktotalk_form_admin' );

	$addnew = add_submenu_page( 'wp3cxc2c',
		__( 'Add New Live Chat & Talk item', '3cx-clicktotalk' ),
		__( 'Add New', '3cx-clicktotalk' ),
		'wp3cxc2c_edit_clicktotalk_forms', 'wp3cxc2c-new',
		'wp3cxc2c_admin_add_new_page' );

	add_action( 'load-' . $addnew, 'wp3cxc2c_load_clicktotalk_form_admin' );

}

add_filter( 'set-screen-option', 'wp3cxc2c_set_screen_options', 10, 3 );

function wp3cxc2c_set_screen_options( $result, $option, $value ) {
	$wp3cxc2c_screens = array(
		'wp3cxc2c_clicktotalk_forms_per_page' );

	if ( in_array( $option, $wp3cxc2c_screens ) ) {
		$result = $value;
	}

	return $result;
}

function wp3cxc2c_load_clicktotalk_form_admin() {
	global $plugin_page;

	$action = wp3cxc2c_current_action();

	if ( 'save' == $action ) {
		$id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : '-1';
		check_admin_referer( 'wp3cxc2c-save-clicktotalk-form_' . $id );

		if ( ! current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', '3cx-clicktotalk' ) );
		}

		$args = $_REQUEST;
		$args['id'] = $id;

		$args['title'] = isset( $_POST['post_title'] )
			? $_POST['post_title'] : null;

		$args['locale'] = isset( $_POST['wp3cxc2c-locale'] )
			? $_POST['wp3cxc2c-locale'] : null;

		$args['config'] = isset( $_POST['wp3cxc2c-config'] )
			? wp3cxc2c_sanitize_config( $_POST['wp3cxc2c-config'] )
			: array();

		$args['style'] = isset( $_POST['wp3cxc2c-style'] )
			? wp3cxc2c_sanitize_style( $_POST['wp3cxc2c-style'] )
			: array();
		$clicktotalk_form = wp3cxc2c_save_clicktotalk_form( $args );

		if ( $clicktotalk_form && wp3cxc2c_validate_configuration() ) {
			$config_validator = new WP3CXC2C_ConfigValidator( $clicktotalk_form );
			$config_validator->validate();
			$config_validator->save();
		}

		$query = array(
			'post' => $clicktotalk_form ? $clicktotalk_form->id() : 0,
			'active-tab' => isset( $_POST['active-tab'] )
				? (int) $_POST['active-tab'] : 0,
		);

		if ( ! $clicktotalk_form ) {
			$query['message'] = 'failed';
		} elseif ( -1 == $id ) {
			$query['message'] = 'created';
		} else {
			$query['message'] = 'saved';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'wp3cxc2c', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'copy' == $action ) {
		$id = empty( $_POST['post_ID'] )
			? absint( $_REQUEST['post'] )
			: absint( $_POST['post_ID'] );

		check_admin_referer( 'wp3cxc2c-copy-clicktotalk-form_' . $id );

		if ( ! current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', '3cx-clicktotalk' ) );
		}

		$query = array();

		if ( $clicktotalk_form = wp3cxc2c_clicktotalk_form( $id ) ) {
			$new_clicktotalk_form = $clicktotalk_form->copy();
			$new_clicktotalk_form->save();

			$query['post'] = $new_clicktotalk_form->id();
			$query['message'] = 'created';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'wp3cxc2c', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'delete' == $action ) {
		if ( ! empty( $_POST['post_ID'] ) ) {
			check_admin_referer( 'wp3cxc2c-delete-clicktotalk-form_' . $_POST['post_ID'] );
		} elseif ( ! is_array( $_REQUEST['post'] ) ) {
			check_admin_referer( 'wp3cxc2c-delete-clicktotalk-form_' . $_REQUEST['post'] );
		} else {
			check_admin_referer( 'bulk-posts' );
		}

		$posts = empty( $_POST['post_ID'] )
			? (array) $_REQUEST['post']
			: (array) $_POST['post_ID'];

		$deleted = 0;

		foreach ( $posts as $post ) {
			$post = WP3CXC2C_ClickToTalkForm::get_instance( $post );

			if ( empty( $post ) ) {
				continue;
			}

			if ( ! current_user_can( 'wp3cxc2c_delete_clicktotalk_form', $post->id() ) ) {
				wp_die( __( 'You are not allowed to delete this item.', '3cx-clicktotalk' ) );
			}

			if ( ! $post->delete() ) {
				wp_die( __( 'Error in deleting.', '3cx-clicktotalk' ) );
			}

			$deleted += 1;
		}

		$query = array();

		if ( ! empty( $deleted ) ) {
			$query['message'] = 'deleted';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'wp3cxc2c', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	$_GET['post'] = isset( $_GET['post'] ) ? $_GET['post'] : '';

	$post = null;

	if ( 'wp3cxc2c-new' == $plugin_page ) {
		$post = WP3CXC2C_ClickToTalkForm::get_template( array(
			'locale' => isset( $_GET['locale'] ) ? $_GET['locale'] : null,
		) );
	} elseif ( ! empty( $_GET['post'] ) ) {
		$post = WP3CXC2C_ClickToTalkForm::get_instance( $_GET['post'] );
	}

	$current_screen = get_current_screen();

	$help_tabs = new WP3CXC2C_Help_Tabs( $current_screen );

	if ( $post && current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post->id() ) ) {
		$help_tabs->set_help_tabs( 'edit' );
	} else {
		$help_tabs->set_help_tabs( 'list' );

		if ( ! class_exists( 'WP3CXC2C_ClickToTalk_Form_List_Table' ) ) {
			require_once WP3CXC2C_PLUGIN_DIR . '/admin/includes/class-clicktotalk-forms-list-table.php';
		}

		add_filter( 'manage_' . $current_screen->id . '_columns',
			array( 'WP3CXC2C_ClickToTalk_Form_List_Table', 'define_columns' ) );

		add_screen_option( 'per_page', array(
			'default' => 20,
			'option' => 'wp3cxc2c_clicktotalk_forms_per_page',
		) );
	}
}

add_action( 'admin_enqueue_scripts', 'wp3cxc2c_admin_enqueue_scripts' );

function wp3cxc2c_admin_enqueue_scripts( $hook_suffix ) {
	if ( false === strpos( $hook_suffix, 'wp3cxc2c' ) ) {
		return;
	}

	wp_enqueue_style( '3cx-clicktotalk-admin',
		wp3cxc2c_plugin_url( 'admin/css/styles.css' ),
		array(), '1.7.0', 'all' );

	if ( wp3cxc2c_is_rtl() ) {
		wp_enqueue_style( '3cx-clicktotalk-admin-rtl',
			wp3cxc2c_plugin_url( 'admin/css/styles-rtl.css' ),
			array(), WP3CXC2C_VERSION, 'all' );
	}

	wp_enqueue_script( 'wp3cxc2c-admin',
		wp3cxc2c_plugin_url( 'admin/js/scripts.js' ),
		array( 'jquery', 'jquery-ui-tabs' ),
		WP3CXC2C_VERSION, true );

	$args = array(
		'apiSettings' => array(
			'root' => esc_url_raw( rest_url( '3cx-clicktotalk/v1' ) ),
			'namespace' => '3cx-clicktotalk/v1',
			'nonce' => ( wp_installing() && ! is_multisite() )
				? '' : wp_create_nonce( 'wp_rest' ),
		),
		'pluginUrl' => wp3cxc2c_plugin_url(),
		'saveAlert' => __(
			"The changes you made will be lost if you navigate away from this page.",
			'3cx-clicktotalk' ),
		'activeTab' => isset( $_GET['active-tab'] )
			? (int) $_GET['active-tab'] : 0,
		'configValidator' => array(
			'errors' => array(),
			'howToCorrect' => __( "How to resolve?", '3cx-clicktotalk' ),
			'oneError' => __( '1 configuration error detected', '3cx-clicktotalk' ),
			'manyErrors' => __( '%d configuration errors detected', '3cx-clicktotalk' ),
			'oneErrorInTab' => __( '1 configuration error detected in this tab panel', '3cx-clicktotalk' ),
			'manyErrorsInTab' => __( '%d configuration errors detected in this tab panel', '3cx-clicktotalk' ),
			'docUrl' => '',
			/* translators: screen reader text */
			'iconAlt' => __( '(configuration error)', '3cx-clicktotalk' ),
		),
	);

	if ( ( $post = wp3cxc2c_get_current_clicktotalk_form() )
	&& current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post->id() )
	&& wp3cxc2c_validate_configuration() ) {
		$config_validator = new WP3CXC2C_ConfigValidator( $post );
		$config_validator->restore();
		$args['configValidator']['errors'] =
			$config_validator->collect_error_messages();
	}

	wp_localize_script( 'wp3cxc2c-admin', 'wp3cxc2c', $args );

}

function wp3cxc2c_admin_management_page() {
	if ( $post = wp3cxc2c_get_current_clicktotalk_form() ) {
		$post_id = $post->initial() ? -1 : $post->id();

		require_once WP3CXC2C_PLUGIN_DIR . '/admin/includes/editor.php';
		require_once WP3CXC2C_PLUGIN_DIR . '/admin/edit-clicktotalk-form.php';
		return;
	}

	$list_table = new WP3CXC2C_ClickToTalk_Form_List_Table();
	$list_table->prepare_items();

?>
<div class="wrap">

<h1 class="wp-heading-inline"><?php
	echo esc_html( __( 'Live Chat & Talk items', '3cx-clicktotalk' ) );
?></h1>

<?php
	if ( current_user_can( 'wp3cxc2c_edit_clicktotalk_forms' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
			esc_url( menu_page_url( 'wp3cxc2c-new', false ) ),
			esc_html( __( 'Add New', '3cx-clicktotalk' ) ) );
	}

	if ( ! empty( $_REQUEST['s'] ) ) {
		echo sprintf( '<span class="subtitle">'
			/* translators: %s: search keywords */
			. __( 'Search results for &#8220;%s&#8221;', '3cx-clicktotalk' )
			. '</span>', esc_html( $_REQUEST['s'] ) );
	}
?>

<hr class="wp-header-end">

<?php do_action( 'wp3cxc2c_admin_warnings' ); ?>
<?php do_action( 'wp3cxc2c_admin_notices' ); ?>

<form method="get" action="">
	<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
	<?php $list_table->search_box( __( 'Search Live Chat & Talk item', '3cx-clicktotalk' ), 'wp3cxc2c-clicktotalk' ); ?>
	<?php $list_table->display(); ?>
</form>

</div>
<?php
}

function wp3cxc2c_admin_add_new_page() {
	$post = wp3cxc2c_get_current_clicktotalk_form();

	if ( ! $post ) {
		$post = WP3CXC2C_ClickToTalkForm::get_template();
	}

	$post_id = -1;

	require_once WP3CXC2C_PLUGIN_DIR . '/admin/includes/editor.php';
	require_once WP3CXC2C_PLUGIN_DIR . '/admin/edit-clicktotalk-form.php';
}

/* Misc */

add_action( 'wp3cxc2c_admin_notices', 'wp3cxc2c_admin_updated_message' );

function wp3cxc2c_admin_updated_message() {
	if ( empty( $_REQUEST['message'] ) ) {
		return;
	}

	if ( 'created' == $_REQUEST['message'] ) {
		$updated_message = __( "Live Chat & Talk item created.", '3cx-clicktotalk' );
	} elseif ( 'saved' == $_REQUEST['message'] ) {
		$updated_message = __( "Live Chat & Talk item saved.", '3cx-clicktotalk' );
	} elseif ( 'deleted' == $_REQUEST['message'] ) {
		$updated_message = __( "Live Chat & Talk item deleted.", '3cx-clicktotalk' );
	}

	if ( ! empty( $updated_message ) ) {
		echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
		return;
	}

	if ( 'failed' == $_REQUEST['message'] ) {
		$updated_message = __( "There was an error saving the Live Chat & Talk item.",
			'3cx-clicktotalk' );

		echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
		return;
	}


}

add_action( 'wp3cxc2c_admin_warnings', 'wp3cxc2c_old_wp_version_error' );

function wp3cxc2c_old_wp_version_error() {
	$wp_version = get_bloginfo( 'version' );

	if ( ! version_compare( $wp_version, WP3CXC2C_REQUIRED_WP_VERSION, '<' ) ) {
		return;
	}

?>
<div class="notice notice-warning">
<p><?php
	/* translators: 1: version of 3CX Live Chat & Talk , 2: version of WordPress, 3: URL */
	echo sprintf( __( '<strong>3CX Live Chat & Talk %1$s requires WordPress %2$s or higher.</strong> Please <a href="%3$s">update WordPress</a> first.', '3cx-clicktotalk' ), WP3CXC2C_VERSION, WP3CXC2C_REQUIRED_WP_VERSION, admin_url( 'update-core.php' ) );
?></p>
</div>
<?php
}

add_action( 'wp3cxc2c_admin_warnings', 'wp3cxc2c_not_allowed_to_edit' );

function wp3cxc2c_not_allowed_to_edit() {
	if ( ! $clicktotalk_form = wp3cxc2c_get_current_clicktotalk_form() ) {
		return;
	}

	$post_id = $clicktotalk_form->id();

	if ( current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post_id ) ) {
		return;
	}

	$message = __( "You are not allowed to edit this Live Chat & Talk item.",
		'3cx-clicktotalk' );

	echo sprintf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html( $message ) );
}






