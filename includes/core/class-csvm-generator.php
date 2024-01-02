<?php
/**
 * Generate default data for models
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_Generator' ) ) {
	/**
	 * Generate default data for models
	 */
	class CSVM_Generator {
		/**
		 * Holds the needed value properties
		 *
		 * @since 1.0
		 *
		 * @var array|string[]
		 */
		private array $options;

		/**
		 * Initiate the generator
		 *
		 * @since 1.0
		 *
		 * @param string $options The options for the generator.
		 * @return void
		 */
		public function __construct( string $options ) {
			$this->options = explode( '|', $options );
		}

		/**
		 * Generates empty values based on the options
		 *
		 * @return string|int|array
		 */
		public function return(): string|int|array {
			if ( in_array( 'string', $this->options, true ) ) {
				return '';
			}

			if ( in_array( 'array', $this->options, true ) ) {
				return array();
			}

			if ( in_array( 'integer', $this->options, true ) ) {
				return 0;
			}

			if ( in_array( 'numeric', $this->options, true ) ) {
				return 0;
			}
		}
	}
}
