<?php

if( ! class_exists( 'CSVM_Import' ) ){

	/**
	 * @property mixed|string     $file_path
	 * @property int|mixed|string $id
	 * @property mixed|string     $file_url
	 * @property array|false      $headers
	 * @property string           $type
	 */
	class CSVM_Import{
		/**
		 * Checks if there is already an import with the same ID
		 *
		 * @since 1.0
		 *
		 * @param string $id
		 *
		 * @return bool
		 */
		public static function exists( string $id ): bool
		{
			if( get_option('csvm-import-' . $id ) ) return true;

			return false;
		}

		/**
		 * Constructs a new Import object if the there isn't one already with the same ID
		 *
		 * @since 1.0
		 *
		 * @param string|bool $id
		 *
		 * @return void
		 */
		public function __construct( string|bool $id = false )
		{
			if( $id ){
				$this->load( $id );

				return;
			}

			$this->new();
		}

		/**
		 * Generates a new Import object based on the given file parameters
		 *
		 * @since 1.0
		 *
		 * @param array $file
		 *
		 * @return void
		 */
		public function process( array $file ): void
		{
			$this->id           = $this->generate_id();
			$this->file_path    = $file['file'];
			$this->file_url     = $file['url'];

			$file = fopen( $this->file_path, 'r' );
			$csv = fgetcsv ( $file );
			fclose($file);

			$this->headers = $csv;
		}

		/**
		 * Saves the current Import if the data is correct and if there isn't one already with the same ID
		 *
		 * @since 1.0
		 *
		 * @return $this
		 */
		public function save(): self
		{
			if( $this->validation() && ! self::exists( $this->id ) ){
				add_option( 'csvm-import-' . $this->id, $this->serialized() );
			}

			return $this;
		}

		/**
		 * Returns the array of headers for that import
		 *
		 * @sice 1.0
		 *
		 * @return array
		 */
		public function get_headers(): array
		{
			return $this->headers;
		}

		public function get_headers_slug_list(): string
		{
			$returnable = array();

			foreach($this->headers as $header){
				$returnable[] = csvm_convert_to_slug($header);
			}

			return json_encode($returnable);
		}

		/**
		 * Generates a unique ID
		 *
		 * @since 1.0
		 *
		 * @param string|bool $prefix
		 *
		 * @return string
		 */
		private function generate_id( string|bool $prefix = false ): string
		{
			$id = uniqid();

			if( $prefix ) $id = uniqid( $prefix );

			if( self::exists( $id ) ){
				$this->generate_id( $prefix );
			}

			return $id;
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
			$data = array(
				'id'        => $this->id,
				'file_path' => $this->file_path,
				'file_url'  => $this->file_url,
				'type'      => $this->type,
				'headers'   => $this->headers
			);

			return serialize( $data );
		}

		/**
		 * Loads the class with the values of the given ID if the ID exists
		 *
		 * @since 1.0
		 *
		 * @param $id
		 *
		 * @return void
		 */
		private function load( $id ): void
		{
			if( $import = $this->get_import( $id ) ){
				$this->populate( $import );
			}else{
				$this->new();
			}
		}

		/**
		 * Validates the class parameters
		 *
		 * @since 1.0
		 *
		 * @return bool
		 */
		private function validation(): bool
		{
			if( empty( $this->id ) || ! is_string( $this->id ) )               return false;
			if( empty( $this->file_path ) || ! is_string( $this->file_path ) ) return false;
			if( empty( $this->file_url ) || ! is_string( $this->file_url ) )   return false;
			if( empty( $this->type ) || ! is_string( $this->type ) )           return false;
			if( empty( $this->headers ) || ! is_array( $this->headers ) )      return false;

			return true;
		}

		/**
		 * Retrieves the import from the database
		 *
		 * @since 1.0
		 *
		 * @param string $id
		 *
		 * @return bool|string
		 *
		 */
		private function get_import( string $id ): bool|string
		{
			return get_option( 'csvm-import-' . $id );
		}

		/**
		 * Populates the class with the data from the database
		 *
		 * @since 1.0
		 *
		 * @param string $option
		 *
		 * @return void
		 */
		private function populate( string $option ): void
		{
			$import = unserialize( $option );

			$this->id          = $import['id'];
			$this->file_path   = $import['file_path'];
			$this->file_url    = $import['file_url'];
			$this->type        = $import['type'];
			$this->headers     = $import['headers'];
		}

		/**
		 * Generate an empty import instance
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function new(): void
		{
			$this->id         = 0;
			$this->file_path  = '';
			$this->file_url   = '';
			$this->type       = '';
			$this->headers    = array();
		}
	}
}