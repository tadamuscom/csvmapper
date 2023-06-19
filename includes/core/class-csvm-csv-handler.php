<?php

if( ! class_exists( 'CSVM_CSV_Handler' ) ){
	class CSVM_CSV_Handler{
		private CSVM_Run $run;
		private CSVM_Import $import;
		private array $row;
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
				$this->complete_run( $this->file );
			}

			fclose($this->file);
			die();
		}

		/**
		 * Runs through the whole file and triggers the creation actions
		 *
		 * @since 1.0
		 *
		 * @param $file
		 *
		 * @return void
		 */
		private function complete_run( $file ): void
		{
			$this->run->set_in_progress();

			if( $this->import->type === 'post-meta' ) {
				foreach ( $this->import->ids as $id ) {
					$this->meta_template( $id );
				}
			}
		}

		private function meta_template( int $id ): void
		{
			foreach( $this->import->template as $key => $value ){
				$this->meta_map_item( $id, $key, $value );
			}
		}

		private function meta_map_item( int $id, string $key, string $value ): void
		{
			while ( ( $row = fgetcsv( $this->file ) ) !== false ) {
				$this->row = $row;

				if( $this->import->type === 'post-meta' ){
					add_post_meta( $id, $this->format_headers( $key ), $this->format_headers( $value ) );
				}
			}
		}

		private function format_headers( string $string ): string
		{
			foreach( $this->import->get_headers() as $key => $header ){
				if( str_contains( $string, '{' . $header . '}' ) ){
					$string = str_replace( '{' . $header  . '}', $this->row[$key] , $string );
				}
			}

			return $string;
		}
	}
}