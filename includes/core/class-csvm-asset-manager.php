<?php

if( ! class_exists( 'CSVM_Asset_Manager' ) ){
	class CSVM_Asset_Manager{
		public function __construct() {
			add_action('admin_enqueue_scripts', array( $this, 'admin_scripts' ));
		}

		/**
		 * Registers the dashboard scripts
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function admin_scripts(): void
		{
			if( is_admin() ){
				wp_register_script( 'csvmapper-first-step', CSVM_JS . '/admin/first-step.js', array(), CSVM_VERSION_NUMBER, true );
				wp_register_script( 'csvmapper-mapping', CSVM_JS . '/admin/mapping.js', array(), CSVM_VERSION_NUMBER, true );
				wp_register_script( 'csvmapper-meta-map', CSVM_JS . '/admin/meta-map.js', array(), CSVM_VERSION_NUMBER, true );
			}
		}
	}

	new CSVM_Asset_Manager();
}