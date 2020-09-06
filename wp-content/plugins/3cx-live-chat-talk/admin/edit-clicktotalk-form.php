    <?php

    // don't load directly
    if ( ! defined( 'ABSPATH' ) ) {
        die( '-1' );
    }

    function wp3cxc2c_admin_save_button( $post_id ) {
        static $button = '';

        if ( ! empty( $button ) ) {
            echo $button;
            return;
        }

        $nonce = wp_create_nonce( 'wp3cxc2c-save-clicktotalk-form_' . $post_id );

        $onclick = sprintf(
            "this.form._wpnonce.value = '%s';"
            . " this.form.action.value = 'save';"
            . " return true;",
            $nonce );

        $button = sprintf(
            '<input type="submit" style="width: 100px!important;" class="button-primary" name="wp3cxc2c-save" value="%1$s" onclick="%2$s" />',
            esc_attr( __( 'Save', '3cx-clicktotalk' ) ),
            $onclick );

        echo $button;
    }

    ?><div class="wrap">

    <h1 class="wp-heading-inline"><?php
        if ( $post->initial() ) {
            echo esc_html( __( 'Add New Live Chat & Talk item', '3cx-clicktotalk' ) );
        } else {
            echo esc_html( __( 'Edit Live Chat & Talk item', '3cx-clicktotalk' ) );
        }
    ?></h1>

    <?php
        if ( ! $post->initial() && current_user_can( 'wp3cxc2c_edit_clicktotalk_forms' ) ) {
            echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
                esc_url( menu_page_url( 'wp3cxc2c-new', false ) ),
                esc_html( __( 'Add New', '3cx-clicktotalk' ) ) );
        }
    ?>

    <hr class="wp-header-end">

    <?php do_action( 'wp3cxc2c_admin_warnings' ); ?>
    <?php do_action( 'wp3cxc2c_admin_notices' ); ?>

    <?php
    if ( $post ) :

        if ( current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post_id ) ) {
            $disabled = '';
        } else {
            $disabled = ' disabled="disabled"';
        }
    ?>

    <form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'wp3cxc2c', false ) ) ); ?>" id="wp3cxc2c-admin-form-element"<?php do_action( 'wp3cxc2c_post_edit_form_tag' ); ?>>
    <?php
        if ( current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post_id ) ) {
            wp_nonce_field( 'wp3cxc2c-save-clicktotalk-form_' . $post_id );
        }
    ?>
    <input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
    <input type="hidden" id="wp3cxc2c-locale" name="wp3cxc2c-locale" value="<?php echo esc_attr( $post->locale() ); ?>" />
    <input type="hidden" id="hiddenaction" name="action" value="save" />
    <input type="hidden" id="active-tab" name="active-tab" value="<?php echo isset( $_GET['active-tab'] ) ? (int) $_GET['active-tab'] : '0'; ?>" />

    <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">
    <div id="titlediv">
    <div id="titlewrap">
        <label class="screen-reader-text" id="title-prompt-text" for="title"><?php echo esc_html( __( 'Enter title here', '3cx-clicktotalk' ) ); ?></label>
    <?php
        $posttitle_atts = array(
            'type' => 'text',
            'name' => 'post_title',
            'size' => 30,
            'value' => $post->initial() ? '' : $post->title(),
            'id' => 'title',
            'spellcheck' => 'true',
            'autocomplete' => 'off',
            'disabled' =>
                current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post_id ) ? '' : 'disabled',
        );

        echo sprintf( '<input %s />', wp3cxc2c_format_atts( $posttitle_atts ) );
    ?>
    </div><!-- #titlewrap -->

    <div class="inside">
    <?php
        if ( ! $post->initial() ) :
    ?>
        <p class="description">
        <label for="wp3cxc2c-shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", '3cx-clicktotalk' ) ); ?></label>
        <span class="shortcode wp-ui-highlight"><input type="text" id="wp3cxc2c-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $post->shortcode() ); ?>" /></span>
        </p>
    <?php
            if ( $old_shortcode = $post->shortcode( array( 'use_old_format' => true ) ) ) :
    ?>
        <p class="description">
        <label for="wp3cxc2c-shortcode-old"><?php echo esc_html( __( "You can also use this old-style shortcode:", '3cx-clicktotalk' ) ); ?></label>
        <span class="shortcode old"><input type="text" id="wp3cxc2c-shortcode-old" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $old_shortcode ); ?>" /></span>
        </p>
    <?php
            endif;
        endif;
    ?>
    </div>
    </div><!-- #titlediv -->
    </div><!-- #post-body-content -->

    <div id="postbox-container-1" class="postbox-container">
    <?php if ( current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post_id ) ) : ?>
    <div id="submitdiv" class="postbox">
    <h3><?php echo esc_html( __( 'Status', '3cx-clicktotalk' ) ); ?></h3>
    <div class="inside">
    <div class="submitbox" id="submitpost">

    <div id="minor-publishing-actions">

    <div class="hidden">
        <input type="submit" class="button-primary" name="wp3cxc2c-save" value="<?php echo esc_attr( __( 'Save', '3cx-clicktotalk' ) ); ?>" />
    </div>

    <?php
        if ( ! $post->initial() ) :
            $copy_nonce = wp_create_nonce( 'wp3cxc2c-copy-clicktotalk-form_' . $post_id );
    ?>
        <input type="submit" name="wp3cxc2c-copy" class="copy button" value="<?php echo esc_attr( __( 'Duplicate', '3cx-clicktotalk' ) ); ?>" <?php echo "onclick=\"this.form._wpnonce.value = '$copy_nonce'; this.form.action.value = 'copy'; return true;\""; ?> />
    <?php endif; ?>
    </div><!-- #minor-publishing-actions -->

    <div id="misc-publishing-actions">
    <?php do_action( 'wp3cxc2c_admin_misc_pub_section', $post_id ); ?>
    </div><!-- #misc-publishing-actions -->

    <div id="major-publishing-actions">

    <?php
        if ( ! $post->initial() ) :
            $delete_nonce = wp_create_nonce( 'wp3cxc2c-delete-clicktotalk-form_' . $post_id );
    ?>
    <div id="delete-action">
        <input type="submit" name="wp3cxc2c-delete" class="delete submitdelete" value="<?php echo esc_attr( __( 'Delete', '3cx-clicktotalk' ) ); ?>" <?php echo "onclick=\"if (confirm('" . esc_js( __( "You are about to delete this Live Chat & Talk item.\n  'Cancel' to stop, 'OK' to delete.", '3cx-clicktotalk' ) ) . "')) {this.form._wpnonce.value = '$delete_nonce'; this.form.action.value = 'delete'; return true;} return false;\""; ?> />
    </div><!-- #delete-action -->
    <?php endif; ?>

    <div id="publishing-action">
        <span class="spinner"></span>
        <?php wp3cxc2c_admin_save_button( $post_id ); ?>
    </div>
    <div class="clear"></div>
    </div><!-- #major-publishing-actions -->
    </div><!-- #submitpost -->
    </div>
    </div><!-- #submitdiv -->
    <?php endif; ?>

    <div id="informationdiv" class="postbox">
    <h3><?php echo esc_html( __( "Do you need help?", '3cx-clicktotalk' ) ); ?></h3>
    <div class="inside">
        <p><?php echo esc_html( __( "Check 3CX Live Chat & Talk resources for further details.", '3cx-clicktotalk' ) ); ?></p>
        <ol>
            <li><?php echo sprintf(
                /* translators: 1: FAQ, 2: Docs ("FAQ & Docs") */
                __( '%1$s &#38; %2$s', '3cx-clicktotalk' ),
                wp3cxc2c_link(
                    __( 'https://www.3cx.com/support/', '3cx-clicktotalk' ),
                    __( 'Support', '3cx-clicktotalk' )
                ),
                wp3cxc2c_link(
                    __( 'https://www.3cx.com/phone-system/wordpress-live-chat-talk/', '3cx-clicktotalk' ),
                    __( 'Docs', '3cx-clicktotalk' )
                )
            ); ?></li>
            <li><?php echo wp3cxc2c_link(
                __( 'http://helpdesk.3cx.com/', '3cx-clicktotalk' ),
                __( 'Support Portal', '3cx-clicktotalk' )
            ); ?></li>
        </ol>
    </div>
    </div><!-- #informationdiv -->

    </div><!-- #postbox-container-1 -->

    <div id="postbox-container-2" class="postbox-container">
    <div id="clicktotalk-form-editor">
    <div class="keyboard-interaction"><?php
        echo sprintf(
            /* translators: 1: ◀ ▶ dashicon, 2: screen reader text for the dashicon */
            esc_html( __( '%1$s %2$s keys switch panels', '3cx-clicktotalk' ) ),
            '<span class="dashicons dashicons-leftright" aria-hidden="true"></span>',
            sprintf(
                '<span class="screen-reader-text">%s</span>',
                /* translators: screen reader text */
                esc_html( __( '(left and right arrow)', '3cx-clicktotalk' ) )
            )
        );
    ?></div>

    <?php

        $editor = new WP3CXC2C_Editor( $post );
        $panels = array();

        if ( current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post_id ) ) {
            $panels = array(
                'config-panel' => array(
                    'title' => __( 'Configuration', '3cx-clicktotalk' ),
                    'callback' => 'wp3cxc2c_editor_panel_config',
                ),
                'style-panel' => array(
                    'title' => __( 'Style', '3cx-clicktotalk' ),
                    'callback' => 'wp3cxc2c_editor_panel_style',
                ),
            );
        }

        $panels = apply_filters( 'wp3cxc2c_editor_panels', $panels );

        foreach ( $panels as $id => $panel ) {
            $editor->add_panel( $id, $panel['title'], $panel['callback'] );
        }

        $editor->display();
    ?>
    </div><!-- #clicktotalk-form-editor -->

    <?php if ( current_user_can( 'wp3cxc2c_edit_clicktotalk_form', $post_id ) ) : ?>
    <p class="submit"><?php wp3cxc2c_admin_save_button( $post_id ); ?></p>
    <?php endif; ?>

    </div><!-- #postbox-container-2 -->

    </div><!-- #post-body -->
    <br class="clear" />
    </div><!-- #poststuff -->
    </form>

    <?php endif; ?>

    </div><!-- .wrap -->

    <?php

        do_action( 'wp3cxc2c_admin_footer', $post );
