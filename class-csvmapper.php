<?php
/**
 * Plugin Name: CSV Mapper
 * Description: Allows you to add posts, WooCommerce Products, Pages, and all kinds of data from CSV files to your website.
 * Plugin URI: https://csvmapper.io
 * Version: 1.0
 * Requires at least: 6.2
 * Author: Tadamus
 * Author URI: https://tadamus.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: csvmapper
 *
 * @package csvmapper
 */

if ( ! defined( 'CSVM_VERSION_NUMBER' ) ) {
	define( 'CSVM_VERSION_NUMBER', 1.0 );
}

/**
 * Initiate the plugin
 */
final class CSVMapper {
	/**
	 * The only instance of the plugin class
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Generate the plugin instance
	 *
	 * @since 1.0
	 *
	 * @return CSVMapper
	 */
	public static function instance(): CSVMapper {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CSVMapper ) ) {
			self::$instance = new CSVMapper();
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->settings();
		}

		return self::$instance;
	}

	/**
	 * Prevents the class from being cloned
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheating huh?', 'csvmapper' ), '1.0' );
	}

	/**
	 * Prevents the class from being created
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheating huh?', 'csvmapper' ), '1.0' );
	}

	/**
	 * Returns the settings of the plugin
	 *
	 * @since 1.0
	 *
	 * @return CSVM_Settings
	 */
	public function settings(): CSVM_Settings {
		return new CSVM_Settings();
	}

	/**
	 * Adds all the required constants
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private function setup_constants(): void {
		if ( ! defined( 'CSVM_PATH' ) ) {
			define( 'CSVM_PATH', plugin_dir_path( __FILE__ ) );
		}

		if ( ! defined( 'CSVM_VIEW' ) ) {
			define( 'CSVM_VIEW', CSVM_PATH . 'views' );
		}

		if ( ! defined( 'CSVM_INCLUDES' ) ) {
			define( 'CSVM_INCLUDES', CSVM_PATH . 'includes' );
		}

		if ( ! defined( 'CSVM_INC_CORE' ) ) {
			define( 'CSVM_INC_CORE', CSVM_INCLUDES . '/core' );
		}

		if ( ! defined( 'CSVM_INC_ADMIN' ) ) {
			define( 'CSVM_INC_ADMIN', CSVM_INCLUDES . '/admin' );
		}

		if ( ! defined( 'CSVM_INC_INTERFACES' ) ) {
			define( 'CSVM_INC_INTERFACES', CSVM_INCLUDES . '/interfaces' );
		}

		if ( ! defined( 'CSVM_INC_ABSTRACTS' ) ) {
			define( 'CSVM_INC_ABSTRACTS', CSVM_INCLUDES . '/abstracts' );
		}

		if ( ! defined( 'CSVM_INC_MODELS' ) ) {
			define( 'CSVM_INC_MODELS', CSVM_INCLUDES . '/models' );
		}

		if ( ! defined( 'CSVM_INC_IMPORTS' ) ) {
			define( 'CSVM_INC_IMPORTS', CSVM_INCLUDES . '/imports' );
		}

		if ( ! defined( 'CSVM_URL' ) ) {
			define( 'CSVM_URL', plugin_dir_url( __FILE__ ) );
		}

		if ( ! defined( 'CSVM_ASSETS' ) ) {
			define( 'CSVM_ASSETS', CSVM_URL . 'assets' );
		}

		if ( ! defined( 'CSVM_CSS' ) ) {
			define( 'CSVM_CSS', CSVM_ASSETS . '/css' );
		}

		if ( ! defined( 'CSVM_JS' ) ) {
			define( 'CSVM_JS', CSVM_ASSETS . '/js' );
		}

		if ( ! defined( 'CSVM_TIME_FORMAT' ) ) {
			define( 'CSVM_TIME_FORMAT', 'Y-m-d H:i:s' );
		}
	}

	/**
	 * Calls the CSVM_Includer class when the plugin is initiated
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private function includes(): void {
		include_once CSVM_PATH . 'includes/core/class-csvm-includer.php';

		new CSVM_Includer();
	}
}

CSVMapper::instance();
