<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CSVM_CSV_Handler' ) ) {
	class CSVM_CSV_Handler {

		/**
		 * The CSVM_Run instance
		 *
		 * @since 1.0
		 *
		 * @var CSVM_Run
		 */
		private CSVM_Run $run;

		/**
		 * The CSVM_Import instance
		 *
		 * @since 1.0
		 *
		 * @var CSVM_Import
		 */
		private CSVM_Import $import;

		/**
		 * Current row in the looping process
		 *
		 * @since 1.0
		 *
		 * @var array
		 */
		private array $row;

		/**
		 * The temporary buffer created form the CSV file
		 *
		 * @since 1.0
		 *
		 * @var array
		 */
		private array $buffer;

		/**
		 * The file buffer
		 *
		 * @since 1.0
		 *
		 * @var
		 */
		private $file;

		public function __construct( CSVM_Run $run ) {
			$this->run    = $run;
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
		public function start( string|bool $start = false, string|bool $limit = false ): void {
			$this->file = fopen( $this->import->file_path, 'r' );

			if ( ! $this->file ) {
				wp_die( __( 'CSV File couldn\'t be open', 'csvmapper' ) );
			}

			if ( ! $start && ! $limit ) {
				$this->complete_run();
			}

			if ( $start && $limit ) {
				$this->partial_run( $start, $limit );
			}

			fclose( $this->file );
		}

		/**
		 * Runs through the whole file and triggers the creation actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function complete_run(): void {
			$this->process_file();
			$this->run();
		}

		private function partial_run( int $start, int $end ): void {
			$this->partial_process_file( $start, $end );
			$this->run( false );
		}

		private function run( $complete = true ): void {
			$this->run->set_in_progress();

			if ( $this->import->type === 'post-meta' || $this->import->type === 'user-meta' ) {
				foreach ( $this->import->ids as $id ) {
					$this->meta_template( $id );
				}
			}

			if ( $this->import->type === 'posts' ) {
				$this->table_template();
			}

			if ( $complete ) {
				$this->run->set_complete();
			}
		}

		/**
		 * Iterates through the whole file and creates a buffer array
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function process_file(): void {
			$index = 0;

			while ( ( $row = fgetcsv( $this->file ) ) !== false ) {
				if ( $index === 0 ) {
					$this->buffer['headers'] = $row;
					++$index;
					continue;
				}

				$this->buffer['rows'][] = $row;

				++$index;
			}
		}

		private function partial_process_file( int $start, int $limit ): void {
			$file_obj = new SplFileObject( $this->import->file_path, 'r' );
			$file_obj->seek( $start );

			$index      = 0;
			$file_index = $start;

			while ( ( $row = $file_obj->fgetcsv() ) !== false ) {
				if ( $index > $limit ) {
					break;
				}

				if ( $index === 0 ) {
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
		private function meta_template( int $id ): void {
			foreach ( $this->import->template as $key => $value ) {
				$this->meta_map_item( $id, $key, $value );
			}
		}

		/**
		 * Runs the import for each map item
		 *
		 * @since 1.0
		 *
		 * @param int    $id
		 * @param string $key
		 * @param string $value
		 *
		 * @return void
		 */
		private function meta_map_item( int $id, string $key, string $value ): void {
			foreach ( $this->buffer['rows'] as $row ) {
				$this->row = $row;

				if ( $this->import->type === 'post-meta' ) {
					add_post_meta( $id, $this->format_headers( $key ), $this->format_headers( $value ) );
				}

				if ( $this->import->type === 'user-meta' ) {
					add_user_meta( $id, $this->format_headers( $key ), $this->format_headers( $value ) );
				}
			}
		}

		/**
		 * Runs the template for the table types of imports
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function table_template(): void {
			$this->process_file();

			global $wpdb;

			foreach ( $this->buffer['rows'] as $row ) {
				$this->row = $row;
				$data      = array();

				foreach ( $this->import->template as $key => $value ) {
					$data[ $key ] = $this->format_headers( $value );
				}

				$wpdb->insert( $wpdb->prefix . $this->import->table, $data );
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
		private function format_headers( string $string ): string {
			$string = strtolower( $string );

			foreach ( $this->buffer['headers'] as $key => $header ) {
				$formatted_header = csvm_convert_to_slug( strtolower( '{' . $header . '}' ) );

				if ( str_contains( $string, $formatted_header ) ) {
					$string = str_replace( $formatted_header, $this->row[ $key ], $string );
				}
			}

			return $string;
		}
	}
}
