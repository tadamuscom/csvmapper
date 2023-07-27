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
				// Styles
				wp_enqueue_style( 'csvmapper-admin-stylesheet', CSVM_CSS . '/admin/style.css', array(), CSVM_VERSION_NUMBER );

				// Scripts
				wp_register_script( 'csvmapper-settings', CSVM_JS . '/admin/settings.js', array(), CSVM_VERSION_NUMBER, true );
				wp_register_script( 'csvmapper-first-step', CSVM_JS . '/admin/first-step.js', array(), CSVM_VERSION_NUMBER, true );
				wp_register_script( 'csvmapper-mapping', CSVM_JS . '/admin/mapping.js', array(), CSVM_VERSION_NUMBER, true );
				wp_register_script( 'csvmapper-meta-map', CSVM_JS . '/admin/meta-map.js', array(), CSVM_VERSION_NUMBER, true );
				wp_register_script( 'csvmapper-third-step', CSVM_JS . '/admin/third-step.js', array(), CSVM_VERSION_NUMBER, true );
				wp_localize_script( 'csvmapper-third-step', 'csvm_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
			}
		}
	}

	new CSVM_Asset_Manager();
}