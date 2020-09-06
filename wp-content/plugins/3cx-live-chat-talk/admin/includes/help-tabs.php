<?php

class WP3CXC2C_Help_Tabs {

	private $screen;

	public function __construct( WP_Screen $screen ) {
		$this->screen = $screen;
	}

	public function set_help_tabs( $type ) {
		switch ( $type ) {
			case 'list':
				$this->screen->add_help_tab( array(
					'id' => 'list_overview',
					'title' => __( 'Overview', '3cx-clicktotalk' ),
					'content' => $this->content( 'list_overview' ) ) );

				$this->screen->add_help_tab( array(
					'id' => 'list_available_actions',
					'title' => __( 'Available Actions', '3cx-clicktotalk' ),
					'content' => $this->content( 'list_available_actions' ) ) );

				$this->sidebar();

				return;
			case 'edit':
				$this->screen->add_help_tab( array(
					'id' => 'edit_overview',
					'title' => __( 'Overview', '3cx-clicktotalk' ),
					'content' => $this->content( 'edit_overview' ) ) );

				$this->sidebar();

				return;
		}
	}

	private function content( $name ) {
		$content = array();

		$content['list_overview'] = '<p>' . __( "On this screen, you can manage Click To Talk item provided by 3CX Live Chat & Talk. You can manage an unlimited number of Live Chat & Talk item. Each Click To Talk item has a unique ID and 3CX Click To Talk shortcode ([3cx-clicktotalk ...]). To insert a Click To Talk item into a post or a text widget, insert the shortcode into the target.", '3cx-clicktotalk' ) . '</p>';

		$content['list_available_actions'] = '<p>' . __( "Hovering over a row in the Live Chat & Talk item list will display action links that allow you to manage your Click To Talk item. You can perform the following actions:", '3cx-clicktotalk' ) . '</p>';
		$content['list_available_actions'] .= '<p>' . __( "<strong>Edit</strong> - Navigates to the editing screen for that Live Chat & Talk item. You can also reach that screen by clicking on the Click To Talk item title.", '3cx-clicktotalk' ) . '</p>';
		$content['list_available_actions'] .= '<p>' . __( "<strong>Duplicate</strong> - Clones that Live Chat & Talkitem. A cloned Live Chat & Talk item inherits all content from the original, but has a different ID.", '3cx-clicktotalk' ) . '</p>';

		$content['edit_overview'] = '<p>' . __( "On this screen, you can edit a Live Chat & Talk item. A Click To Talk item includes following components:", '3cx-clicktotalk' ) . '</p>';
		$content['edit_overview'] .= '<p>' . __( "<strong>Title</strong> is the title of a Live Chat & Talk item. This title is only used for labeling a Click To Talk item, and can be edited.", '3cx-clicktotalk' ) . '</p>';
		$content['edit_overview'] .= '<p>' . __( "<strong>Configuration</strong> is the section where you enter you 3CX PBX configuration. Look at PBX Management Console into WebMeeting section to find your parameters.", '3cx-clicktotalk' ) . '</p>';

		if ( ! empty( $content[$name] ) ) {
			return $content[$name];
		}
	}

	public function sidebar() {
		$content = '<p><strong>' . __( 'For more information:', '3cx-clicktotalk' ) . '</strong></p>';
		$content .= '<p>' . wp3cxc2c_link( __( 'https://www.3cx.com/phone-system/wordpress-live-chat-talk/', '3cx-clicktotalk' ), __( '3CX Live Chat & Talk', '3cx-clicktotalk' ) ) . '</p>';
		$content .= '<p>' . wp3cxc2c_link( __( 'https://www.3cx.com/support/', '3cx-clicktotalk' ), __( 'Support', '3cx-clicktotalk' ) ) . '</p>';

		$this->screen->set_help_sidebar( $content );
	}
}
