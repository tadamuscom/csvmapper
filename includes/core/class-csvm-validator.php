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
				case 'array':
					$this->array( $this->value );
					return;
				case 'date':
					$this->date( $this->value );
					return;
				default:
					return;
			}
		}

		private function required( $value ): void
		{
			if( empty( $value ) ){
				$this->trigger_error( 'must be a string' );
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
				$this->trigger_error( 'must be a integer' );
			}
		}

		private function array( $value ): void
		{
			if( ! is_array( $value ) ){
				$this->trigger_error( 'must be a array' );
			}
		}

		private function date( $value ): void
		{
			if( ! DateTime::createFromFormat( CSVM_TIME_FORMAT, $value ) ){
				$this->trigger_error( 'must be a date' );
			}

		}
	}
}