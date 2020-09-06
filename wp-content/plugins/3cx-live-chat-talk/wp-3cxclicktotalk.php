<?php
/*
Plugin Name: 3CX Live Chat and Talk
Plugin URI: https://www.3cx.com/phone-system/video-conferencing/
Description: Integrate 3CX ClickToTalk in your Wordpress website
Author: 3CX
Author URI: https://www.3cx.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: 3cx-clicktotalk
Domain Path: /languages/
Version: 1.7.1

3CX ClickToTalk Wordpress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
3CX ClickToTalk Wordpress Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with 3CX ClickToTalk Wordpress Plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

define( 'WP3CXC2C_VERSION', '1.0.1' );

define( 'WP3CXC2C_REQUIRED_WP_VERSION', '4.8' );

define( 'WP3CXC2C_PLUGIN', __FILE__ );

define( 'WP3CXC2C_PLUGIN_BASENAME', plugin_basename( WP3CXC2C_PLUGIN ) );

define( 'WP3CXC2C_PLUGIN_NAME', trim( dirname( WP3CXC2C_PLUGIN_BASENAME ), '/' ) );

define( 'WP3CXC2C_PLUGIN_DIR', untrailingslashit( dirname( WP3CXC2C_PLUGIN ) ) );

define( 'WP3CXC2C_PLUGIN_MODULES_DIR', WP3CXC2C_PLUGIN_DIR . '/modules' );

if ( ! defined( 'WP3CXC2C_LOAD_JS' ) ) {
	define( 'WP3CXC2C_LOAD_JS', true );
}

if ( ! defined( 'WP3CXC2C_LOAD_CSS' ) ) {
	define( 'WP3CXC2C_LOAD_CSS', true );
}

if ( ! defined( 'WP3CXC2C_AUTOP' ) ) {
	define( 'WP3CXC2C_AUTOP', true );
}

if ( ! defined( 'WP3CXC2C_USE_PIPE' ) ) {
	define( 'WP3CXC2C_USE_PIPE', true );
}

if ( ! defined( 'WP3CXC2C_ADMIN_READ_CAPABILITY' ) ) {
	define( 'WP3CXC2C_ADMIN_READ_CAPABILITY', 'edit_posts' );
}

if ( ! defined( 'WP3CXC2C_ADMIN_READ_WRITE_CAPABILITY' ) ) {
	define( 'WP3CXC2C_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );
}

if ( ! defined( 'WP3CXC2C_VERIFY_NONCE' ) ) {
	define( 'WP3CXC2C_VERIFY_NONCE', false );
}

if ( ! defined( 'WP3CXC2C_VALIDATE_CONFIGURATION' ) ) {
	define( 'WP3CXC2C_VALIDATE_CONFIGURATION', true );
}

require_once WP3CXC2C_PLUGIN_DIR . '/settings.php';
