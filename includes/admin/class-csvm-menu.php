<?php
/**
 * Add and manage admin pages
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_Menu' ) ) {
	/**
	 * Add and manage admin pages
	 */
	class CSVM_Menu {
		/**
		 * Add the pages through the admin_menu hook
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'pages' ) );
		}

		/**
		 * Adds the pages and subpages to the dashboard menu
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function pages(): void {
			add_menu_page( 'CSV Mapper', 'CSV Mapper', 'manage_options', 'csvmapper', array( $this, 'main_callback' ) );
			add_submenu_page( 'csvmapper', 'CSV Mapper - Settings', 'Settings', 'manage_options', 'csvmapper-settings', array( $this, 'settings_callback' ) );
		}

		/**
		 * The callback for the main admin page
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function main_callback(): void {
			new CSVM_View( 'admin/main-page', false, false );
		}

		/**
		 * The callback for the settings admin page
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function settings_callback(): void {
			wp_enqueue_script( 'csvmapper-settings' );

			new CSVM_View( 'admin/settings', false, false );
		}
	}

	new CSVM_Menu();
}
