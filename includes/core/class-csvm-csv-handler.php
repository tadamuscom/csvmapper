<?php

if( ! class_exists( 'CSVM_CSV_Handler' ) ){
	class CSVM_CSV_Handler{
		private CSVM_Run $run;
		private CSVM_Import $import;
		private array $row;
		private array $buffer;
		private $file;

		public function __construct( CSVM_Run $run ) {
			$this->run = $run;
			$this->import = new CSVM_Import( $run->import_id );
		}

		/**
		 * Starts the CSV process
		 *
		 * @since 1.0
		 *
		 * @param string|bool $start
		 * @param string|bool $limit
		 *
		 * @return void
		 */
		public function start( string|bool $start = false, string|bool $limit = false ): void
		{
			$this->file = fopen( $this->import->file_path, 'r' );

			if( ! $this->file ){
				wp_die( __('CSV File couldn\'t be open', 'csvmapper') );
			}

			if( ! $start && ! $limit ){
				$this->complete_run();
			}

			fclose($this->file);
		}

		/**
		 * Runs through the whole file and triggers the creation actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function complete_run(): void
		{
			$this->run->set_in_progress();

			$this->process_file();

			if( $this->import->type === 'post-meta' || $this->import->type === 'user-meta' ) {
				foreach ( $this->import->ids as $id ) {
					$this->meta_template( $id );
				}
			}

			$this->run->set_complete();
		}

		/**
		 * Iterates through the whole file and creates a buffer array
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function process_file(): void
		{
			$index = 0;

			while ( ( $row = fgetcsv( $this->file ) ) !== false ) {
				if( $index === 0 ){
					$this->buffer['headers'] = $row;
					++$index;
					continue;
				}

				$this->buffer['rows'][] = $row;

				++$index;
			}
		}

		/**
		 * Runs the template for the meta types of imports
		 *
		 * @since 1.0
		 *
		 * @param int $id
		 *
		 * @return void
		 */
		private function meta_template( int $id ): void
		{
			foreach( $this->import->template as $key => $value ) {
				$this->meta_map_item( $id, $key, $value );
			}
		}

		/**
		 * Runs the import for each map item
		 *
		 * @since 1.0
		 *
		 * @param int $id
		 * @param string $key
		 * @param string $value
		 *
		 * @return void
		 */
		private function meta_map_item( int $id, string $key, string $value ): void
		{
			foreach( $this->buffer['rows'] as $row ){
				$this->row = $row;

				if( $this->import->type === 'post-meta' ){
					add_post_meta( $id, $this->format_headers( $key ), $this->format_headers( $value ) );
				}

				if( $this->import->type === 'user-meta' ){
					add_user_meta( $id, $this->format_headers( $key ), $this->format_headers( $value ) );
				}
			}
		}

		/**
		 * Formats the given string replacing the content with the respective header
		 *
		 * @since 1.0
		 *
		 * @param string $string
		 *
		 * @return string
		 */
		private function format_headers( string $string ): string
		{
			$string = strtolower( $string );

			foreach( $this->buffer['headers'] as $key => $header ){
				$formatted_header = csvm_convert_to_slug(strtolower( '{' . $header . '}' ) );

				if( str_contains( $string, $formatted_header ) ){
					$string = str_replace( $formatted_header, $this->row[$key] , $string );
				}
			}

			return $string;
		}
	}
}