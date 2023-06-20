<?php

if(!class_exists('CSVM_Validator')){
	class CSVM_Validator{
		private string $value;
		private string $error;
		private array $options;
		private bool $returnable;
		private string $field;

		public function __construct( $field, $value, $options )
		{
			$this->field = $field;
			$this->value = $value;
			$this->options = explode( '|', $options );
			$this->returnable = true;

			$this->validate();
		}

		public function validate(): void
		{
			foreach( $this->options as $option ){
				$this->individual_validation( $option );

				if( $this->returnable === false ){
					break;
				}
			}
		}

		public function result(): bool
		{
			return $this->returnable;
		}

		public function get_error(): string
		{
			if( ! empty( $this->error ) ){
				return $this->error;
			}

			return 'No error';
		}

		private function trigger_error( $contents ): void
		{
			$this->returnable = false;
			$this->error = __( $this->field . ' ' . $contents, 'csvmapper' );

		}

		private function individual_validation( $option ): void
		{
			switch ($option){
				case 'required':
					$this->required( $this->value );
					return;
				case 'string':
					$this->string( $this->value );
					return;
				case 'integer':
					$this->integer( $this->value );
					return;
				case 'numeric':
					$this->numeric( $this->value );
					return;
				case 'float':
					$this->float( $this->value );
					return;
				case 'array':
					$this->array( $this->value );
					return;
				case 'date':
					$this->date( $this->value );
					return;
			}

			if( str_contains( $option, 'min:' ) ){
				$limit = (explode( ':', $option ))[1];

				$this->minimum( $this->value, $limit );
			}

			if( str_contains( $option, 'max:' ) ){
				$limit = (explode( ':', $option ))[1];

				$this->maximum( $this->value, $limit );
			}
		}

		private function required( $value ): void
		{
			if( $value != 0 ){
				if( empty( $value )  ){
					$this->trigger_error( 'is required' );
				}
			}

		}

		private function string( $value ): void
		{
			if(!is_string( $value )){
				$this->trigger_error( 'must be a string' );
			}

		}

		private function integer( $value ): void
		{
			if( ! is_integer( $value ) ){
				$this->trigger_error( 'must be an integer' );
			}
		}

		private function numeric( $value ): void
		{
			if( ! is_numeric( $value ) ){
				$this->trigger_error( 'must be a number' );
			}
		}

		private function float( $value ): void
		{
			if( ! is_float( $value ) ){
				$this->trigger_error( 'must be a float' );
			}
		}

		private function array( $value ): void
		{
			if( ! is_array( $value ) ){
				$this->trigger_error( 'must be an array' );
			}
		}

		private function date( $value ): void
		{
			if( ! strtotime( $value ) ){
				$this->trigger_error( 'must be a date' );
			}

		}

		private function minimum( string $value, int $limit ): void
		{
			if( strlen( $value ) < $limit ){
				$this->trigger_error( 'must have a minimum of ' . $limit . ' characters' );
			}
		}

		private function maximum( string $value, int $limit ): void
		{
			if( strlen( $value ) > $limit ){
				$this->trigger_error( 'must have a maximum of ' . $limit . ' characters' );
			}
		}
	}
}