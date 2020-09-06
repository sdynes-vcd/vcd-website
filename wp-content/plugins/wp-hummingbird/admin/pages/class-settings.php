<?php
/**
 * Setting admin page.
 *
 * @package Hummingbird\Admin\Pages
 */

namespace Hummingbird\Admin\Pages;

use Hummingbird\Admin\Page;
use Hummingbird\Core\Settings as Settings_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Settings extends Page
 */
class Settings extends Page {

	/**
	 * Settings page constructor.
	 *
	 * @param string $slug        The slug name to refer to this menu by (should be unique for this menu).
	 * @param string $page_title  The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string $menu_title  The text to be used for the menu.
	 * @param bool   $parent      Parent or child.
	 * @param bool   $render      Use a callback function.
	 */
	public function __construct( $slug, $page_title, $menu_title, $parent = false, $render = true ) {
		parent::__construct( $slug, $page_title, $menu_title, $parent, $render );

		$this->tabs = array(
			'data' => __( 'Data & Settings', 'wphb' ),
			'main' => __( 'Accessibility', 'wphb' ),
		);
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		$this->add_meta_box(
			'data',
			__( 'Data & Settings', 'wphb' ),
			array( $this, 'data_metabox' ),
			null,
			array( $this, 'accessibility_metabox_footer' ),
			'data'
		);

		$this->add_meta_box(
			'settings',
			__( 'Accessibility', 'wphb' ),
			array( $this, 'accessibility_metabox' ),
			null,
			array( $this, 'accessibility_metabox_footer' ),
			'main'
		);
	}

	/**
	 * Accessibility meta box.
	 */
	public function accessibility_metabox() {
		$args = array(
			'settings' => Settings_Module::get_settings( 'settings' ),
		);

		$this->view( 'settings/accessibility-meta-box', $args );
	}

	/**
	 * Accessibility meta box footer.
	 */
	public function accessibility_metabox_footer() {
		$this->view( 'settings/accessibility-meta-box-footer', array() );
	}

	/**
	 * Data & Settings meta box.
	 *
	 * @since 2.0.0
	 */
	public function data_metabox() {
		$args = array(
			'settings' => Settings_Module::get_settings( 'settings' ),
		);

		$this->view( 'settings/data-meta-box', $args );
	}

}