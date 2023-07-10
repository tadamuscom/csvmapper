<?php

if( ! class_exists( 'CSVM_Generator' ) ){
	class CSVM_Generator{
		/**
		 * Holds the needed value properties
		 *
		 * @since 1.0
		 *
		 * @var array|string[]
		 */
		private array $options;

		public function __construct( string $options )
		{
			$this->options = explode( '|', $options );
		}

		/**
		 * Generates empty values based on the options
		 *
		 * @return string|int|array
		 */
		public function return(): string|int|array
		{
			if( in_array( 'string', $this->options ) ){
				return '';
			}

			if( in_array( 'array', $this->options ) ){
				return array();
			}

			if( in_array( 'integer', $this->options ) ){
				return 0;
			}

			if( in_array( 'numeric', $this->options ) ){
				return 0;
			}
		}
	}
}