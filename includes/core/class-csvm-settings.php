<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'CSVM_Settings' ) ){
	class CSVM_Settings{
		/**
		 * The name of the option in which the settings will be saved
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		private string $option_name = 'csvm_settings';

		/**
		 * The name of the settings
		 *
		 * @since 1.0
		 *
		 * @var array
		 */
		private array $setting_names = array();

		public function __construct() {
			$this->load();
		}

		/**
		 * Load the settings if they are not loaded already
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function load(): void
		{
			if( get_option( $this->option_name ) && !empty( get_option( 'csvm_settings' ) ) ) return;

			$this->set_defaults();

			csvm_add_or_update_option( $this->option_name, $this->json() );
		}

		/**
		 * Sets the default settings in the class
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function set_defaults(): void
		{
			// Add defaults
		}

		/**
		 * Loops through the current settings and returns a JSON string
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		private function json(): string
		{
			$returnable = array();

			foreach( $this->setting_names as $setting ){
				$returnable[] = $this->{$setting};
			}

			return json_encode($returnable);
		}
	}
}