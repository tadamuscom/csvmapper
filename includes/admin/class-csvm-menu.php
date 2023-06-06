<?php

if( ! class_exists( 'CSVM_Menu' ) ){
	class CSVM_Menu{
		public function __construct() {
			add_action( 'admin_menu', array($this, 'pages') );
		}

		/**
		 * Adds the pages and subpages to the dashboard menu
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function pages(): void
		{
			add_menu_page( 'CSV Mapper', 'CSV Mapper', 'manage_options', 'csvmapper', array( $this, 'main_callback' ) );
		}

		/**
		 * The callback for the main admin page
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function main_callback(): void
		{
			new CSVM_View( 'admin/main_page', false, false );
		}
	}

	new CSVM_Menu();
}