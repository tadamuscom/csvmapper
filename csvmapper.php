<?php

/*
Plugin Name: CSV Mapper
Description: Allows you to add posts, WooCommerce Products, Pages, and all kinds of data from CSV files to your website.
Plugin URI: https://csvmapper.io
Version: 1.0
Author: Tadamus
Author URI: https://tadamus.com/
Text Domain: csvmapper
*/

final class CSVMapper{
	private static $instance;

	public static function instance(): CSVMapper
	{

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CSVMapper ) ) {

			self::$instance = new CSVMapper();
			self::$instance->setup_constants();
			self::$instance->includes();

//			self::$instance->updater();

		}

		return self::$instance;
	}

	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-fusion' ), '1.6' );
	}

	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-fusion' ), '1.6' );
	}

	private function setup_constants(): void
	{
		if ( ! defined( 'CSVM_PATH' ) ) {
			define( 'CSVM_PATH', plugin_dir_path( __FILE__ ) );
		}

		if ( ! defined( 'CSVM_INCLUDES' ) ) {
			define( 'CSVM_INCLUDES', CSVM_PATH . '/includes' );
		}

		if ( ! defined( 'CSVM_INC_CORE' ) ) {
			define( 'CSVM_INC_CORE', CSVM_INCLUDES . '/core' );
		}

		if ( ! defined( 'CSVM_INC_ADMIN' ) ) {
			define( 'CSVM_INC_ADMIN', CSVM_INCLUDES . '/admin' );
		}

		if ( ! defined( 'CSVM_URL' ) ) {
			define( 'CSVM_URL', plugin_dir_url( __FILE__ ) );
		}
	}

	private function includes(): void
	{
		require_once CSVM_PATH . 'includes/core/class-csvm-includer.php';

		new CSVM_Includer();
	}
}

if ( ! function_exists( 'csvmapper' ) ) {
	function csvmapper(): CSVMapper
	{
		return CSVMapper::instance();
	}

	// Turn on the plugin
	csvmapper();
}