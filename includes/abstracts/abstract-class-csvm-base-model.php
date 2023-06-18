<?php

if(!class_exists('CSVM_Base_Model')){
	abstract class CSVM_Base_Model{
		protected string $option_prefix;
		protected array $fields;
		public string $id;

		public function __construct( string|bool $id = false ){
			if( $id ){
				$this->id = $id;

				$this->load( $id );

				return;
			}

			$this->new();
		}

		/**
		 * Saves the current model if the data is correct and if there isn't one already with the same ID
		 *
		 * @since 1.0
		 *
		 * @return $this|bool
		 */
		public function save(): self|bool
		{
			if( $this->validation() ){
				csvm_add_or_update_option( $this->get_option_name(), $this->serialized() );

				return new static( $this->id );
			}

			return false;
		}

		/**
		 * Validates the data of the model based on the rules provided in the $fields array
		 *
		 * @since 1.0
		 *
		 * @return bool
		 */
		private function validation(): bool
		{
			foreach( $this->fields as $field => $options ){
				$array_options = explode( '|', $options );

				if( ! empty( $this->{$field} ) && ! is_array( $this->{$field} ) && ! in_array( 'array', $array_options ) ){
					$validator = new CSVM_Validator( $field, $this->{$field}, $options );

					if( ! $validator->result() ){
						return false;
					}
				}
			}

			return true;
		}

		/**
		 * Generates an empty model instance
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function new(): void
		{
			foreach( $this->fields as $field => $options ){
				$this->{$field} = (new CSVM_Generator( $options ))->return();
			}
		}

		/**
		 * Populates the class with the data from the database
		 *
		 * @since 1.0
		 *
		 * @param string $data
		 *
		 * @return void
		 */
		private function populate( string $data ): void
		{
			$import = unserialize( $data );

			foreach( $this->get_field_names() as $field ){
				if( ! empty( $import[$field] ) ){
					$this->{$field} = $import[$field];
				}
			}
		}

		/**
		 * Takes the object parameters and turns them into a serialized string
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		private function serialized(): string
		{
			$data = array();

			foreach( $this->fields as $field => $options ){
				$options = explode( '|', $options );

				if( in_array( 'required', $options ) ){
					$data[$field] = $this->{$field};
					continue;
				}

				if( ! empty( $this->{$field} ) ){
					$data[$field] = $this->{$field};
				}
			}

			return serialize( $data );
		}

		/**
		 * Loads the class with the values of the given ID if the ID exists
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		protected function load(): void
		{
			if( $import = $this->retrieve() ){
				$this->populate( $import );
			}else{
				$this->new();
			}
		}

		/**
		 * Retrieves the raw string data if it exists
		 *
		 * @since 1.0
		 *
		 * @return mixed
		 */
		protected function retrieve(): mixed
		{
			if( $this->exists() ){
				return get_option( $this->get_option_name() );
			}

			return false;
		}

		/**
		 * Checks if there is already an import with the same ID
		 *
		 * @since 1.0
		 *
		 * @return bool
		 */
		protected function exists(): bool
		{
			if( ! empty( $this->option_prefix ) && ! empty( $this->id ) ) {
				if ( get_option( $this->get_option_name() ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Generates the name of the WordPress option
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		protected function get_option_name(): string
		{
			return $this->option_prefix . '-' . $this->id;
		}

		/**
		 * Generates an array with the field names without the options
		 *
		 * @since 1.0
		 *
		 * @return array
		 */
		protected function get_field_names(): array
		{
			$returnable = array();

			foreach( $this->fields as $field => $options ){
				$returnable[] = $field;
			}

			return $returnable;
		}
	}
}