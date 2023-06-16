<?php

if( ! class_exists( 'CSVM_Generator' ) ){
	class CSVM_Generator{
		private array $options;

		public function __construct( string $options )
		{
			$this->options = explode( '|', $options );
		}

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
		}
	}
}